<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Helpers\WildberriesHelper;

class upload implements ShouldQueue
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

        // выгрузка данных в БД
        $this->wildberries->upload(
            ($this->data_name === 'excise-goods') ? 'excise_goods' : $this->data_name,
            $data
        );
    }
}
