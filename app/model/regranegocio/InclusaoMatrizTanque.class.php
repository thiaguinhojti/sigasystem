<?php
/**
 * InclusaoMatrizTanque Active Record
 * @author  <your-name-here>
 */
class InclusaoMatrizTanque extends TRecord
{
    const TABLENAME = 'inclusaomatriztanque';
    const PRIMARYKEY= 'idIncMatTanque';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $matriz;
    private $tanque;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('idTanque');
        parent::addAttribute('idMatriz');
        parent::addAttribute('dataInclusao');
    }

    
    /**
     * Method set_matriz
     * Sample of usage: $inclusao_matriz_tanque->matriz = $object;
     * @param $object Instance of Matriz
     */
    public function set_matriz(Matriz $object)
    {
        $this->matriz = $object;
        $this->idMatriz = $object->id;
    }
    
    /**
     * Method get_matriz
     * Sample of usage: $inclusao_matriz_tanque->matriz->attribute;
     * @returns Matriz instance
     */
    public function get_matriz()
    {
        // loads the associated object
        if (empty($this->matriz))
            $this->matriz = new Matriz($this->idMatriz);
    
        // returns the associated object
        return $this->matriz->numeroChipMatriz;
    }
    
    
    /**
     * Method set_tanque
     * Sample of usage: $inclusao_matriz_tanque->tanque = $object;
     * @param $object Instance of Tanque
     */
   
    public function set_tanque(Tanque $object)
    {
        $this->tanque = $object;
        $this->idTanque = $object->id;
    }
    
    
    /**
     * Method get_tanque
     * Sample of usage: $inclusao_matriz_tanque->tanque->attribute;
     * @returns Tanque instance
     */
    public function get_tanque()
    {
       if (empty($this->tanque)){
            $this->tanque = new Tanque($this->idTanque);
       }
       return $this->tanque->numeroTanque;
    }
    
    


}
