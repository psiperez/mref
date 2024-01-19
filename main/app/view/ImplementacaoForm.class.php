<?php
/**
 * ImplementacaoForm Form
 * @author  <your name here>
 */
class ImplementacaoForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Implementacao');
        $this->form->setFormTitle('Implementacao');
        

        // create the form fields
        $ID_IMPLEMENTACAO = new TEntry('ID_IMPLEMENTACAO');
        $CAPITULO_I = new TCombo('CAPITULO_I');
        
        $valores_item_CAPITULO_I = [
                                     'I. Strategic context'=>'I. Strategic context',
                                     'II. Overview of Action for Peacekeeping Plus priorities'=>'II. Overview of Action for Peacekeeping Plus priorities',
                                     'III. Political impact of peacekeeping'=>'III. Political impact of peacekeeping',
                                     'IV. Women and peace and security'=>'IV. Women and peace and security',
                                     'V. Protection'=>'V. Protection',
                                     'VI. Safety and security'=>'VI. Safety and security',
                                     'VII. Performance and accountability'=>'VII. Performance and accountability',
                                     'VIII. Peacebuilding and sustaining peace'=>'VIII. Peacebuilding and sustaining peace',
                                     'IX. Partnerships'=>'IX. Partnerships',
                                     'X. Conduct of peacekeepers and of peacekeeping operations'=>'X. Conduct of peacekeepers and of peacekeeping operations'
                                     //''=>'',
                                     
                                      
                                    ];
        $CAPITULO_I -> addItems($valores_item_CAPITULO_I);
        
        $ITEM_I = new TEntry('ITEM_I');
        $SUBITEM_I = new TEntry('SUBITEM_I');
        $NR_PARAG_I = new TEntry('NR_PARAG_I');
        $TEXTO_I = new TText('TEXTO_I');


        // add the fields
        $this->form->addFields( [ new TLabel('Id Implementacao') ], [ $ID_IMPLEMENTACAO ] );
        $this->form->addFields( [ new TLabel('Capitulo I') ], [ $CAPITULO_I ] );
        $this->form->addFields( [ new TLabel('Item I') ], [ $ITEM_I ] );
        $this->form->addFields( [ new TLabel('Subitem I') ], [ $SUBITEM_I ] );
        $this->form->addFields( [ new TLabel('Nr Parag I') ], [ $NR_PARAG_I ] );
        $this->form->addFields( [ new TLabel('Texto I') ], [ $TEXTO_I ] );



        // set sizes
        $ID_IMPLEMENTACAO->setSize('100%');
        $CAPITULO_I->setSize('100%');
        $ITEM_I->setSize('100%');
        $SUBITEM_I->setSize('100%');
        $NR_PARAG_I->setSize('100%');
        $TEXTO_I->setSize('100%');



        if (!empty($ID_IMPLEMENTACAO))
        {
            $ID_IMPLEMENTACAO->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('matriz_referencias'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Implementacao;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated ID_IMPLEMENTACAO
            $data->ID_IMPLEMENTACAO = $object->ID_IMPLEMENTACAO;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            $acao = new TAction(array('ImplementacaoForm','onClear'));//a ação de clear a ser chamada abaixo no message
           new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'),$acao);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('matriz_referencias'); // open a transaction
                $object = new Implementacao($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
