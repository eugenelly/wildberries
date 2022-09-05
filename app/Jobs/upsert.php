<?php

namespace App\Jobs;

use App\Helpers\WildberriesHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class upsert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected WildberriesHelper $wildberries;
    protected string $data_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WildberriesHelper $wildberries, string $data_name)
    {
        $this->wildberries = $wildberries;
        $this->data_name = $data_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // получение данных по GET-запросу
        $data = $this->wildberries->get($this->data_name);

        // коррекция списка продаж по реализации (добавление поля 'ppvz_office_name' там где его нет)
        if ($this->data_name === 'reportDetailByPeriod') {
            for ($i = 0; $i < count($data); $i++) {
                if (count($data[$i]) === 50) {
                    $data[$i]['ppvz_office_name'] = null;
                }
            }
        }

        // выгрузка данных в БД
        $this->wildberries->upsert(
            ($this->data_name === 'excise-goods') ? 'excise_goods' : $this->data_name,
            $data
        );
    }
}
