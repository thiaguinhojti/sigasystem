<?php
/**
 * IncubadoraReproducao Active Record
 * @author  <your-name-here>
 */
class IncRep extends TRecord
{
    const TABLENAME = 'increp';
    const PRIMARYKEY= 'idIncRep';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($idIncRep = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($idIncRep, $callObjectLoad);
        parent::addAttribute('idIncubadora');
        parent::addAttribute('idReproducao');
    }


}
