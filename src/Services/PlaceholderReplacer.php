<?php

namespace Karja\EmailConfig\Services;

class PlaceholderReplacer
{
    /**
     * Replace {{ key }} style placeholders in a string.
     *
     * @param  array<string, string|int|float|bool|null>  $variables
     */
    public function replace(string $content, array $variables): string
    {
        if ($variables === []) {
            return $content;
        }

        return (string) preg_replace_callback(
            '/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/',
            function (array $matches) use ($variables): string {
                $key = $matches[1];

                if (! array_key_exists($key, $variables)) {
                    return $matches[0];
                }

                $value = $variables[$key];

                if ($value === null) {
                    return '';
                }

                if (is_bool($value)) {
                    return $value ? '1' : '0';
                }

                return (string) $value;
            },
            $content
        );
    }
}
