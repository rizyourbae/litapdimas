<?php

namespace App\Cells;

use App\Models\NotificationModel;

class NotificationCell
{
    public function render(): string
    {
        $auth = service('auth');
        if (!$auth->isLoggedIn()) {
            return '';
        }

        $userId = $auth->userId();
        $model = new NotificationModel();
        
        $data = [
            'unreadCount' => $model->countUnread($userId),
            'latest'      => $model->getLatestByUser($userId, 5)
        ];

        return view('cells/notification_cell', $data);
    }
}
