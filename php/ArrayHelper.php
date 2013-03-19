<?php

class ArrayHelper
{
	/**
	 * Extracts values from the source array according to the specified
	 * keys and returns a new array with matching keys.
	 * Optionally the returned arra keys can be remapped by the newKeys argument.
     * If a key is missing from source the returned array will contain the key with an empty string value.
     * 
	 * @return array
	*/
	public static function map( array $source, $keys, $newKeys = null )
	{
		$r = array();
		$i = 0;
		$len = count( $keys );
		for ( ; $i < $len; $i++ )
		{
			$key = $keys[$i];
			$value = isset($source[$key]) ? $source[$key] : '';
			if ( $newKeys === null || empty($newKeys[$i]) )
				$r[ $key ] = $value;
			else
				$r[ $newKeys[$i] ] = $value;
		}
		return $r;
	}
}