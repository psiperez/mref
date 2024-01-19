<?php
/**
 * RelatorioModList Listing
 * @author  <your name here>
 */
class RelatorioModList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_RelatorioMod');
        $this->form->setFormTitle('Registro');
        

        // create the form fields
        $ID_RELATORIO = new TEntry('ID_RELATORIO');
        $DOC = new TDBCombo('DOC','matriz_referencias','RelatorioMod','DOC','DOC');
        $CAPITULO_R = new TDBMultiSearch('CAPITULO_R','matriz_referencias','RelatorioMod','CAPITULO_R','CAPITULO_R');
        $ITEM_R = new TDBMultiSearch('ITEM_R','matriz_referencias','RelatorioMod','ITEM_R','ITEM_R');
        $SUBITEM_R = new TEntry('SUBITEM_R');
        $NR_PARAG_R = new TEntry('NR_PARAG_R');
        $TEXTO_R = new TEntry('TEXTO_R');
        $IDEAS = new TEntry('IDEAS');
        $REF = new TEntry('REF');
        $SUGESTAO = new TEntry('SUGESTAO');


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

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('RelatorioMod_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['RelatorioModForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_ID_RELATORIO = new TDataGridColumn('ID_RELATORIO', 'Id Relatorio', 'right');
        $column_DOC = new TDataGridColumn('DOC', 'Doc', 'left');
        $column_CAPITULO_R = new TDataGridColumn('CAPITULO_R', 'Capitulo R', 'left');
        $column_ITEM_R = new TDataGridColumn('ITEM_R', 'Item R', 'left');
        $column_SUBITEM_R = new TDataGridColumn('SUBITEM_R', 'Subitem R', 'left');
        $column_NR_PARAG_R = new TDataGridColumn('NR_PARAG_R', 'Nr Parag R', 'right');
        $column_TEXTO_R = new TDataGridColumn('TEXTO_R', 'Texto R', 'left');
        $column_IDEAS = new TDataGridColumn('IDEAS', 'Ideas', 'left');
        $column_REF = new TDataGridColumn('REF', 'Ref', 'left');
        $column_SUGESTAO = new TDataGridColumn('SUGESTAO', 'Sugestao', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_ID_RELATORIO);
        $this->datagrid->addColumn($column_DOC);
        $this->datagrid->addColumn($column_CAPITULO_R);
        $this->datagrid->addColumn($column_ITEM_R);
        $this->datagrid->addColumn($column_SUBITEM_R);
        $this->datagrid->addColumn($column_NR_PARAG_R);
        $this->datagrid->addColumn($column_TEXTO_R);
        $this->datagrid->addColumn($column_IDEAS);
        $this->datagrid->addColumn($column_REF);
        $this->datagrid->addColumn($column_SUGESTAO);

        
        // create EDIT action
        $action_edit = new TDataGridAction(['RelatorioModForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('ID_RELATORIO');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        //$action_del->setUseButton(TRUE);
        //$action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('ID_RELATORIO');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $this->datagrid->disableDefaultClick();
        
        // put datagrid inside a form
        $this->formgrid = new TForm;
        $this->formgrid->add($this->datagrid);
        
        // creates the delete collection button
        $this->deleteButton = new TButton('delete_collection');
        $this->deleteButton->setAction(new TAction(array($this, 'onDeleteCollection')), AdiantiCoreTranslator::translate('Delete selected'));
        $this->deleteButton->setImage('fa:remove red');
        $this->formgrid->addField($this->deleteButton);
        
        $gridpack = new TVBox;
        $gridpack->style = 'width: 100%';
        $gridpack->add($this->formgrid);
        $gridpack->add($this->deleteButton)->style = 'background:whiteSmoke;border:1px solid #cccccc; padding: 3px;padding: 5px;';
        
        $this->transformCallback = array($this, 'onBeforeLoad');


        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $gridpack, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('matriz_referencias'); // open a transaction with database
            $object = new RelatorioMod($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('RelatorioModList_filter_ID_RELATORIO',   NULL);
        TSession::setValue('RelatorioModList_filter_DOC',   NULL);
        TSession::setValue('RelatorioModList_filter_CAPITULO_R',   NULL);
        TSession::setValue('RelatorioModList_filter_ITEM_R',   NULL);
        TSession::setValue('RelatorioModList_filter_SUBITEM_R',   NULL);
        TSession::setValue('RelatorioModList_filter_NR_PARAG_R',   NULL);
        TSession::setValue('RelatorioModList_filter_TEXTO_R',   NULL);
        TSession::setValue('RelatorioModList_filter_IDEAS',   NULL);
        TSession::setValue('RelatorioModList_filter_REF',   NULL);
        TSession::setValue('RelatorioModList_filter_SUGESTAO',   NULL);

        if (isset($data->ID_RELATORIO) AND ($data->ID_RELATORIO)) {
            $filter = new TFilter('ID_RELATORIO', 'like', "%{$data->ID_RELATORIO}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_ID_RELATORIO',   $filter); // stores the filter in the session
        }


        if (isset($data->DOC) AND ($data->DOC)) {
            $filter = new TFilter('DOC', 'like', "%{$data->DOC}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_DOC',   $filter); // stores the filter in the session
        }


        if (isset($data->CAPITULO_R) AND ($data->CAPITULO_R)) {
            $filter = new TFilter('CAPITULO_R', 'like', "%{$data->CAPITULO_R}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_CAPITULO_R',   $filter); // stores the filter in the session
        }


        if (isset($data->ITEM_R) AND ($data->ITEM_R)) {
            $filter = new TFilter('ITEM_R', 'like', "%{$data->ITEM_R}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_ITEM_R',   $filter); // stores the filter in the session
        }


        if (isset($data->SUBITEM_R) AND ($data->SUBITEM_R)) {
            $filter = new TFilter('SUBITEM_R', 'like', "%{$data->SUBITEM_R}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_SUBITEM_R',   $filter); // stores the filter in the session
        }


        if (isset($data->NR_PARAG_R) AND ($data->NR_PARAG_R)) {
            $filter = new TFilter('NR_PARAG_R', 'like', "%{$data->NR_PARAG_R}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_NR_PARAG_R',   $filter); // stores the filter in the session
        }


        if (isset($data->TEXTO_R) AND ($data->TEXTO_R)) {
            $filter = new TFilter('TEXTO_R', 'like', "%{$data->TEXTO_R}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_TEXTO_R',   $filter); // stores the filter in the session
        }


        if (isset($data->IDEAS) AND ($data->IDEAS)) {
            $filter = new TFilter('IDEAS', 'like', "%{$data->IDEAS}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_IDEAS',   $filter); // stores the filter in the session
        }


        if (isset($data->REF) AND ($data->REF)) {
            $filter = new TFilter('REF', 'like', "%{$data->REF}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_REF',   $filter); // stores the filter in the session
        }


        if (isset($data->SUGESTAO) AND ($data->SUGESTAO)) {
            $filter = new TFilter('SUGESTAO', 'like', "%{$data->SUGESTAO}%"); // create the filter
            TSession::setValue('RelatorioModList_filter_SUGESTAO',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('RelatorioMod_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'matriz_referencias'
            TTransaction::open('matriz_referencias');
            
            // creates a repository for RelatorioMod
            $repository = new TRepository('RelatorioMod');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'ID_RELATORIO';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('RelatorioModList_filter_ID_RELATORIO')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_ID_RELATORIO')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_DOC')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_DOC')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_CAPITULO_R')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_CAPITULO_R')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_ITEM_R')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_ITEM_R')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_SUBITEM_R')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_SUBITEM_R')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_NR_PARAG_R')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_NR_PARAG_R')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_TEXTO_R')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_TEXTO_R')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_IDEAS')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_IDEAS')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_REF')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_REF')); // add the session filter
            }


            if (TSession::getValue('RelatorioModList_filter_SUGESTAO')) {
                $criteria->add(TSession::getValue('RelatorioModList_filter_SUGESTAO')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('matriz_referencias'); // open a transaction with database
            $object = new RelatorioMod($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Ask before delete record collection
     */
    public function onDeleteCollection( $param )
    {
        $data = $this->formgrid->getData(); // get selected records from datagrid
        $this->formgrid->setData($data); // keep form filled
        
        if ($data)
        {
            $selected = array();
            
            // get the record id's
            foreach ($data as $index => $check)
            {
                if ($check == 'on')
                {
                    $selected[] = substr($index,5);
                }
            }
            
            if ($selected)
            {
                // encode record id's as json
                $param['selected'] = json_encode($selected);
                
                // define the delete action
                $action = new TAction(array($this, 'deleteCollection'));
                $action->setParameters($param); // pass the key parameter ahead
                
                // shows a dialog to the user
                new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
            }
        }
    }
    
    /**
     * method deleteCollection()
     * Delete many records
     */
    public function deleteCollection($param)
    {
        // decode json with record id's
        $selected = json_decode($param['selected']);
        
        try
        {
            TTransaction::open('matriz_referencias');
            if ($selected)
            {
                // delete each record from collection
                foreach ($selected as $id)
                {
                    $object = new RelatorioMod;
                    $object->delete( $id );
                }
                $posAction = new TAction(array($this, 'onReload'));
                $posAction->setParameters( $param );
                new TMessage('info', AdiantiCoreTranslator::translate('Records deleted'), $posAction);
            }
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }


    /**
     * Transform datagrid objects
     * Create the checkbutton as datagrid element
     */
    public function onBeforeLoad($objects, $param)
    {
        // update the action parameters to pass the current page to action
        // without this, the action will only work for the first page
        $deleteAction = $this->deleteButton->getAction();
        $deleteAction->setParameters($param); // important!
        
        $gridfields = array( $this->deleteButton );
        
        foreach ($objects as $object)
        {
            $object->check = new TCheckButton('check' . $object->ID_RELATORIO);
            $object->check->setIndexValue('on');
            $gridfields[] = $object->check; // important
        }
        
        $this->formgrid->setFields($gridfields);
    }

    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
