<?php

declare(strict_types=1);

namespace Proyecto\core;

class Request
{
    public static function capture(): self
    {
        return new self();
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function uri(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function input(
        string $key,
        mixed $default = null
    ): mixed {
        return $_POST[$key]
            ?? $_GET[$key]
            ?? $default;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    public function header(string $key): ?string
    {
        $headers = getallheaders();

        return $headers[$key] ?? null;
    }

    public function json(): array
    {
        $input = file_get_contents('php://input');

        return json_decode($input, true) ?? [];
    }
}