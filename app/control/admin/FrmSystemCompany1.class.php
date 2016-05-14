<?php
/**
 * FrmSystemCompany Registration
 * @author  <your name here>
 */
class FrmSystemCompany extends TPage
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
        
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Cadastro de Empresa');
        


        // create the form fields
        $idEmpresa = new TEntry('idEmpresa');
        $cnpjEmpresa = new TEntry('cnpjEmpresa');
        $inscEstadualEmpresa = new TEntry('inscEstadualEmpresa');
        $razaoSocial = new TEntry('razaoSocial');
        $nomeFantasia = new TEntry('nomeFantasia');
        $enderecoEmpresa = new TEntry('enderecoEmpresa');
        $bairroEmpresa = new TEntry('bairroEmpresa');
        $estado = new TDBCombo('idEstado','permission','SystemState','idState','uf');
        $cidade = new TCombo('idCidade');
        $cepEmpresa = new TEntry('cepEmpresa');
        $telefoneEmpresa = new TEntry('telefoneEmpresa');
        $emailEmpresa = new TEntry('emailEmpresa');
        $siteEmpresa = new TEntry('siteEmpresa');
        $responsavelEmpresa = new TEntry('responsavelEmpresa');


        // add the fields
        $this->form->addQuickField('CÓDIGO..:', $idEmpresa,  100 );
        $this->form->addQuickFields('CNPJ..:', array($cnpjEmpresa, new TLabel('INSC. ESTADUAL..:'), $inscEstadualEmpresa));
        $this->form->addQuickFields('RAZÃO SOCIAL..:', array($razaoSocial, new TLabel('NOME FANTASIA..:'), $nomeFantasia ));
        $this->form->addQuickFields('LOGRADOURO..:', array($enderecoEmpresa, new TLabel('BAIRRO..:'),  $bairroEmpresa));
        
        $this->form->addQuickField('ESTADO..:', $estado,  100 );
        $this->form->addQuickField('CIDADE..:', $cidade,  200 );
        $this->form->addQuickField('CEP..:', $cepEmpresa,  200 );
        $this->form->addQuickField('TELEFONE..:', $telefoneEmpresa,  200 );
        $this->form->addQuickField('EMAIL..:', $emailEmpresa,  200 );
        $this->form->addQuickField('SITE..:', $siteEmpresa,  200 );
        $this->form->addQuickField('RESPONSÁVEL..:', $responsavelEmpresa,  200 );

        $estado->setChangeAction(new TAction(array($this,'onChangeAction')));

        
        if (!empty($idEmpresa))
        {
            $idEmpresa->setEditable(FALSE);
        }
        if(isset($idEmpresa))
        {
            $cnpjEmpresa->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        $this->form->addQuickAction(_t('Back to the listing'),new TAction(array('FrmListaSystemCompany','onReload')),'fa:table blue'); 
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 70%; margin-left:12%;';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
    public static function onChangeAction($param){
    
        TTransaction::open('permission');
        $repo = new TRepository('SystemCity');
        $criterio = new TCriteria;
        
        if($param['idEstado']){
        
            $criterio->add(new TFilter('estado','=',$param['idEstado']));
        }
        $cidades = $repo->load($criterio);
        TTransaction::close();
        
        $opcoes = array();
        foreach($cidades as $cidade){
                
                $opcoes[$cidade->idCity] = $cidade->nome;
                
            }
            
        TCombo::reload('form_SystemCompany','idCidade',$opcoes);
        
    }
}
