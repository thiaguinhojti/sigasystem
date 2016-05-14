<?php

class FrmEmpresa extends TPage{

    private $notebook;
    private $frm;
    private $identificacao;
    private $contato;
    private $localizacao;
    private $cidade;
        
    
    public function __construct(){
    
        parent::__construct();
        try{
            TTransaction::open('dbwebfish');
            
            $this->notebook = new TNotebook(400, 290);
            
            $this->frm = new TForm('frm_empresa');
                        
            $identificacao = new TTable;
            $contato = new TTable;
            $localizacao = new TTable;
            
            
            $this->notebook->appendPage('Identificação', $identificacao);
            $this->notebook->appendPage('Contato',$contato);
            $this->notebook->appendPage('Localização', $localizacao);
            
            $this->frm->add($this->notebook);
            
            $id = new TEntry('id');
            $razaoSocial = new TEntry('razaoSocial');
            $nomeFantasia = new TEntry('nomeFantasia');
            $cnpjEmpresa = new TEntry('cnpjEmpresa');
            $inscEstadualEmpresa = new TEntry('inscEstadualEmpresa');
            $telefoneEmpresa = new TEntry('telefoneEmpresa');
            $emailEmpresa = new TEntry('emailEmpresa');
            $siteEmpresa = new TEntry('siteEmpresa');
            $enderecoEmpresa = new TEntry('enderecoEmpresa');
            $bairroEmpresa = new TEntry('bairroEmpresa');
            $estado = new TDBCombo('idEstado','dbwebfish','Estado','id','uf');
            $cidade = new TCombo('idCidade');
            $cepEmpresa = new TEntry('cepEmpresa');
            $responsavelEmpresa = new TEntry('responsavelEmpresa');
            
            $id->setEditable(FALSE);
            $id->setSize(50);
            $razaoSocial->setSize(200);
            $nomeFantasia->setSize(200);
            $cnpjEmpresa->setSize(200);
            $inscEstadualEmpresa->setSize(200);
            $telefoneEmpresa->setSize(200);
            $emailEmpresa->setSize(200);
            $siteEmpresa->setSize(200);
            $enderecoEmpresa->setSize(200);
            $bairroEmpresa->setSize(200);
            $estado->setSize(55);
            $cidade->setSize(200);
            $cepEmpresa->setSize(200);
            $responsavelEmpresa->setSize(200);
            
            $cnpjEmpresa->setMaxLength(18);
            $cnpjEmpresa->setMask('99.999.999/9999-99');
            $inscEstadualEmpresa->setMask('99.999.999-1');
            $inscEstadualEmpresa->setMaxLength(13);
            $telefoneEmpresa->setMask('(9999)9999-9999');
            $telefoneEmpresa->setMaxLength(17);
            $cepEmpresa->setMask('99900-000');
            $cepEmpresa->setMaxLength(9);
            
           
               
            
            $cnpjEmpresa->addValidation('cnpjEmpresa',new TCNPJValidator);
            
            //Adicionando linhas nas tabelas dos notes
            //Identificação
            $row = $identificacao->addRow();
            $row->addCell(new TLabel('ID..:'));
            $cell = $row->addCell($id);
            
            $row = $identificacao->addRow();
            $row->addCell(new TLabel('RAZÃO SOCIAL..:'));
            $cell = $row->addCell($razaoSocial);
            
            $row = $identificacao->addRow();
            $row->addCell(new TLabel('NOME FANTASIA..:'));
            $cell = $row->addCell($nomeFantasia);
            
            $row = $identificacao->addRow();
            $row->addCell(new TLabel('CNPJ....:'));
            $cell = $row->addCell($cnpjEmpresa);
            
            $row = $identificacao->addRow();
            $row->addCell(new TLabel('INSC. ESTADUAL..:'));
            $cell = $row->addCell($inscEstadualEmpresa);
            
            $proximo1 = new TButton('proximo1');
            $proximo1->setAction(new TAction(array($this, 'onStep2')), 'Próximo');
            $proximo1->setImage('next.png');
            $row = $identificacao->addRow();
            $row->addCell($proximo1);
            
            //Contato
            
            $row = $contato->addRow();
            $row->addCell(new TLabel('TELEFONE...:'));
            $cell = $row->addCell($telefoneEmpresa);
            
            $row = $contato->addRow();
            $row->addCell(new TLabel('EMAIL...:'));
            $cell = $row->addCell($emailEmpresa);
            
            $row = $contato->addRow();
            $row->addCell(new TLabel('SITE...:'));
            $cell = $row->addCell($siteEmpresa);
            
            $row = $contato->addRow();
            $row->addCell(new TLabel('RESPONSÁVEL...:'));
            $cell = $row->addCell($responsavelEmpresa);
            
            $anterior1 = new TButton('voltar1');
            $anterior1->setAction(new TAction(array($this,'onStep1')),'Anterior');
            $anterior1->setImage('ico_previous.png');
            $row = $contato->addRow();
            $row->addCell($anterior1);
            
            $proximo2 = new TButton('proximo2');
            $proximo2->setAction(new TAction(array($this, 'onStep3')), 'Próximo');
            $proximo2->setImage('next.png');
            //$row = $contato->addRow();
            $row->addCell($proximo2);
            //localização
            
            $row = $localizacao->addRow();
            $row->addCell(new TLabel('ENDEREÇO...:'));
            $cell = $row->addCell($enderecoEmpresa);
            
            $row = $localizacao->addRow();
            $row->addCell(new TLabel('BAIRRO...:'));
            $cell = $row->addCell($bairroEmpresa);
            
            $row = $localizacao->addRow();
            $row->addCell(new TLabel('ESTADO..:'));
            $cell = $row->addCell($estado);
            
            $row = $localizacao->addRow();
            $row->addCell(new TLabel('CIDADE...:'));
            $cell = $row->addCell($cidade);
            
              
            $row = $localizacao->addRow();
            $row->addCell(new TLabel('CEP...:'));
            $cell = $row->addCell($cepEmpresa);
            
            $anterior2 = new TButton('voltar2');
            $anterior2->setAction(new TAction(array($this,'onStep2')),'Anterior');
            $anterior2->setImage('ico_previous.png');
            $row = $localizacao->addRow();
            $row->addCell($anterior2);
            
            $salvar = new TButton('salvar');
            $salvar->setAction(new TAction(array($this,'onSave')),'Salvar');
            $salvar->setImage('ico_save.png');
            $row = $localizacao->addRow();
            $row->addCell($salvar);
            
            $novo = new TButton('novo');
            $novo->setAction(new TAction(array($this,'onEdit')),'Novo');
            $novo->setImage('ico_new.png');
            //$row = $localizacao->addRow();
            $row->addCell($novo);
            
            $listar = new TButton('listar');
            $listar->setAction(new TAction(array('FrmListaEmpresa','onReload')),'Listar');
            $listar->setImage('ico_datagrid.png');
            $row = $identificacao->addRow();
            $row->addCell($listar);  
                      
            $estado->setChangeAction(new TAction(array($this,'onChangeAction')));
            
            $this->frm->setFields(array($id, $razaoSocial, $nomeFantasia, $cnpjEmpresa, $inscEstadualEmpresa,
            $telefoneEmpresa, $emailEmpresa, $siteEmpresa, $enderecoEmpresa,
            $bairroEmpresa, $cidade, $estado, $cepEmpresa, $responsavelEmpresa,
            $proximo1, $proximo2, $anterior1, $anterior2, $salvar,$novo, $listar ));
            
            $vbox = new TVBox;
            $vbox->add($this->frm);
            
            parent::add($vbox);     
            
            TTransaction::close();
        }
        catch(Exception $e){
            
            new TMessage('info',$e->getMessage(), null, 'Erro',$e->getCode());
        }
    }
    public function onStep1(){
    
        $this->notebook->setCurrentPage(0);
        $this->frm->setData($this->frm->getData());
    
    }
    public function onStep2(){
    
        //$data = $this->frm->getData();
        $this->notebook->setCurrentPage(1);
        $this->frm->setData($this->frm->getData());
    }
    public function onStep3(){
    
       // $data = $this->frm->getData();
        $this->notebook->setCurrentPage(2);
        $this->frm->setData($this->frm->getData());  
    
    }
    public function onSave(){
        
        $mask = new PMaskFormate();
        try{
            
            TTransaction::open('dbwebfish');
            $obj = $this->frm->getData('Empresa');
            $this->frm->validate();
            
            $obj->cnpjEmpresa = $mask->clean_string($obj->cnpjEmpresa);
            $obj->inscEstadualEmpresa = $mask->clean_string($obj->inscEstadualEmpresa);
            $obj->telefoneEmpresa = $mask->clean_string($obj->telefoneEmpresa);
            $obj->cepEmpresa = $mask->clean_string($obj->cepEmpresa);
            
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
                
                $empresa = new Empresa($key);
                
                $this->frm->setData($empresa);
                
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
    public static function onChangeAction($param){
    
        TTransaction::open('dbwebfish');
        $repo = new TRepository('Cidade');
        $criterio = new TCriteria;
        
        if($param['idEstado']){
        
            $criterio->add(new TFilter('estado','=',$param['idEstado']));
        }
        $cidades = $repo->load($criterio);
        TTransaction::close();
        
        $opcoes = array();
        foreach($cidades as $cidade){
                
                $opcoes[$cidade->id] = $cidade->nome;
                
            }
            
        TCombo::reload('frm_empresa','id',$opcoes);
        
    }
   
            
        
}

?>