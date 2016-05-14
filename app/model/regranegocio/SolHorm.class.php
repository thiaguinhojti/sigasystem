<?php
/**
 * SolucaoHormonio Active Record
 * @author  <your-name-here>
 */
class SolHorm extends TRecord
{
    const TABLENAME = 'solhorm';
    const PRIMARYKEY= 'idSolHorm';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('idsoro');
        parent::addAttribute('idsolucao');
        parent::addAttribute('idhormonio');
        parent::addAttribute('idsolucao');
    }


}
