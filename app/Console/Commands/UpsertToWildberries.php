<?php

namespace App\Console\Commands;

use App\Jobs;
use Illuminate\Console\Command;

use App\Helpers\WildberriesHelper;

class UpsertToWildberries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wildberries:upload {key} {dateFrom} {--dateTo=} {--flag=} {--limit=} {--rrdid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение данных через RESTful и выгрузка в БД (wildberries_db)';

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
        Jobs\upload::dispatch($wildberries, 'incomes');
        Jobs\upload::dispatch($wildberries, 'stocks');
        Jobs\upload::dispatch($wildberries, 'orders');
        Jobs\upload::dispatch($wildberries, 'sales');
        Jobs\upload::dispatch($wildberries, 'reportDetailByPeriod');
        Jobs\upload::dispatch($wildberries, 'excise-goods');
    }
}
