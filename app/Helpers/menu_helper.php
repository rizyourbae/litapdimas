<?php
if (!function_exists('render_menu')) {
    function render_menu(array $menu, int $level = 0): string
    {
        $html = '';
        foreach ($menu as $item) {
            $hasChildren = !empty($item['children']);
            $activeClass = $item['active'] ? ' active' : '';
            $expandClass = ($hasChildren && $item['expand']) ? ' menu-open' : '';
            $badge = $item['badge'] ?? '';

            $html .= '<li class="nav-item' . $expandClass . '">';
            $html .= '<a href="' . esc($item['url']) . '" class="nav-link' . $activeClass . '">';
            $html .= '<i class="nav-icon bi ' . esc($item['icon']) . '"></i>';
            $html .= '<p>' . esc($item['label']);
            if ($hasChildren) {
                $html .= '<i class="nav-arrow bi bi-chevron-right"></i>';
            }
            if ($badge) {
                $html .= '<span class="nav-badge badge text-bg-secondary me-3">' . esc($badge) . '</span>';
            }
            $html .= '</p></a>';

            if ($hasChildren) {
                $subLevel = $level + 1;
                $html .= '<ul class="nav nav-treeview">';
                $html .= render_menu($item['children'], $subLevel); // rekursif
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}
