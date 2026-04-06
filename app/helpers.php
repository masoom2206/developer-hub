<?php

if (!function_exists('highlightSearch')) {
    function highlightSearch(string $text, string $query): string
    {
        if (empty($query)) {
            return e($text);
        }

        $escaped = e($text);
        $pattern = preg_quote(e($query), '/');

        return preg_replace(
            "/({$pattern})/i",
            '<mark class="bg-yellow-200 text-yellow-900 px-0.5 rounded">$1</mark>',
            $escaped
        );
    }
}
