<?php

namespace App\Services;

use App\Models\NotificationModel;

class NotificationService
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Mengirim notifikasi ke user
     */
    public function send(int $userId, string $title, string $message, string $link = null, string $type = 'info'): bool
    {
        $data = [
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'type'    => $type,
            'is_read' => false
        ];

        return (bool) $this->notificationModel->insert($data);
    }

    /**
     * Tandai semua notifikasi user sebagai terbaca
     */
    public function markAllAsRead(int $userId): bool
    {
        return $this->notificationModel
                    ->where('user_id', $userId)
                    ->set(['is_read' => true])
                    ->update();
    }

    /**
     * Tandai satu notifikasi sebagai terbaca
     */
    public function markAsRead(int $id): bool
    {
        return $this->notificationModel->update($id, ['is_read' => true]);
    }
}
