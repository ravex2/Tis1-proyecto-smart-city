<?php

class NotificationController {
    private $model;
    public function __construct(){
        $this->model = new Sesion();
    }

    
    // En tu controlador o servicio PHP
    function enviarNotificacionMensaje(int $receptorId, string $remitenteNombre, int $mensajeId): void {
        $client = new NotificationClient();
        
        $client->create(
            userId: $receptorId,
            title: 'Nuevo mensaje',
            message: "{$remitenteNombre} te envió un mensaje",
            options: [
                'type' => 'info',
                'priority' => 'normal',
                'data' => [
                    'action' => 'open_message',
                    'messageId' => $mensajeId,
                    'sender' => $remitenteNombre
                ]
            ]
        );
    }

    // En un evento de compra completada
    function notificarCompraCompletada(int $userId, float $total): void {
        $client = new NotificationClient();
        
        $client->create(
            userId: $userId,
            title: '¡Compra exitosa!',
            message: "Tu compra por $" . number_format($total, 2) . " ha sido procesada",
            options: [
                'type' => 'success',
                'priority' => 'normal'
            ]
        );
    }

    // Notificación masiva (ej: actualización del sistema)
    function notificarMantenimiento(array $userIds, string $fecha): void {
        $client = new NotificationClient();
        
        $client->broadcast(
            userIds: $userIds,
            title: 'Mantenimiento programado',
            message: "El sistema estará en mantenimiento el {$fecha}",
            options: [
                'type' => 'warning',
                'priority' => 'high'
            ]
        );
    }

}

?>


