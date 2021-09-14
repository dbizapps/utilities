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

class OperatingSystem
{

	/**
     * Detect underlying operating system
     * 
     * @param {array}  $options
     */
	public static function getOs()
	{
		// resolve php operating system
		$os = PHP_OS;

		if ( static::isWindows() )
			return 'win';

		if ( static::isMac() )
			return static::macArchicteture();

		return 'linux';
	}


	/**
	 * Determine if operating system is windows 
	 */
	public static function isWindows()
	{
		// resolve php operating system
		$os = PHP_OS;

		return in_array($os, ['WIN32', 'WINNT', 'Windows']) || strpos( php_uname(), 'Microsoft') ?
			true : false;

	}


	/**
	 * Determine if operating system is windows 
	 */
	public static function isMac()
	{
		// resolve php operating system
		$os = PHP_OS;

		return $os === 'Darwin' ?
			true : false;
	}


	/**
	 * Determine macos architecture
	 */
	public static function macArchicteture()
	{
		if ( php_uname('m') == 'arm64' ) 
			return 'mac-arm';

		if ( php_uname('m') == 'x86_64' ) 
			return 'mac-intel';

		return 'mac';
	}

}