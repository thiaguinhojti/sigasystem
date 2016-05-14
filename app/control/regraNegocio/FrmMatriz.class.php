<?php
/**
 * FrmMatriz Form
 * @author  <your name here>
 */
class FrmMatriz extends TPage
{
    protected $form; // form
    private   $frame;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Matriz');
        $this->form->class = 'tform'; // change CSS class
        //$this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:70%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Matriz');
        
        $this->form->setFieldsByRow(2);

        // create the form fields
        $idMatriz = new TEntry('idMatriz');
        $numeroChipMatriz = new TEntry('numeroChipMatriz');
        $pesoMatriz = new TEntry('pesoMatriz');
        $compCabecaMatriz = new TEntry('compCabecaMatriz');
        $compParcialMatriz = new TEntry('compParcialMatriz');
        $compTotalMatriz = new TEntry('compTotalMatriz');
        $sexoMatriz = new TCombo('sexoMatriz');
        $larguraMatriz = new TEntry('larguraMatriz');
        $dataCadastro = new THidden('dataCadastro');
        $idEspecie = new TDBCombo('idEspecie','dbwf','Especie','idEspecie','nomePopularEspecie');
        $status = new TCombo('status');
        $photo_path = new TFile('photo_path');
        
        
        
               
        $pesoMatriz->setNumericMask(3,',','.');
        $compCabecaMatriz->setNumericMask(2,',','.');
        $compParcialMatriz->setNumericMask(2,',','.');
        $compTotalMatriz->setNumericMask(2,',','.');
        $larguraMatriz->setNumericMask(2,',','.');
        
        $itens = array();
        $itens['F'] = 'FEMININO';
        $itens['M'] = 'MASCULINO';
        
        $statusItens = array();
        $statusItens['A'] = 'ATIVO';
        $statusItens['I'] = 'INATIVO';
        
        $sexoMatriz->addItems($itens);
        $status->addItems($statusItens);


        // add the fields
        
        $this->form->addQuickField('CÓDIGO..: ', $idMatriz,  100 );
        $this->form->addQuickField('CHIP..: ', $numeroChipMatriz,  100 );
        $this->form->addQuickField('PESO(kg)..: ', $pesoMatriz,  100 );
        $this->form->addQuickField('CABEÇA..: ', $compCabecaMatriz,  100 );
        $this->form->addQuickField('CORPO..: ', $compParcialMatriz,  100 );
        $this->form->addQuickField('TOTAL..: ', $compTotalMatriz,  100 );
        $this->form->addQuickField('SEXO..: ', $sexoMatriz,  100 );
        $this->form->addQuickField('LARGURA..: ', $larguraMatriz,  100 );
        $this->form->addQuickField('ESPECIE..:', $idEspecie, 100);
        $this->form->addQuickField('STATUS..: ', $status,  100 );
        $this->form->addQuickField('IMAGEM..:', $photo_path,200);
        
                   
        $exit_action = new TAction(array($this,'onExitAction'));
        $compParcialMatriz->setExitAction($exit_action);
        $compTotalMatriz->setEditable(FALSE);
        $numeroChipMatriz->style='text-align:right;';
        
        
        

        if (!empty($idMatriz))
        {
            $idMatriz->setEditable(FALSE);
        }
        
        $this->frame = new TElement('div');
        $this->frame->id = 'photo_frame';
        $this->frame->style = 'width:400px;height:auto;min-height:200px;border:1px solid gray;padding:4px;';
        $row = $this->form->addRow();
        $row->addCell('');
        $row->addCell($this->frame);
        
        $photo_path->setSize(200, 40);
       
        $photo_path->setCompleteAction(new TAction(array($this, 'onComplete')));
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        $this->form->addQuickAction(_t('Find'),new TAction(array('FrmListaMatriz','onReload')),'fa:table blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 60%; position:absolut; margin-left:10%;';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Cadastro de Matrizes', $this->form));
        
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
            $object = new Matriz;  // create an empty object          
            $source_file   = 'tmp/'.$object->photo_path;
            $target_file   = 'images/' . $object->photo_path;
            $finfo         = new finfo(FILEINFO_MIME_TYPE);
            
             if (file_exists($source_file) AND ($finfo->file($source_file) == 'image/png' OR $finfo->file($source_file) == 'image/jpeg'))
            {
                // move to the target directory
                rename($source_file, $target_file);
                $object = new Matriz;  // create an empty object
                $data = $this->form->getData(); // get form data as array
                $object->fromArray( (array) $data); // load the object with data
                $object->pesoMatriz = str_replace(',','.',$object->pesoMatriz);
                $object->compCabecaMatriz = str_replace(',','.', $object->compCabecaMatriz);
                $object->compParcialMatriz = str_replace(',','.', $object->compParcialMatriz);
                $object->compTotalMatriz = str_replace(',','.', $object->compTotalMatriz);
                $object->larguraMatriz = str_replace(',','.', $object->larguraMatriz);
                $object->idMatriz = str_pad($object->idMatriz, 10,"0", STR_PAD_LEFT);    
                $object->photo_path = 'images/'.$object->photo_path;                     
                $object->store(); // save the object
                
                
                
                // get the generated idMatriz
                $data->idMatriz = $object->idMatriz;
                
                $this->form->setData($data); // fill form data
                TTransaction::close(); // close the transaction
             }
                new TMessage('info', 'Registro Gravado com Sucesso!');
                 $image = new TImage($object->photo_path);
                 $image->style = 'width: 100%';
                 $this->frame->add( $image );
             }   
        
           
        
        catch (Exception $e) // in case of exception
        {
            new TMessage('Erro ao gravar o registro', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public static function onComplete($param)
    {
        new TMessage('info', 'Upload finalizado: '.$param['photo_path']);
        
        // refresh photo_frame
        TScript::create("$('#photo_frame').html('')");
        TScript::create("$('#photo_frame').append(\"<img style='width:100%' src='tmp/{$param['photo_path']}'>\");");
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
                    $object = new Matriz($key); // instantiates the Active Record
                    $object->idMatriz = str_pad($object->idMatriz, 10,"0", STR_PAD_LEFT); 
                    $object->pesoMatriz = str_replace('.',',', $object->pesoMatriz);
                    $object->compCabecaMatriz = str_replace('.',',', $object->compCabecaMatriz);
                    $object->compParcialMatriz = str_replace('.',',', $object->compParcialMatriz);
                    $object->compTotalMatriz = str_replace('.',',', $object->compTotalMatriz);
                    $object->larguraMatriz = str_replace('.',',', $object->larguraMatriz);
                    if ($object)
                    {
                        $image = new TImage($object->photo_path);
                        $image->style = 'width: 100%';
                        $this->frame->add( $image );
                    }
                    //$uniqueValidator->validate('CHIP', $object->numeroChipMatriz, array('database' => 'dbwf', 'model' => 'Matriz','field' => 'numeroChipMatriz', 'id' => $object->idMatriz));
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
            new TMessage('Erro ao Gravar o Registro. ', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public static function onExitAction($param){
    
        $compCabeca = (double) str_replace(',','.', $param['compCabecaMatriz']);
        $compParcial = (double) str_replace(',','.', $param['compParcialMatriz']);
    
        $obj = new StdClass;
        $obj->compTotalMatriz = number_format(($compCabeca+$compParcial),2,',','.');
     
        
        
        TForm::sendData('form_Matriz',$obj);
    
    }
    
    
    
}
