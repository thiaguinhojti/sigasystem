<?php
/**
 * Hormonio Active Record
 * @author  <your-name-here>
 */
class Hormonio extends TRecord
{
    const TABLENAME = 'hormonio';
    const PRIMARYKEY= 'idHormonio';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nomeHormonio');
        parent::addAttribute('descHormonio');
        parent::addAttribute('valorHormonio');
    }


}
