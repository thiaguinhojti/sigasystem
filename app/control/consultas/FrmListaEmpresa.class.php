<?php

class FrmListaEmpresa extends TPage{

    private $frm;
    private $datagrid;
    private $pagenavigation;
    private $loaded;
    
    public function __construct(){
    
        parent::__construct();
        new TSession;
        
        //criando o formulario
        
        $this->frm = new TForm('Frm_lista_empresa');
        $this->frm->class='tform';
        
        $tabEmpresa = new TTable;
        $tabEmpresa->width = '50%';
        $tabEmpresa->align='center';
        $tabEmpresa->addRowSet(new TLabel('Buscar Empresas'),'')->class='tformtitle';
        
        $this->frm->add($tabEmpresa);
        
        $filtro1 = new TEntry('razaoSocial');
        $filtro1->setValue(TSession::getValue('empresa_razaoSocial'));
        
        $filtro1->setSize(150);
        
        $rowRazaoSocial = $tabEmpresa->addRow();
        $rowRazaoSocial->addCell(new TLabel('RAZÃO SOCIAL..:'));
        $rowRazaoSocial->addCell($filtro1);
        
        $buscar = new TButton('buscar');
        $novo = new TButton('novo');
        
        $buscar->setAction(new TAction(array($this,'onSearch')),('Buscar'));
        $buscar->setImage('ico_find.png');
        
        $novo->setAction(new TAction(array('FrmEmpresa','onEdit')),('Novo'));
        $novo->setImage('ico_new.png');
        
        $tabEmpresa->addRowSet('',array($buscar, $novo))->class='tformaction';
        $this->frm->setFields(array($filtro1, $buscar, $novo));
        
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(300);
        
        $this->datagrid->addQuickColumn('ID','id','left',50, new TAction(array($this, 'onReload')), array('order','id'));
        $this->datagrid->addQuickColumn('RAZAO SOCIAL','razaoSocial','center',150, new TAction(array($this, 'onReload')), array('order','razaoSocial'));
        $this->datagrid->addQuickColumn('NOME FANTASIA','nomeFantasia','center',150);
        $this->datagrid->addQuickColumn('CNPJ','cnpjEmpresa','center',150);
        $this->datagrid->addQuickColumn('ESTADO','estado->uf','center',50);
        $this->datagrid->addQuickColumn('CIDADE','cidade->nome','center',150);
        
        $this->datagrid->addQuickAction('Editar', new TDataGridAction(array('FrmEmpresa','onEdit')),'id','ico_edit.png');
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
            
            TTransaction::open('dbwf');
            
            //Criando o repositorio para manipular a classe Tanque
            
            $limit = 10;
            
            $criterio = new TCriteria;
            if(isset($param)){
                
                
                $criterio->setProperties($param);
                $criterio->setProperty('limit',$limit);
                
                $empresas = Empresa::getObjects($criterio);
            }else{
                $empresas = Empresa::getObjects();
            }
            
            $this->datagrid->clear();
            if($empresas){
                
                foreach($empresas as $empresa){
                    
                    $this->datagrid->addItem($empresa);
                }
            }
            $criterio->resetProperties();
            $cont = count($empresas);
            
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
        
        if(isset($data->razaoSocial) and ($data->razaoSocial)){
            
            $filter = new TFilter('razaoSocial','like',"{data->razaoSocial}%");
            
            TSession::setValue('empresa_filtro', $filter);
            TSession::setValue('empresa_razoSocial',   $data->razaoSocial);
            
        }
        else{
            TSession::setValue('empresa_filtro', NULL);
            TSession::setValue('empresa_razoSocial',  '');
        }
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
            
            TTransaction::open('dbwf');
            
            $empresa = new Empresa($key);
            
            $empresa->delete();
            
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