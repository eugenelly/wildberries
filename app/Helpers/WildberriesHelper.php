<?php

namespace App\Helpers;

use App\Models\Incomes;
use App\Models\Stocks;
use App\Models\Orders;
use App\Models\Sales;
use App\Models\Reports;
use App\Models\ExciseGoods;

/**
 * Класс для работы с Wildberries (RESTful API)
 */
class WildberriesHelper
{
    /**
     * @var string ссылка для GET запросов
     */
    public string $url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/';

    /**
     * @var string API-ключ для аутентификации
     */
    private string $key;

    /**
     * @var array Query-параметры по умолчанию
     */
    private array $defaultQueryParams;

    /**
     * @var string Время, с которого нужно получить данные
     */
    private string $dateFrom;

    /**
     * @var string Время, по которое нужно получить данные
     */
    private string $dateTo;

    /**
     * @var int Флажок для получения различных видов данных
     *
     * • Если параметр flag=0 (или не указан в строке запроса), при вызове API возвращаются данные у которых значение поля lastChangeDate (дата время обновления информации в сервисе) больше переданного в вызов значения параметра dateFrom. При этом количество возвращенных строк данных варьируется в интервале от 0 до примерно 100000.
     * • Если параметр flag=1, то будет выгружена информация обо всех заказах или продажах с датой равной переданному параметру dateFrom(в данном случае время в дате значения не имеет). При этом количество возвращенных строк данных равно количеству всех заказов или продаж, сделанных в дате, переданной в параметре dateFrom. Ограничение на разовый запрос — около ста тысяч записей.
     */
    private int $flag;

    /**
     * @var int Максимальное количество строк отчета получаемых в результате вызова API.
     * Рекомендуем загружать отчет небольшими частями, например, по 100000 строк на один вызов.
     */
    private int $limit;

    /**
     * @var int Уникальный идентификатор строки отчета. Необходим для получения отчета частями.
     *
     * Загрузку отчета нужно начинать с rrdid=0, и при последующих вызовах API передавать максимальное значение rrdid полученное в результате предыдущего вызова. Таким образом для загрузки одного отчета может понадобится вызывать API до тех пор, пока количество возвращаемых строк не станет равным нулю.
     */
    private int $rrdid;

    /**
     * Конструктор для класса Wildberries
     *
     * @param string $key API-ключ для аутентификации
     * @param string $dateFrom Время, с которого нужно получить данные
     * Параметр dateFrom:
     * • Формат времени по умолчанию использует стандарт RFC3339.
     * • Можно передавать миллисекунды, также можно не передавать вообще ничего кроме даты.
     * Примеры корректных дат в формате RFC3339:
     * • 2019-06-20
     * • 2019-06-20T00:00:00Z
     * • 2019-06-20T23:59:59
     * • 2019-06-20T00:00:00.12345Z
     * • 2019-06-20T00:00:00.12345
     * • 2017-03-25T00:00:00
     */
    public function __construct(
        string $key,
        string $dateFrom,
        array  $otherProps = []
    )
    {
        $this->key = $key;
        $this->dateFrom = $dateFrom;

        $this->defaultQueryParams = [
            'key' => $this->key,
            'dateFrom' => $this->dateFrom
        ];

        (isset($otherProps['dateTo']))
            ? $this->dateTo = $otherProps['dateTo']
            : $this->dateTo = date('Y-m-d');


        (isset($otherProps['flag']))
            ? $this->flag = $otherProps['flag']
            : $this->flag = 0;

        if (isset($otherProps['limit'])) {
            if ($otherProps['limit'] <= 100_000) {
                $this->limit = $otherProps['limit'];
            } else {
                echo 'Рекомендуемое количество превышено. ';
                $this->limit = 100_000;
                echo 'Лимит установлен в 100 000';
            }
        } else {
            $this->limit = 1_000;
        }

        (isset($otherProps['rrdid']))
            ? $this->rrdid = $otherProps['rrdid']
            : $this->rrdid = 0;
    }

