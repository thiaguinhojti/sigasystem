<?php
/**
 * FrmListaMatriz Listing
 * @author  <your name here>
 */
class FrmListaMatriz extends TPage
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
        $this->form = new TQuickForm('form_search_Matriz');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Matriz');
        

        // create the form fields
        $idMatriz = new TEntry('idMatriz');
        $numeroChipMatriz = new TEntry('numeroChipMatriz');


        // add the fields
        $this->form->addQuickField('CÓDIGO...:', $idMatriz,  100 );
        $this->form->addQuickField('CHIP....:', $numeroChipMatriz,  100 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Matriz_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('FrmMatriz', 'onEdit')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';
        $this->datagrid->enablePopover('Matriz', "<img src='{photo_path}'>");
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_idMatriz = new TDataGridColumn('idMatriz', 'CÓDIGO', 'center');
        $column_numeroChipMatriz = new TDataGridColumn('numeroChipMatriz', 'CHIP', 'center');
        $column_pesoMatriz = new TDataGridColumn('pesoMatriz', 'PESO', 'center');
        $column_compCabecaMatriz = new TDataGridColumn('compCabecaMatriz', 'CABEÇA', 'center');
        $column_compParcialMatriz = new TDataGridColumn('compParcialMatriz', 'CORPO', 'center');
        $column_compTotalMatriz = new TDataGridColumn('compTotalMatriz', 'COMPRIMENTO', 'center');
        $column_sexoMatriz = new TDataGridColumn('sexoMatriz', 'SEXO', 'center');
        $column_idEspecie = new TDataGridColumn('especies_descricao', 'ESPÉCIE', 'center');
        $column_status = new TDataGridColumn('status', 'STATUS', 'center');
        
        $column_pesoMatriz->setTransformer(function($value, $object, $row) {
            $pesoMatriz = number_format($value, 2,',','.');
            return $pesoMatriz;
        });
        $column_compCabecaMatriz->setTransformer(function($value, $object, $row) {
            $cabecaMatriz = number_format($value, 2,',','.');
            return $cabecaMatriz;
        });
        $column_compParcialMatriz->setTransformer(function($value, $object, $row) {
            $corpoMatriz = number_format($value, 2,',','.');
            return $corpoMatriz;
        });
        $column_compTotalMatriz->setTransformer(function($value, $object, $row) {
            $tamanhoMatriz = number_format($value, 2,',','.');
            return $tamanhoMatriz;
        });
        
        


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_idMatriz);
        $this->datagrid->addColumn($column_numeroChipMatriz);
        $this->datagrid->addColumn($column_pesoMatriz);
        $this->datagrid->addColumn($column_compCabecaMatriz);
        $this->datagrid->addColumn($column_compParcialMatriz);
        $this->datagrid->addColumn($column_compTotalMatriz);
        $this->datagrid->addColumn($column_sexoMatriz);
        $this->datagrid->addColumn($column_idEspecie);
        $this->datagrid->addColumn($column_status);
        
       


        // creates the datagrid column actions
        $order_idMatriz = new TAction(array($this, 'onReload'));
        $order_idMatriz->setParameter('order', 'idMatriz');
        $column_idMatriz->setAction($order_idMatriz);
        
        $order_numeroChipMatriz = new TAction(array($this, 'onReload'));
        $order_numeroChipMatriz->setParameter('order', 'numeroChipMatriz');
        $column_numeroChipMatriz->setAction($order_numeroChipMatriz);
        
        $order_idEspecie = new TAction(array($this, 'onReload'));
        $order_idEspecie->setParameter('order', 'idEspecie');
        $column_idEspecie->setAction($order_idEspecie);
        

        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FrmMatriz', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('idMatriz');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('idMatriz');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
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
        $container->add(TPanelGroup::pack('Consulta Matriz', $this->form));
        $container->add($gridpack);
        $container->add($this->pageNavigation);
        
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
            
            TTransaction::open('dbwf'); // open a transaction with database
            $object = new Matriz($key); // instantiates the Active Record
            $object->dataCadastro = TDate::date2br($object->dataCadastro);
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
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
        TSession::setValue('FrmListaMatriz_filter_idMatriz',   NULL);
        TSession::setValue('FrmListaMatriz_filter_numeroChipMatriz',   NULL);

        if (isset($data->idMatriz) AND ($data->idMatriz)) {
            $filter = new TFilter('idMatriz', '=', "$data->idMatriz"); // create the filter
            TSession::setValue('FrmListaMatriz_filter_idMatriz',   $filter); // stores the filter in the session
        }


        if (isset($data->numeroChipMatriz) AND ($data->numeroChipMatriz)) {
            $filter = new TFilter('numeroChipMatriz', '=', "$data->numeroChipMatriz"); // create the filter
            TSession::setValue('FrmListaMatriz_filter_numeroChipMatriz',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Matriz_filter_data', $data);
        
        $param=array();
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
            // open a transaction with database 'dbwf'
            TTransaction::open('dbwf');
            
            // creates a repository for Matriz
            $repository = new TRepository('Matriz');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'idMatriz';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('FrmListaMatriz_filter_idMatriz')) {
                $criteria->add(TSession::getValue('FrmListaMatriz_filter_idMatriz')); // add the session filter
            }


            if (TSession::getValue('FrmListaMatriz_filter_numeroChipMatriz')) {
                $criteria->add(TSession::getValue('FrmListaMatriz_filter_numeroChipMatriz')); // add the session filter
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
    public function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('dbwf'); // open a transaction with database
            $object = new Matriz($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted')); // success message
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
            TTransaction::open('dbwf');
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
            $object->check = new TCheckButton('check' . $object->idMatriz);
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
