<?php
/**
 * Relatorio Active Record
 * @author  <your-name-here>
 */
class Relatorio extends TRecord
{
    const TABLENAME = 'relatorio';
    const PRIMARYKEY= 'ID_RELATORIO';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('CAPITULO_R');
        parent::addAttribute('ITEM_R');
        parent::addAttribute('SUBITEM_R');
        parent::addAttribute('NR_PARAG_R');
        parent::addAttribute('TEXTO_R');
        parent::addAttribute('REF');
        parent::addAttribute('SUGESTAO');
    }


}
