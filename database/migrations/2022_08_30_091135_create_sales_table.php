<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->char('gNumber', 50); // номер заказа
            $table->date('date'); // дата продажи
            $table->dateTime('lastChangeDate'); // дата время обновления информации в сервисе
            $table->char('supplierArticle', 75); // ваш артикул
            $table->char('techSize', 30); // размер
            $table->char('barcode', 30); // штрих-код
            $table->decimal('totalPrice'); // начальная розничная цена товара
            $table->double('discountPercent'); // согласованная скидка на товар
            $table->integer('isSupply'); // договор поставки
            $table->integer('isRealization'); // договор реализации
            $table->double('promoCodeDiscount'); // согласованный промокод
            $table->char('warehouseName', 50); // склад отгрузки
            $table->char('countryName', 200); // страна
            $table->char('oblastOkrugName', 200); // округ
            $table->char('regionName', 200); // регион
            $table->integer('incomeID'); // номер поставки
            $table->char('saleID', 15); // уникальный идентификатор продажи/возврата (SXXXXXXXXXX — продажа, RXXXXXXXXXX — возврат, DXXXXXXXXXXX — доплата, 'AXXXXXXXXX' – сторно продаж (все значения полей как у продажи, но поля с суммами и кол-вом с минусом как в возврате). SaleID='BXXXXXXXXX' - сторно возврата(все значения полей как у возврата, но поля с суммами и кол-вом с плюсом, в противоположность возврату))
            $table->bigInteger('odid')->unique(); // уникальный идентификатор позиции заказа
            $table->double('spp'); // согласованная скидка постоянного покупателя (СПП)
            $table->decimal('forPay'); // к перечислению поставщику
            $table->decimal('finishedPrice'); // фактическая цена из заказа (с учетом всех скидок, включая и от ВБ)
            $table->decimal('priceWithDisc'); // цена, от которой считается вознаграждение поставщика forpay (с учетом всех согласованных скидок)
            $table->integer('nmId'); // код WB
            $table->char('subject', 50); // предмет
            $table->char('category', 50); // категория
            $table->char('brand', 50); // бренд
            $table->char('sticker'); // аналогично стикеру, который клеится на товар в процессе сборки
            $table->integer('IsStorno');
            $table->char('srid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
