<?php

namespace App\Controllers;

use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaController extends BaseController
{
    /**
     * Generate and output a captcha image
     * 
     * Memisahkan logika Image Generation dari AuthController 
     * untuk memenuhi Single Responsibility Principle (SRP).
     */
    public function generate()
    {
        // 5 karakter kombinasi huruf kapital dan angka (tanpa O, 0, I, 1)
        $phraseBuilder = new PhraseBuilder(5, 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
        $builder = new CaptchaBuilder(null, $phraseBuilder);

        // Styling: Background putih bersih
        $builder->setBackgroundColor(255, 255, 255);
        
        // Text warna hijau gelap
        $builder->setTextColor(15, 117, 57);
        
        // Tanpa coretan garis yang mengganggu
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);

        $builder->build(150, 50);

        // Simpan ke session
        session()->set('captcha_phrase', $builder->getPhrase());

        // Keluarkan sebagai image JPEG
        $this->response->setHeader('Content-Type', 'image/jpeg');
        $this->response->setBody($builder->get());
        
        return $this->response;
    }
}
