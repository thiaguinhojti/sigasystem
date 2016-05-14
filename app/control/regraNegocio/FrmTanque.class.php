<?php
/**
 * FrmTanque Form
 * @author  <your name here>
 */
class FrmTanque extends TPage
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
        $this->form = new TQuickForm('form_Tanque');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:65%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Tanque');
        


        // create the form fields
        $idTanque = new TEntry('idTanque');
        $numeroTanque = new TEntry('numeroTanque');
        $areaMetroQuadradoTanque = new TEntry('areaMetroQuadradoTanque');
        $profMediaTanque = new TEntry('profMediaTanque');
        $VolumeAcMediaTanque = new TEntry('VolumeAcMediaTanque');
        $tipoTanque = new TCombo('tipoTanque');
        
        $tipo_tanque = array();
        $tipo_tanque['E'] = 'Escavado';
        $tipo_tanque['A'] = 'Alvenaria';
        $tipo_tanque['B'] = 'Barracão';
        $tipoTanque->addItems($tipo_tanque);


        // add the fields
        $this->form->addQuickField('CÓDIGO...:', $idTanque,  100 );
        $this->form->addQuickField('NÚMERO....:', $numeroTanque,  100 );
        $this->form->addQuickField('ÁREA..........:', $areaMetroQuadradoTanque,  200 );
        $this->form->addQuickField('PROFUNDIDADE...:', $profMediaTanque,  200 );
        $this->form->addQuickField('VOLUME...:', $VolumeAcMediaTanque,  200 );
        $this->form->addQuickField('TIPO...:', $tipoTanque,  200 );

        


        if (!empty($idTanque))
        {
            $idTanque->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'margin-left:13%; width: 800px;';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Cadastro de Tanques', $this->form));
        
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
            
            $object = new Tanque;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated idTanque
            $data->idTanque = $object->idTanque;
            
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
                $object = new Tanque($key); // instantiates the Active Record
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
