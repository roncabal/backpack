<?php

/**
* 
*/
class BackpackHelper
{

	public static function is_decimal( $val )
	{
	    return is_numeric( $val ) && floor( $val ) != $val;
	}

	public static function getRandomString($length)
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';

		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
	}
}