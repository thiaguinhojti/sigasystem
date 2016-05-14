<?php
/**
 * FamiliaEspecie Active Record
 * @author  <your-name-here>
 */
class FamiliaEspecie extends TRecord
{
    const TABLENAME = 'familiaespecie';
    const PRIMARYKEY= 'idFamiliaEspecie';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricaoFamilia');
    }


}
