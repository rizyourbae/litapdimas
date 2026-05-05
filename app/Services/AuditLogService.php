<?php

namespace App\Services;

use App\Models\AuditLogModel;
use Config\Services;

/**
 * AuditLogService
 * 
 * "Fat" Service Layer for handling all logging business logic.
 * Follows SRP: Responsible only for activity logging.
 */
class AuditLogService
{
    protected AuditLogModel $model;

    public function __construct()
    {
        $this->model = new AuditLogModel();
    }

    /**
     * Log an activity
     * 
     * @param string $action The action performed (VIEW_FILE, LOGIN, etc)
     * @param string|null $resourceType Type of resource (proposal, logbook, etc)
     * @param string|null $resourceId UUID or ID of the resource
     * @param string|null $description Custom detail message
     * @return bool
     */
    public function log(string $action, ?string $resourceType = null, ?string $resourceId = null, ?string $description = null): bool
    {
        $request = Services::request();
        $auth = service('auth');
        
        $data = [
            'user_id'       => (int) (user()['id'] ?? 0) ?: null,
            'action'        => strtoupper($action),
            'resource_type' => $resourceType,
            'resource_id'   => $resourceId,
            'description'   => $description,
            'ip_address'    => $request->getIPAddress(),
            'user_agent'    => (string) $request->getUserAgent(),
            'created_at'    => date('Y-m-d H:i:s')
        ];

        return (bool) $this->model->insert($data);
    }

    /**
     * Prepare logs for Admin View
     * 
     * Handles all formatting logic so the View stays clean.
     */
    public function getFormattedLogs(int $limit = 100): array
    {
        $logs = $this->model->getLatestLogs($limit);
        
        return array_map(function($log) {
            return [
                'id'            => $log->id,
                'user'          => $log->user_name ?: ($log->username ?: 'Guest'),
                'action_label'  => $this->formatActionLabel($log->action),
                'action_class'  => $this->getActionClass($log->action),
                'resource'      => $log->resource_type ? ucfirst($log->resource_type) : '-',
                'description'   => $log->description ?: '-',
                'ip'            => $log->ip_address,
                'time_ago'      => $this->timeAgo($log->created_at),
                'date_full'     => date('d M Y H:i:s', strtotime($log->created_at))
            ];
        }, $logs);
    }

    private function formatActionLabel(string $action): string
    {
        return str_replace('_', ' ', $action);
    }

    private function getActionClass(string $action): string
    {
        return match($action) {
            'LOGIN'       => 'bg-success',
            'LOGOUT'      => 'bg-secondary',
            'VIEW_FILE'   => 'bg-info',
            'UPLOAD'      => 'bg-primary',
            'DELETE'      => 'bg-danger',
            'APPROVE'     => 'bg-success',
            'REJECT'      => 'bg-danger',
            'SUBMIT'      => 'bg-warning text-dark',
            default       => 'bg-light text-dark'
        };
    }

    private function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;
        
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff/60) . ' menit lalu';
        if ($diff < 86400) return floor($diff/3600) . ' jam lalu';
        
        return date('d M Y', $time);
    }
}
