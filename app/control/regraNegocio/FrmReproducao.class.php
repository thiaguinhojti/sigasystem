<?php
class FrmReproducao extends TPage
{
    protected $form;
    public function __construct()
    {
        parent::__construct();
        
        $qform = new TQuickForm;
        $qform->class='tform';
        $qform->addQuickAction('Iniciar Reprodução', new TAction(array($this, 'onInputDialog')), 'ico_open.png');
        
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($qform);
        
        parent::add( $vbox );
    }
    
    /**
     * Open an input dialog
        parent::addAttribute('dataInicioReproducao');
        parent::addAttribute('temperatura');
        parent::addAttribute('equipeReproducao');
        parent::addAttribute('climaDia');
        
     */
    public function onInputDialog( $param )
    {
        $form = new TQuickForm('input_form');
        $form->style = 'padding:20px';
        
        $tbl_reproducao = new TTable;
        $tbl_matrizes = new TTable;
        $tbl_dados_primarios = new TTable;
        $hbox1 = new THBox;
        
        
        $tbl_reproducao->style='Width:100%;';
        $tbl_dados_primarios->style='width:100%';
              
        $form->add($tbl_reproducao);
        
        $tbl_reproducao->addRowSet( new TLabel('Reprodução'), '', '','' )->class = 'tformtitle';
        $tbl_reproducao->addRowSet($hbox1);
        
        
        $hbox1->add($tbl_dados_primarios);
        $form->add($tbl_reproducao);
        
        
        
        
        $codigo = new TEntry('codigo');
        $codigo = (int) ('$codigo + 1');
        $dataReproducao = new TDate('dataInicioReproducao');
        $dataReproducao->setMask('dd/mm/yyyy');
        //$codigo->setExitAction(
        
        
        
        $row = $tbl_dados_primarios->addRow();
        $row->addCell(new TLabel('Nº'.'....:'))->style='width:100px';
        $row->addCell($codigo);
        $tbl_dados_primarios->addRowSet(new TLabel('DATA'.'...:'), $dataReproducao);
        
        
        
        $form->addQuickAction(_t('Save'), new TAction(array('FrmMestreReproducao', 'onSave')), 'fa:floppy-o');
        //$form->addQuickAction('Confirm 2', new TAction(array($this, 'onConfirm2')), 'ico_apply.png');
        
        // show the input dialog
        new TInputDialog('Iniciar Reprodução', $form);
    }
    
    /**
     * Show the input dialog data
     */
   
    public function onConfirm2( $param )
    {
        new TMessage('info', 'Confirm2 : ' . json_encode($param));
    }
     
    public function onClear( $param )
    {
       // $this->form->clear();
    }
}
