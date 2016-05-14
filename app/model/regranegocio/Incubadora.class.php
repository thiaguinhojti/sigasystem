<?php
/**
 * Incubadora Active Record
 * @author  <your-name-here>
 */
class Incubadora extends TRecord
{
    const TABLENAME = 'incubadora';
    const PRIMARYKEY= 'idIncubadora';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $reproducaos;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descIncubadora');
    }

    
    
    

}
