<?php
/**
 * FrmListaSystemCompany Listing
 * @author  <your name here>
 */
class FrmListaSystemCompany extends TStandardList
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
        
        parent::setDatabase('permission');            // defines the database
        parent::setActiveRecord('SystemCompany');   // defines the active record
        parent::setDefaultOrder('idEmpresa', 'asc');         // defines the default order
        // parent::setCriteria($criteria) // define a standard filter

        parent::addFilterField('idEmpresa', '=', 'idEmpresa'); // filterField, operator, formField
        parent::addFilterField('cnpjEmpresa', 'like', 'cnpjEmpresa'); // filterField, operator, formField
        parent::addFilterField('razaoSocial', 'like', 'razaoSocial'); // filterField, operator, formField
        
        // creates the form
        $this->form = new TQuickForm('form_search_SystemCompany');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('SystemCompany');
        

        // create the form fields
        $idEmpresa = new TEntry('idEmpresa');
        $cnpjEmpresa = new TEntry('cnpjEmpresa');
        $razaoSocial = new TEntry('razaoSocial');


        // add the fields
        $this->form->addQuickField('CÓDIGO', $idEmpresa,  200 );
        $this->form->addQuickField('CNPJ', $cnpjEmpresa,  200 );
        $this->form->addQuickField('RAZÃO SOCIAL', $razaoSocial,  200 );

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemCompany_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('FrmSystemCompany', 'onEdit')), 'bs:plus-sign green');
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_check = new TDataGridColumn('check', '', 'center');
        $column_idEmpresa = new TDataGridColumn('idEmpresa', 'CÓDIGO', 'center');
        $column_cnpjEmpresa = new TDataGridColumn('cnpjEmpresa', 'CNPJ', 'center');
        $column_inscEstadualEmpresa = new TDataGridColumn('inscEstadualEmpresa', 'INSCRIÇÃO ESTADUAL', 'center');
        $column_razaoSocial = new TDataGridColumn('razaoSocial', 'RAZÃO SOCIAL', 'center');
        $column_nomeFantasia = new TDataGridColumn('nomeFantasia', 'NOME FANTASIA', 'center');
        $column_emailEmpresa = new TDataGridColumn('emailEmpresa', 'EMAIL', 'center');
        $column_siteEmpresa = new TDataGridColumn('siteEmpresa', 'SITE', 'center');
        $column_responsavelEmpresa = new TDataGridColumn('responsavelEmpresa', 'RESPONSÁVEL', 'center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_check);
        $this->datagrid->addColumn($column_idEmpresa);
        $this->datagrid->addColumn($column_cnpjEmpresa);
        $this->datagrid->addColumn($column_inscEstadualEmpresa);
        $this->datagrid->addColumn($column_razaoSocial);
        $this->datagrid->addColumn($column_nomeFantasia);
        $this->datagrid->addColumn($column_emailEmpresa);
        $this->datagrid->addColumn($column_siteEmpresa);
        $this->datagrid->addColumn($column_responsavelEmpresa);


        // creates the datagrid column actions
        $order_idEmpresa = new TAction(array($this, 'onReload'));
        $order_idEmpresa->setParameter('order', 'idEmpresa');
        $column_idEmpresa->setAction($order_idEmpresa);
        
        $order_cnpjEmpresa = new TAction(array($this, 'onReload'));
        $order_cnpjEmpresa->setParameter('order', 'cnpjEmpresa');
        $column_cnpjEmpresa->setAction($order_cnpjEmpresa);
        

        
        // create EDIT action
        $action_edit = new TDataGridAction(array('FrmSystemCompany', 'onEdit'));
        $action_edit->setUseButton(TRUE);
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('idEmpresa');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setUseButton(TRUE);
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('idEmpresa');
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
        $container->add(TPanelGroup::pack('Lista de Empresas', $this->form));
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
            $object->check = new TCheckButton('check' . $object->idEmpresa);
            $object->check->setIndexValue('on');
            $gridfields[] = $object->check; // important
        }
        
        $this->formgrid->setFields($gridfields);
    }

}
