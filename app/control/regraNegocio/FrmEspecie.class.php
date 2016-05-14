<?php
/**
 * FrmEspecie Form
 * @author  <your name here>
 */
class FrmEspecie extends TPage
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
        $this->form = new TQuickForm('form_Especie');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Especie');
        


        // create the form fields
        $idEspecie = new TEntry('idEspecie');
        $nomePopularEspecie = new TEntry('nomePopularEspecie');
        $nomeCientificoEspecie = new TEntry('nomeCientificoEspecie');
        $tamanhoMaximo = new TEntry('tamanhoMaximo');
        $horaGrauInicioReproducao = new TEntry('horaGrauInicioReproducao');
        $qtdeSoroKgPv1 = new TEntry('qtdeSoroKgPv1');
        $qtdeSoroKgPv2 = new TEntry('qtdeSoroKgPv2');
        $QtdeMaximaAplicacoes = new TEntry('QtdeMaximaAplicacoes');
        $idFamiliaEspecie =  new TDBCombo('idFamiliaEspecie','dbwf','familiaespecie','idFamiliaEspecie','descricaoFamilia');
        $tipoEspecie = new TCombo('tipoEspecie');
        $tamanhoMaximo->setTip('Tamanho máximo alcançado pela espécie');
        $tamanhoMaximo->setNumericMask(2,',','.');
        
        $qtdeSoroKgPv1->setNumericMask(2,',','.');
        $qtdeSoroKgPv2->setNumericMask(2,',','.');
        $tipo_especie = array();
        $tipo_especie['c'] = 'Carnívoro';
        $tipo_especie['h'] = 'Herbívoro';
        $tipo_especie['o'] = 'Onívoro';
        $tipoEspecie->addItems($tipo_especie);

        // add the fields
        $this->form->addQuickField('CODIGO...:', $idEspecie,  100 );
        $this->form->addQuickField('NOME POPULAR...:', $nomePopularEspecie,  200 , new TRequiredValidator);
        $this->form->addQuickField('NOME CIENTIFICO...:', $nomeCientificoEspecie,  200 );
        $this->form->addQuickField('TAMANHO MÁXIMO(cm)...:', $tamanhoMaximo,  200 );
        $this->form->addQuickField('HORA GRAU DE REP...:', $horaGrauInicioReproducao,  100 );
        $this->form->addQuickField('QUANTIDADE SORO 1ª..:', $qtdeSoroKgPv1,  100 );
        $this->form->addQuickField('QUANTIDADE SORO 2ª..:', $qtdeSoroKgPv2,  100 );
        $this->form->addQuickField('QUANTIDADE DE APLICAÇÕES..:', $QtdeMaximaAplicacoes,  100 );
        $this->form->addQuickField('FAMILIA .....:', $idFamiliaEspecie,  200 , new TRequiredValidator);
        $this->form->addQuickField('TIPO.....:', $tipoEspecie,  200 );




        if (!empty($idEspecie))
        {
            $idEspecie->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        $this->form->addQuickAction(_t('Find'),new TAction(array('FrmListaEspecie','onReload')),'fa:table blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 70%;position: relative; left:10%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Cadastro de Espécie', $this->form));
        
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
            
            $object = new Especie;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->idEspecie = str_pad($object->idEspecie, 10,"0", STR_PAD_LEFT);
            $object->fromArray( (array) $data); // load the object with data
            $object->tamanhoMaximo = str_replace(',','.',$object->tamanhoMaximo);
            $object->qtdeSoroKgPv1 = str_replace(',','.',$object->qtdeSoroKgPv1);
            $object->qtdeSoroKgPv2 = str_replace(',','.',$object->qtdeSoroKgPv2);
            $object->store(); // save the object
            
            // get the generated idEspecie
            $data->idEspecie = $object->idEspecie;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info','Registro gravado com sucesso');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('ERRO AO GRAVAR O REGISTRO',$e->getMessage()); // shows the exception error message
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
                $object = new Especie($key); // instantiates the Active Record
                $object->idEspecie = str_pad($object->idEspecie, 10,"0", STR_PAD_LEFT);
                $object->tamanhoMaximo = str_replace('.',',', $object->tamanhoMaximo);
                $object->qtdeSoroKgPv1 = str_replace('.',',', $object->qtdeSoroKgPv1);
                $object->qtdeSoroKgPv2 = str_replace('.',',', $object->qtdeSoroKgPv2);
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
             new TMessage('error','ERRO AO GRAVAR REGISTRO!' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
