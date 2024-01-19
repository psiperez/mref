<?php
/**
 * RelatorioModForm Form
 * @author  <your name here>
 */
class RelatorioModForm extends TPage
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
        $this->form = new BootstrapFormBuilder('form_RelatorioMod');
        $this->form->setFormTitle('Registro');
        

        // create the form fields
        $ID_RELATORIO = new TEntry('ID_RELATORIO');
        $DOC = new TEntry('DOC');
        /*
        $DOC = new TCombo('DOC');
        $valores_item_DOC = [
                                     'Relatório'=>'Relatório',
                                     'Implementação'=>'Implementação'
                                     //''=>'',
        $DOC -> addItems($valores_item_CAPITULO_R);
                                     
        */
        
        $CAPITULO_R = new TEntry('CAPITULO_R');
        /*
        
        $valores_item_CAPITULO_R = [
                                     'I. Introduction'=>'I. Introduction',
                                     'II. Organizational matters'=>'II. Organizational matters',
                                     'III. Consideration ofthe draft report of the Working Group of the Whole'=>'III. Consideration ofthe draft report of the Working Group of the Whole',
                                     'IV. Adoption of the report to the General Assembly at its seventy-seventh session'=>'IV. Adoption of the report to the General Assembly at its seventy-seventh session',
                                     'V. Proposals, recommendations and conclusions of the Special Committee'=>'V. Proposals, recommendations and conclusions of the Special Committee'
                                     //''=>'',
        
                                     
        */
        
        /*
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
        
        */
     
        $ITEM_R = new TEntry('ITEM_R');
        /*
        $valores_item_ITEM_R = [
                                'NC'=>'NC',//NC = nada consta
                                'II.A. Opening and duration of the session'=>'II.A. Opening and duration of the session',
                                'II.B. Election of officers'=>'II.B. Election of officers',
                                'II.C. Agenda'=>'II.C. Agenda',
                                'II.D. Organization of work'=>'II.D. Organization of work',
                                'II.E. Proceedings of the Committee'=>'II.E. Proceedings of the Committee',
                                'V.A. Introduction'=>'V.A. Introduction',
                                'V.B. Guiding principles, definitions and implementation of mandates'=>'V.B. Guiding principles, definitions and implementation of mandates',
                                'V.C. Conduct of peacekeepers and peacekeeping operations'=>'V.C. Conduct of peacekeepers and peacekeeping operations',
                                'V.D. Partnerships'=>'V.D. Partnerships',
                                'V.E. Peace building and sustaining peace'=>'V.E. Peace building and sustaining peace',
                                'V.F. Performance and accountability'=>'V.F. Performance and accountability',
                                'V.G. Politics'=>'V.G. Politics',
                                'V.H. Protection'=>'V.H. Protection',
                                'V.I. Safety and securit'=>'V.I. Safety and securit',
                                'V.J. Women,peace and security'=>'V.J. Women,peace and security'
        */
        
        
        $SUBITEM_R = new TEntry('SUBITEM_R');
        $NR_PARAG_R = new TEntry('NR_PARAG_R');
        $TEXTO_R = new TText('TEXTO_R');
        $IDEAS = new TText('IDEAS');
        $REF = new TText('REF');
        $SUGESTAO = new TText('SUGESTAO');


        // add the fields
        $this->form->addFields( [ new TLabel('Id Relatorio') ], [ $ID_RELATORIO ] );
        $this->form->addFields( [ new TLabel('Doc') ], [ $DOC ] );
        $this->form->addFields( [ new TLabel('Capitulo R') ], [ $CAPITULO_R ] );
        $this->form->addFields( [ new TLabel('Item R') ], [ $ITEM_R ] );
        $this->form->addFields( [ new TLabel('Subitem R') ], [ $SUBITEM_R ] );
        $this->form->addFields( [ new TLabel('Nr Parag R') ], [ $NR_PARAG_R ] );
        $this->form->addFields( [ new TLabel('Texto R') ], [ $TEXTO_R ] );
        $this->form->addFields( [ new TLabel('Ideas') ], [ $IDEAS ] );
        $this->form->addFields( [ new TLabel('Ref') ], [ $REF ] );
        $this->form->addFields( [ new TLabel('Sugestao') ], [ $SUGESTAO ] );



        // set sizes
        $ID_RELATORIO->setSize('100%');
        $DOC->setSize('100%');
        $CAPITULO_R->setSize('100%');
        $ITEM_R->setSize('100%');
        $SUBITEM_R->setSize('100%');
        $NR_PARAG_R->setSize('100%');
        $TEXTO_R->setSize('100%');
        $IDEAS->setSize('100%');
        $REF->setSize('100%');
        $SUGESTAO->setSize('100%');



        if (!empty($ID_RELATORIO))
        {
            $ID_RELATORIO->setEditable(FALSE);
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
            
            $object = new RelatorioMod;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated ID_RELATORIO
            $data->ID_RELATORIO = $object->ID_RELATORIO;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            $acao = new TAction(array('RelatorioModForm','onClear'));//a ação de clear a ser chamada abaixo no message

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
                $object = new RelatorioMod($key); // instantiates the Active Record
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
