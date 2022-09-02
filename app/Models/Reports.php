<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для таблицы Incomes
 */
class Reports extends Model
{
    protected $fillable = [
        'realizationreport_id',
        'suppliercontract_code',
        'rid',
        'rr_dt',
        'rrd_id',
        'gi_id',
        'subject_name',
        'nm_id',
        'brand_name',
        'sa_name',
        'ts_name',
        'barcode',
        'doc_type_name',
        'quantity',
        'retail_price',
        'retail_amount',
        'sale_percent',
        'commission_percent',
        'office_name',
        'supplier_oper_name',
        'order_dt',
        'sale_dt',
        'shk_id',
        'retail_price_withdisc_rub',
        'delivery_amount',
        'return_amount',
        'delivery_rub',
        'gi_box_type_name',
        'product_discount_for_report',
        'supplier_promo',
        'ppvz_spp_prc',
        'ppvz_kvw_prc_base',
        'ppvz_kvw_prc',
        'ppvz_sales_commission',
        'ppvz_for_pay',
        'ppvz_reward',
        'ppvz_vw',
        'ppvz_vw_nds',
        'ppvz_office_id',
        'ppvz_office_name',
        'ppvz_supplier_id',
        'ppvz_supplier_name',
        'ppvz_inn',
        'declaration_number',
        'sticker_id',
        'site_country',
    ];
}
