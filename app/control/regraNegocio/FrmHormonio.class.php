<?php
/**
 * FrmHormonio Registration
 * @author  <your name here>
 */
class FrmHormonio extends TPage
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
        
        $this->setDatabase('dbwf');              // defines the database
        $this->setActiveRecord('Hormonio');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Hormonio');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Hormonio');
        


        // create the form fields
        $idHormonio = new TEntry('idHormonio');
        $nomeHormonio = new TEntry('nomeHormonio');
        $descHormonio = new TEntry('descHormonio');
        $valorHormonio = new TEntry('valorHormonio');
        

        // add the fields
        $this->form->addQuickField('CÓDIGO...:', $idHormonio,  100 );
        $this->form->addQuickField('NOME....:', $nomeHormonio,  200 );
        $this->form->addQuickField('DESCRIÇÃO.....:', $descHormonio,  200 );
        $this->form->addQuickField('VALOR R$...:', $valorHormonio,  200 );



        
        if (!empty($idHormonio))
        {
            $idHormonio->setEditable(FALSE);
        }
        $this->idHormonio = str_pad($this->idHormonio, 10,"0", STR_PAD_LEFT);
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        $this->form->addQuickAction(_t('Find'),new TAction(array('FrmListaHormonio','onReload')),'fa:table blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 50%; margin-left: 10%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Cadastro de Hormônios', $this->form));
        
        parent::add($container);
    }
}
