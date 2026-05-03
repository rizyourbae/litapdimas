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

        if ($this->shouldTrimTrailingIdentifier($segments)) {
            array_pop($segments);
        }

        // Home selalu ada
        $breadcrumbs[] = ['title' => 'Home', 'url' => site_url()];

        foreach ($segments as $i => $segment) {
            $routeName = $this->getRouteName($segments, $i);

            $title = $this->getTitle($routeName, $segment);
            $isLast = ($i === count($segments) - 1);
            $breadcrumbs[] = [
                'title' => $title,
                'url'   => $isLast ? null : $this->buildBreadcrumbUrl($segments, $i, $segment),
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

    protected function shouldTrimTrailingIdentifier(array $segments): bool
    {
        if (count($segments) < 2) {
            return false;
        }

        $lastSegment = (string) end($segments);
        $previousSegment = (string) ($segments[count($segments) - 2] ?? '');

        if (! $this->looksLikeIdentifier($lastSegment)) {
            return false;
        }

        return in_array($previousSegment, [
            'show',
            'edit',
            'update',
            'detail',
            'view',
            'resetPassword',
            'print',
        ], true);
    }

    protected function looksLikeIdentifier(string $segment): bool
    {
        if ($segment === '' || ctype_digit($segment)) {
            return true;
        }

        if (preg_match('/^id[-_][a-z0-9]+$/i', $segment) === 1) {
            return true;
        }

        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $segment) === 1) {
            return true;
        }

        return strlen($segment) >= 16 && preg_match('/^[A-Za-z0-9_-]+$/', $segment) === 1;
    }

    protected function buildBreadcrumbUrl(array $segments, int $index, string $segment): string
    {
        if ($index === 0) {
            return match ($segment) {
                'admin' => site_url('admin/dashboard'),
                'dosen' => site_url('dosen/dashboard'),
                'reviewer' => site_url('reviewer/dashboard'),
                default => site_url($segment),
            };
        }

        return site_url(implode('/', array_slice($segments, 0, $index + 1)));
    }
}
