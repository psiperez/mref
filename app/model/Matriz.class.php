<?php
/**
 * Matriz Active Record
 * @author  <your-name-here>
 */
class Matriz extends TRecord
{
    const TABLENAME = 'matriz';
    const PRIMARYKEY= 'ID_MATRIZ';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('TAG');
        parent::addAttribute('ORIGEM');
        parent::addAttribute('DESTINO');
        parent::addAttribute('DETALHE_ORIGEM');
        parent::addAttribute('DETALHE_DESTINO');
    }


}
