<?php

/*
 * This file is part of the Utilities package.
 *
 * (c) Mark Fluehmann mark.fluehmann@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dbizapps\Utilities;

class Currency
{
    /**
     * @var string
     */
    private $config = 'currencies.php';

    /**
     * @var array
     */
    private $currencies;


    /**
     * Class constructor
     *
     * @param array  $currencies
     */
    public function __construct( array $currencies = [] )
    {
        if ( !empty($currencies) && $currencies = $this->validateCurrencies($currencies) )
            $this->currencies = $currencies;

        if ( empty($currencies) )
            $this->currencies = $this->readConfigFile();
    }


    /**
     * Read Config File
     * 
     * @return array|exception
     */
    private function readConfigFile()
    {
        if ( function_exists('config_path') )
            $configFile = config_path($this->config);

        if (! function_exists('config_path') )
            $configFile = './config/'.$this->config;

        // throw errer if file does not exist
        if ( !fileExists($configFile) )
            throw new \Exception("Can not find config file: {$configFile}");

        // load config from config file
        $currencies = require $configFile;

        return $this->validateCurrencies($currencies);
    }


    /**
     * Validate currencies
     * 
     * @param array  $currencies
     * @return array
     */
    private function validateCurrencies( array $currencies = [] )
    {
        if (! is_array($currencies) )
            throw new \Exception('Currencies are expected in array format.');

        if (! is_array(reset($currencies)) )
            throw new \Exception('Currencies are expected as array of arrays format.');

        if ( !array_key_exists('code', $currencies[0]) )
            throw new \Exception('Invalid timezone dataset: <code> is not available.');

        if ( !array_key_exists('display', $currencies[0]) )
            throw new \Exception('Invalid timezone dataset: <display> is not available.');

        if ( !array_key_exists('symbol', $currencies[0]) )
            throw new \Exception('Invalid timezone dataset: <symbol> is not available.');

        if ( !array_key_exists('digits', $currencies[0]) )
            throw new \Exception('Invalid timezone dataset: <digits> is not available.');

        if ( !array_key_exists('iso', $currencies[0]) )
            throw new \Exception('Invalid timezone dataset: <iso> is not available.');

        return $currencies;
    }


    /**
     * Get all currencies
     *
     * @return array
     */
    public function all()
    {
        return $this->currencies;
    }


    /**
     * Get currencies by key value
     *
     * @param  string $key
     * @param  string $value
     * @return array
     */
    public function search( string $key, string $value )
    {
        if (! in_array($key, array_keys($this->currencies[0])) )
            throw new \Exception('The currency key you are looking for is not available in currencies');

        $result = [];

        foreach($this->currencies as $currency ) {

            if ( $currency[$key] == $value )
                $result[] = $currency;

        }

        if ( empty($result) )
            return null;

        if ( count($result) == 1 )
            return $result[0];

        return $result;
    }
}
