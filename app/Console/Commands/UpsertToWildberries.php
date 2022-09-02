<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\WildberriesHelper;

class UpsertToWildberries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wildberries:upsert {key} {dateFrom}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление БД Wildberries';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $props = $this->argument();

        $otherProps = WildberriesHelper::checkInput(
            $props,
            $this->options()
        );

        // идентификация в Wildberries
        $wildberries = new WildberriesHelper(
            $props['key'],
            $props['dateFrom'],
            $otherProps
        );

        // получение списка поставок (поступлений)
        $incomes = $wildberries->get('incomes');
        // получение списка склада (остатков)
        $stocks = $wildberries->get('stocks');
        // получение списка заказов (от покупателей)
        $orders = $wildberries->get('orders');
        // получение списка продаж
        $sales = $wildberries->get('sales');
        // получение списка продаж по реализации
        $reportDetailByPeriod = $wildberries->get('reportDetailByPeriod');

        // коррекция списка продаж по реализации (добавление поля 'ppvz_office_name' там где его нет)
        for ($i = 0; $i < count($reportDetailByPeriod); $i++) {
            if (count($reportDetailByPeriod[$i]) === 50) {
                $reportDetailByPeriod[$i]['ppvz_office_name'] = null;
            }
        }
        // получение отчета по КиЗам
        $excise_goods = $wildberries->get('excise_goods');

        // выгрузка списка поставок в БД
        $wildberries->upsertToWildberriesBD('incomes', $incomes);
        // выгрузка списка склада в БД
        $wildberries->upsertToWildberriesBD('stocks', $stocks);
        // выгрузка списка заказов в БД
        $wildberries->upsertToWildberriesBD('orders', $orders);
        // выгрузка списка продаж в БД
        $wildberries->upsertToWildberriesBD('sales', $sales);
        // выгрузка списка о продажах по реализации в БД
        $wildberries->upsertToWildberriesBD('reportDetailByPeriod', $reportDetailByPeriod);
        // выгрузка отчета по КиЗам в БД
        $wildberries->upsertToWildberriesBD('excise_goods', $excise_goods);
    }
}
