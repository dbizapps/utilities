<?php

namespace App\Modules\Base;

use ArrayAccess;

class ConfigResolver
{
    /**
     * Get resource by path 
     *
     * @param  {string}  $path    path in dotted notation
     * @param  {string}  $base    base resource path, default: config
     * @return
     */
    public static function get($path, $base = 'config')
    {
        // get resource path from configuration
        $resource_path = static::loadPath($base);

        // import resource
        $data = static::getResource($path, $resource_path);

        // split dotted key into first and remaining path
        [$file, $key] = static::splitPath($path);

        return static::resolveKey($data, $key);
    }

 
    /**
     * Check if a key exist in an array using "dot" notation.
     *
     * @param  {string}  $path    path in dotted notation
     * @param  {string}  $key     key to resolve
     * @return bool
     */
    public static function has($path, $key, $base = 'config')
    {
        // get resource path from configuration
        $resource_path = static::loadPath($base);

        // import resource
        $data = static::getResource($path, $base);

        // split dotted key into first and remaining path
        [$file, $path] = static::splitPath($path);

        $index = is_null($path) ? $key : $path.'.'.$key;

        return static::keyExists($data, $index);
    }


    /**
     * Split dotted path into first and remaining path segments
     * 
     * @param  {string}  $path    path in dotted notation
     * @return {array}   first|path
     */
    protected static function splitPath($path)
    {
        // non-dotted path
        if (!strpos($path, '.'))
            return [$path, null];

        // split path into parts
        $parts = explode('.', $path);

        // first part is supposed to be resource file
        $first = array_shift($parts);

        // return first and remaining path
        return [$first, implode('.', $parts)];
    }


    /**
     * Load Configuration
     *
     * @return (array)
     */
    protected static function loadPath($resource = '')
    {
        // load configuration
        $config = static::getResource(self::CONFIG, 'config');

        // resolve file path for requested resource
        return static::resolveKey($config, $resource.'.path');
    }


    /**
     * Read resource
     *
     * @param  {string}  $path    path in dotted notation
     * @param  {string}  $base    resource base
     * @return (array)
     */
    protected static function getResource($path, $base)
    {
        // split path into file and key path
        [$file, $key] = static::splitPath($path);

        // resolve resource path
        $resource_path = static::resolvePath($base, $file);

        // throw error if file does not exist
        if ( !static::fileExists($resource_path) )
            throw new \Exception("Resource file doesn't exist: {$resource_path}");

        // load config from config file
        return require $resource_path;
    }


    /**
     * Resolve resource path
     *
     * @param  {string}  $path
     * @param  {string}  $file
     * @return {string}
     */
    protected static function resolvePath($path = '', $file = '')
    { 
        // application path
        $app_path = dirname(__DIR__, 1);

        // resource path
        $res_path = $path ? $app_path.DIRECTORY_SEPARATOR.$path : $app_path;

        // build resource or file path
        return $file ? $res_path.DIRECTORY_SEPARATOR.$file.'.php' : $res_path;
    }


    /**
     * Determine if given file exists
     *
     * @param {string}      $file
     * @return bool
     */
    protected static function fileExists($file)
    {
        if (!is_file($file) || !file_exists($file))
            return false;

        return true;
    }


    /**
     * Resolve key within resource
     *
     * @param  {array}  $array      resource
     * @param  {string} $key        key to resolve, might be in dotted notation
     * @return {void}
     */
    protected static function resolveKey($array = [], $key = '', $default = null )
    {
        if (static::exists($array, $key))
            return $array[$key];

        // split dotted key into first and remaining path
        [$index, $remaining] = static::splitPath($key);

        if (is_null($remaining))
            throw new \Exception("Key not found in config resource: {$key}");

        return static::resolveKey($array[$index], $remaining, $default);
    }


    /**
     * Determine if key exists in resource
     *
     * @param  {array}  $array      resource
     * @param  {string} $path       path to look at
     * @return {bool}
     */
    protected static function keyExists($array = [], $path = null)
    {
        if (static::exists($array, $path))
            return true;

        if ( is_null($path) )
            return false;

        // split dotted key into first and remaining path
        [$index, $remaining] = static::splitPath($path);

        if ( is_null($index) || !array_key_exists($index, $array))
            return false;

        return static::keyExists($array[$index], $remaining);
    }


    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    protected static function exists($array, $key)
    {
        if ( !is_array($array) || is_null($key) )
            return false;

        if ($array instanceof ArrayAccess)
            return $array->offsetExists($key);

        return array_key_exists($key, $array);
    }

}