<?php

class JSONify
{
	private $json_tabLevel;
	private $json_tabs;
	private $lineBreak;
    private $tabber;
	private $output;
    private $spacing;

	public function makeFromArray( array $a, $useSpacing = true, $tabber = "\t" )
	{
		// config
		$this->spacing      = $useSpacing;
		$this->tabber       = $useSpacing ? $tabber : '';
		$this->lineBreak    = $useSpacing ? "\r" : '';
		$this->output       = '';

        // init
		$this->json_tabLevel = 0;
		$this->json_tabs = '';
		
		// walk
		$this->walk( $a, false, false );
		
		// result
		return $this->output;
	}
	
	private function walk( $items, $openingTab = true, $nested = true )
	{
        $this->json_openObject( false, $openingTab );

        foreach ( $items as $key => $item )
        {
            $this->parseValue( $item, $key );
        }

        $this->json_closeObject( false, $nested );
	}
	
	private function parseValue( $value, $prop = null )
	{
		// value is for an object
		if ( $prop )
			$this->json_addParam( $prop );
		
		if ( is_array( $value ) )
		{
			if ( $prop )
				$this->walk( $value, false ); // forProp value does not need indenting
			else
				$this->walk( $value, true );
		}
		else
		{
			if ( $prop )
				$this->json_addValue( $value ); // value is for an object
			else
				$this->json_addValue( $value, true ); // value is for an array
		}
	}

	private function json_openObject( $isArray = false, $tab = true )
	{
		$this->output .= ( $this->output != '' && $this->spacing ? ($tab ? $this->jsonjson_tabs : ' ') : '') . ( $isArray ? '[' : '{' ) . $this->lineBreak;
		$this->json_incrementTabLevel( 1 );
	}
	
	private function json_closeObject( $isArray = false, $nested = true )
	{
		$this->json_incrementTabLevel( -1 );
		
		$commaPosition = strlen( $this->output ) - ($this->spacing ? 2 : 1);
		$endOfOutput = mb_substr( $this->output, $commaPosition );
		
		if ( $endOfOutput === ',' . $this->lineBreak )
		{	 
			// remove trailing commas in paramters
			$this->output = substr( $this->output, 0, $commaPosition ) . $this->lineBreak;
		}
		
		$this->output .= $this->json_tabs . ( $isArray ? ']' : '}' ) . ($nested ? ',' : '' ) . $this->lineBreak;
	}
	
	private function json_addParam( $name )
	{
		$this->output .= $this->json_tabs . '"' . $name . ($this->spacing ? '": ' : '":' );
	}
	
	private function json_addValue( $value, $tabbed = false  )
	{
		$this->output .= ($this->spacing ? ($tabbed ? $this->json_tabs : ' ') : '') . '"' . $value . '",' . $this->lineBreak;
	}
	
	private function json_incrementTabLevel( $value )
	{
		$this->json_tabLevel = $this->json_tabLevel + $value;
		$this->json_tabs = '';
		
		// rather than split logic, just keep $json_tabs flat
		if ( $this->spacing )
		{
			$i = 0;
			for ( ; $i < $this->json_tabLevel; $i++ ) 
				$this->json_tabs .= $this->tabber;
		}
	}
}