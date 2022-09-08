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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('realizationreport_id')->unique()->index(); // Номер отчета
            $table->char('suppliercontract_code')->nullable(); // Договор
            $table->bigInteger('rid'); // Уникальный идентификатор позиции заказа
            $table->dateTime('rr_dt'); // Дата операции
            $table->biginteger('rrd_id'); // Номер строки
            $table->integer('gi_id'); // Номер поставки
            $table->char('subject_name')->nullable(); // Предмет
            $table->integer('nm_id')->nullable(); // Артикул
            $table->char('brand_name')->nullable(); // Бренд
            $table->char('sa_name')->nullable(); // Артикул поставщика
            $table->char('ts_name')->nullable(); // Размер
            $table->char('barcode'); // Баркод
            $table->char('doc_type_name'); // Тип документа
            $table->integer('quantity'); // Количество
            $table->decimal('retail_price'); // Цена розничная
            $table->decimal('retail_amount'); // Сумма продаж(Возвратов)
            $table->decimal('sale_percent'); // Согласованная скидка
            $table->decimal('commission_percent'); // Процент комиссии
            $table->char('office_name')->nullable(); // Склад
            $table->char('supplier_oper_name'); // Обоснование для оплаты
            $table->dateTime('order_dt'); // Даты заказа
            $table->dateTime('sale_dt'); // Дата продажи
            $table->bigInteger('shk_id'); // ШК
            $table->decimal('retail_price_withdisc_rub'); // Цена розничная с учетом согласованной скидки
            $table->integer('delivery_amount'); // Кол-во доставок
            $table->integer('return_amount'); // Кол-во возвратов
            $table->decimal('delivery_rub'); // Стоимость логистики
            $table->char('gi_box_type_name'); // Тип коробов
            $table->integer('product_discount_for_report'); // Согласованный продуктовый дисконт
            $table->integer('supplier_promo'); // Промокод
            $table->decimal('ppvz_spp_prc'); // Скидка постоянного Покупателя (СПП)
            $table->decimal('ppvz_kvw_prc_base'); // Размер кВВ без НДС, % Базовый
            $table->decimal('ppvz_kvw_prc'); // Итоговый кВВ без НДС, %
            $table->decimal('ppvz_sales_commission'); // Вознаграждение с продаж до вычета услуг поверенного, без НДС
            $table->decimal('ppvz_for_pay'); // К перечислению Продавцу за реализованный Товар
            $table->decimal('ppvz_reward'); // Возмещение Расходов услуг поверенного
            $table->decimal('ppvz_vw'); // Вознаграждение Вайлдберриз(ВВ), без НДС
            $table->decimal('ppvz_vw_nds'); // НДС с Вознаграждения Вайлдберриз
            $table->integer('ppvz_office_id'); // Номер офиса
            $table->char('ppvz_office_name')->nullable(); // Наименование офиса доставки
            $table->integer('ppvz_supplier_id'); // Номер партнера
            $table->char('ppvz_supplier_name'); // Партнер
            $table->char('ppvz_inn'); // ИНН партнера
            $table->char('declaration_number'); // Номер таможенной декларации
            $table->char('sticker_id'); // Аналогично стикеру, который клеится на товар в процессе сборки
            $table->char('site_country'); // Страна продажи
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
        Schema::dropIfExists('reports');
    }
};
