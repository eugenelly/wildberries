<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для таблицы Stocks
 */
class Stocks extends Model
{
    protected $fillable = [
        'lastChangeDate',
        'supplierArticle',
        'techSize',
        'barcode',
        'quantity',
        'isSupply',
        'isRealization',
        'quantityFull',
        'quantityNotInOrders',
        'warehouseName',
        'inWayToClient',
        'inWayFromClient',
        'nmId',
        'subject',
        'category',
        'daysOnSite',
        'brand',
        'SCCode',
        'warehouse',
        'Price',
        'Discount',
    ];
}
