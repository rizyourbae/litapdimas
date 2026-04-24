<?php

namespace App\Services;

# use CodeIgniter\Config\BaseConfig;

class MenuBuilderService
{
    protected $menuConfig;

    public function __construct()
    {
        // Nanti kita ambil dari Config/Menu.php
        $this->menuConfig = config('Menu');
    }

    /**
     * Bangun menu untuk user yang sedang login (berdasarkan permission).
     */
    public function buildForUser($user = null): array
    {
        $menu = $this->menuConfig->sidebar;
        $auth = service('auth');

        // Filter menu berdasarkan permission user (jika tidak login, return kosong)
        if (!$auth->isLoggedIn()) {
            return [];
        }

        $filtered = $this->filterByPermission($menu, $auth);
        return $this->prepareMenu($filtered);
    }

    /**
     * Filter item menu berdasarkan permission user secara rekursif.
     */
    protected function filterByPermission(array $items, $auth): array
    {
        $result = [];
        foreach ($items as $item) {
            $permission = $item['permission'] ?? null;

            // Tampilkan jika tidak ada permission gate atau user memiliki permission
            if ($permission === null || $auth->can($permission)) {
                if (!empty($item['children'])) {
                    $item['children'] = $this->filterByPermission($item['children'], $auth);
                    // Sembunyikan parent jika tidak ada anak yang lolos filter
                    if (empty($item['children'])) {
                        continue;
                    }
                }
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * Ubah struktur config jadi array sederhana dengan penanda active berdasarkan URI saat ini.
     */
    protected function prepareMenu(array $items, string $parentUrl = ''): array
    {
        $request = \Config\Services::request();
        $currentUrl = $request->getUri()->getPath();

        $result = [];
        foreach ($items as $item) {
            $url = $item['url'] ?? '#';
            if ($url !== '#' && strpos($url, '://') === false) {
                $url = site_url($url);
            }

            $isActive = $this->isActive($url, $currentUrl, $item['children'] ?? []);

            $menuItem = [
                'label'      => $item['label'],
                'icon'       => $item['icon'] ?? 'bi-circle',
                'url'        => $url,
                'active'     => $isActive,
                'expand'     => $isActive || ($item['expanded'] ?? false), // buka submenu jika aktif
                'badge'      => $item['badge'] ?? null,
                'permission' => $item['permission'] ?? null,
                'children'   => [],
            ];

            if (!empty($item['children'])) {
                $menuItem['children'] = $this->prepareMenu($item['children'], $url);
            }

            $result[] = $menuItem;
        }
        return $result;
    }

    /**
     * Deteksi apakah menu ini aktif (berdasarkan URL).
     * Untuk item dengan anak, aktif jika anak ada yang aktif.
     */
    private function isActive(string $url, string $currentUrl, array $children = []): bool
    {
        // Jika URL menu bukan '#' dan cocok dengan current URL, aktif.
        if ($url !== '#' && $currentUrl === parse_url($url, PHP_URL_PATH)) {
            return true;
        }

        // Jika punya anak, cek secara rekursif
        foreach ($children as $child) {
            $childUrl = $child['url'] ?? '#';
            if ($childUrl !== '#' && $currentUrl === site_url($childUrl)) {
                return true;
            }
            if (!empty($child['children'])) {
                if ($this->isActive('', $currentUrl, $child['children'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
