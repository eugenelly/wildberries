<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Выполнить миграцию Incomes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('incomeid')->unique()->index(); // номер поставки
            $table->char('number', 40); // номер УПД
            $table->date('date'); // дата поступления
            $table->dateTime('lastChangeDate'); // дата и время обновления информации в сервисе
            $table->char('supplierArticle', 75); // ваш артикул
            $table->char('techSize', 30); // размер
            $table->char('barcode', 30); // штрих-код
            $table->integer('quantity'); // кол-во
            $table->decimal('totalPrice'); // цена из УПД
            $table->date('dateClose'); // дата принятия (закрытия) у нас
            $table->char('warehouseName', 50); // название склада
            $table->integer('nmid'); // Код WB
            $table->char('status', 50); //Текущий статус поставки
            $table->timestamps();
        });
    }

    /**
     * Откатить миграцию Users
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
};
