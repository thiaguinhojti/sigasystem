<?php
/**
 * FrmSystemCompany Form
 * @author  <your name here>
 */
class FrmSystemCompany extends TPage
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
        

        $cnpjEmpresa->setMaxLength(18);
        $cnpjEmpresa->setMask('99.999.999/9999-99');
        $cnpjEmpresa->addValidation('CNPJ', new TCNPJValidator);
        $inscEstadualEmpresa->setMask('99.999.999-1');
        $inscEstadualEmpresa->setMaxLength(13);
        $telefoneEmpresa->setMask('(9999)9999-9999');
        $telefoneEmpresa->setMaxLength(17);
        $cepEmpresa->setMask('99900-000');
        $cepEmpresa->setMaxLength(9);
       
             
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        $this->form->addQuickAction(_t('Find'),new TAction(array('FrmListaSystemCompany','onReload')),'fa:table blue'); 
        
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
    public function onSave( $param )
    {
        $mask = new PMaskFormate();
        try
        {
            TTransaction::open('permission'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            
            $object = new SystemCompany;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->cnpjEmpresa = $mask->clean_string($object->cnpjEmpresa);
            $object->inscEstadualEmpresa = $mask->clean_string($object->inscEstadualEmpresa);
            $object->telefoneEmpresa = $mask->clean_string($object->telefoneEmpresa);
            $object->cepEmpresa = $mask->clean_string($object->cepEmpresa);
            
            $uniqueValidator = new TUniqueValidator;
            $uniqueValidator->validate('CNPJ', $object->cnpjEmpresa, array('database' => 'permission', 'model' => 'SystemCompany','field' => 'cnpjEmpresa', 'id' => $object->idEmpresa));
            $object->idEmpresa = str_pad($object->idEmpresa, 10,"0", STR_PAD_LEFT);
            $object->store(); // save the object
            
            // get the generated idEmpresa
            $data->idEmpresa = $object->idEmpresa;
            
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', 'Registro Gravado com sucesso!');
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
        $this->form->clear();
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        $mask = new PMaskFormate();
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('permission'); // open a transaction
                $object = new SystemCompany($key); // instantiates the Active Record
                $object->idEmpresa = str_pad($object->idEmpresa, 10,"0", STR_PAD_LEFT);
                $object->cnpjEmpresa = $mask->maskFormate($object->cnpjEmpresa, $tipo="cnpj");
                
                $object->inscEstadualEmpresa = $mask->maskFormate($object->inscEstadualEmpresa, $tipo="ie");
                $object->telefoneEmpresa = $mask->maskFormate($object->telefoneEmpresa, $tipo="fone");
                $object->cepEmpresa = $mask->maskFormate($object->cepEmpresa, $tipo="cep");
                $uniqueValidator = new TUniqueValidator;
                $uniqueValidator->validate('CNPJ', $object->cnpjEmpresa, array('database' => 'permission', 'model' => 'SystemCompany','field' => 'cnpjEmpresa', 'id' => $object->idEmpresa));     
                $this->form->setData($object); // fill the form
                   
                
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
      
}
