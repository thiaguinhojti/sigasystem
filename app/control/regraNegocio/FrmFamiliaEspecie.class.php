<?php
/**
 * FrmFamiliaEspecie Form
 * @author  <your name here>
 */
class FrmFamiliaEspecie extends TPage
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
        $this->form = new TQuickForm('form_FamiliaEspecie');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('FamiliaEspecie');
        


        // create the form fields
        $idFamiliaEspecie = new TEntry('idFamiliaEspecie');
        $descricaoFamilia = new TEntry('descricaoFamilia');


        // add the fields
        $this->form->addQuickField('CÓDIGO...:', $idFamiliaEspecie,  100 );
        $this->form->addQuickField('DESCRIÇÃO...:', $descricaoFamilia,  200 );

        $idFamiliaEspecie->setEditable(FALSE);


        if (!empty($idFamiliaEspecie))
        {
            $idFamiliaEspecie->setEditable(FALSE);
        }
        $this->idFamiliaEspecie = str_pad($this->idFamiliaEspecie, 10,"0", STR_PAD_LEFT);
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        $this->form->addQuickAction(_t('Find'),new TAction(array('FrmListaFamiliaEspecie','onReload')),'fa:table blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 50%; margin-left: 10%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Família de Espécies', $this->form));
        
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
            TTransaction::open('dbwf'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            
            $object = new FamiliaEspecie;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->idFamiliaEspecie = str_pad($object->idFamiliaEspecie, 10,"0", STR_PAD_LEFT);
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated idFamiliaEspecie
            $data->idFamiliaEspecie = $object->idFamiliaEspecie;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', 'Registro gravado com sucesso!');
            $this->form->clear();
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
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('dbwf'); // open a transaction
                $object = new FamiliaEspecie($key); // instantiates the Active Record
                $object->idFamiliaEspecie = str_pad($object->idFamiliaEspecie, 10,"0", STR_PAD_LEFT);
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
