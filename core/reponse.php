<?php

declare(strict_types=1);

namespace Proyecto\core;

class Response
{
    public function json(
        array $data,
        int $status = 200
    ): void {

        http_response_code($status);

        header('Content-Type: application/json');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );
    }

    public function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }

    public function view(
        string $view,
        array $data = []
    ): void {
        extract($data);
        require_once dirname(__DIR__, 2)
            . "/resources/views/{$view}.php";
    }
}