<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        // Data untuk landing page (nanti bisa dinamis dari database)
        $data = [
            'title'       => 'Litapdimas - Direktori Data',
            'currentPage' => 'home',
        ];

        // Gunakan layout khusus landing (tanpa sidebar)
        return view('layouts/landing', $data);
    }
}
