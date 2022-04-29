<?php

namespace Ex3mm\ExchangeRate;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use App\Models\ExchangeRate;


class ExchangeRateService
{

    private string $fiatUrl = 'https://www.cbr.ru/scripts/XML_daily.asp';
    private \SimpleXMLElement $fiatXml;

    public function __construct()
    {
        // Get fiat xml data
        $this->fiatXml = simplexml_load_file($this->fiatUrl);
    }

    /**
     * Modify rate
     *
     * @param $rate
     * @return float
     */
    public function modRate($rate): float
    {
        return number_format(str_ireplace(',', '.', $rate), 2);
    }


    /**
     * Update all rates
     */
    public function updateAllRates(): bool|float
    {
        // Получаем массив валют для парсинга со статусом true
        $currencies = ExchangeRate::where('status', true)->where('type', 'fiat')->pluck('currency')->toArray();

        foreach ($this->fiatXml as $item){

            // If is currency in array
            if (in_array(mb_strtolower($item->CharCode), $currencies)) {

                return $this->updateRate(mb_strtolower($item->CharCode), $item->Value);
            }
        }

        return false;
    }


    /**
     * Update currency rate
     *
     * @param $currency
     * @param $rate
     * @return string
     */
    public function updateRate($currency, $rate): bool
    {
        $exchange_rate = ExchangeRate::where('currency', $currency)->first();
        $exchange_rate->rate = $rate;
        $exchange_rate->save();
        return $rate;
    }


    /**
     * Get one rate
     *
     * @param $currency
     * @return float|void
     */
    public function getRate($currency)
    {
        foreach ($this->fiatXml as $item){

            // If is currency in xml array
            if (mb_strtolower($item->CharCode) == mb_strtolower($currency)) {
                return  $this->modRate($item->Value);
            }
        }
    }


    /**
     * Update simple rate
     *
     * @param $currency
     * @return string|void
     */
    public function updateSimpleRate($currency)
    {
        return $this->updateRate(mb_strtolower($currency), $this->getRate($currency));
    }


    /**
     * Get currency rate
     *
     * @param $currency
     * @return mixed
     */
    public function getCurrencyRate($currency): mixed
    {
        $rate = ExchangeRate::where('currency', mb_strtolower($currency))->get()->first();
        return $rate->rate ?? $this->getRate($currency);
    }

}
