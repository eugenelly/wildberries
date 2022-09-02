<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для таблицы Incomes
 */
class Incomes extends Model
{
    protected $fillable = [
        'number',
        'date',
        'lastChangeDate',
        'supplierArticle',
        'techSize',
        'barcode',
        'quantity',
        'totalPrice',
        'dateClose',
        'warehouseName',
        'nmid',
        'status',
    ];
}
