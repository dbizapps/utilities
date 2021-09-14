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

class Timezone
{
    /**
     * @var string
     */
    private $config = 'timezones.php';

    /**
     * @var array
     */
    private $timezones;


    /**
     * Class constructor
     *
     * @param array  $timezones
     */
    public function __construct( array $timezones = [] )
    {
        if ( !empty($timezones) && $zones = $this->validateTimezones($timezones) )
            $this->timezones = $zones;

        if ( empty($timezones) )
            $this->timezones = $this->readConfigFile();
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
        $timezones = require $configFile;

        return $this->validateTimezones($timezones);
    }


    /**
     * Validate timezones
     * 
     * @param array  $timezones
     * @return array
     */
    private function validateTimezones( array $timezones = [] )
    {
        if (! is_array($timezones) )
            throw new \Exception('Timezones are expected in array format.');

        if (! is_array(reset($timezones)) )
            throw new \Exception('Timezones are expected as array of arrays format.');

        if ( !array_key_exists('code', $timezones[0]) )
            throw new \Exception('Invalid timezone dataset: <code> is not available.');

        if ( !array_key_exists('country', $timezones[0]) )
            throw new \Exception('Invalid timezone dataset: <country> is not available.');

        if ( !array_key_exists('zone', $timezones[0]) )
            throw new \Exception('Invalid timezone dataset: <zone> is not available.');

        if ( !array_key_exists('utc_offset', $timezones[0]) )
            throw new \Exception('Invalid timezone dataset: <utc_offset> is not available.');

        if ( !array_key_exists('utc_dst_offset', $timezones[0]) )
            throw new \Exception('Invalid timezone dataset: <utc_dst_offset> is not available.');

        return $timezones;
    }


    /**
     * Get all timezones
     *
     * @return array
     */
    public function all()
    {
        return $this->timezones;
    }



    /**
     * Get timezones by key value
     *
     * @param  string $key
     * @param  string $value
     * @return array
     */
    public function search( $key, $value )
    {
        if (! in_array($key, array_keys($this->timezones[0])) )
            throw new \Exception('The timzone key you are looking for is not available in timezones');

        $result = [];

        foreach($this->timezones as $tz ) {

            if ( $tz[$key] == $value )
                $result[] = $tz;

        }

        if ( empty($result) )
            return null;

        if ( count($result) == 1 )
            return $result[0];

        return $result;
    }
}
