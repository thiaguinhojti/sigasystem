<?php
class FrmSolucao extends TPage
{
public function __construct()
    {
        parent::__construct();

        // creates the customer form and add a table inside it
        $this->form = new TQuickForm('FrmSolucao');
        $this->form->class = 'tform';
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->setFormTitle('Aplicação de Hormônio');
        
        // create the form fields
        $reproducao    = new TDBSeekButton('idReproducao', 'dbwf', 'FrmSolucao', 'Reproducao', 'codigo', 'idReproducao', 'reproducao_codigo');
        $reproducao_codigo  = new TEntry('reproducao_codigo');
        
        $this->form->addQuickField('CÓDIGO', $reproducao);
        $this->form->addQuickField('NÚMERO', $reproducao_codigo);
        $this->form->addQuickAction('Gerar Aplicações', new TAction(array($this, 'onCheckStatus')), 'ico_apply.png');
        $reproducao->setSize(50);
        $reproducao_codigo->setSize(200);
        $reproducao_codigo->setEditable(FALSE);
        //$this->form->setFieldsByRow(2);
        // wrap the page content using vertical box
        /*$vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        parent::add($vbox);*/
        
        $container = new TVBox;
        $container->style = 'width: 60%; position:absolut; margin-left:10%;';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Aplicação de Hormônio', $this->form));
        
        parent::add($container);
    }
    
    /**
     * Show customer data and sales
     */
    public function onCheckStatus( $param )
    {
        try
        {
            $data = (object) $param;
            $this->form->setData( $data ); // keep the form filled
            
            // load the html template
            $html = new THtmlRenderer('app/resources/customer_status.html');
            
            // load CSS styles
            parent::include_css('app/resources/styles.css');
            
            TTransaction::open('dbwf');
            if (isset($data->customer_id))
            {
                // load customer identified in the form
                $object = new Customer( $data->customer_id );
                if ($object)
                {
                    // create one array with the customer data
                    $array_object = $object->toArray();
                    $array_object['city_name'] = $object->city_name;
                    $array_object['category_name'] = $object->category_name;
                    // replace variables from the main section with the object data
                    $html->enableSection('main',  $array_object);
                    
                    $replaces = array();
                    $sales = $object->getSales();
                    if ($sales)
                    {
                        $total = 0;
                        // iterate the customer sales
                        foreach ($sales as $sale)
                        {
                            // foreach sale item
                            foreach ($sale->getSaleItems() as $item)
                            {
                                // define the multidimensional array with the sale items
                                $replaces[] = array('date'                => $sale->date,
                                                    'product_id'          => $item->product_id,
                                                    'product_description' => $item->product->description,
                                                    'sale_price'          => number_format($item->sale_price,2),
                                                    'amount'              => $item->amount,
                                                    'discount'            => $item->discount,
                                                    'total'               => number_format($item->total, 2)); 
                                $total += $item->total;
                            }
                        }
                        $totals['total'] = number_format($total, 2);
                        
                        // replace sale items and totals
                        $html->enableSection('sale-details',  $replaces, TRUE);
                        $html->enableSection('sale-totals',   $totals);
                    }
                    
                    $replaces2 = array();
                    $contacts = $object->getContacts();
                    if ($contacts)
                    {
                        $total = 0;
                        // iterate the customer sales
                        foreach ($contacts as $contact)
                        {
                            $replaces2[] = array('contact_id' => $contact->id,
                                                 'type'    => $contact->type,
                                                 'value'   => $contact->value);
                        }
                        $html->enableSection('contact-details',  $replaces2, TRUE);
                    }
                }
                else
                {
                    throw new Exception('Customer not found');
                }
            }
            
            TTransaction::close();
            parent::add($html);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public static function delete($param)
    {
        try
        {
            TTransaction::open('samples');
            $key = $param['key'];
            $object = new Contact($key);
            $customer_id = $object->customer_id;
            $object->delete();
            TTransaction::close();
            
            $action = new TAction(array('CustomerStatusView', 'onCheckStatus'));
            $action->setParameter('customer_id', $customer_id);
            new TMessage('info', 'Record deleted', $action);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}

