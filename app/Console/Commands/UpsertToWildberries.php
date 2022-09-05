<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs;

use App\Helpers\WildberriesHelper;

class UpsertToWildberries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wildberries:upsert {key} {dateFrom} {--dateTo=} {--flag=} {--limit=} {--rrdid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение данных через RESTful и выгрузка в БД (WildberriesDB)';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        // обработка переданной в консоль команды и извлечение из нее аргументов и параметров
        $props = WildberriesHelper::input(
            $this->arguments(),
            $this->options()
        );

        // идентификация в Wildberries
        $wildberries = new WildberriesHelper(
            $props['key'],
            $props['dateFrom'],
            [
                $props['flag'],
                $props['limit'],
                $props['rrdid'],
            ]
        );

        // получение данных по GET-запросам и выгрузка их в БД
        Jobs\upsert::dispatch($wildberries, 'incomes');
        Jobs\upsert::dispatch($wildberries, 'stocks');
        Jobs\upsert::dispatch($wildberries, 'orders');
        Jobs\upsert::dispatch($wildberries, 'sales');
        Jobs\upsert::dispatch($wildberries, 'reportDetailByPeriod');
        Jobs\upsert::dispatch($wildberries, 'excise-goods');
    }
}
