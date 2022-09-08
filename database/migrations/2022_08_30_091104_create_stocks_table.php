<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполнить миграцию Stocks
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('lastChangeDate'); // дата и время обновления информации в сервисе
            $table->char('supplierArticle', 75); // артикул поставщика
            $table->char('techSize', 30); // размер
            $table->char('barcode', 30)->index(); // штрих-код
            $table->integer('quantity'); // кол-во доступное для продажи
            $table->integer('isSupply'); // договор поставки
            $table->integer('isRealization'); // договор реализации
            $table->integer('quantityFull'); // кол-во полное
            $table->integer('quantityNotInOrders'); // кол-во не в заказе
            $table->char('warehouseName', 50); // название склада
            $table->integer('inWayToClient'); // в пути к клиенту (штук)
            $table->integer('inWayFromClient'); // в пути от клиента (штук)
            $table->integer('nmId')->index(); // код WB
            $table->char('subject', 50); // предмет
            $table->char('category', 50); // категория
            $table->integer('daysOnSite'); // кол-во дней на сайте
            $table->char('brand', 50); // бренд
            $table->char('SCCode', 50); // код контракта
            $table->integer('warehouse')->index(); // уникальный идентификатор склада
            $table->decimal('Price'); // цена товара
            $table->integer('Discount'); // скидка на товар установленная продавцом
            $table->timestamps();
        });
    }

    /**
     * Откатить миграцию Stocks
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
