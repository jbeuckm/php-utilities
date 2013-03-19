<?php

class RegEx
{
    private static $delimeter = "~";
    private static $defaultModifiers = "isUu"; // PCRE_CASELESS, PCRE_DOTALL, PCRE_UNGREEDY, PCRE_UTF8
    private static $instance;
    private static $compiliedPattern;

    /**
     * Creates a complied PCRE pattern that all instances of RegEx will utilize.
     *
     * @param String $undelimited A PCRE pattern that is not delimited
     * @param String $modifiers Optional. The pattern modifiers for *$undelimited*. Passing *null* will result in static::$defaultModifiers being used.
     * @return RegEx An instance of the RegEx class for chained calls
     */
    public static function pattern( $undelimited, $modifiers = null )
    {
        static::$compiliedPattern = static::$delimeter
            . str_replace( static::$delimeter, "\\" . static::$delimeter, $undelimited )
            . static::$delimeter
            . ( $modifiers === null ? static::$defaultModifiers : $modifiers);

        return static::getInstance();
    }

    /**
     * @return RegEx
     */
    public static function getInstance()
    {
        if ( static::$instance === null )
            static::$instance = new RegEx;

        return static::$instance;
    }

	public function replace( $input, $replacement, $maxReplacements = -1 )
	{
        return preg_replace( static::$compiliedPattern, $replacement, $input, $maxReplacements );
	}

    /**
     * @param string $input
     * @param bool $all
     * @param bool $getOffsets
     * @param int $startByte
     * @return MatchResults
     */
    public function match( $input, $all = true, $getOffsets = false, $startByte = 0 )
    {
        if ( $all )
            preg_match_all( static::$compiliedPattern, $input, $result, ($getOffsets ? PREG_OFFSET_CAPTURE : 0) | PREG_SET_ORDER, $startByte );
        else
            preg_match( static::$compiliedPattern, $input, $result, ($getOffsets ? PREG_OFFSET_CAPTURE : 0), $startByte );

        if ( ! $all ) // basically this normalizes the difference between the result of preg_match versus preg_match_all
            $result = array( $result );

        $pms = array();
        for ( $i = 0, $iLen = count($result); $i < $iLen; $i++ )
        {
            $pm = new PatternMatch;
            $pms[] = $pm;
            for ( $k = 0, $kCount = count( $result[$i] ); $k < $kCount; $k++ )
            {
                if ( $k == 0 ) // first item gets stashed in match
                {
                    if ( $getOffsets )
                    {
                        $pm->match = $result[$i][$k][0];
                        $pm->offset = $result[$i][$k][1];
                    }
                    else
                        $pm->match = $result[$i][$k];
                }
                else // subsequent items collected into subMatches
                {
                    if ( $getOffsets )
                    {
                        $pm->subMatches[] = $result[$i][$k][0];
                        $pm->subOffsets[] = $result[$i][$k][1];
                    }
                    else
                        $pm->subMatches[] = $result[$i][$k];
                }
            }
        }
        return new MatchResults( $pms );
    }

    public static function split( $input, $withDelims = false, $limit = -1  )
    {
        return preg_split( static::$compiliedPattern, $input, $limit, ($withDelims ? PREG_SPLIT_DELIM_CAPTURE : 0) | PREG_SPLIT_NO_EMPTY );
    }
}

class PatternMatch
{
    public $match;
    public $offset;
    public $subMatches = array();
    public $subOffsets = array();

    function __toString()
    {
        return "PatternMatch matched '" . $this->match . "' with " . count($this->subMatches) . " sub-matches.";
    }
}

/**
 * @property bool $done
 * @property PatternMatch $next Retrieves current element at pointer and post-increments pointer
 * @property PatternMatch $current Retrieves current element at pointer
 * @property int $count Number of elements
 * @property int $position Position of pointer
 * @proeprty array $element
 *
 */
class MatchResults
{
    private $done = false;
    private $matches;
    private $pointer;
    private $max;
    private $count;

    function __construct( $collection )
    {
        $this->matches = $collection;
        $this->count = count( $collection );
        $this->max = $this->count - 1;
        if ( $this->max == -1 )
            $this->done = true;
        $this->pointer = -1;
    }

    public function __get( $name )
    {
        if ( $name === 'done' )
            return $this->done;

        if ( $name === 'next' )
        {
            $this->pointer++;

            if ( $this->pointer > $this->max )
                return null;
            else if ( $this->pointer === $this->max )
                $this->done = true;

            return $this->matches[ $this->pointer ];
        }

        if ( $name === 'current' )
            return $this->pointer === -1 ? $this->matches[ ++$this->pointer ] : $this->matches[ $this->pointer ];

        if ( $name === 'position' )
            return $this->pointer === -1 ? 0 : $this->pointer;

        if ( $name === 'count' )
            return $this->count;
    }

    /**
     * @param int $n
     * @return PatternMatch
     */
    public function element( $n )
    {
        return $this->matches[ $n ];
    }

    function __toString()
    {
        return "MatchResults: " . print_r($this->matches, true);
    }
}
