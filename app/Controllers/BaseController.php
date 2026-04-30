<?php

namespace App\Controllers;

use App\Services\BreadcrumbService;
use App\Services\MenuBuilderService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /** @var \CodeIgniter\HTTP\IncomingRequest */
    protected $request;

    /** @var \CodeIgniter\HTTP\ResponseInterface */
    protected $response;

    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    protected $helpers = ['menu'];
    protected $menuBuilder;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->menuBuilder = new MenuBuilderService();
    }

    /**
     * Render view dengan otomatis menyisipkan breadcrumb (sementara manual)
     */
    protected function renderView(string $view, array $data = [])
    {
        // Breadcrumb
        $breadcrumbService = new BreadcrumbService();
        $data['breadcrumbs'] = $breadcrumbService->generate();

        // Sidebar menu (dengan pengecekan user nanti)
        $data['sidebarMenu'] = $this->menuBuilder->buildForUser();

        return view($view, $data);
    }

    /**
     * Breadcrumb sementara berdasarkan URI
     */
    protected function generateBreadcrumb(): array
    {
        $segments = $this->request->getUri()->getSegments();
        $breadcrumbs = [];
        $url = '';

        // Home
        $breadcrumbs[] = ['title' => 'Home', 'url' => site_url()];

        foreach ($segments as $i => $segment) {
            $url .= '/' . $segment;
            $isLast = ($i === count($segments) - 1);
            $breadcrumbs[] = [
                'title' => ucfirst(str_replace('-', ' ', $segment)),
                'url' => $isLast ? null : site_url($url)  // item terakhir tidak punya link
            ];
        }

        return $breadcrumbs;
    }
}
