<?php
class FrmMestreReproducao extends TPage
{
   protected $form;
   private $form_matrizes;
   private $total;
   private $matgrid;
   private $lista_matrizes;
   public function __construct(){
   
       parent::__construct();
       
       TPage::include_css('app/resources/styles.css');
        // loads the mask javascript library
       TPage::include_js('app/lib/jquery/jquery.mask.js');
       TPage::include_js('app/lib/jquery/jquery.mask.min.js');
       
       $this->form = new TQuickForm('FrmMestreReproducao');
       $this->form->class = 'tform';
       
       
       parent::include_css('app/resources/custom-frame.css');
       
       $tbl_reproducao = new TTable;
       $tbl_matrizes = new TTable;
       $tbl_dados_primarios = new TTable;
       $tbl_estatisticas = new TTable;
       $tbl_matriz2 = new TTable;
       $tbl_dados_primarios->style='background-color: red';
       $hbox1 = new THBox;
       $hbox2 = new THBox;
       $hbox3 = new THBox;
       
       $tbl_reproducao->style='Width:100%';
       $tbl_dados_primarios->style='width:100%';
       $tbl_matrizes->style='width:100%';//margin-left:25px;
       $tbl_estatisticas->style='width:100%';//margin-left:10px;
       $tbl_matriz2->style='width:100%;margin:4px;margin-left:123px';//
       $this->form->add($tbl_reproducao);
       
       
       $tbl_reproducao->addRowSet( new TLabel('Reprodução'), '', '','' )->class = 'tformtitle';
       $tbl_reproducao->addRowSet($hbox1);
       $tbl_reproducao->addRowSet($hbox2);
       $tbl_reproducao->addRowSet($hbox3);
       
       $hbox1->add($tbl_dados_primarios);
       $hbox1->add($tbl_estatisticas);
       $hbox2->add($tbl_matrizes);
       $hbox2->add($tbl_matriz2);
       $this->form->add($tbl_reproducao);
       
       $frame_matrizes = new TFrame(NULL, 290);
       $frame_matrizes->setLegend('Matrizes');
       $frame_matrizes->style .= 'background:whiteSmoke;margin: 4px';
       
       $frame_incubadora = new TFrame(NULL, 290);
       $frame_incubadora->setLegend('Incubadora');
       $frame_incubadora->style .= 'margin-left: 4px;width:375px;height:360px;';
       
            
                   
       $idReproducao        = new THidden('idReproducao');
       $codigo              = new TEntry('codigo');
       $dataInicioReproducao = new TDate('dataInicioReproducao');
       $temperatura        = new TEntry('temperatura');
       //$equipeReproducao   = new TEntry('equipeReproducao');
       $climaDia           = new TEntry('climaDia');
       $pesoTotMatFemea    = new TEntry('pesoTotMatFemea');
       $pesoTotMatMacho    = new TEntry('pesoTotMatMacho');
       $qtdeMatFemea       = new TEntry('qtdeMatFemea');
       $qtdeMatMacho       = new TEntry('qtdeMatMacho');
       $pesoGeralMatriz    = new TEntry('pesoGeralMatriz');
       $totalGeralHormonio = new TEntry('totalGeralHormonio');
       $dataFinalReproducao = new TEntry('dataFinalReproducao');
       $reproducao_incubadoras  = new TDBCheckGroup('reproducao_incubadoras','dbwf','Incubadora','idIncubadora','descIncubadora');
       $idMatriz           = new TDBSeekButton('idMatriz','dbwf',$this->form->getName(),'Matriz','numeroChipMatriz','matrizes_idMatriz','matrizes_numeroChipMatriz');
       //$matriz             = new TEntry('matriz');
       $numero             = new TEntry('numeroChipMatriz');
       $pesoMatriz         = new TEntry('pesoMatriz');
       $identMatriz        = new TEntry('identMatriz');
       $sexoMatriz         = new TEntry('sexoMatriz');
       //$multi_matrizes = new TMultiCampo('matrizes');
       $multi_matrizes = new TMultiField('matrizes');
       $this->lista_matrizes = $multi_matrizes; 
       $txEclosao          = new TEntry('txEclosao');
       $txFecundacao       = new TEntry('txFecundacao');
       
       
       if (!empty($codigo))
       {
            $codigo->setEditable(TRUE);
       }
       $idMatriz->setExitAction(new TAction(array($this, 'onExitMatriz')));
       
       $dataInicioReproducao->setMask('dd/mm/yyyy'); 
       $dataFinalReproducao->setMask('99/99/9999');
       //$dataInicioReproducao->setMask('99/99/9999');
       $scroll = new TScroll;
       $scroll->setSize(290, 240);
       $scroll->add( $reproducao_incubadoras  );
       $frame_incubadora->add($scroll);
       $frame_matrizes->add($multi_matrizes);
       //$matriz->setEditable(false);
       
       
       $codigo->setSize('50%');
       $temperatura->setSize('100%');
       //equipeReproducao->setSize('100%');
       $climaDia->setSize('100%');
       $pesoTotMatFemea->setSize('50%');
       $pesoTotMatMacho->setSize('50%');
       $totalGeralHormonio->setSize('50%');
       $qtdeMatFemea->setSize('50%');
       $qtdeMatFemea->style='margin-left:29px';
       $qtdeMatMacho->setSize('50%');
       $pesoGeralMatriz->setSize('50%');
       
       $numero->setEditable(false);
       $sexoMatriz->setSize(50);      
       $sexoMatriz->setEditable(false);
       $pesoTotMatFemea->setEditable(false);
       $pesoGeralMatriz->setEditable(false);
       $pesoTotMatMacho->setEditable(false);
       $qtdeMatFemea->setEditable(false);
       $qtdeMatMacho->setEditable(false);
       $totalGeralHormonio->setEditable(false);
       
       $pesoTotMatFemea->style='color:#FF0000;';
       $pesoTotMatMacho->style='color:#FF0000;';
       $pesoGeralMatriz->style='color:#FF0000;';
       $qtdeMatFemea->style='color:#FF0000;';
       $qtdeMatMacho->style='color:#FF0000;';
       $totalGeralHormonio->style='color:#FF0000;';
      
       
       $multi_matrizes->setHeight(120);
       
       
       $multi_matrizes->setClass('RepMatriz');
       $multi_matrizes->addField('idMatriz','Matriz', $idMatriz,60);
       $multi_matrizes->addField('numeroChipMatriz','Numero', $numero, 60,'center');
       $multi_matrizes->addField('identMatriz','Identificação',$identMatriz,100);
       $multi_matrizes->addField('pesoMatriz','Peso Atual', $pesoMatriz,80);
       $multi_matrizes->addField('sexoMatriz','Sexo',$sexoMatriz,60,'center');
       $multi_matrizes->setOrientation('vertical');
       
       $row = $tbl_dados_primarios->addRow();
       $row->addCell(new TLabel('Nº:'))->style='width:75px';
       $row->addcell($codigo);
       $row->addCell(new TLabel(''))->style='width:75px';
       $row->addcell($idReproducao);
       $tbl_dados_primarios->addRowSet(new TLabel('INICIO'.': ' ),    $dataInicioReproducao);
       $tbl_dados_primarios->addRowSet(new TLabel('TEMPERATURA'.': ' ),    $temperatura);
       //$tbl_dados_primarios->addRowSet(new TLabel('EQUIPE'.': '),    $equipeReproducao);
       $tbl_dados_primarios->addRowSet(new TLabel('CLIMA DO DIA'.': '),    $climaDia);
       //$tbl_dados_primarios->addRowSet(new TLabel('FINAL'.': '),    $dataFinalReproducao);
       
       $row = $tbl_estatisticas->addRow();
       $row->addCell(new TLabel('TAXA DE ECLOSÃO:'))->style='width:150px';
       $row->addCell($txEclosao);
       $tbl_estatisticas->addRowSet(new TLabel('TAXA DE FECUNDAÇÃO'.': '),    $txFecundacao);
       $tbl_estatisticas->addRowSet(new TLabel('FINAL'.': '),    $dataFinalReproducao);
       //$tbl_estatisticas->addRowSet(new TLabel('TOTAL MACHOS'.': '),    $qtdeMatMacho);
       $row = $tbl_matrizes->addRow();
       $row->addCell(new TLabel('<b>TOTAL FÊMEAS:</b> '))->style='width:150px;margin-right:70px;';
       $row->addCell($qtdeMatFemea);
       $tbl_matrizes->addRowSet(new TLabel('<b>TOTAL MACHOS'.': </b>'),    $qtdeMatMacho)->style='width:80px;margin-right:70px;';
       $tbl_matrizes->addRowSet(new TLabel('<b>TOTAL HORMÔNIO'.':</b> '),    $totalGeralHormonio);
              
       $row = $tbl_matriz2->addRow();
       $row->addCell(new TLabel('<b>PESO FÊMEAS'.': </b>'))->style='width:150px';
       $row->addCell($pesoTotMatFemea);
       $tbl_matriz2->addRowSet(new TLabel('<b>PESO MACHOS'.':</b> '),    $pesoTotMatMacho);
       $tbl_matriz2->addRowSet(new TLabel('<b>PESO GERAL'.': </b>'),    $pesoGeralMatriz);
       
       $hbox3->add($frame_matrizes)->style .='vertical-align:top';
       $hbox3->add($frame_incubadora)->style .='vertical-align:top';
       
       $save_button=new TButton('save');
       $save_button->setAction(new TAction(array($this, 'onSave')), _t('Save'));
       $save_button->setImage('fa:floppy-o');
        
        // create an new button (edit with no parameters)
        $new_button=new TButton('new');
        $new_button->setAction(new TAction(array($this, 'onEdit')), _t('New'));
        $new_button->setImage('fa:plus-square green');
        
        $list_button=new TButton('list');
        $list_button->setAction(new TAction(array('FrmListaReproducao','onReload')), _t('Back to the listing'));
        $list_button->setImage('fa:table blue');
        
        // define the form fields
        $this->form->setFields(array($idReproducao, /*$equipeReproducao,*/ $txEclosao, $codigo, $dataInicioReproducao, $dataFinalReproducao, $temperatura, $climaDia, $pesoTotMatFemea, $pesoTotMatMacho,
        $qtdeMatFemea, $qtdeMatMacho, $pesoGeralMatriz, $txFecundacao, $totalGeralHormonio, $reproducao_incubadoras, $multi_matrizes, $save_button, $new_button, $list_button));
        
        $buttons = new THBox;
        $buttons->add($save_button);
        $buttons->add($new_button);
        $buttons->add($list_button);

        $row=$tbl_reproducao->addRow();
        $row->class = 'tformaction';
        $row->addCell( $buttons );

        $container = new TTable;
        $container->style = 'width: 80%';
        //$container->addRow()->addCell(new TXMLBreadCrumb('menu.xml', 'SystemUserList'));
        $container->addRow()->addCell($this->form);

        // add the form to the page
        parent::add($container);
       
   }
   public function onSave(){
   
       $qtdM = 0;
       $qtdF = 0;
       $pM = 0;
       $pF = 0;
       try{
       
           TTransaction::open('dbwf');
           TTransaction::setLogger(new TLoggerTXT('C:\log.txt'));
           TTransaction::log('Inserir Reproducao ');
           $this->form->validate();
                              
           $object = $this->form->getData('Reproducao');
           $data = $this->form->getData();
           $idReproducao = $object->idReproducao;
           $codigoTemp = 0;
           $codigoTemp = $codigoTemp +1;       
           if($data->matrizes)
           {
               //$rep_matrizes = array();
               
               foreach($data->matrizes as $mat)
               {
                 $matriz = new Matriz($mat->idMatriz); 
                 $reproducao_matriz = new RepMatriz();
                 $reproducao_matriz->matriz = $matriz;
                 $reproducao_matriz->pesoMatriz = $mat->pesoMatriz;
                 $reproducao_matriz->identMatriz = $mat->identMatriz;
                 //$object->set_repMatriz($reproducao_matriz);
                 if($matriz->sexoMatriz=='M')
                 {
                     $pM = $pM + $reproducao_matriz->pesoMatriz;    
                     $qtdM = $qtdM + 1;
                 }
                 else
                 {
                     $pF = $pF + $reproducao_matriz->pesoMatriz;
                     $qtdF = $qtdF + 1;
                 }
                 $object->repMatriz = $reproducao_matriz;  
                                  
               }
              
               $CONSTHORM = 0.5;
               $object->codigo = str_pad($object->codigo, 10,"0", STR_PAD_LEFT);
               $object->pesoTotMatMacho = $pM;
               $object->pesoTotMatFemea = $pF;
               $object->qtdeMatFemea = $qtdF;
               $object->qtdeMatMacho = $qtdM;
               $object->pesoGeralMatriz = number_format(($object->pesoTotMatFemea + $object->pesoTotMatFemea),2,'.',',');
               $object->totalGeralHormonio = number_format(($object->pesoGeralMatriz * $CONSTHORM),2,'.',',');
           }
           if($data->reproducao_incubadoras)
           {
               foreach ($data->reproducao_incubadoras as $incubadora)
               {
                   $object->addIncubadora(new Incubadora($incubadora));
               }
           }
           
           $object->pesoTotMatMacho = str_replace(',','.',$object->pesoTotMatMacho);
           $object->pesoTotMatFemea = str_replace(',','.',$object->pesoTotMatFemea);
           $object->pesoGeralMatriz = str_replace(',','.',$object->pesoGeralMatriz);
           $object->dataInicioReproducao = TDate::date2us($object->dataInicioReproducao);
           $object->dataFinalReproducao = TDate::date2us($object->dataFinalReproducao);
                  
           $object->store();
          
           
           
           TTransaction::close();
           $param['idReproducao'] = $idReproducao;
           
           FrmMestreReproducao::onCalcular($param);
           new TMessage('info','Registro Gravado com sucesso!');
       }
       catch(Exception $e){
       
           new TMessage('error','<b>Erro ao gravar o Registro! </b>'. $e->getMessage());
           $this->form->setData( $this->form->getData() );
           TTransaction::rollback();
       }
   
   }
   public function onEdit($param)
   {
   
       try
       {
       
           if(isset($param['key']))
           {
               $key = $param['key'];
               TTransaction::open('dbwf');
                TTransaction::setLogger(new TLoggerTXT('C:\Carregar_log.txt'));
                TTransaction::log('Carregar Reproducao ');
               $object = new Reproducao($key);
               $incubadoras = $object->getIncubadoras();
               $repmatrizes = $object->repMatriz;
               if($object->numero)
                {
                    
                }
              // var_dump($repmatrizes);
               $reproducao_incubadoras = array();
               
               if($incubadoras)
               {
                   foreach($incubadoras as $incubadora){
                       $reproducao_incubadoras[] = $incubadora->idIncubadora;           
                   } 
               
               }
               TEntry::disableField('FrmMestreReproducao','codigo');
               $object->reproducao_incubadoras = $reproducao_incubadoras;
               $object->codigo = str_pad($object->codigo, 10,"0", STR_PAD_LEFT);
               $object->dataInicioReproducao = TDate::date2br($object->dataInicioReproducao);
               $object->dataFinalReproducao = TDate::date2br($object->dataFinalReproducao);
               $object->pesoTotMatFemea = str_replace(',','.',$object->pesoTotMatFemea);
               $object->pesoTotMatMacho = str_replace(',','.',$object->pesoTotMatMacho);
               $object->pesoGeralMatriz = str_replace(',','.',$object->pesoGeralMatriz);
               //$object->dataFinalReproducao = TEntry::setEditable(false);
               $reproducao_matrizes = array();
               
               if($repmatrizes)
               {
               
                   foreach($repmatrizes as $repmatriz)
                   
                   {
                       //$reproducao_matrizes[$repmatriz] = $value;
                       //$object->matrizes_id = $repmatriz->idMatriz;
                       //$object->matrizes_numeroChipMatriz = $repmatriz->matriz->numeroChipMatriz;
                       //$object->matrizes_identMatriz = $repmatriz->identMatriz;
                       //$object->matrizes_pesoMatriz = $repmatriz->pesoMatriz;
                       //$object->matrizes_sexoMatriz = $repmatriz->matriz->sexoMatriz;
                       $repmatriz->numeroChipMatriz = $repmatriz->matriz->numeroChipMatriz;
                       $repmatriz->sexoMatriz = $repmatriz->matriz->sexoMatriz;
                       $reproducao_matrizes[] = $repmatriz;
                       //$reproducao_matrizes[] = $repmatriz->matriz;
                       //$reproducao_matrizes['']
                       var_dump($repmatriz->numeroChipMatriz);
                   }
                   $object->matrizes = $reproducao_matrizes;
                   FrmMestreReproducao::onCalcular($param);
                   //$this->lista_matrizes->setValue($object->matrizes);
                   
                   $object->pesoTotMatMacho = str_replace('.',',', $object->pesoTotMatMacho);
                   $object->pesoTotMatFemea = str_replace('.',',', $object->pesoTotMatFemea);
                   $object->pesoGeralMatriz = str_replace('.',',', $object->pesoGeralMatriz);
                   $object->qtdeMatFemea  = str_replace('.',',', $object->pesoTotMatMacho);
                   
               
               }
               
               $this->form->setData($object);
               TTransaction::close();
              
         }
       }       
       catch(Exception $e)
       {
           new TMessage('error','<b>Erro ao gravar o Registro!</b>'. $e->getMessage());
           TTransaction::rollback();
       }
   
   }
   public static function onExitMatriz($param)
   {
       
     $idMatriz = $param['key'];
      
       
       try
       {
       
           TTransaction::open('dbwf');
               $matriz  = new Matriz($idMatriz);
               //$reproducao = new Reproducao($idReproducao);
               
               $obj = new StdClass;
               $obj->matrizes_sexoMatriz = $matriz->sexoMatriz;
                   
                     
           TTransaction::close();
           TForm::sendData('FrmMestreReproducao', $obj);
       
       }
       catch(Exception $e)
       {
           
       
       }
   
   }
   public static function onCalcular($param)
   {
     //$idMatriz = $param['key'];
     $idReproducao = $param['idReproducao'];
     //$pesoMatriz = (double) str_replace(',','', $param['matrizes_pesoMatriz']); 
     $doseHormonio = 0.5;
       try
       {
       
           TTransaction::open('dbwf');
               
               $reproducao = new Reproducao($idReproducao);
               
               $obj = new StdClass;
              
               $obj->pesoTotMatMacho = 0;
               $obj->qtdeMatMacho = 0;
               $qtdeMatMacho = 0;
               $obj->pesoTotMatFemea = 0;
               $obj->qtdeMatFemea = 0;
               $obj->pesoGeralMatriz = 0;
               $repositorio = new TRepository('RepMatriz');
               $criteria = new TCriteria;
               $criteria->add(new TFilter('idReproducao','=',$reproducao->idReproducao));
               $rep_matrizes = array();
               $rep_matrizes = $repositorio->load($criteria);
               if($rep_matrizes)
               {
                   foreach($rep_matrizes as $result)
                   {
                       $matriz = new Matriz($result->idMatriz);
                       if($matriz->sexoMatriz=='M')
                       {
                           
                           //$qtdeMatMacho += ($result->pesoMatriz);
                           $obj->pesoTotMatMacho += ($result->pesoMatriz);
                           $obj->qtdeMatMacho += count($result);    
                       }
                       else
                       {
                           $obj->pesoTotMatFemea +=($result->pesoMatriz);
                           $obj->qtdeMatFemea += count($result);
                       }
                                               
                   }
                   
                   $obj->pesoGeralMatriz = number_format(($obj->pesoTotMatMacho + $obj->pesoTotMatFemea),2,'.',',');
                   $obj->totalGeralHormonio = number_format(($obj->pesoGeralMatriz * $doseHormonio),2,'.',',');    
               } 
               
           TTransaction::close();
           TForm::sendData('FrmMestreReproducao', $obj);
       
       }
       catch(Exception $e)
       {
           
       
       }
   
  }
}