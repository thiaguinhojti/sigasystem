<?php
/**
 * SystemCity Active Record
 * @author  <your-name-here>
 */
class SystemCity extends TRecord
{
    const TABLENAME = 'system_city';
    const PRIMARYKEY= 'idCity';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('estado');
        parent::addAttribute('uf');
        parent::addAttribute('nome');
    }


}
