<?php
/**
 * EspecieReport Report
 * @author  <your name here>
 */
class FrmRelEspecie extends TPage
{
    protected $form; // form
    protected $notebook;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Especie_report');
        $this->form->class = 'tform'; // change CSS class
        
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Relatório de Espécies');
        
        // create the form fields
        $idEspecie = new TEntry('idEspecie');
        $nomePopularEspecie = new TEntry('nomePopularEspecie');
        $output_type = new TRadioGroup('output_type');


        // add the fields
        $this->form->addQuickField('Especie', $idEspecie,  50 );
        $this->form->addQuickField('Nome Popular', $nomePopularEspecie,  100 );
        $this->form->addQuickField('Saída', $output_type,  100 , new TRequiredValidator);



        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'RTF'));;
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        // add the action button
        $this->form->addQuickAction(_t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:cog blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }
    
    /**
     * Generate the report
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'dbwf'
            TTransaction::open('dbwf');
            
            // get the form data into an active record
            $formdata = $this->form->getData();
            
            $repository = new TRepository('Especie');
            $criteria   = new TCriteria;
            
            if ($formdata->idEspecie)
            {
                $criteria->add(new TFilter('idEspecie', '=', "{$formdata->idEspecie}"));
            }
            if ($formdata->nomePopularEspecie)
            {
                $criteria->add(new TFilter('nomePopularEspecie', 'like', "%{$formdata->nomePopularEspecie}%"));
            }

           
            $objects = $repository->load($criteria, FALSE);
            $format  = $formdata->output_type;
            
            if ($objects)
            {
                $widths = array(50,100,100,100,100,100,50,50,100);
                
                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                // create the document styles
                $tr->addStyle('title', 'Arial', '10', 'B',   '#ffffff', '#A3A3A3');
                $tr->addStyle('datap', 'Arial', '10', '',    '#000000', '#EEEEEE');
                $tr->addStyle('datai', 'Arial', '10', '',    '#000000', '#ffffff');
                $tr->addStyle('header', 'Arial', '16', '',   '#ffffff', '#6B6B6B');
                $tr->addStyle('footer', 'Times', '10', 'I',  '#000000', '#A3A3A3');
                
                // add a header row
                $tr->addRow();
                $tr->addCell('Especie', 'center', 'header', 9);
                
                // add titles row
                $tr->addRow();
                //$tr->addCell('Idespecie', 'right', 'title');
                $tr->addCell('Espécie', 'center', 'title');
                $tr->addCell('Nomecientificoespecie', 'center', 'title');
                $tr->addCell('Tamanhomaximo', 'center', 'title');
                $tr->addCell('Horagrauinicioreproducao', 'center', 'title');
                $tr->addCell('Qtdesolvquilovivopeixe', 'center', 'title');
                $tr->addCell('Qtdemaximaaplicacoes', 'center', 'title');
                $tr->addCell('Familia', 'center', 'title');
                $tr->addCell('Tipo', 'center', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    //$tr->addCell($object->idEspecie, 'right', $style);
                    $tr->addCell($object->nomePopularEspecie, 'center', $style);
                    $tr->addCell($object->nomeCientificoEspecie, 'center', $style);
                    $tr->addCell($object->tamanhoMaximo, 'center', $style);
                    $tr->addCell($object->horaGrauInicioReproducao, 'center', $style);
                    $tr->addCell($object->QtdeSolvQuiloVivoPeixe, 'center', $style);
                    $tr->addCell($object->QtdeMaximaAplicacoes, 'center', $style);
                    $tr->addCell($object->familia_especie, 'center', $style);
                    $tr->addCell($object->tipoEspecie, 'left', $style);

                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('d-m-Y H:i:s'), 'center', 'footer', 9);
                // stores the file
                if (!file_exists("app/output/Especie.{$format}") OR is_writable("app/output/Especie.{$format}"))
                {
                    $tr->save("app/output/Especie.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/Especie.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/Especie.{$format}");
                
                // shows the success message
                new TMessage('info', 'Report generated. Please, enable popups.');
            }
            else
            {
                new TMessage('error', 'No records found');
            }
    
            // fill the form with the active record data
            $this->form->setData($formdata);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
