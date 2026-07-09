<?php

require_once __DIR__ . '/../config/s3.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Service {
    private S3Client $client;
    private string $bucket;

    public function __construct() {
        $this->client = S3Config::getClient();
        $this->bucket = S3Config::getBucketName();
    }

    public function uploadFile(string $key, string $filePath, ?string $contentType = null, string $acl = 'public-read'): ?string {
        if (!file_exists($filePath)) {
            return null;
        }

        if ($contentType === null) {
            $contentType = mime_content_type($filePath) ?: 'application/octet-stream';
        }

        try {
            $result = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => $filePath,
                'ACL' => $acl,
                'ContentType' => $contentType,
            ]);

            return $result['ObjectURL'] ?? null;
        } catch (AwsException $e) {
            error_log('S3 upload error: ' . $e->getMessage());
            return null;
        }
    }

    public function overwriteFile(string $key, string $filePath, ?string $contentType = null, string $acl = 'public-read'): ?string {
        return $this->uploadFile($key, $filePath, $contentType, $acl);
    }

    public function deleteFile(string $key): bool {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
            ]);

            return true;
        } catch (AwsException $e) {
            error_log('S3 delete error: ' . $e->getMessage());
            return false;
        }
    }

    public function getFileUrl(string $key): string {
        return sprintf('https://%s.s3.%s.amazonaws.com/%s', $this->bucket, $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1', ltrim($key, '/'));
    }
}
