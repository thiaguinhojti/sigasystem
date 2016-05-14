<?php

    class FrmListaTipoEspecie extends TPage{
    
        private $frm;    
        private $datagrid;
        private $pagenavigation;
        private $loaded;
        
        public function __construct(){
        
            parent::__construct();
            $this->frm = new TForm('FrmLista_Tipo_Especie');
            $this->frm->class = 'tform';
            $this->frm->style='align: center';
            
            $tabBuscar = new TTable;
            $tabBuscar->width='100%';
            
            $this->frm->add($tabBuscar);
            
            $tabBuscar->addRowSet(new TLabel(('Buscar Tipo de Especie')), '')->class='tformtitle';
            
            //Criando campos do formulario
            
            $filtro = new TEntry('descricaoTipo');
            $filtro->setValue(TSession::getValue('tipoEspecie_descricao'));
            
            $row=$tabBuscar->addRow();
            $row->addCell(new TLabel(('DESCRIÇÃO').'..:'));
            $row->addCell($filtro);
            
            //criando os botoes
            
            $buscar = new TButton('buscar');
            $novo = new TButton('novo');
            
            $buscar->setAction(new TAction(array($this,'onSearch')),('Buscar'));
            $buscar->setImage('ico_find.png');
            
            $novo->setAction(new TAction(array('FrmTipoEspecie','onEdit')),('Novo'));
            $novo->setImage('ico_new.png');
            
            $tabBuscar->addRowSet('',array($buscar, $novo))->class='tformaction';
            $this->frm->setFields(array($filtro, $buscar, $novo));
            
            
            $this->datagrid = new TDataGrid;
            $this->datagrid->setHeight(320);
            
            $id = new TDataGridColumn('id','ID','center',50);
            $descricao = new TDataGridColumn('descricaoTipo','Descrição','center',100);
            
            //criando as ações do datagrid
            $ordem1 = new TAction(array($this,'onReload'));
            $ordem2 = new TAction(array($this,'onReload'));
            
            $ordem1->setParameter('order','id');
            $ordem2->setParameter('order','descricaoTipo');
            
            $id->setAction($ordem1);
            $descricao->setAction($ordem2);
            
            //Criando as colunas do datagrid  
            
            $this->datagrid->addColumn($id);
            $this->datagrid->addColumn($descricao);
            
            //criando ações da grid
            
            $editar = new TDataGridAction(array('FrmTipoEspecie', 'onEdit'));
            $editar->setLabel('Editar');
            $editar->setImage('ico_edit.png');
            $editar->setField('id');
            
            $delete = new TDataGridAction(array($this,'onDelete'));
            $delete->setLabel('Deletar');
            $delete->setImage('ico_delete.png');
            $delete->setField('id');
            
            $this->datagrid->addAction($editar);
            $this->datagrid->addAction($delete);
            
            $this->datagrid->createModel();
            
            //Criando navegação de páginas
            
            $this->pagenavigation = new TPageNavigation;
            $this->pagenavigation->setAction(new TAction(array($this,'onReload')));
            $this->pagenavigation->setWidth($this->datagrid->getWidth());
            
            //Criando a estrutura do formulario em forma de caixa vertical
            
            $conteiner = new TVBox;
            
            $conteiner->add($this->frm);
            $conteiner->add($this->datagrid);
            $conteiner->add($this->pagenavigation);
            
            parent::add($conteiner);
        }
        public function onReload($param = NULL){
            
            try{
                TTransaction::open('dbwf');
                
                $criterio = new TCriteria;
                if (empty($param['order'])){
                
                    $param['order'] = 'id';
                    
                }
                
                $repositorio = new TRepository('TipoEspecie');
                $limit = 10;
                $criterio->setProperties($param);
                $criterio->setProperty('limit',$limit);
                
                if(TSession::getValue('tipoEspecie_filtro')){
                
                    $criterio->add(TSession::getValue('tipoEspecie_filtro'));
                }
                $objetos = $repositorio->load($criterio);
                $this->datagrid->clear();
                
                if($objetos){
                    foreach($objetos as $obj){
                        $this->datagrid->addItem($obj);
                    
                    }
                }
                $criterio->resetProperties();
                $cont = $repositorio->count($criterio);
                
                $this->pagenavigation->setCount($cont);
                $this->pagenavigation->setProperties($param);
                $this->pagenavigation->setLimit($limit);
                
                TTransaction::close();
                $this->loaded = true;
            
            }
            
            catch(Exception $e){
            
                new TMessage('error',$e->getMessage());
                TTransaction::rollback();
            }
        }
        
        public function onSearch(){
            $data = $this->frm->getData();
            
             TSession::setValue('tipoEspecie_filtro', NULL);
             TSession::setValue('tipoEspecie_descricao', '');
            
            if(isset($data->descricaoTipo)){
               
                $filtro = new TFilter('descricaoTipo','like',"%{$data->descricaoTipo}%");
                //armazenar o filtro na sessao
                
                TSession::setValue('tipoEspecie_filtro', $filtro);
                TSession::setValue('tipoEspecie_descricao',$data->descricaoTipo);
                
                $this->frm->setData($data);
            }
            
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
                
                TTransaction::open('dbwf');
                
                $tipoEspecie = new TipoEspecie($key);
                
                $tipoEspecieS->delete();
                
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