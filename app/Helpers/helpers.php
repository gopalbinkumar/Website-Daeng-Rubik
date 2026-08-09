<?php

if (! function_exists('assetVersion')) {
    function assetVersion(string $path): string
    {
        $assetPath = ltrim($path, '/');
        $filePath = parse_url($assetPath, PHP_URL_PATH);

        if (! is_string($filePath) || $filePath === '') {
            return asset($assetPath);
        }

        $file = public_path($filePath);

        if (! is_file($file)) {
            return asset($assetPath);
        }

        $url = asset($assetPath);
        $fragment = '';
        $fragmentPosition = strpos($url, '#');

        if ($fragmentPosition !== false) {
            $fragment = substr($url, $fragmentPosition);
            $url = substr($url, 0, $fragmentPosition);
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'v=' . filemtime($file) . $fragment;
    }
}
