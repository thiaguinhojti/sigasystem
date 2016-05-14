<?php
/**
 * FrmReproducao1 Master/Detail
 * @author  <your name here>
 */
class FrmMestreReproducao extends TPage
{
    protected $form; // form
    protected $formFields;
    protected $detail_list;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $notebook = new BootstrapNotebookWrapper( new TNotebook(400,230) );
        
        $page1 = new TTable;
        $page2 = new TPanel(370,180);
        $page3 = new TTable;
        
               
        // adds two pages in the notebook
        $notebook->appendPage('Basic data', $page1);
        $notebook->appendPage('Other data', $page2);
        $notebook->appendPage('Other note', $page3);
        
        // creates the form
        $this->form = new TForm('form_Reproducao');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'max-width:700px'; // style
        parent::include_css('app/resources/custom-frame.css');
        
        //$this->form->add($notebook);
        
        $table_master = new TTable;
        $table_master->width = '100%';
        
        $table_master->addRowSet( new TLabel('Reproducao'), '', '')->class = 'tformtitle';
        
        // add a table inside form
        $table_general = new TTable;
        $table_detail  = new TTable;
        $table_general-> width = '100%';
        $table_detail-> width  = '100%';
        
        $frame_general = new TFrame;
        $frame_general->setLegend('Reproducao');
        $frame_general->style = 'background:whiteSmoke';
        $frame_general->add($table_general);
        
        $table_master->addRow()->addCell( $frame_general )->colspan=2;
        $row = $table_master->addRow();
        $row->addCell( $table_detail );
        
        $this->form->add($table_master);
        
        // master fields
        $idReproducao = new THidden('idReproducao');
        $codigo = new TEntry('codigo');
        $dataInicioReproducao = new TEntry('dataInicioReproducao');
        $temperatura = new TEntry('temperatura');
        $equipeReproducao = new TEntry('equipeReproducao');
        $climaDia = new TEntry('climaDia');
        $pesoTotMatFemea = new TEntry('pesoTotMatFemea');
        $pesoTotMatMacho = new TEntry('pesoTotMatMacho');
        $qtdeMatFemea = new TEntry('qtdeMatFemea');
        $qtdeMatMacho = new TEntry('qtdeMatMacho');
        $pesoGeralMatriz = new TEntry('pesoGeralMatriz');
        $totalGeralHormonio = new TEntry('totalGeralHormonio');
        $dataFinalReproducao = new TEntry('dataFinalReproducao');
        $txEclosao = new TEntry('txEclosao');
        $txFecundacao = new TEntry('txFecundacao');
        
        if (!empty($idReproducao))
        {
            $idReproducao->setEditable(FALSE);
        }
        
        // detail fields
        $detail_idRepMat = new THidden('detail_idRepMat');
        $detail_idMatriz = new TDBSeekButton('idMatriz', 'dbwf',$this->form->getName(),'Matriz','numeroChipMatriz','idRepMatriz','numeroChipMatriz');
        $datail_numeroMatriz = new TEntry('numeroChipMatriz');
        $detail_identMatriz = new TEntry('detail_identMatriz');
        $detail_pesoMatriz = new TEntry('detail_pesoMatriz');

        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
        
