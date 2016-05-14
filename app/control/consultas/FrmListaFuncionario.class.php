<?php

class FrmListaFuncionario extends TPage{

    private $frm;
    private $datagrid;
    private $pagenavigation;
    private $loaded;
    
    public function __construct(){
    
        parent::__construct();
        new TSession;
        
        //criando o formulario
        
        $this->frm = new TForm('Frm_lista_funcionario');
        $this->frm->class='tform';
        
        $tabFuncionario = new TTable;
        $tabFuncionario->width = '50%';
        $tabFuncionario->align='center';
        $tabFuncionario->addRowSet(new TLabel('Buscar Funcionário'),'')->class='tformtitle';
        
        $this->frm->add($tabFuncionario);
        
        //criando os campos de busca
        
        $nomeFuncionario = new TEntry('nomeFuncionario');
        $nomeFuncionario->setValue(TSession::getValue('funcionario_nomeFuncionario'));
                 
        $cpfFuncionario = new TEntry('cpfFuncionario');
        $cpfFuncionario->setValue(TSession::getValue('funcionario_cpfFuncionario'));
        
        $nomeFuncionario->setSize(150);
        $cpfFuncionario->setSize(150);
        
        $rowNomeFuncionario = $tabFuncionario->addRow();
        $rowNomeFuncionario->addCell(new TLabel('NOME....:'));
        $rowNomeFuncionario->addCell($nomeFuncionario);
        
        $rowCpfFuncionario = $tabFuncionario->addRow();
        $rowCpfFuncionario->addCell(new TLabel('CPF....:'));
        $rowCpfFuncionario->addCell($cpfFuncionario);
        
        $buscar = new TButton('buscar');
        $novo = new TButton('novo');
        
        $buscar->setAction(new TAction(array($this,'onSearch')),('Buscar'));
        $buscar->setImage('ico_find.png');
        
        $novo->setAction(new TAction(array('FrmFuncionario','onEdit')),('Novo'));
        $novo->setImage('ico_new.png');
        
        $tabFuncionario->addRowSet('',array($buscar, $novo))->class='tformaction';
        $this->frm->setFields(array($nomeFuncionario, $cpfFuncionario, $buscar, $novo));
        
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(300);
        
        $this->datagrid->addQuickColumn('ID','id','left',50, new TAction(array($this, 'onReload')), array('order','id'));
        $this->datagrid->addQuickColumn('NOME','nomeFuncionario','center',150, new TAction(array($this, 'onReload')), array('order','nomeFuncionario'));
        $this->datagrid->addQuickColumn('CPF','cpfFuncionario','center',150, new TAction(array($this, 'onReload')), array('order','cpfFuncionario'));
        $this->datagrid->addQuickColumn('MATRICULA','matricula','center',150);
        $this->datagrid->addQuickColumn('EMPRESA','empresa->nomeFantasia','center',150);
        
        $this->datagrid->addQuickAction('Editar', new TDataGridAction(array('FrmFuncionario','onEdit')),'id','ico_edit.png');
        $this->datagrid->addQuickAction('Deletar', new TDataGridAction(array($this,'onDelete')),'id','ico_delete.png');
        
        $this->datagrid->createModel();
        
        
        
        $this->pagenavigation = new TPageNavigation;
        $this->pagenavigation->setAction(new TAction(array($this,'onReload')));
        $this->pagenavigation->setWidth($this->datagrid->getWidth());
        
        
        $conteiner = new TVBox;
        
        $conteiner->add($this->frm);
        $conteiner->add($this->datagrid);
        $conteiner->add($this->pagenavigation);
        
        parent::add($conteiner);
        
        
    }
    public function onReload($param = NULL){
    
    try{
            
            TTransaction::open('permission');
            
            //Criando o repositorio para manipular a classe Tanque
            
            $limit = 10;
            
            $criterio = new TCriteria;
            if(isset($param)){
                
                
                $criterio->setProperties($param);
                $criterio->setProperty('limit',$limit);
                
                $funcionarios = Funcionario::getObjects($criterio);
            }else{
                $funcionarios = Funcionario::getObjects();
            }
            
            $this->datagrid->clear();
            if($funcionarios){
                
                foreach($funcionarios as $funcionario){
                    
                    $this->datagrid->addItem($funcionario);
                }
            }
            $criterio->resetProperties();
            $cont = count($funcionarios);
            
            $this->pagenavigation->setCount($cont);
            $this->pagenavigation->setProperties($param);
            $this->pagenavigation->setLimit($limit);
            
            TTransaction::close();
            $this->loaded = true;
            
        }
        catch(Exception $e){
            
            new TMessage('error',' Erro ao carregar os registros! '.$e->getMessage());
            TTransaction::rollback();
        }
    
    }
    public function onSearch(){
    
        $data = $this->frm->getData();
        
        if(isset($data->nomeFuncionario) AND ($data->nomeFuncionario)){
        
            $filtroNome = new TFilter('nomeFuncionario','like',"{$data->nomeFuncionario}%");
            TSession::setValue('funcionario_nomeFuncionario_filtro', $filtroNome);
            TSession::setValue('funcionario_nomeFuncionario',$data->nomeFuncionario);
        }
        else{
            TSession::setValue('funcionario_nomeFuncionario_filtro', NULL);
            TSession::setValue('funcionario_nomeFuncionario','');
        }
        if(isset($data->cpfFuncionario) AND ($data->cpfFuncionario)){
        
            $filtroCpf = new TFilter('cpfFuncionario','like',"{$data->cpfFuncionario}%");
            TSession::setValue('funcionario_cpfFuncionario_filtro', $filtroCpf);
            TSession::setValue('funcionario_cpfFuncionario',$data->cpfFuncionario);
        }
        else{
            TSession::setValue('funcionario_cpfFuncionario_filtro', NULL);
            TSession::setValue('funcionario_cpfFuncionario','');
        }
        
   
       $this->frm->setData($data);
       
       $param = array();
       $param['offset']    =0;
       $param['first_page']=1;
       $this->onReload($param);
        
        
    }
    public function onDelete($param){
        $key = $param['key'];
    
        $action = new TAction(array($this,'Delete'));
        
        $action->setParameter('key',$key);
        
        new TQuestion('Deseja realmente excluir esse registro?', $action);
    }
    public function Delete($param){
    
        try{
            $key = $param['key'];
            
            TTransaction::open('permission');
            
            $funcionario = new Funcionario($key);
            
            $funcionario->delete();
            
            TTransaction::close();
            
            $this->onReload();
            
            new TMessage('info','Registro deletado com sucesso!');
        }
        catch(Exception $e){
            new TMessage('Error',$e->getMessage());
            TTransaction::rollback();
        }
    
    }
    public function show(){
        if (!$this->loaded){
            $this->onReload(func_get_arg(0));
            parent::show();
        }

    }

    



}

?>