    /**
     * Проверка ввода данных с консоли и сбор дополнительных параметров
     *
     * @param array $props Аргументы
     * @param array $option Параметры
     * @return array Массив дополнительных параметров
     */
    public static function checkInput(array $props, array $options): array
    {
        if (!strlen($props['key']) && strlen($props['key']) !== 48) {
            die('Ключ пустой или некорректен');
        }

        $pattern_date = '/^\d{4}-(0\d|1[1-2])-(0\d|[1-2]\d|3[0-1])$/';
        self::checkPropsOrOptions($props, 'dateFrom', $pattern_date);
        $otherProps['dateTo'] = self::checkPropsOrOptions($options, 'dateTo', $pattern_date);
        $otherProps['flag'] = self::checkPropsOrOptions($options, 'flag', '/^[0-1]$/');
        $otherProps['limit'] = self::checkPropsOrOptions($options, 'limit', '/^\d+$/');
        $otherProps['rrdid'] = self::checkPropsOrOptions($options, 'rrdid', '/^[0-1]$/');

        return $otherProps;
    }

    /**
     * Проверка аргументов и параметров
     *
     * @param array $poo Аргумент или параметр который нуждается в проверке
     * @param string $key Ключ
     * @param string $pattern Паттерн проверки
     * @return mixed|void
     */
    private static function checkPropsOrOptions(array $poo, string $key, string $pattern)
    {
        if (isset($poo[$key])) {
            if (!preg_match(
                $pattern,
                $poo[$key])) {
                die ('Формат ' . $key . ' не верен');
            }

            return $poo[$key];
        } else return null;
    }

    /**
     * Получение данных через RESTful API
     *
     * @param string $method Медод API
     * @return array|null Массив запрошенных данных
     */
    public function get(string $method): ?array
    {
        $allowed_methods = ['incomes', 'stocks', 'orders', 'sales', 'reportDetailByPeriod', 'excise-goods',];

        if (!in_array($method, $allowed_methods)) {
            return null;
        }

        $url = $this->url . $method;

        switch ($method) {
            case 'sales':
                $queryParams = [
                    ...$this->defaultQueryParams,
                    'flag' => $this->flag
                ];
                break;
            case 'reportDetailByPeriod':
                $queryParams = [
                    ...$this->defaultQueryParams,
                    'dateTo' => $this->dateTo,
                    'limit' => $this->limit,
                    'rrdid' => $this->rrdid
                ];
                break;
            default:
                $queryParams = $this->defaultQueryParams;
        }

        return $this->curlGet($url, $queryParams);
    }

    /**
     * GET-запрос с параметрами
     *
     * @param string $url URL-адрес
     * @return array Ответ в виде ассоциативного массива
     */
    public function curlGet(string $url, array $queryParams): ?array
    {
        $url = $url . '?' . http_build_query($queryParams);

        // инициализация сессии cURL
        $ch = curl_init($url);
        // конфигурирование настроек текущей сессии cURL
        curl_setopt(
            $ch,
            CURLOPT_RETURNTRANSFER,
            true
        );

        // получение результата через запрос
        $response = curl_exec($ch);

        // закрытие сессии
        curl_close($ch);

        return json_decode(
            $response,
            true
        );
    }

