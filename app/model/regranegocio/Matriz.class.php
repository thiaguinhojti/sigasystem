<?php
/**
 * Matriz Active Record
 * @author  <your-name-here>
 */
class Matriz extends TRecord
{
    const TABLENAME = 'matriz';
    const PRIMARYKEY= 'idMatriz';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $especies;
    private $tanque;

    /**
     * Constructor method
     */
    public function __construct($idMatriz = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($idMatriz, $callObjectLoad);
        parent::addAttribute('numeroChipMatriz');
        parent::addAttribute('pesoMatriz');
        parent::addAttribute('compCabecaMatriz');
        parent::addAttribute('compParcialMatriz');
        parent::addAttribute('compTotalMatriz');
        parent::addAttribute('sexoMatriz');
        parent::addAttribute('larguraMatriz');
        parent::addAttribute('idEspecie');
        parent::addAttribute('dataCadastro');
        parent::addAttribute('status');
        parent::addAttribute('photo_path');
    }

    
    /**
     * Method addEspecie
     * Add a Especie to the Matriz
     * @param $object Instance of Especie
     */
    public function addEspecie(Especie $object)
    {
        $this->especies[] = $object;
    }
    
    /**
     * Method getEspecies
     * Return the Matriz' Especie's
     * @return Collection of Especie
     */
    public function getEspecies()
    {
        return $this->especies;
    }
    public function get_especies_descricao(){
    
        if(empty($this->especies)){
            $this->especies = new Especie($this->idEspecie);
        }
        return $this->especies->nomePopularEspecie;
    
    }
    
    /**
     * Method set_tanque
     * Sample of usage: $matriz->tanque = $object;
     * @param $object Instance of Tanque
     */
    public function set_tanque(Tanque $object)
    {
        $this->tanque = $object;
        $this->idtanque = $object->id;
    }
    
    /**
     * Method get_tanque
     * Sample of usage: $matriz->tanque->attribute;
     * @returns Tanque instance
     */
    public function get_tanque()
    {
        // loads the associated object
        if (empty($this->tanque))
            $this->tanque = new Tanque($this->idtanque);
    
        // returns the associated object
        return $this->tanque;
    }
    

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->especies = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Especie objects
        $repository = new TRepository('Especie');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idEspecie', '=', $id));
        $this->especies = $repository->load($criteria);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        // delete the related Especie objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idEspecie', '=', $this->id));
        $repository = new TRepository('Especie');
        $repository->delete($criteria);
        // store the related Especie objects
        if ($this->especies)
        {
            foreach ($this->especies as $especie)
            {
                unset($especie->id);
                $especie->idMatriz = $this->id;
                $especie->store();
            }
        }
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        // delete the related Especie objects
        $repository = new TRepository('Especie');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idEspecie', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
