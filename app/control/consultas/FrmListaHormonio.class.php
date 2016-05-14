<?php
/**
 * FrmListaHormonio Listing
 * @author  <your name here>
 */
class FrmListaHormonio extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('dbwf');            // defines the database
        parent::setActiveRecord('Hormonio');   // defines the active record
        parent::setDefaultOrder('idHormonio', 'asc');         // defines the default order
        // parent::setCriteria($criteria) // define a standard filter

        parent::addFilterField('idHormonio', '=', 'idHormonio'); // filterField, operator, formField
        parent::addFilterField('nomeHormonio', 'like', 'nomeHormonio'); // filterField, operator, formField
        
        // creates the form
        $this->form = new TQuickForm('form_search_Hormonio');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Hormonio');
        

        // create the form fields
        $idHormonio = new TEntry('idHormonio');
        $nomeHormonio = new TEntry('nomeHormonio');


        // add the fields
        $this->form->addQuickField('CÓDIGO...:', $idHormonio,  200 );
        $this->form->addQuickField('NOME....:', $nomeHormonio,  200 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Hormonio_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('FrmHormonio', 'onEdit')), 'bs:plus-sign green');
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_idHormonio = new TDataGridColumn('idHormonio', 'CÓDIGO', 'center');
        $column_nomeHormonio = new TDataGridColumn('nomeHormonio', 'NOME', 'center');
        $column_descHormonio = new TDataGridColumn('descHormonio', 'DESCRIÇÃO', 'center');
        $column_valorHormonio = new TDataGridColumn('valorHormonio', 'VALOR', 'center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_idHormonio);
        $this->datagrid->addColumn($column_nomeHormonio);
        $this->datagrid->addColumn($column_descHormonio);
        $this->datagrid->addColumn($column_valorHormonio);


        // creates the datagrid column actions
        $order_idHormonio = new TAction(array($this, 'onReload'));
        $order_idHormonio->setParameter('order', 'idHormonio');
        $column_idHormonio->setAction($order_idHormonio);
        
        $order_nomeHormonio = new TAction(array($this, 'onReload'));
        $order_nomeHormonio->setParameter('order', 'nomeHormonio');
        $column_nomeHormonio->setAction($order_nomeHormonio);
        

        // define the transformer method over image
        $column_valorHormonio->setTransformer( function($value, $object, $row) {
            return 'R$ ' . number_format($value, 2, ',', '.');
        });


        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FrmHormonio', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('idHormonio');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('idHormonio');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
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
        $container->add(TPanelGroup::pack('Consultar Hormônios', $this->form));
        $container->add($gridpack);
        $container->add($this->pageNavigation);
        
        parent::add($container);
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
            $object->check = new TCheckButton('check' . $object->idHormonio);
            $object->check->setIndexValue('on');
            $gridfields[] = $object->check; // important
        }
        
        $this->formgrid->setFields($gridfields);
    }

}