    /**
     * Обновление данных в БД полученных через API
     *
     * @param string $name Название таблицы в БД
     * @param array|null $data Передаваемые в таблицу данные
     * @return bool Статус передачи данных (данные успешно переданы или же нет)
     */
    public function upsertToWildberriesBD(string $name, ?array $data): bool
    {
        $allowed_tables = ['incomes', 'stocks', 'orders', 'sales', 'reportDetailByPeriod', 'excise_goods',];

        if (!in_array($name, $allowed_tables)) {
            return false;
        }

        if (isset($data['errors'])) {
            echo 'Ошибка в запросе или на стороне сервера';
            return false;
        }

        if (!empty($data)) {
            // выгрузка списка поставок в БД
            try {
                switch ($name) {
                    case 'incomes':
                        Incomes::upsert(
                            $data,
                            ['incomeid',],
                            ['number', 'date', 'lastChangeDate', 'supplierArticle', 'techSize', 'barcode', 'quantity', 'totalPrice', 'dateClose', 'warehouseName', 'nmId', 'status',]
                        );
                        break;
                    case 'stocks':
                        Stocks::upsert(
                            $data,
                            ['nmId',],
                            ['lastChangeDate', 'supplierArticle', 'techSize', 'barcode', 'quantity', 'isSupply', 'isRealization', 'quantityFull', 'quantityNotInOrders', 'warehouseName', 'inWayToClient', 'inWayFromClient', 'subject', 'category', 'daysOnSite', 'brand', 'SCCode', 'warehouse', 'Price', 'Discount',]
                        );
                        break;
                    case 'orders':
                        Orders::upsert(
                            $data,
                            ['odid',],
                            ['gNumber', 'date', 'lastChangeDate', 'supplierArticle', 'techSize', 'barcode', 'totalPrice', 'discountPercent', 'warehouseName', 'oblast', 'incomeID', 'nmId', 'subject', 'category', 'brand', 'isCancel', 'sticker',]
                        );
                        break;
                    case 'sales':
                        Sales::upsert(
                            $data,
                            ['odid',],
                            ['gNumber', 'date', 'lastChangeDate', 'supplierArticle', 'techSize', 'barcode', 'totalPrice', 'discountPercent', 'isSupply', 'isRealization', 'promoCodeDiscount', 'warehouseName', 'countryName', 'oblastOkrugName', 'regionName', 'incomeID', 'saleID', 'odid', 'spp', 'forPay', 'finishedPrice', 'priceWithDisc', 'nmId', 'subject', 'category', 'brand', 'sticker',]
                        );
                        break;
                    case 'reportDetailByPeriod':
                        Reports::upsert(
                            $data,
                            ['rrd_id',],
                            ['realizationreport_id', 'suppliercontract_code', 'rid', 'rr_dt', 'gi_id', 'subject_name', 'nm_id', 'brand_name', 'sa_name', 'ts_name', 'barcode', 'doc_type_name', 'quantity', 'retail_price', 'retail_amount', 'sale_percent', 'commission_percent', 'office_name', 'supplier_oper_name', 'order_dt', 'sale_dt', 'shk_id', 'retail_price_withdisc_rub', 'delivery_amount', 'return_amount', 'delivery_rub', 'gi_box_type_name', 'product_discount_for_report', 'supplier_promo', 'ppvz_spp_prc', 'ppvz_kvw_prc_base', 'ppvz_kvw_prc', 'ppvz_sales_commission', 'ppvz_for_pay', 'ppvz_reward', 'ppvz_vw', 'ppvz_vw_nds', 'ppvz_office_id', 'ppvz_office_name', 'ppvz_supplier_id', 'ppvz_supplier_name', 'ppvz_inn', 'declaration_number', 'sticker_id', 'site_country',]
                        );
                        break;
                    case 'excise_goods':
                        ExciseGoods::upsert(
                            $data,
                            ['id',],
                            ['inn', 'finishedPrice', 'operationTypeId', 'fiscalDt', 'docNumber', 'fnNumber', 'regNumber', 'excise', 'date',]
                        );
                        break;
                }
                echo 'Данные ' . $name . ' успешно выгружены в БД' . PHP_EOL;

            } catch (\Exception $ex) {
                echo 'Проблема выгрузки ' . $name . ' в БД';
                echo $ex->getMessage();
            }
        } else echo 'Список ' . $name . ' пуст!' . PHP_EOL;

        sleep(3);

        return true;
    }
}
