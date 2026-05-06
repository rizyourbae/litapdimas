<?php

namespace App\Controllers;

use App\Services\NotificationService;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationService;
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Halaman daftar semua notifikasi
     */
    public function index()
    {
        $userId = service('auth')->userId();
        
        $data = [
            'title' => 'Semua Notifikasi',
            'notifications' => $this->notificationModel
                                ->where('user_id', $userId)
                                ->orderBy('created_at', 'DESC')
                                ->paginate(20),
            'pager' => $this->notificationModel->pager
        ];

        return $this->renderView('notifications/index', $data);
    }

    /**
     * Baca notifikasi dan redirect ke tujuan
     */
    public function read(int $id)
    {
        $userId = service('auth')->userId();
        $notification = $this->notificationModel->where('id', $id)->where('user_id', $userId)->first();

        if ($notification) {
            $this->notificationService->markAsRead($id);
            if ($notification->link) {
                return redirect()->to($notification->link);
            }
        }

        return redirect()->back();
    }

    /**
     * Tandai semua sebagai terbaca
     */
    public function markAllAsRead()
    {
        $userId = service('auth')->userId();
        $this->notificationService->markAllAsRead($userId);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai terbaca.');
    }
}
