<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Aws\S3\S3Client;

class S3Config {
    public static function getClient(): S3Client {
        $config = [
            'version' => 'latest',
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? '',
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? '',
            ],
        ];

        if (!empty($_ENV['AWS_S3_ENDPOINT'])) {
            $config['endpoint'] = $_ENV['AWS_S3_ENDPOINT'];
            $config['use_path_style_endpoint'] = true;
        }

        return new S3Client($config);
    }

    public static function getBucketName(): string {
        return $_ENV['AWS_BUCKET'] ?? '';
    }
}
