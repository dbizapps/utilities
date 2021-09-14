<?php

namespace Tests;

use dbizapps\Utilities\Currency;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Event.
 */
class CurrencyTest extends TestCase
{

    public function testCreateCurrency()
    {
        $currency = new Currency;

        $this->assertTrue( count($currency->all()) == 299 );
    }


    public function testCreateCustomCurrency()
    {
        $currency = new Currency([['code' => 'AAA', 'display' => 'A', 'symbol' => 'AAA', 'digits' => 2, 'iso' => 2]]);

        $this->assertTrue( count($currency->all()) == 1 );
    }


    public function testCreateInvalidCustomCurrency()
    {
        try {
            $currency = new Currency(['code' => 'AAA', 'display' => 'A', 'symbol' => 'AAA', 'digits' => 2, 'iso' => 2]);
        }

        catch ( \Exception $e ) {
            $this->assertTrue( $e->getMessage() == 'Currencies are expected as array of arrays format.');
        }
    }


    public function testSearchCurrencyByCode()
    {
        $currency = new Currency;

        $search = $currency->search('code', 'AOA');

        $this->assertTrue( !is_null($search) );
        $this->assertTrue( $search['display'] == 'Angolan Kwanza' );
    }
    

    public function testSearchCurrencyByDisplay()
    {
        $currency = new Currency;

        $search = $currency->search('display', 'Angolan Kwanza');

        $this->assertTrue( !is_null($search) );
        $this->assertTrue( $search['code'] == 'AOA' );
    }


    public function testSearchCurrencyByISO()
    {
        $currency = new Currency;

        $search = $currency->search('iso', '0');

        $this->assertTrue( count($search) > 1 );
    }

}
