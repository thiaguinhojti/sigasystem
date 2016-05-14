<?php
/**
 * RepMatriz Active Record
 * @author  <your-name-here>
 */
class RepMatriz extends TRecord
{
    const TABLENAME = 'repmatriz';
    const PRIMARYKEY= 'idRepMat';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $matriz;
    private $reproducao;
    private $sexoMatriz;
    private $numeroChipMatriz;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('identMatriz');
        parent::addAttribute('pesoMatriz');
        parent::addAttribute('idMatriz');
        parent::addAttribute('idReproducao');
    }

    
    /**
     * Method set_matriz
     * Sample of usage: $rep_matriz->matriz = $object;
     * @param $object Instance of Matriz
     */
    public function set_matriz(Matriz $object)
    {
        $this->matriz = $object;
        $this->idmatriz = $object->id;
    }
    
    /**
     * Method get_matriz
     * Sample of usage: $rep_matriz->matriz->attribute;
     * @returns Matriz instance
     */
    public function get_matriz()
    {
        // loads the associated object
        if (empty($this->matriz))
        {
            $this->matriz = new Matriz($this->idMatriz);
            $this->numeroChipMatriz = $this->matriz->numeroChipMatriz;
            $this->sexoMatriz = $this->matriz->sexoMatriz;
        }
        // returns the associated object
        return $this->matriz;
    }
    public function set_numeroChipMatriz($numeroChip)    
        
    {
        $this->numeroChipMatriz = $numeroChip;
    
    }
    public function set_sexoMatriz($sexo)    
        
    {
        $this->sexoMatriz = $sexo;
    
    }
    public function get_numeroChipMatriz()
    {
        return $this->numeroChipMatriz;
    }
    public function get_sexoMatriz()
    {
        return $this->sexoMatriz;
    }
    
        
    /**
     * Method set_reproducao
     * Sample of usage: $rep_matriz->reproducao = $object;
     * @param $object Instance of Reproducao
     */
    


}
