<?php

class HTMLHelper
{
	public $markup = '';
	private $lastOpenTag = '';

	public function openDocToBody( $title = '', $css = '' )
	{
		$this->markup = <<<MARKUP
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>$title</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <!--<link rel="stylesheet" href="css/normalize.css">-->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="$css">
        <script src="js/vendor/modernizr-2.6.1.min.js"></script>
        <script src="js/vendor/jquery-1.8.1.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
MARKUP;
	}

    public function openDocToBodyInlining( $title = '', $css = '', $js = '' )
    {
        $this->markup = <<<MARKUP
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>$title</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <style>
           $css
        </style>
        <script>
            $js
        </script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
MARKUP;
    }

	public function closeBodyAndDoc()
	{
		$this->markup .= "</body>\n</html>";
	}

	public function tag( $tag, $text = '', $class = '', $id = '', $other = '' )
	{
		$tag = $this->stripGtLt( $tag );

        $this->markup .= "<$tag". (( $id != '' ) ? " id='$id'" : '') . (( $class != '' ) ? " class='$class'" : '') . (( $other != '' ) ? ' ' . $other : '');

		if ( $text == '' )
			$this->markup .= " />\n";
		else
			$this->markup .= ">$text</$tag>\n";
	}

	public function img( $src, $class = '', $id = '', $atts = '' )
	{
		$this->markup .= "<img src='$src'". (( $id != '' ) ? " id='$id'" : '') . (( $class != '' ) ? " class='$class'" : '') . (( $atts != '' ) ? ' ' . $atts : '') . "/>";
	}

	public function open( $tag, $class = '', $id = '', $atts = '' )
	{
		$tag = $this->stripGtLt( $tag );
        $this->lastOpenTag = $tag;
        $this->markup .= "<$tag". (( $id != '' ) ? " id='$id'" : '') . (( $class != '' ) ? " class='$class'" : '') . (( $atts != '' ) ? ' ' . $atts : '') . ">\n";
	}

    public function formStart( $class = '', $id = '', $atts = '' )
    {

    }

    public function select( $name, array $optionValues, array $optionTexts = null, $selectedItem = '', $class = '', $id = '', $atts = '', $labelText = '', $labelBefore = true  )
    {
        if ( $labelText != '' && $labelBefore )
            $this->label( $labelText, $id );

        $this->open( 'select', $class, $id, "name='$name' $atts" );

        $i = 0;
        $len = count($optionValues);
        for ( ; $i < $len; $i++ )
        {
            $value = $optionValues[$i];
            $text = $optionTexts && isset( $optionTexts[$i] ) ? $optionTexts[$i] : $value;
            $selected = ( $selectedItem != '' && $selectedItem == $value ) ? ' selected' : '';

            $this->tag( 'option', $text, '', '', 'value="'. $value . '"' . $selected );
        }

        $this->close( 'select' );

        if ( $labelText != '' && $labelBefore === false )
            $this->label( $labelText, $id );
    }

    public function label( $labelText, $for, $class = '', $id = '', $atts='' )
    {
        $this->tag( 'label', $labelText, $class, $id, "for='$for' $atts" );
    }

    public function input( $name, $value, $class = '', $id = '', $atts = '', $labelText = '', $labelBefore = true )
    {
        if ( $labelText != '' && $labelBefore )
            $this->label( $labelText, $id );

        $this->tag( 'input', '', $class, $id, "name='$name' value='$value' $atts" );

        if ( $labelText != '' && $labelBefore === false )
            $this->label( $labelText, $id );
    }


	public function close( $tag = '' )
	{
        if ( $tag == '' && $this->lastOpenTag != '' )
            $this->markup .= "</" . $this->lastOpenTag . ">";
        else
        {
            $tag = $this->stripGtLt( $tag );
            $this->markup .= "</$tag>\n";
        }
	}

    public function div( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'div', $text, $class, $id, $atts );
    }

    public function p( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'p', $text, $class, $id, $atts );
    }

    public function h1( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'h1', $text, $class, $id, $atts );
    }

    public function h2( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'h2', $text, $class, $id, $atts );
    }

    public function h3( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'h3', $text, $class, $id, $atts );
    }

    public function h4( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'h4', $text, $class, $id, $atts );
    }

    public function pre( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'pre', $text, $class, $id, $atts );
    }

    public function li( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'li', $text, $class, $id, $atts );
    }

	public function link( $text, $href, $target = '', $class = '', $id = '', $other = '' )
	{
		$this->markup .= "<a ". (( $id != '' ) ? "id='$id'" : '') . (( $class != '' ) ? "class='$class'" : '') . " href='$href'" . (( $target != '' ) ? " target='$target'" : '') .  (( $other != '' ) ? ' ' . $other : '') . ">$text</a>";
	}

	public function listlinks( $texts, $hrefs, $targets ='', $classes ='', $ids = '' )
	{
		$i = 0;
		$len = count( $texts );
		for (; $i < $len; $i++ )
		{
			$class = (gettype($classes) == "array") ? ( isset($classes[$i]) ? $classes[$i] : '' ) : $classes;
			$target = (gettype($targets) == "array") ? ( isset($targets[$i]) ? $targets[$i] : '' ) : $targets;
			$this->open( 'li', $class, isset($ids[$i]) ? $ids[$i] : '' );
			$this->link( $texts[$i], isset($hrefs[$i]) ? $hrefs[$i] : '', $target );
			$this->close( 'li' );
		}
	}

    public function th( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'th', $text, $class, $id, $atts );
    }

    public function td( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'td', $text, $class, $id, $atts );
    }

    public function tr( $text, $class = '', $id = '', $atts = '' )
    {
        $this->tag( 'tr', $text, $class, $id, $atts );
    }

	public function append( $text )
	{
		$this->markup .= $text;
	}

    public function clear()
    {
        $this->markup .= "<br style='clear:both' />";
    }

	public function clearMarkup()
	{
		$this->markup = '';
	}

	public function output()
	{
		echo $this->markup;
	}

	private function stripGtLt( $input )
	{
		return preg_replace( "/<|>|\//u", '', $input );
	}
}
