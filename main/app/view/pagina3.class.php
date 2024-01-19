<?php
class pagina3 extends TScript


{
    /**
     * Create a script
     * @param $code source code
     */
    public static function create( $code,  $timeout = null )
    {
        if ($timeout)
        {
            $code = "setTimeout( function() { $code }, $timeout )";
        }
        
        $script = new TElement('app/resources/14jan23_arquivo1.php');
        $script->{'language'} = 'Python';
        //$script->setUseSingleQuotes(TRUE);
        //$script->setUseLineBreaks(FALSE);
        //$script->add( str_replace( ["\n", "\r"], [' ', ' '], $code) );
        //if ($show)
        //{
           // $script->show();
        //}
        return $script;
    }
    
    /**
     * Import script
     * @param $script Script file name
     */
    public static function importFromFile( $script,  $timeout = null )
    {
        TScript::create('$.getScript("'.$script.'");', $timeout);
    }
}