        // master
        $table_general->addRowSet( new TLabel(''), $idReproducao );
        $table_general->addRowSet( new TLabel('Nº REPRODUÇÃO...:'), $codigo );
        $table_general->addRowSet( new TLabel('DATA...:'), $dataInicioReproducao );
        $table_general->addRowSet( new TLabel('TEMPERATURA...:'), $temperatura );
        $table_general->addRowSet( new TLabel('EQUIPE...:'), $equipeReproducao );
        $table_general->addRowSet( new TLabel('CLIMA DO DIA...:'), $climaDia );
        $table_general->addRowSet( new TLabel('PESO TOTAL FÊMEAS(kg)...:'), $pesoTotMatFemea );
        $table_general->addRowSet( new TLabel('PESO TOTAL MACHOS(kg)..:'), $pesoTotMatMacho );
        $table_general->addRowSet( new TLabel('TOTAL DE FÊMEAS...:'), $qtdeMatFemea );
        $table_general->addRowSet( new TLabel('TOTAL DE MACHOS...:'), $qtdeMatMacho );
        $table_general->addRowSet( new TLabel('TOTAL (kg/PV)...:'), $pesoGeralMatriz );
        $table_general->addRowSet( new TLabel('TOTAL DE HORMÔNIO....:'), $totalGeralHormonio );
        $table_general->addRowSet( new TLabel('FINAL REPRODUÇÃO..:'), $dataFinalReproducao );
        $table_general->addRowSet( new TLabel('TAXA DE ECLOSÃO(%)...:'), $txEclosao );
        $table_general->addRowSet( new TLabel('TAXA DE FECUNDAÇÃO(%)...:'), $txFecundacao );
        
         // detail
        $frame_details = new TFrame();
        $frame_details->setLegend('RepMatriz');
        $row = $table_detail->addRow();
        $row->addCell($frame_details);
        
        $btn_save_detail = new TButton('btn_save_detail');
        $btn_save_detail->setAction(new TAction(array($this, 'onSaveDetail')), 'Register');
        $btn_save_detail->setImage('fa:save');
        
        $table_details = new TTable;
        $frame_details->add($table_details);
        
        $table_details->addRowSet( '', $detail_idRepMat );
        $table_details->addRowSet( new TLabel('MATRIZ'), $detail_idMatriz );
        $table_details->addRowSet( new TLabel('NUMERO..:'), $datail_numeroMatriz );
        $table_details->addRowSet( new TLabel('IDENTIFICAÇÃO...:'), $detail_identMatriz );
        $table_details->addRowSet( new TLabel('PESO...:'), $detail_pesoMatriz );
        
        $table_details->addRowSet( $btn_save_detail );
        
        $this->detail_list = new TQuickGrid;
        $this->detail_list->setHeight( 175 );
        $this->detail_list->makeScrollable();
        $this->detail_list->disableDefaultClick();
        $this->detail_list->addQuickColumn('', 'edit', 'left', 50);
        $this->detail_list->addQuickColumn('', 'delete', 'left', 50);
        
        // items
        $this->detail_list->addQuickColumn('MATRIZ', 'idMatriz', 'left', 100);
        $this->detail_list->addQuickColumn('MATRIZ', 'numeroChipMatriz', 'left', 100);
        $this->detail_list->addQuickColumn('IDENTIFICAÇÃO', 'identMatriz', 'left', 200);
        $this->detail_list->addQuickColumn('PESO', 'pesoMatriz', 'left', 200);
        $this->detail_list->createModel();
        
        $row = $table_detail->addRow();
        $row->addCell($this->detail_list);
        
        // create an action button (save)
        $save_button=new TButton('save');
        $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
        $save_button->setImage('ico_save.png');

        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onClear')), _t('New'));
        $new_button->setImage('ico_new.png');
        
        // define form fields
        $this->formFields   = array($idReproducao,$codigo,$dataInicioReproducao,$temperatura,$equipeReproducao,$climaDia,$pesoTotMatFemea,$pesoTotMatMacho,$qtdeMatFemea,$qtdeMatMacho,$pesoGeralMatriz,$totalGeralHormonio,$dataFinalReproducao,$txEclosao,$txFecundacao,$detail_idMatriz,$detail_identMatriz,$detail_pesoMatriz);
        $this->formFields[] = $btn_save_detail;
        $this->formFields[] = $save_button;
        $this->formFields[] = $new_button;
        $this->formFields[] = $detail_idRepMat;
        $this->form->setFields( $this->formFields );
        
        $table_master->addRowSet( array($save_button, $new_button), '', '')->class = 'tformaction'; // CSS class
        
