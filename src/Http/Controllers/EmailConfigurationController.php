<?php

namespace App\Http\Controllers;

use App\Models\emailConfiguration;
use Illuminate\Http\Request;

class EmailConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List all email templates
        return response()->json(emailConfiguration::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used for API
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:email_configurations,name',
            'subject' => 'required|string',
            'slug' => 'required|string|unique:email_configurations,slug',
            'html_content' => 'required|string',
            'text_content' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean',
            'type' => 'nullable|string',
        ]);

        // Convert variables to JSON if present
        if (isset($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $emailConfig = emailConfiguration::create($data);
        return response()->json($emailConfig, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(emailConfiguration $emailConfiguration)
    {
        return response()->json($emailConfiguration);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(emailConfiguration $emailConfiguration)
    {
        // Not used for API
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, emailConfiguration $emailConfiguration)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|unique:email_configurations,name,' . $emailConfiguration->id,
            'subject' => 'sometimes|required|string',
            'slug' => 'sometimes|required|string|unique:email_configurations,slug,' . $emailConfiguration->id,
            'html_content' => 'sometimes|required|string',
            'text_content' => 'nullable|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean',
            'type' => 'nullable|string',
        ]);

        if (isset($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }

        $data['updated_by'] = auth()->id();

        $emailConfiguration->update($data);
        return response()->json($emailConfiguration);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(emailConfiguration $emailConfiguration)
    {
        $emailConfiguration->delete();
        return response()->json(['message' => 'Deleted']);
    }
    /**
     * Test sending an email using a template.
     */
    public function testSend(Request $request, emailConfiguration $emailConfiguration)
    {
        $data = $request->validate([
            'to' => 'required|email',
            'variables' => 'nullable|array',
        ]);

        // Prepare variables for template
        $variables = $data['variables'] ?? [];
        $html = $emailConfiguration->html_content;
        $text = $emailConfiguration->text_content;
        foreach ($variables as $key => $value) {
            $html = str_replace(['{'.$key.'}', '{{'.$key.'}}'], $value, $html);
            $text = str_replace(['{'.$key.'}', '{{'.$key.'}}'], $value, $text);
        }

        try {
            \Mail::send([], [], function ($message) use ($data, $emailConfiguration, $html, $text) {
                $message->to($data['to'])
                    ->subject($emailConfiguration->subject)
                    ->html($html);
                if (!empty($text)) {
                    $message->text($text);
                }
            });
            return response()->json(['success' => true, 'message' => 'Test email sent.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
