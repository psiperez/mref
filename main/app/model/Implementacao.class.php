<?php
/**
 * Implementacao Active Record
 * @author  <your-name-here>
 */
class Implementacao extends TRecord
{
    const TABLENAME = 'implementacao';
    const PRIMARYKEY= 'ID_IMPLEMENTACAO';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('CAPITULO_I');
        parent::addAttribute('ITEM_I');
        parent::addAttribute('SUBITEM_I');
        parent::addAttribute('NR_PARAG_I');
        parent::addAttribute('TEXTO_I');
    }


}
