<?php

namespace App\Services;

class MenuBuilderService
{
    protected $menuConfig;

    public function __construct()
    {
        $this->menuConfig = config('Menu');
    }

    public function buildForUser($user = null): array
    {
        $menu = isset($this->menuConfig->sidebar) ? $this->menuConfig->sidebar : [];
        $auth = service('auth');

        if (!$auth->isLoggedIn()) {
            return [];
        }

        $filtered = $this->filterByPermission($menu, $auth);
        return $this->prepareMenu($filtered);
    }

    protected function filterByPermission(array $items, $auth): array
    {
        $result = [];
        foreach ($items as $item) {
            $permission = $item['permission'] ?? null;

            if ($permission === null || $auth->can($permission)) {
                if (!empty($item['children'])) {
                    $item['children'] = $this->filterByPermission($item['children'], $auth);
                    if (empty($item['children'])) {
                        continue;
                    }
                }
                $result[] = $item;
            }
        }
        return $result;
    }

    protected function prepareMenu(array $items, string $parentUrl = ''): array
    {
        $result = [];
        foreach ($items as $item) {
            $rawUrl = $item['url'] ?? '#';

            // Format link URL untuk tag href
            $url = $rawUrl;
            if ($url !== '#' && strpos($url, '://') === false) {
                $url = site_url($url);
            }

            // Panggil logic pengecekan active yang baru
            $isActive = $this->isActive($rawUrl, $item['children'] ?? []);

            $menuItem = [
                'label'      => $item['label'],
                'icon'       => $item['icon'] ?? 'bi-circle',
                'url'        => $url,
                'active'     => $isActive,
                'expand'     => $isActive || ($item['expanded'] ?? false),
                'badge'      => $item['badge'] ?? null,
                'permission' => $item['permission'] ?? null,
                'children'   => [],
            ];

            if (!empty($item['children'])) {
                $menuItem['children'] = $this->prepareMenu($item['children'], $rawUrl);
            }

            $result[] = $menuItem;
        }
        return $result;
    }

    /**
     * LOGIKA BARU YANG KEBAL PELURU (Absolute URL Check)
     */
    private function isActive(string $rawUrl, array $children = []): bool
    {
        if ($rawUrl !== '#') {
            // 1. Ambil Full URL saat ini (contoh: http://localhost/litapdimas/public/admin/users)
            $currentUrl = rtrim(current_url(), '/');

            // 2. Format target menjadi Full URL
            $targetUrl = (strpos($rawUrl, '://') !== false) ? $rawUrl : site_url($rawUrl);
            $targetUrl = rtrim($targetUrl, '/');

            // EXACT MATCH: Kalau URL-nya sama persis, langsung true
            if ($currentUrl === $targetUrl) {
                return true;
            }

            // SUB-PATH MATCH: Untuk halaman edit/create (contoh target: /admin/users, current: /admin/users/create)
            // Kita cegah URL Dashboard (/admin) dan Base (/) agar tidak nyala di semua sub-menu
            $dashboardUrl = rtrim(site_url('admin'), '/');
            $homeUrl = rtrim(site_url('/'), '/');

            if ($targetUrl !== $dashboardUrl && $targetUrl !== $homeUrl) {
                // Cek apakah string current_url diawali dengan target_url + '/'
                if (strpos($currentUrl, $targetUrl . '/') === 0) {
                    return true;
                }
            }
        }

        // 3. REKURSIF: Kalau submenu ada yang aktif, parent-nya otomatis harus aktif
        foreach ($children as $child) {
            $childUrl = $child['url'] ?? '#';
            if ($this->isActive($childUrl, $child['children'] ?? [])) {
                return true;
            }
        }

        return false;
    }
}
