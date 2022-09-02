<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для таблицы Sales
 */
class Sales extends Model
{
    protected $fillable = [
        'gNumber',
        'date',
        'lastChangeDate',
        'supplierArticle',
        'techSize',
        'barcode',
        'totalPrice',
        'discountPercent',
        'isSupply',
        'isRealization',
        'promoCodeDiscount',
        'warehouseName',
        'countryName',
        'oblastOkrugName',
        'regionName',
        'incomeID',
        'saleID',
        'odid',
        'spp',
        'forPay',
        'finishedPrice',
        'priceWithDisc',
        'nmId',
        'subject',
        'category',
        'brand',
        'sticker',
    ];
}
