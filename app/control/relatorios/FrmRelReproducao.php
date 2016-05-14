<?php
/**
 * FrmRelReproducao Report
 * @author  <your name here>
 */
class FrmRelReproducao extends TPage
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
        $this->form = new TQuickForm('form_Reproducao_report');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Reproducao Report');
        


        // create the form fields
        $idReproducao = new TEntry('idReproducao');
        $codigo = new TEntry('codigo');
        $output_type = new TRadioGroup('output_type');


        // add the fields
        $this->form->addQuickField('ID', $idReproducao,  50 );
        $this->form->addQuickField('Codigo', $codigo,  50 );
        $this->form->addQuickField('Output', $output_type,  100 , new TRequiredValidator);



        
        $output_type->addItems(array('html'=>'HTML', 'pdf'=>'PDF', 'rtf'=>'RTF'));;
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        // add the action button
        $this->form->addQuickAction(_t('Generate'), new TAction(array($this, 'onGenerate')), 'fa:cog blue');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Relatório de Reprodução', $this->form));
        
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
            
            $repository = new TRepository('Reproducao');
            $criteria   = new TCriteria;
            
            if ($formdata->idReproducao)
            {
                $criteria->add(new TFilter('idReproducao', '=', "{$formdata->idReproducao}"));
            }
            if ($formdata->codigo)
            {
                $criteria->add(new TFilter('codigo', 'like', "%{$formdata->codigo}%"));
            }

           
            $objects = $repository->load($criteria, FALSE);
            $format  = $formdata->output_type;
            
            if ($objects)
            {
                $widths = array(50,50,100,100,100,50,50,100,100,50);
                
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
                $tr->addCell('Reproducao', 'center', 'header', 10);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('ID', 'center', 'title');
                $tr->addCell('Codigo', 'center', 'title');
                $tr->addCell('Início', 'center', 'title');
                $tr->addCell('Peso Fêmea', 'center', 'title');
                $tr->addCell('Peso Macho', 'center', 'title');
                $tr->addCell('Quantidade Fêmea', 'center', 'title');
                $tr->addCell('Quantidade Macho', 'center', 'title');
                $tr->addCell('Peso Total', 'center', 'title');
                $tr->addCell('Total Hormônio', 'center', 'title');
                $tr->addCell('Final Reprodução', 'center', 'title');

                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objects as $object)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($object->idReproducao, 'center', $style);
                    $tr->addCell($object->codigo, 'center', $style);
                    $tr->addCell($object->dataInicioReproducao, 'center', $style);
                    $tr->addCell($object->pesoTotMatFemea, 'center', $style);
                    $tr->addCell($object->pesoTotMatMacho, 'center', $style);
                    $tr->addCell($object->qtdeMatFemea, 'center', $style);
                    $tr->addCell($object->qtdeMatMacho, 'center', $style);
                    $tr->addCell($object->pesoGeralMatriz, 'center', $style);
                    $tr->addCell($object->totalGeralHormonio, 'center', $style);
                    $tr->addCell($object->dataFinalReproducao, 'center', $style);

                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 10);
                // stores the file
                if (!file_exists("app/output/Reproducao.{$format}") OR is_writable("app/output/Reproducao.{$format}"))
                {
                    $tr->save("app/output/Reproducao.{$format}");
                }
                else
                {
                    throw new Exception(_t('Permission denied') . ': ' . "app/output/Reproducao.{$format}");
                }
                
                // open the report file
                parent::openFile("app/output/Reproducao.{$format}");
                
                // shows the success message
                new TMessage('info', 'Relatório gerado. Habilitar popup no navegador!');
            }
            else
            {
                new TMessage('error', 'Nenhum Registro encontrado!');
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
