<?php

class FrmMatriz extends TPage{

    private $frm;
    private $total;
    
    public function __construct(){
        
        parent::__construct();
        //parent::setSize(600,450);
        try{
            TTransaction::open('dbwf');
            $this->frm = new TQuickForm('frm_matriz');
            $this->frm->setFormTitle('Cadastro de Matrizes');
            $this->frm = new BootstrapFormWrapper($this->frm);
            //$this->frm->class = 'tform';
            $this->frm->style = 'display: table;width:100%'; 
            
                    
            //criando os campos do formulario
            
            $id = new TEntry('idMatriz');
            $numeroChipMatriz = new TEntry('numeroChipMatriz');
            $numeroChipMatriz->addValidation('</br>numeroChip', new TRequiredValidator);
            $pesoMatriz = new TEntry('pesoMatriz');
            $pesoMatriz->setNumericMask(3,',','.');
            $compCabecaMatriz = new TEntry('compCabecaMatriz');
            $compCabecaMatriz->setNumericMask(2,',','.');
            $compParcialMatriz = new TEntry('compParcialMatriz');
            $compParcialMatriz->setNumericMask(2,',','.');
            $compTotalMatriz = new TEntry('compTotalMatriz');
            $compTotalMatriz->setNumericMask(2,',','.');
            $sexoMatriz = new TCombo('sexoMatriz');
            $larguraMatriz = new TEntry('larguraMatriz');
            $larguraMatriz->setNumericMask(2,',','.');
            $especie = new TDBCombo('idEspecie','dbwf','especie','idEspecie','nomePopularEspecie');
            $dataCadastro = new THidden('dataCadastro');
            $statusMatriz = new TCombo('status');
                                                    
            $itens = array();
            $itens['F'] = 'FEMININO';
            $itens['M'] = 'MASCULINO';
            
            $status = array();
            $status['A'] = 'ATIVO';
            $status['I'] = 'INATIVO';
            
            $sexoMatriz->addItems($itens);
            $statusMatriz->addItems($status);
                
            $this->frm->addQuickField('CÓDIGO...: ',$id,50);
            $this->frm->addQuickField('CHIP.....: ',$numeroChipMatriz,50);
            $this->frm->addQuickField('PESO.....:(kg)',$pesoMatriz,100);
            $this->frm->addQuickField('CABEÇA...:(cm) ',$compCabecaMatriz,100);
            $this->frm->addQuickField('CORPO....:(cm) ',$compParcialMatriz,100);
            $this->frm->addQuickField('COMPRIMENTO..:(cm) ',$compTotalMatriz,100);
            $this->frm->addQuickField('SEXO.....: ',$sexoMatriz,150);
            $this->frm->addQuickField('LARGURA..:(cm) ',$larguraMatriz,100);
            $this->frm->addQuickField('ESPECIE..: ',$especie,200);
            //$this->frm->addQuickField('DATA.....: ',$data,100);
            $this->frm->addQuickField('STATUS ..:',$statusMatriz,150);
          
            
            $id->setEditable(FALSE);
            
           
            $compTotalMatriz->setEditable(FALSE);
                     
            $exit_action = new TAction(array($this,'onExitAction'));
            $compParcialMatriz->setExitAction($exit_action);
            
            $salvar = new TAction(array($this, 'onSave'));
            //$listar = new TAction(array('FrmListaMatriz','onReload'));
            $novo = new TAction(array($this,'onClear'));
            
                       
            $this->frm->addQuickAction('Salvar',$salvar,'fa:floppy-o');
            //$this->frm->addQuickAction('Listar',$listar,'ico_datagrid.png');
            $this->frm->addQuickAction('Novo',$novo, 'bs:plus-sign green');
            $container = new TVBox;
            $container->style = 'width: 70%; position: relative; left: 10%;';
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
            $container->add(TPanelGroup::pack('Cadastro de Matrizes', $this->frm));
            
            parent::add($container);
            
            
        }
        catch(Exception $e){
            
            new TMessage('info',$e->getMessage(), null, 'Erro',$e->getCode());
        }
        
        
    }
    public function onSave(){
    
        try{
            
            TTransaction::open('dbwf');
            $this->frm->validate();
            $obj = new Matriz;
            $data = $this->frm->getData();
            $obj->fromArray((array) $data);
            $obj->dataCadastro = TDate::date2us($obj->dataCadastro);
            $obj->pesoMatriz = TDBEntry::setNumericMask(3,'.','.');;
            $obj->compCabecaMatriz = TDBEntry::setNumericMask(2,'.','.');
            $obj->compParcialMatriz = TDBEntry::setNumericMask(2,'.','.');
            $obj->compTotalMatriz = TDBEntry::setNumericMask(2,'.','.');
            $obj->larguraMatriz = TDBEntry::setNumericMask(2,'.','.');
            $obj->store();
                      
            $this->frm->setData($obj);
            
            new TMessage('info','Registro gravado com sucesso');
            $this->frm->clear();
            TTransaction::close();
            
            
        }
        catch(Exception $e){
            
            new TMessage('error','ERRO AO GRAVAR O REGISTRO'.$e->getMessage());
            TTransaction::rollback();
            
        }
    
    }
    public function onEdit($param){
        try{
            if(isset($param['key'])){
                $key = $param['key'];
                
                TTransaction::open('dbwf');
                
                $matriz = new Matriz($key);
                                       
                $matriz->dataCadastro = TDate::date2br($matriz->dataCadastro);
                $matriz->pesoMatriz = TDBEntry::setNumericMask(3,',','.');
                $matriz->compCabecaMatriz = TDBEntry::setNumericMask(2,',','.');
                $matriz->compParcialMatriz = TDBEntry::setNumericMask(2,',','.');
                $matriz->compTotalMatriz = TDBEntry::setNumericMask(2,',','.');
                $matriz->larguraMatriz = TDBEntry::setNumericMask(2,',','.');
                
                $this->frm->setData($matriz);
                
                TTransaction::close();
            }
            else{
                $this->frm->clear();
            }
            
        }
        catch(Exception $e){
            new TMessage('error','ERRO AO GRAVAR REGISTRO!' . $e->getMessage());
            
        }
       
        
    }
    
    public function onClear( $param ){
            $this->frm->clear();
    }
    public static function onExitAction($param){
    
        $obj = new StdClass;
        $obj->compTotalMatriz = $param['compCabecaMatriz']+$param['compParcialMatriz'];
        
        
        TForm::sendData('frm_matriz',$obj);
    
    }

}

?>