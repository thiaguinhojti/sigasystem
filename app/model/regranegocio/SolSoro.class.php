<?php
/**
 * SolucaoSoro Active Record
 * @author  <your-name-here>
 */
class SolSoro extends TRecord
{
    const TABLENAME = 'solsoro';
    const PRIMARYKEY= 'idSolSoro';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('idsoro');
        parent::addAttribute('idsolucao');
    }


}
