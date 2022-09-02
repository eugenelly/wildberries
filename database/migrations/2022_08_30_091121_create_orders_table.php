<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполнить миграцию Orders
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('gNumber', 50); // номер заказа
            $table->date('date'); // дата заказа
            $table->dateTime('lastChangeDate'); // дата время обновления информации в сервисе
            $table->char('supplierArticle', 75); // ваш артикул
            $table->char('techSize', 30); // размер
            $table->char('barcode', 30); // штрих-код
            $table->decimal('totalPrice'); // цена до согласованной скидки/промо/спп
            $table->integer('discountPercent'); // согласованный итоговый дисконт
            $table->char('warehouseName', 50); // склад отгрузки
            $table->char('oblast', 200); // область
            $table->integer('incomeID'); // номер поставки
            $table->bigInteger('odid')->unique(); // уникальный идентификатор позиции заказа
            $table->integer('nmId'); // Код WB
            $table->char('subject', 50); // предмет
            $table->char('category', 50); // категория
            $table->char('brand'); // бренд
            $table->integer('isCancel'); // Отмена заказа. 1 – заказ отменен до оплаты
            $table->char('sticker'); // аналогично стикеру, который клеится на товар в процессе сборки
            $table->dateTime('cancel_dt');
            $table->char('srid');
            $table->timestamps();
        });
    }

    /**
     * Откатить миграцию Orders
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
