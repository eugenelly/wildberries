<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    public function __invoke(): Response
    {
        echo '<pre>';
        Artisan::call(
            'wildberries:upsert '
            . config('services')['wildberries']['key']
            . ' 2022-06-01'
        );
        echo '</pre>';

        return new Response('Запрос выполнен и обработан успешно');
    }
}
