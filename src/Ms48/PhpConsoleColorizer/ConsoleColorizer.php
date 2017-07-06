<?php

namespace Ms48\PhpConsoleColorizer;

/**
 * Add ANSI colorizing to your console.
 *
 * @author Shanuka Dilshan <https://github.com/ms48>
 * @license MIT License
 */
class ConsoleColorizer
{

    const ESC = "\033[";
    const RESET_STYLES = "\033[0m";
            
    /**
     * Total completed count.
     *
     * @var string
     */
    protected $currentString;
    
    /**
     * color styles array.
     *
     * @var array
     */
    protected $styles;
    
    /**
     * CLI color support.
     *
     * @var boolean
     */
    protected $colorSupport;
    
   
    public function __construct()
    {
        $this->currentString = "";        
        
        //initialize the array 
        $this->styles = [
            /*
             * font styles
             */            
            'reset'            => '0',
            'bold'             => '1',
            'dark'             => '2',
            'italic'           => '3',
            'underline'        => '4',
            
            /*
             * foreground colors
             */
            'black'            => '30',
            'red'              => '31',
            'green'            => '32',
            'yellow'           => '33',
            'blue'             => '34',
            'magenta'          => '35',
            'cyan'             => '36',
            
            'light_gray'       => '37',
            
            'default'          => '39',
            
            'dark_gray'        => '90',
            'light_red'        => '91',
            'light_green'      => '92',
            'light_yellow'     => '93',
            'light_blue'       => '94',
            'light_magenta'    => '95',
            'light_cyan'       => '96',
            'white'            => '97',
            
            /*
             * background colors
             */
            'bg_black'         => '40',
            'bg_red'           => '41',
            'bg_green'         => '42',
            'bg_yellow'        => '43',
            'bg_blue'          => '44',
            'bg_magenta'       => '45',
            'bg_cyan'          => '46',
            'bg_light_gray'    => '47',
            
            'bg_default'       => '49',
        ];
        
        //check CLI is supporting colors;
        $this->colorSupport = $this->hasColorSupport();
    }  
    
    /**
     * Returns true if the stream supports colorization.
     *
     * Colorization is disabled if not supported by the stream:
     *
     *  -  Windows != 10.0.10586 without Ansicon, ConEmu or Mintty
     *  -  non tty consoles
     *
     * @return bool true if the stream supports colorization, false otherwise
     * @link https://github.com/symfony/console/blob/master/Output/StreamOutput.php#L93
     */
    protected function hasColorSupport()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return
                '10.0.10586' === PHP_WINDOWS_VERSION_MAJOR.'.'.PHP_WINDOWS_VERSION_MINOR.'.'.PHP_WINDOWS_VERSION_BUILD
                || false !== getenv('ANSICON')
                || 'ON' === getenv('ConEmuANSI')
                || 'xterm' === getenv('TERM');
        }
        return function_exists('posix_isatty') && @posix_isatty($this->stream);
    }
    
    
    /**
     * Add string to the class and do the styling works.
     * You can chaining this method as you wish.
     * To get an out put, you have to call the get() method after finished styling works
     *
     * @param string $text String to be colorized
     * @param string $color optional foreground color
     * @param string $background optional background color
     * @param string $style optional font style
     * @return  Colorize The Colorize object     
     */
    
    public function addColor(
        $text, 
        $color = "white", 
        $background = "default", 
        $style = ""
    ) {
        
        //check color support
        if(!$this->colorSupport){
            return $this;
        }
        
        $this->setForgroundColor($color);
        $this->setBackgroundColor($background);
        
        //check styles added
        if($style){
            $this->setStyle($style);
        }
         $this->currentString .= $text.self::RESET_STYLES;
         return $this;
    }  
    
    /**
     * Get colorized output as string.
     *
     * @return  Colorize()     
     */
    public function get()
    {
        $str = $this->currentString;
        $this->currentString = '';
        return $str;
    }
    
    
    
    protected function setForgroundColor($color)
    {
        $str = strtolower($color);
        if(!$this->isStyleNameExist($str)){
            throw new StyleNotFoundEception('Font color not found'); 
        }
        
        $code = $this->styles[$str];        
        $this->currentString .= self::ESC.$code."m";
        
        return $this;
    }
    
    protected function setBackgroundColor($background)
    {
        $str = strtolower($background);
        if(!$this->isStyleNameExist('bg_'.$str)){
            throw new StyleNotFoundEception('Background color not found'); 
        }
        
        $code = $this->styles['bg_'.$str];        
        $this->currentString .= self::ESC.$code."m";
        
        return $this;
    }
    
    protected function setStyle($style)
    {
        $str = strtolower($style);
        if(!$this->isStyleNameExist($str)){
            throw new StyleNotFoundEception('Font color not found'); 
        }
        
        $code = $this->styles[$str];        
        $this->currentString .= self::ESC.$code."m";
        
        return $this;
    }
    
    protected function isStyleNameExist($name)
    {
        return array_key_exists($name, $this->styles);
    }    
}
