<?php
/**
 * MatrizList Listing
 * @author  <your name here>
 */
class MatrizList extends TPage
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
        $this->form = new BootstrapFormBuilder('form_Matriz');
        $this->form->setFormTitle('Matriz');
        

        // create the form fields
        $ID_MATRIZ = new TEntry('ID_MATRIZ');
        $TAG = new TEntry('TAG');
        $ORIGEM = new TEntry('ORIGEM');
        $DESTINO = new TEntry('DESTINO');
        $DETALHE_ORIGEM = new TEntry('DETALHE_ORIGEM');
        $DETALHE_DESTINO = new TEntry('DETALHE_DESTINO');


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

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Matriz_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['MatrizForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_ID_MATRIZ = new TDataGridColumn('ID_MATRIZ', 'Id Matriz', 'right');
        $column_TAG = new TDataGridColumn('TAG', 'Tag', 'left');
        $column_ORIGEM = new TDataGridColumn('ORIGEM', 'Origem', 'right');
        $column_DESTINO = new TDataGridColumn('DESTINO', 'Destino', 'right');
        $column_DETALHE_ORIGEM = new TDataGridColumn('DETALHE_ORIGEM', 'Detalhe Origem', 'left');
        $column_DETALHE_DESTINO = new TDataGridColumn('DETALHE_DESTINO', 'Detalhe Destino', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_ID_MATRIZ);
        $this->datagrid->addColumn($column_TAG);
        $this->datagrid->addColumn($column_ORIGEM);
        $this->datagrid->addColumn($column_DESTINO);
        $this->datagrid->addColumn($column_DETALHE_ORIGEM);
        $this->datagrid->addColumn($column_DETALHE_DESTINO);

        
        // create EDIT action
        $action_edit = new TDataGridAction(['MatrizForm', 'onEdit']);
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('ID_MATRIZ');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        //$action_del->setUseButton(TRUE);
        //$action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('ID_MATRIZ');
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
            $object = new Matriz($key); // instantiates the Active Record
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
        TSession::setValue('MatrizList_filter_ID_MATRIZ',   NULL);
        TSession::setValue('MatrizList_filter_TAG',   NULL);
        TSession::setValue('MatrizList_filter_ORIGEM',   NULL);
        TSession::setValue('MatrizList_filter_DESTINO',   NULL);
        TSession::setValue('MatrizList_filter_DETALHE_ORIGEM',   NULL);
        TSession::setValue('MatrizList_filter_DETALHE_DESTINO',   NULL);

        if (isset($data->ID_MATRIZ) AND ($data->ID_MATRIZ)) {
            $filter = new TFilter('ID_MATRIZ', 'like', "%{$data->ID_MATRIZ}%"); // create the filter
            TSession::setValue('MatrizList_filter_ID_MATRIZ',   $filter); // stores the filter in the session
        }


        if (isset($data->TAG) AND ($data->TAG)) {
            $filter = new TFilter('TAG', 'like', "%{$data->TAG}%"); // create the filter
            TSession::setValue('MatrizList_filter_TAG',   $filter); // stores the filter in the session
        }


        if (isset($data->ORIGEM) AND ($data->ORIGEM)) {
            $filter = new TFilter('ORIGEM', 'like', "%{$data->ORIGEM}%"); // create the filter
            TSession::setValue('MatrizList_filter_ORIGEM',   $filter); // stores the filter in the session
        }


        if (isset($data->DESTINO) AND ($data->DESTINO)) {
            $filter = new TFilter('DESTINO', 'like', "%{$data->DESTINO}%"); // create the filter
            TSession::setValue('MatrizList_filter_DESTINO',   $filter); // stores the filter in the session
        }


        if (isset($data->DETALHE_ORIGEM) AND ($data->DETALHE_ORIGEM)) {
            $filter = new TFilter('DETALHE_ORIGEM', 'like', "%{$data->DETALHE_ORIGEM}%"); // create the filter
            TSession::setValue('MatrizList_filter_DETALHE_ORIGEM',   $filter); // stores the filter in the session
        }


        if (isset($data->DETALHE_DESTINO) AND ($data->DETALHE_DESTINO)) {
            $filter = new TFilter('DETALHE_DESTINO', 'like', "%{$data->DETALHE_DESTINO}%"); // create the filter
            TSession::setValue('MatrizList_filter_DETALHE_DESTINO',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Matriz_filter_data', $data);
        
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
            
            // creates a repository for Matriz
            $repository = new TRepository('Matriz');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'ID_MATRIZ';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('MatrizList_filter_ID_MATRIZ')) {
                $criteria->add(TSession::getValue('MatrizList_filter_ID_MATRIZ')); // add the session filter
            }


            if (TSession::getValue('MatrizList_filter_TAG')) {
                $criteria->add(TSession::getValue('MatrizList_filter_TAG')); // add the session filter
            }


            if (TSession::getValue('MatrizList_filter_ORIGEM')) {
                $criteria->add(TSession::getValue('MatrizList_filter_ORIGEM')); // add the session filter
            }


            if (TSession::getValue('MatrizList_filter_DESTINO')) {
                $criteria->add(TSession::getValue('MatrizList_filter_DESTINO')); // add the session filter
            }


            if (TSession::getValue('MatrizList_filter_DETALHE_ORIGEM')) {
                $criteria->add(TSession::getValue('MatrizList_filter_DETALHE_ORIGEM')); // add the session filter
            }


            if (TSession::getValue('MatrizList_filter_DETALHE_DESTINO')) {
                $criteria->add(TSession::getValue('MatrizList_filter_DETALHE_DESTINO')); // add the session filter
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
            $object = new Matriz($key, FALSE); // instantiates the Active Record
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
                    $object = new Matriz;
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
            $object->check = new TCheckButton('check' . $object->ID_MATRIZ);
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
