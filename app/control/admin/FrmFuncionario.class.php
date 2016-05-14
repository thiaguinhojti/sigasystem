<?php

class FrmFuncionario extends TPage{

    private $frm;
    
    public function __construct(){
        
        parent::__construct();
        
        try{
            TTransaction::open('dbwebfish');
                  
            $this->frm = new TQuickForm('frm_funcionario');
            $this->frm->setFormTitle('Cadastro de Funcionários');
            $this->frm->class = 'tform';
            $this->frm->style = 'width: 550px';
            $id = new TEntry('id');
            $nomeFuncionario = new TEntry('nomeFuncionario');
            $cpfFuncionario = new TEntry('cpfFuncionario');
            $matricula = new TEntry('matricula');
            $dataNascimentoFuncionario = new TDate('dataNascimentoFuncionario');
            $sexoFuncionario = new TRadioGroup('sexo');
            $telefoneFuncionario = new TEntry('telefoneFuncionario');
            $empresa = new TDBCombo('idEmpresa','dbwebfish','empresa','id','razaoSocial');
            if(isset($id)){                
                $cpfFuncionario->setMask('999.999.999-99');
                $cpfFuncionario->setMaxLength(14);
                $telefoneFuncionario->setMask('(9999)99999-9999');
                $telefoneFuncionario->setMaxLength(17);
                $dataNascimentoFuncionario->setMask('yyyy-mm-dd');
                //$dataNascimentoFuncionario = implode("-", array_reverse(explode("/",$dataNascimentoFuncionario)));
                
                
                $itemSexo = array('F' => ('Feminino'), 'M' => ('Masculino'));
                $sexoFuncionario->addItems($itemSexo);
                $sexoFuncionario->setLayout('horizontal');
                
                
                $this->frm->addQuickField('ID..:',$id,50);
                $this->frm->addQuickField('NOME COMPLETO.....:',$nomeFuncionario,200);
                $this->frm->addQuickField('CPF...............:',$cpfFuncionario,200);
                $this->frm->addQuickField('MATRICULA.........:',$matricula,50);
                $this->frm->addQuickField('DATA DE NASCIMENTO.....:',$dataNascimentoFuncionario,100);
                $this->frm->addQuickField('SEXO..:',$sexoFuncionario,100);
                $this->frm->addQuickField('TELEFONE..:',$telefoneFuncionario,200);
                $this->frm->addQuickField('EMPRESA..:',$empresa,200);
                
                $id->setEditable(FALSE);
                $cpfFuncionario->addValidation('cpfFuncionario', new TCPFValidator);
                
                $salvar = new TAction(array($this, 'onSave'));
                $listar = new TAction(array('FrmListaFuncionario','onReload'));
                $novo = new TAction(array($this,'onEdit'));
                
                
                
                $this->frm->addQuickAction('Salvar',$salvar,'ico_save.png');
                $this->frm->addQuickAction('Listar',$listar,'ico_datagrid.png');
                $this->frm->addQuickAction('Novo',$novo,'ico_new.png');
                parent::add($this->frm);
            }
            TTransaction::close();
        }
        catch(Exception $e){
            
            new TMessage('info',$e->getMessage(), null, 'Erro',$e->getCode());
        }
            
            
    }
     public function onSave(){
        
        $mask = new PMaskFormate();
        try{
            
            TTransaction::open('dbwebfish');
            $obj = $this->frm->getData('Funcionario');
            $this->frm->validate();
            
            $obj->cpfFuncionario = $mask->clean_string($obj->cpfFuncionario);
            $obj->telefoneFuncionario = $mask->clean_string($obj->telefoneFuncionario);
            
            
            $obj->store();
            
            
            $this->frm->setData($obj);
            
            new TMessage('info','Registro gravado com sucesso');
            $this->frm->clear();
            TTransaction::close();
            
            
            
        }
        catch(Exception $e){
            
            new TMessage('ERRO AO GRAVAR O REGISTRO',$e->getMessage());
            TTransaction::rollback();
            
        }
    }
    public function onEdit($param){
        try{
            if(isset($param['key'])){
                $key = $param['key'];
                
                TTransaction::open('dbwebfish');
                
                $funcionario = new Funcionario($key);
                
                $this->frm->setData($funcionario);
                
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
    
}

?>