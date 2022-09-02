<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для таблицы Orders
 */
class Orders extends Model
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
        'warehouseName',
        'oblast',
        'incomeID',
        'odid',
        'nmId',
        'subject',
        'category',
        'brand',
        'isCancel',
        'sticker',
    ];
}
