<?php
/**
 * FrmListaEspecie Listing
 * @author  <your name here>
 */
class FrmListaEspecie extends TPage
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
        $this->form = new TQuickForm('form_search_Especie');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Especie');
        

        // create the form fields
        $idEspecie = new TEntry('idEspecie');
        $nomePopularEspecie = new TEntry('nomePopularEspecie');
        $tipoEspecie = new TEntry('tipoEspecie');


        // add the fields
        $this->form->addQuickField('CÓDIGO...:', $idEspecie,  100 );
        $this->form->addQuickField('NOME POPULAR...:', $nomePopularEspecie,  200 );
        $this->form->addQuickField('TIPO...:', $tipoEspecie,  200 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Especie_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('FrmEspecie', 'onEdit')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        
        /*$column_idEspecie = $this->datagrid->addQuickColumn('CÓDIGO', 'idEspecie', 'center', 200);
        $column_nomePopularEspecie = $this->datagrid->addQuickColumn('NOME POPULAR', 'nomePopularEspecie', 'center', 200);
        $column_tamanhoMaximo = $this->datagrid->addQuickColumn('TAMANHO', 'tamanhoMaximo', 'center', 200);
        $column_idFamiliaEspecie = $this->datagrid->addQuickColumn('FAMILIA', 'familia_especie_descricao', 'center', 200);
        $column_tipoEspecie = $this->datagrid->addQuickColumn('TIPO', 'tipoEspecie', 'center', 200);*/
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_idEspecie = new TDataGridColumn('idEspecie', 'CÓDIGO', 'center');
        $column_nomePopularEspecie = new TDataGridColumn('nomePopularEspecie', 'NOME POPULAR', 'center');
        $column_tamanhoMaximo = new TDataGridColumn('tamanhoMaximo', 'TAMANHO', 'center');
        $column_idFamiliaEspecie = new TDataGridColumn('familia_especie_descricao', 'FAMILIA', 'center');
        $column_tipoEspecie = new TDataGridColumn('tipoEspecie', 'TIPO', 'center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_idEspecie);
        $this->datagrid->addColumn($column_nomePopularEspecie);
        $this->datagrid->addColumn($column_tamanhoMaximo);
        $this->datagrid->addColumn($column_idFamiliaEspecie);
        $this->datagrid->addColumn($column_tipoEspecie);


        // creates the datagrid column actions
        $order_idEspecie = new TAction(array($this, 'onReload'));
        $order_idEspecie->setParameter('order', 'idEspecie');
        $column_idEspecie->setAction($order_idEspecie);
        
        $order_nomePopularEspecie = new TAction(array($this, 'onReload'));
        $order_nomePopularEspecie->setParameter('order', 'nomePopularEspecie');
        $column_nomePopularEspecie->setAction($order_nomePopularEspecie);
        
        $order_idFamiliaEspecie = new TAction(array($this, 'onReload'));
        $order_idFamiliaEspecie->setParameter('order', 'idFamiliaEspecie');
        $column_idFamiliaEspecie->setAction($order_idFamiliaEspecie);
        

        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FrmEspecie', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('idEspecie');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('idEspecie');
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
        $container->add(TPanelGroup::pack('Consultar Espécies', $this->form));
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
            $object = new Especie($key); // instantiates the Active Record
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
        TSession::setValue('FrmListaEspecie_filter_idEspecie',   NULL);
        TSession::setValue('FrmListaEspecie_filter_nomePopularEspecie',   NULL);
        TSession::setValue('FrmListaEspecie_filter_tipoEspecie',   NULL);

        if (isset($data->idEspecie) AND ($data->idEspecie)) {
            $filter = new TFilter('idEspecie', '=', "$data->idEspecie"); // create the filter
            TSession::setValue('FrmListaEspecie_filter_idEspecie',   $filter); // stores the filter in the session
        }


        if (isset($data->nomePopularEspecie) AND ($data->nomePopularEspecie)) {
            $filter = new TFilter('nomePopularEspecie', 'like', "%{$data->nomePopularEspecie}%"); // create the filter
            TSession::setValue('FrmListaEspecie_filter_nomePopularEspecie',   $filter); // stores the filter in the session
        }
        if (isset($data->tipoEspecie) AND ($data->tipoEspecie)) {
            $filter = new TFilter('tipoEspecie', 'like', "%{$data->tipoEspecie}%"); // create the filter
            TSession::setValue('FrmListaEspecie_filter_tipoEspecie',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Especie_filter_data', $data);
        
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
            
            // creates a repository for Especie
            $repository = new TRepository('Especie');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'idEspecie';
                $param['direction'] = 'asc';
            }
            
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('FrmListaEspecie_filter_idEspecie')) {
                $criteria->add(TSession::getValue('FrmListaEspecie_filter_idEspecie')); // add the session filter
            }


            if (TSession::getValue('FrmListaEspecie_filter_nomePopularEspecie')) {
                $criteria->add(TSession::getValue('FrmListaEspecie_filter_nomePopularEspecie')); // add the session filter
            }
            
            if (TSession::getValue('FrmListaEspecie_filter_tipoEspecie')) {
                $criteria->add(TSession::getValue('FrmListaEspecie_filter_tipoEspecie')); // add the session filter
            }
            if (TSession::getValue('FrmListaEspecie_filter_familiaEspecie')) {
                $criteria->add(TSession::getValue('FrmListaEspecie_filter_familiaEspecie')); // add the session filter
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
            $object = new Especie($key, FALSE); // instantiates the Active Record
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
                    $object = new Especie;
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
            $object->check = new TCheckButton('check' . $object->idEspecie);
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
