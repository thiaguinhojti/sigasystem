<?php
/**
 * Horagrau Active Record
 * @author  <your-name-here>
 */
class HoraGrau extends TRecord
{
    const TABLENAME = 'horagrau';
    const PRIMARYKEY= 'idHoraGrau';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $reproducao;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('faseLua');
        parent::addAttribute('climaDia');
        parent::addAttribute('horaMedida');
        parent::addAttribute('temperaturaMedida');
        parent::addAttribute('mediaTemperatura');
        parent::addAttribute('horaGrauCalculada');
        parent::addAttribute('idReproducao');
    }

    
    /**
     * Method set_reproducao
     * Sample of usage: $horagrau->reproducao = $object;
     * @param $object Instance of Reproducao
     */
    public function set_reproducao(Reproducao $object)
    {
        $this->reproducao = $object;
        $this->idreproducao = $object->id;
    }
    
    /**
     * Method get_reproducao
     * Sample of usage: $horagrau->reproducao->attribute;
     * @returns Reproducao instance
     */
    public function get_reproducao()
    {
        // loads the associated object
        if (empty($this->reproducao))
            $this->reproducao = new Reproducao($this->idreproducao);
    
        // returns the associated object
        return $this->reproducao;
    }
    


}
