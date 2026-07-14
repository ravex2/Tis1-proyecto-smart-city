<?php

class NotificationClient {
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;

    public function __construct(string $baseUrl = 'http://localhost:3001', string $apiKey = '', int $timeout = 5) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
    }

    /**
     * Crear una notificación
     */
    public function create(int $userId, string $title, string $message, array $options = []): array {
        $data = array_merge([
            'userId' => (string)$userId,
            'title' => $title,
            'message' => $message,
        ], $options);

        return $this->request('POST', '/api/notifications', $data);
    }

    /**
     * Enviar notificación a múltiples usuarios
     */
    public function broadcast(array $userIds, string $title, string $message, array $options = []): array {
        $data = array_merge([
            'userIds' => array_map('strval', $userIds),
            'title' => $title,
            'message' => $message,
        ], $options);

        return $this->request('POST', '/api/notifications/broadcast', $data);
    }

    /**
     * Obtener notificaciones de un usuario
     */
    public function getUserNotifications(int $userId, int $page = 1, int $limit = 20, bool $unreadOnly = false): array {
        $query = http_build_query([
            'page' => $page,
            'limit' => $limit,
            'unread_only' => $unreadOnly ? 'true' : 'false'
        ]);

        return $this->request('GET', "/api/notifications/{$userId}?{$query}");
    }

    /**
     * Obtener solo el conteo de no leídas (muy rápido)
     */
    public function getUnreadCount(int $userId): int {
        $result = $this->request('GET', "/api/notifications/{$userId}/unread-count");
        return $result['unreadCount'] ?? 0;
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead(int $userId, string $notificationId): array {
        return $this->request('PATCH', "/api/notifications/{$userId}/{$notificationId}/read");
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead(int $userId): array {
        return $this->request('PATCH', "/api/notifications/{$userId}/read-all");
    }

    /**
     * Eliminar una notificación
     */
    public function delete(int $userId, string $notificationId): array {
        return $this->request('DELETE', "/api/notifications/{$userId}/{$notificationId}");
    }

    
    /** Realizar petición HTTP */
    private function request(string $method, string $endpoint, array $data = []): array {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Error de conexión: {$error}");
        }

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new Exception("Error HTTP {$httpCode}: " . ($result['error'] ?? 'Error desconocido'));
        }

        return $result;
    }
}