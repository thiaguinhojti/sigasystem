<?php

    class FrmTipoEspecie extends TWindow{
    
     public function __construct(){
        
            parent::__construct();
            parent::setSize(650,200);
            
            $this->frm = new TQuickForm('Cad_Tipo_Especie');
            
            $this->frm->setFormTitle('Tipos de Especies');
            $this->frm->class = 'tform';
            $this->frm->style = 'width: 550px';
            
            //buscando os campos da tabela
            $id = new TEntry('id');
            $descricao = new TEntry('descricaoTipo');
            
            $id->setEditable(FALSE);            
            //criando os campos no formulario
            
            $this->frm->addQuickField('ID..:',$id,100);
            $this->frm->addQuickField('TIPO DA ESPECIE..:',$descricao,300);
            //aplicando metodo do formulario
            $salvar = new TAction(array($this, 'onSave'));
            $listar = new TAction(array('FrmListaTipoEspecie','onReload'));
            $novo = new TAction(array($this,'onEdit'));
            //Criando o botao salvar
            
            $this->frm->addQuickAction('Salvar',$salvar,'ico_save.png');
            $this->frm->addQuickAction('Listar',$listar,'ico_datagrid.png');
            $this->frm->addQuickAction('Novo',$novo,'ico_new.png');
            
            parent::add($this->frm);
            
        }
        public function onSave(){
            
            try{
                TTransaction::open('dbwebfish');
                
                $obj = $this->frm->getData('tipoespecie');
                $obj->store();
                
                $this->frm->setData($obj);
                
                new TMessage('info',' Registro Gravado com Sucesso!');
                $this->frm->clear();
                TTransaction::close();
            
            }
            catch(Exception $e){
                new TMessage('Erro',$e->getMessage());
                TTransaction::rollback(); 
                
            
            }
        
        }
        public function onEdit($param){
            try{
                if(isset($param['key'])){
                    $key = $param['key'];
                    
                    TTransaction::open('dbwebfish');
        
                    $tipoEspecie = new TipoEspecie($key);
            
                    $this->frm->setData($tipoEspecie);
            
                    TTransaction::close();
                }
                else{
                    $this->frm->clear();
                }
                

           }
           catch(Exception $e){
               new TMessage('error', $e->getMessage());
     
           }   
        
        }
    
    }

?>