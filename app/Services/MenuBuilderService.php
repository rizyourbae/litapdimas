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
     * Bangun menu untuk user yang sedang login (berdasarkan role/permission).
     * Saat ini belum ada auth, jadi kembalikan menu default (semua item).
     */
    public function buildForUser($user = null): array
    {
        $menu = $this->menuConfig->sidebar;

        // Jika sudah ada user dan sistem permission, kita bisa filter di sini.
        // Contoh nanti: filter by can($item['permission'])
        return $this->prepareMenu($menu);
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
