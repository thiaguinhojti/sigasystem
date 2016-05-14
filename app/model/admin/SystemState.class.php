<?php
/**
 * SystemState Active Record
 * @author  <your-name-here>
 */
class SystemState extends TRecord
{
    const TABLENAME = 'system_state';
    const PRIMARYKEY= 'idState';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_citys;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('uf');
        parent::addAttribute('nome');
    }

    
    /**
     * Method addSystemCity
     * Add a SystemCity to the SystemState
     * @param $object Instance of SystemCity
     */
    public function addSystemCity(SystemCity $object)
    {
        $this->system_citys[] = $object;
    }
    
    /**
     * Method getSystemCitys
     * Return the SystemState' SystemCity's
     * @return Collection of SystemCity
     */
    public function getSystemCitys()
    {
        return $this->system_citys;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->system_citys = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->system_citys = parent::loadComposite('SystemCity', 'estado', $id);
    
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
    
        parent::saveComposite('SystemCity', 'estado', $this->id, $this->system_citys);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('SystemCity', 'estado', $id);
    
        // delete the object itself
        parent::delete($id);
    }


}
