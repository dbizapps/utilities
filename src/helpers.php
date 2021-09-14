<?php

if (! function_exists('array_keys_exist')) {

    /**
     * Evaluate if at least one needle exists in haystack
     *
     * @param array $needles
     * @param array $haystack
     * @return boolean
     */
    function array_keys_exist(array $needles, array $haystack)
    {
        foreach ($needles as $needle) {
            if ( isset($haystack[$needle]) ) return true;
        }

        return false;
    }
}


if (! function_exists('array_all_keys_exist')) {

    /**
     * Evaluate if all keys exist in haystack
     *
     * @param  array $keys        keys to check
     * @param  array $haystack    lookup array
     * @return boolean
     */
    function array_all_keys_exist( array $keys, array $haystack ) {

       return !array_diff_key(array_flip($keys), $haystack);
    
    }
}


if (! function_exists('findKeyValue')) {

    /**
     * Find Key/Value pair in array
     *
     * @param  array $array   lookup array
     * @param  mixed $key     key to look for
     * @param  mixed $val     value in key to look for
     * @return array|null
     */
    function findKeyValue( array $array, $key, $val )
    {
        foreach ($array as $item) {

var_dump($item);
            if (is_array($item) && findKeyValue($item, $key, $val)) return item;

            if (isset($item[$key]) && $item[$key] == $val) return item;
        }

        return null;
    }
}


if (! function_exists('fileExists')) {

    /**
     * Determine if config file exists
     * 
     * Load and extract configuration by key
     *
     * @param string      $file
     * @return bool
     */
    function fileExists($file)
    {
        if (!is_file($file) || !file_exists($file))
            return false;

        return true;
    }
}



