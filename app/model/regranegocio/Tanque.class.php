<?php
/**
 * Tanque Active Record
 * @author  <your-name-here>
 */
class Tanque extends TRecord
{
    const TABLENAME = 'tanque';
    const PRIMARYKEY= 'idTanque';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numeroTanque');
        parent::addAttribute('areaMetroQuadradoTanque');
        parent::addAttribute('profMediaTanque');
        parent::addAttribute('VolumeAcMediaTanque');
        parent::addAttribute('tipoTanque');
    }


}
