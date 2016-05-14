<?php
/**
 * SystemEmployee Active Record
 * @author  <your-name-here>
 */
class SystemEmployee extends TRecord
{
    const TABLENAME = 'system_employee';
    const PRIMARYKEY= 'idFuncionario';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_companys;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nomeFuncionario');
        parent::addAttribute('cpfFuncionario');
        parent::addAttribute('matricula');
        parent::addAttribute('dataNascimentoFuncionario');
        parent::addAttribute('sexo');
        parent::addAttribute('telefone');
        parent::addAttribute('idEmpresa');
    }

    
    /**
     * Method addSystemCompany
     * Add a SystemCompany to the SystemEmployee
     * @param $object Instance of SystemCompany
     */
    public function addSystemCompany(SystemCompany $object)
    {
        $this->system_companys[] = $object;
    }
    
    /**
     * Method getSystemCompanys
     * Return the SystemEmployee' SystemCompany's
     * @return Collection of SystemCompany
     */
    public function getSystemCompanys()
    {
        return $this->system_companys;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->system_companys = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
        $this->system_companys = parent::loadComposite('SystemCompany', 'idFuncionario', $id);
    
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
    
        parent::saveComposite('SystemCompany', 'idFuncionario', $this->id, $this->system_companys);
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        parent::deleteComposite('SystemCompany', 'idFuncionario', $id);
    
        // delete the object itself
        parent::delete($id);
    }


}
