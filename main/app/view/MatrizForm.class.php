<?php
/**
 * MatrizForm Form
 * @author  <your name here>
 */
class MatrizForm extends TPage
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
        $this->form = new BootstrapFormBuilder('form_Matriz');
        $this->form->setFormTitle('Matriz');
        

        // create the form fields
        $ID_MATRIZ = new TEntry('ID_MATRIZ');
        $TAG = new TText('TAG');
        $ORIGEM = new TEntry('ORIGEM');
        $DESTINO = new TEntry('DESTINO');
        $DETALHE_ORIGEM = new TText('DETALHE_ORIGEM');
        $DETALHE_DESTINO = new TText('DETALHE_DESTINO');


        // add the fields
        $this->form->addFields( [ new TLabel('Id Matriz') ], [ $ID_MATRIZ ] );
        $this->form->addFields( [ new TLabel('Tag') ], [ $TAG ] );
        $this->form->addFields( [ new TLabel('Origem') ], [ $ORIGEM ] );
        $this->form->addFields( [ new TLabel('Destino') ], [ $DESTINO ] );
        $this->form->addFields( [ new TLabel('Detalhe Origem') ], [ $DETALHE_ORIGEM ] );
        $this->form->addFields( [ new TLabel('Detalhe Destino') ], [ $DETALHE_DESTINO ] );



        // set sizes
        $ID_MATRIZ->setSize('100%');
        $TAG->setSize('100%');
        $ORIGEM->setSize('100%');
        $DESTINO->setSize('100%');
        $DETALHE_ORIGEM->setSize('100%');
        $DETALHE_DESTINO->setSize('100%');



        if (!empty($ID_MATRIZ))
        {
            $ID_MATRIZ->setEditable(FALSE);
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
            
            $object = new Matriz;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated ID_MATRIZ
            $data->ID_MATRIZ = $object->ID_MATRIZ;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            $acao = new TAction(array('MatrizForm','onClear'));//a ação de clear a ser chamada abaixo no message
            
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
                $object = new Matriz($key); // instantiates the Active Record
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
