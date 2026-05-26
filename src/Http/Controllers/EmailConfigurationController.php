<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Karja\EmailConfig\Contracts\UserIdResolver;
use Karja\EmailConfig\Http\Requests\StoreEmailConfigurationRequest;
use Karja\EmailConfig\Http\Requests\TestSendEmailConfigurationRequest;
use Karja\EmailConfig\Http\Requests\UpdateEmailConfigurationRequest;
use Karja\EmailConfig\Http\Resources\EmailConfigurationResource;
use Karja\EmailConfig\Mail\TemplatedEmail;
use Karja\EmailConfig\Models\EmailConfiguration;
use Karja\EmailConfig\Services\PlaceholderReplacer;
use Throwable;

class EmailConfigurationController extends Controller
{
    public function __construct(
        protected PlaceholderReplacer $placeholderReplacer,
        protected UserIdResolver $userIdResolver
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $items = EmailConfiguration::query()->orderBy('name')->get();

        return EmailConfigurationResource::collection($items);
    }

    public function show(EmailConfiguration $emailConfiguration): EmailConfigurationResource
    {
        return new EmailConfigurationResource($emailConfiguration);
    }

    public function store(StoreEmailConfigurationRequest $request): JsonResponse
    {
        $userId = $this->userIdResolver->resolve();

        $data = $this->normalizePayload($request->validated());
        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        $configuration = EmailConfiguration::query()->create($data);

        return (new EmailConfigurationResource($configuration))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateEmailConfigurationRequest $request, EmailConfiguration $emailConfiguration): EmailConfigurationResource
    {
        $data = $this->normalizePayload($request->validated());
        $data['updated_by'] = $this->userIdResolver->resolve();

        $emailConfiguration->update($data);

        return new EmailConfigurationResource($emailConfiguration->fresh());
    }

    public function destroy(EmailConfiguration $emailConfiguration): JsonResponse
    {
        $emailConfiguration->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    public function testSend(TestSendEmailConfigurationRequest $request, EmailConfiguration $emailConfiguration): JsonResponse
    {
        if (! $emailConfiguration->is_active) {
            return response()->json(['message' => 'Template is inactive.'], 422);
        }

        /** @var array<string, mixed> $variables */
        $variables = $request->input('variables', []);
        $normalized = $this->normalizeVariableMap($variables);

        $subject = $this->placeholderReplacer->replace($emailConfiguration->subject, $normalized);
        $html = $this->placeholderReplacer->replace($emailConfiguration->html_content, $normalized);
        $text = $emailConfiguration->text_content !== null
            ? $this->placeholderReplacer->replace($emailConfiguration->text_content, $normalized)
            : null;

        try {
            Mail::to($request->string('to')->toString())->send(new TemplatedEmail($subject, $html, $text));
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Failed to send email.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        return response()->json(['message' => 'Email sent.']);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function normalizePayload(array $validated): array
    {
        if (isset($validated['variables']) && is_array($validated['variables'])) {
            $validated['variables'] = array_values(array_map(
                static fn ($v) => is_string($v) ? $v : (string) $v,
                $validated['variables']
            ));
        }

        if (array_key_exists('slug', $validated) && is_string($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $variables
     * @return array<string, string>
     */
    protected function normalizeVariableMap(array $variables): array
    {
        $out = [];

        foreach ($variables as $key => $value) {
            if (! is_string($key) || $key === '') {
                continue;
            }

            if ($value === null) {
                $out[$key] = '';

                continue;
            }

            if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $out[$key] = (string) $value;
            }
        }

        return $out;
    }
}
