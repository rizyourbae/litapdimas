<?php

namespace App\Services;

class BreadcrumbService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('Breadcrumbs');
    }

    public function generate(): array
    {
        $request = \Config\Services::request();
        $segments = $request->getUri()->getSegments();
        $breadcrumbs = [];
        $url = '';

        // Home selalu ada
        $breadcrumbs[] = ['title' => 'Home', 'url' => site_url()];

        foreach ($segments as $i => $segment) {
            $url .= '/' . $segment;
            $routeName = $this->getRouteName($segments, $i);

            $title = $this->getTitle($routeName, $segment);
            $isLast = ($i === count($segments) - 1);
            $breadcrumbs[] = [
                'title' => $title,
                'url'   => $isLast ? null : site_url($url),
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Dapatkan nama rute berdasarkan akumulasi segmen sampai indeks tertentu.
     * Untuk sekarang kita mapping manual dari config Breadcrumbs.
     */
    protected function getRouteName(array $segments, int $index): string
    {
        $path = implode('/', array_slice($segments, 0, $index + 1));
        return $path;
    }

    /**
     * Ambil judul breadcrumb dari config jika ada, kalau tidak fallback ke ucwords segment.
     */
    protected function getTitle(string $path, string $segment): string
    {
        $map = $this->config->titles ?? [];
        if (isset($map[$path])) {
            return $map[$path];
        }
        return ucfirst(str_replace('-', ' ', $segment));
    }
}
