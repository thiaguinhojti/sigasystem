<?php
/**
 * Soro Active Record
 * @author  <your-name-here>
 */
class Soro extends TRecord
{
    const TABLENAME = 'soro';
    const PRIMARYKEY= 'idSoro';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nomeSoro');
        parent::addAttribute('descSoro');
        parent::addAttribute('valorSoro');
    }


}
