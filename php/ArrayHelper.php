<?php

class ArrayHelper
{
	/**
	 * Extracts values from the source array according to the specified keys and returns a new array with matching keys.
	 * If a key is missing from source the returned array will contain an empty string value at that key.	 
	 * Optionally the returned array keys can be remapped by the $newKeys argument.
	 *
	 * @param $source array The array to extract values from
	 * @param $keys array The keys used to extract from $source
	 * @param $newKeys array Used to rename keys from $source to $return
	 * @return array The result of the mapping
	*/
	public static function map( array $source, array $keys, array $newKeys = null )
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