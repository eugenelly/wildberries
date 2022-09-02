<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для таблицы Incomes
 */
class ExciseGoods extends Model
{
    protected $fillable = [
        'id',
        'inn',
        'finishedPrice',
        'operationTypeId',
        'fiscalDt',
        'docNumber',
        'fnNumber',
        'regNumber',
        'excise',
        'date',
    ];
}
