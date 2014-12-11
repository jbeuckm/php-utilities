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


  /**
   * Nest source items into an array of arrays with keys as the unique values of a specified key in the items.
   *
   * @param $source array/object The array to extract values from
   * @param $key string The key of each array item to
   * @param $extrasKey string Include items from the original array that don't specify the key
   */
  public static function nest(array $source, $key, $extrasKey = FALSE)
  {
    $result = array();
    if ($extrasKey) {
      $result[$extrasKey] = array();
    }

		foreach ($source as $item) {

      $item = (array) $item;

      if (isset($item[$key])) {

        $val = $item[$key];

        if (!isset($result[$val])) {
          $result[$val] = array();
        }

        array_push($result[$val], $item);
      }
      elseif ($extrasKey) {
        array_push($result[$extrasKey], $item);
      }

    }

    return $result;
  }


}