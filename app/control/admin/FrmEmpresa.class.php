<?php
/**
 * SystemCompanyForm Registration
 * @author  <your name here>
 */
class FrmEmpresa extends TPage
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('permission');              // defines the database
        $this->setActiveRecord('SystemCompany');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_SystemCompany');
        $this->form->class = 'tform'; // change CSS class
        //$this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('SystemCompany');
        


        // create the form fields
        $idEmpresa = new TEntry('idEmpresa');
        $razaoSocial = new TEntry('razaoSocial');
        $nomeFantasia = new TEntry('nomeFantasia');
        $cnpjEmpresa = new TEntry('cnpjEmpresa');
        $inscEstadualEmpresa = new TEntry('inscEstadualEmpresa');
        $telefoneEmpresa = new TEntry('telefoneEmpresa');
        $emailEmpresa = new TEntry('emailEmpresa');
        $enderecoEmpresa = new TEntry('enderecoEmpresa');
        $bairroEmpresa = new TEntry('bairroEmpresa');
        $idEstado = new TEntry('idEstado');
        $idCidade = new TEntry('idCidade');
        $cepEmpresa = new TEntry('cepEmpresa');
        $responsavelEmpresa = new TEntry('responsavelEmpresa');
        $siteEmpresa = new TEntry('siteEmpresa');


        // add the fields
        $this->form->addQuickField('CÓDIGO..:', $idEmpresa,  100 );
        $this->form->addQuickField('RAZÃO SOCIAL..:', $razaoSocial,  200 );
        $this->form->addQuickField('NOME FANTASIA..:', $nomeFantasia,  200 );
        $this->form->addQuickFields('CNPJ..:', array($cnpjEmpresa, new TLabel('INSC. ESTADUAL..:'), $inscEstadualEmpresa));
        //$this->form->addQuickField('INSC. ESTADUAL..:', $inscEstadualEmpresa,  200 );
        $this->form->addQuickField('TELEFONE..:', $telefoneEmpresa,  200 );
        $this->form->addQuickField('EMAIL..:', $emailEmpresa,  200 );
        $this->form->addQuickField('LOGRADOURO..:', $enderecoEmpresa,  200 );
        $this->form->addQuickField('BAIRRO..:', $bairroEmpresa,  200 );
        $this->form->addQuickField('ESTADO..:', $idEstado,  100 );
        $this->form->addQuickField('CIDADE..:', $idCidade,  100 );
        $this->form->addQuickField('CEP..:', $cepEmpresa,  200 );
        $this->form->addQuickField('RESPONSÁVEL..:', $responsavelEmpresa,  200 );
        $this->form->addQuickField('SITE..:', $siteEmpresa,  200 );



        
        if (!empty($idEmpresa))
        {
            $idEmpresa->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Title', $this->form));
        
        parent::add($container);
    }
}
