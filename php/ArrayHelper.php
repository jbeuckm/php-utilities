<?php

class ArrayHelper
{
	/**
	 * @return array
	*/
	public static function map( array $source, $keys, $newKeys = null )
	{
		$return = array();
		$i = 0;
		$len = count( $keys );
		for ( ; $i < $len; $i++ )
		{
			$value = isset($source[ $keys[ $i ] ]) ? $source[ $keys[$i] ] : '';
			if ( $newKeys === null || ! isset($newKeys[$i]) )
				$return[ $keys[$i] ] = $value;
			else
				$return[ $newKeys[$i] ] = $value;
		}
		return $return;
	}
}