        // create the page container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($notebook);
        $container->add($this->form);
        parent::add($container);
    }
    
    
    /**
     * Clear form
     * @param $param URL parameters
     */
    public function onClear($param)
    {
        $this->form->clear();
        TSession::setValue(__CLASS__.'_items', array());
        $this->onReload( $param );
    }
    
    /**
     * Save an item from form to session list
     * @param $param URL parameters
     */
    public function onSaveDetail( $param )
    {
        try
        {
            TTransaction::open('dbwf');
            $data = $this->form->getData();
            
            /** validation sample
            if (! $data->fieldX)
                throw new Exception('The field fieldX is required');
            **/
            
            $items = TSession::getValue(Matriz.'numeroChipMatriz');
            $key = empty($data->detail_idRepMat) ? 'X'.mt_rand(1000000000, 1999999999) : $data->detail_idRepMat;
            
            $items[ $key ] = array();
            $items[ $key ]['idRepMat'] = $key;
            $items[ $key ]['idMatriz'] = $data->detail_idMatriz;
            $items[ $key ]['numeroChipMatriz'] = $data->detail_numeroMatriz;
            $items[ $key ]['identMatriz'] = $data->detail_identMatriz;
            $items[ $key ]['pesoMatriz'] = $data->detail_pesoMatriz;
            
            TSession::setValue(__CLASS__.'_items', $items);
            
            // clear detail form fields
            $data->detail_idRepMat = '';
            $data->detail_idMatriz = '';
            $data->detail_identMatriz = '';
            $data->detail_pesoMatriz = '';
            
            TTransaction::close();
            $this->form->setData($data);
            
            $this->onReload( $param ); // reload the items
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * Load an item from session list to detail form
     * @param $param URL parameters
     */
    public function onEditDetail( $param )
    {
        $data = $this->form->getData();
        
        // read session items
        $items = TSession::getValue(__CLASS__.'_items');
        
        // get the session item
        $item = $items[ $param['item_key'] ];
        
        $data->detail_idRepMat = $item['idRepMat'];
        $data->detail_idMatriz = $item['idMatriz'];
        $data->detail_identMatriz = $item['identMatriz'];
        $data->detail_pesoMatriz = $item['pesoMatriz'];
        
        // fill detail fields
        $this->form->setData( $data );
    
        $this->onReload( $param );
    }
    
    /**
     * Delete an item from session list
     * @param $param URL parameters
     */
    public function onDeleteDetail( $param )
    {
        $data = $this->form->getData();
        
        // reset items
            $data->detail_idMatriz = '';
            $data->detail_identMatriz = '';
            $data->detail_pesoMatriz = '';
        
        // clear form data
        $this->form->setData( $data );
        
        // read session items
        $items = TSession::getValue(__CLASS__.'_items');
        
        // delete the item from session
        unset($items[ $param['item_key'] ] );
        TSession::setValue(__CLASS__.'_items', $items);
        
        // reload items
        $this->onReload( $param );
    }
    
    /**
     * Load the items list from session
     * @param $param URL parameters
     */
    public function onReload($param)
    {
        // read session items
        $items = TSession::getValue(__CLASS__.'_items');
        
        $this->detail_list->clear(); // clear detail list
        $data = $this->form->getData();
        
        if ($items)
        {
            $cont = 1;
            foreach ($items as $list_item_key => $list_item)
            {
                $item_name = 'prod_' . $cont++;
                $item = new StdClass;
                
                // create action buttons
                $action_del = new TAction(array($this, 'onDeleteDetail'));
                $action_del->setParameter('item_key', $list_item_key);
                
                $action_edi = new TAction(array($this, 'onEditDetail'));
                $action_edi->setParameter('item_key', $list_item_key);
                
                $button_del = new TButton('delete_detail'.$cont);
                $button_del->class = 'btn btn-default btn-sm';
                $button_del->setAction( $action_del, '' );
                $button_del->setImage('fa:trash-o red fa-lg');
                
                $button_edi = new TButton('edit_detail'.$cont);
                $button_edi->class = 'btn btn-default btn-sm';
                $button_edi->setAction( $action_edi, '' );
                $button_edi->setImage('fa:edit blue fa-lg');
                
                $item->edit   = $button_edi;
                $item->delete = $button_del;
                
                $this->formFields[ $item_name.'_edit' ] = $item->edit;
                $this->formFields[ $item_name.'_delete' ] = $item->delete;
                
                // items
                $item->idRepMat = $list_item['idRepMat'];
                $item->idMatriz = $list_item['idMatriz'];
                $item->numeroMatriz = $list_item['numeroChipMatriz'];
                $item->identMatriz = $list_item['identMatriz'];
                $item->pesoMatriz = $list_item['pesoMatriz'];
                
                $row = $this->detail_list->addItem( $item );
                $row->onmouseover='';
                $row->onmouseout='';
            }

            $this->form->setFields( $this->formFields );
        }
        
        $this->loaded = TRUE;
    }
    
    /**
     * Load Master/Detail data from database to form/session
     */
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('dbwf');
            
            if (isset($param['key']))
            {
                $key = $param['key'];
                
                $object = new Reproducao($key);
                $items  = RepMatriz::where('idReproducao', '=', $key)->load();
                
                $session_items = array();
                foreach( $items as $item )
                {
                    $item_key = $item->idRepMat;
                    $session_items[$item_key] = $item->toArray();
                    $session_items[$item_key]['idRepMat'] = $item->idRepMat;
                    $session_items[$item_key]['idMatriz'] = $item->idMatriz;
                    $session_items[$item_key]['identMatriz'] = $item->identMatriz;
                    $session_items[$item_key]['pesoMatriz'] = $item->pesoMatriz;
                }
                TSession::setValue(__CLASS__.'_items', $session_items);
                
                $this->form->setData($object); // fill the form with the active record data
                $this->onReload( $param ); // reload items list
                TTransaction::close(); // close transaction
            }
            else
            {
                $this->form->clear();
                TSession::setValue(__CLASS__.'_items', null);
                $this->onReload( $param );
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Save the Master/Detail data from form/session to database
     */
    public function onSave()
    {
        try
        {
            // open a transaction with database
            TTransaction::open('dbwf');
            
            $data = $this->form->getData();
            $master = new Reproducao;
            $master->fromArray( (array) $data);
            $this->form->validate(); // form validation
            
            $master->store(); // save master object
            // delete details
            $old_items = RepMatriz::where('idReproducao', '=', $master->idReproducao)->load();
            
            $keep_items = array();
            
            // get session items
            $items = TSession::getValue(__CLASS__.'_items');
            
            if( $items )
            {
                foreach( $items as $item )
                {
                    if (substr($item['idRepMat'],0,1) == 'X' ) // new record
                    {
                        $detail = new RepMatriz;
                    }
                    else
                    {
                        $detail = RepMatriz::find($item['idRepMat']);
                    }
                    $detail->idMatriz  = $item['idMatriz'];
                    $detail->identMatriz  = $item['identMatriz'];
                    $detail->pesoMatriz  = $item['pesoMatriz'];
                    $detail->idReproducao = $master->idReproducao;
                    $detail->store();
                    
                    $keep_items[] = $detail->idRepMat;
                }
            }
            
            if ($old_items)
            {
                foreach ($old_items as $old_item)
                {
                    if (!in_array( $old_item->idRepMat, $keep_items))
                    {
                        $old_item->delete();
                    }
                }
            }
            TTransaction::close(); // close the transaction
            
            // reload form and session items
            $this->onEdit(array('key'=>$master->idReproducao));
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback();
        }
    }
    
    /**
     * Show the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
