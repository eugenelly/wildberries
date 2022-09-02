<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    public function __invoke()
    {
        echo '<pre>';
        Artisan::call('wildberries:upsert ' . env('KEY_API_WILDBERRIES') . '2022-06-01');
        echo '</pre>';

        return new Response('Запрос выполнен и обработан успешно');
    }
}
