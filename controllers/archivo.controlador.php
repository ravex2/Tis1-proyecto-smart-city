<?php

require_once __DIR__ . '/../services/S3Service.php';

class ArchivoController {
    private S3Service $s3Service;

    public function __construct() {
        $this->s3Service = new S3Service();
    }

    public function subirArchivo(array $archivo, string $folder = ''): array {
        if (empty($archivo['tmp_name']) || $archivo['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'El archivo no se pudo cargar correctamente.',
            ];
        }

        $nombreArchivo = basename($archivo['name']);
        $carpeta = trim($folder, '/');
        $key = ($carpeta !== '' ? $carpeta . '/' : '') . uniqid('', true) . '_' . $nombreArchivo;
        $url = $this->s3Service->uploadFile($key, $archivo['tmp_name'], $archivo['type'] ?? null);

        if ($url === null) {
            return [
                'success' => false,
                'message' => 'No se pudo subir el archivo a S3.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Archivo subido correctamente.',
            'key' => $key,
            'url' => $url,
        ];
    }

    public function sobrescribirArchivo(string $key, array $archivo): array {
        if (empty($archivo['tmp_name']) || $archivo['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'El archivo no se pudo cargar correctamente para sobrescribir.',
            ];
        }

        $url = $this->s3Service->overwriteFile($key, $archivo['tmp_name'], $archivo['type'] ?? null);

        if ($url === null) {
            return [
                'success' => false,
                'message' => 'No se pudo sobrescribir el archivo en S3.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Archivo sobrescrito correctamente.',
            'key' => $key,
            'url' => $url,
        ];
    }

    public function eliminarArchivo(string $key): bool {
        return $this->s3Service->deleteFile($key);
    }
}
