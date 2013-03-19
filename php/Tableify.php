<?php

class Tableify
{
	private $rows;
	public $columnNames;
	private $rowIsOpen;
	
	function __construct()
	{
		$this->init();
	}
	
	public function reset()
	{
		$this->init();
	}
	
	private function init()
	{
		$this->rowIsOpen = false;
		$this->rows = array();
		$this->columnNames = array();		
	}
	
	public function getTable()
	{
		return $this->rows;
	}	
	
	public function getColumnNames()
	{
		return $this->columnNames;	
	}
	
	public function addRow()
	{
		if ( $this->rowIsOpen ) {
			$this->closeRow();
		}
		$this->rowIsOpen = true;
		array_push( $this->rows, array() );	
	}
	
	public function addColumn( $name, $value )
	{
		$col = $this->columnIndexByName( $name );

		if ( gettype($value) != 'array' )
		{
			$add = $value;
		}
		else
		{
			$i = 0;
			$add = '';
			foreach( $value as $k => $v )
			{
				$add .= $k . ': ' . $v;
				if ( $i != count($value) - 1 )
				{
					$add .= ', ';
				}
				$i++;
			}			
		}
		$this->rows[ $this->currentRow() ][ $col ] = $add;
	}
	
	public function columnIndexByName( $name )
	{
		$i = 0;
		$len = $this->colCount();
		$index;
		
		for ( ; $i < $len; $i++ )
		{
			if ( $name == $this->columnNames[ $i ] )
			{
				$index = $i;
				break;
			}
		}
		
		if ( ! isset($index) )
		{
			// add the new column
			array_push( $this->columnNames, $name );
			$index = $this->colCount() - 1;
		}
		
		return $index;
	}

	public function closeRow()
	{
		if ( $this->rowIsOpen )
		{
			$i = 0;
			$len = $this->colCount();
			for ( ; $i < $len; $i++ )
			{
				if ( ! isset( $this->rows[ $this->currentRow() ][ $i ]) )
				{
					$this->rows[ $this->currentRow() ][ $i ] = '';
				}
			}
		}
		$this->rowIsOpen = false;
	}
	
	private function colCount()
	{
		return count($this->columnNames);
	}
	
	private function currentRow()
	{
		return count($this->rows) - 1;
	}
}

?>