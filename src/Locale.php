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

class Locale
{
    /**
     * @var string
     */
    private $config = 'locales.php';

    /**
     * @var array
     */
    private $locales;


    /**
     * Class constructor
     *
     * @param array  $locales
     */
    public function __construct( array $locales = [] )
    {
        if ( !empty($locales) && $locales = $this->validateLocales($locales) )
            $this->locales = $locales;

        if ( empty($locales) )
            $this->locales = $this->readConfigFile();
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
        $locales = require $configFile;

        return $this->validateLocales($locales);
    }


    /**
     * Validate locales
     * 
     * @param array  $locales
     * @return array
     */
    private function validateLocales( array $locales = [] )
    {
        if (! is_array($locales) )
            throw new \Exception('Locales are expected in array format.');

        if (! is_array(reset($locales)) )
            throw new \Exception('Locales are expected as array of arrays format.');

        if ( !array_key_exists('code', $locales[0]) )
            throw new \Exception('Invalid timezone dataset: <code> is not available.');

        if ( !array_key_exists('display', $locales[0]) )
            throw new \Exception('Invalid timezone dataset: <display> is not available.');

        if ( !array_key_exists('native', $locales[0]) )
            throw new \Exception('Invalid timezone dataset: <native> is not available.');

        if ( !array_key_exists('common', $locales[0]) )
            throw new \Exception('Invalid timezone dataset: <common> is not available.');

        return $locales;
    }


    /**
     * Get all locales
     *
     * @return array
     */
    public function all()
    {
        return $this->locales;
    }


    /**
     * Get locales by key value
     *
     * @param  string $key
     * @param  string $value
     * @return array
     */
    public function search( $key, $value )
    {
        if (! in_array($key, array_keys($this->locales[0])) )
            throw new \Exception('The locale key you are looking for is not available in locales');

        $result = [];

        foreach($this->locales as $locale ) {

            if ( $locale[$key] == $value )
                $result[] = $locale;

        }

        if ( empty($result) )
            return null;

        if ( count($result) == 1 )
            return $result[0];

        return $result;
    }
}
