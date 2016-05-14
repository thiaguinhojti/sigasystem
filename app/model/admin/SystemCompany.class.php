<?php
/**
 * SystemCompany Active Record
 * @author  <your-name-here>
 */
class SystemCompany extends TRecord
{
    const TABLENAME = 'system_company';
    const PRIMARYKEY= 'idEmpresa';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_city;
    private $system_state;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razaoSocial');
        parent::addAttribute('nomeFantasia');
        parent::addAttribute('cnpjEmpresa');
        parent::addAttribute('inscEstadualEmpresa');
        parent::addAttribute('telefoneEmpresa');
        parent::addAttribute('emailEmpresa');
        parent::addAttribute('enderecoEmpresa');
        parent::addAttribute('siteEmpresa');
        parent::addAttribute('bairroEmpresa');
        parent::addAttribute('idEstado');
        parent::addAttribute('idCidade');
        parent::addAttribute('cepEmpresa');
        parent::addAttribute('responsavelEmpresa');
    }

    
    /**
     * Method set_system_city
     * Sample of usage: $system_company->system_city = $object;
     * @param $object Instance of SystemCity
     */
    public function set_system_city(SystemCity $object)
    {
        $this->system_city = $object;
        $this->idsystem_city = $object->id;
    }
    
    /**
     * Method get_system_city
     * Sample of usage: $system_company->system_city->attribute;
     * @returns SystemCity instance
     */
    public function get_system_city()
    {
        // loads the associated object
        if (empty($this->system_city))
            $this->system_city = new SystemCity($this->idsystem_city);
    
        // returns the associated object
        return $this->system_city;
    }
    
    
    /**
     * Method set_system_state
     * Sample of usage: $system_company->system_state = $object;
     * @param $object Instance of SystemState
     */
    public function set_system_state(SystemState $object)
    {
        $this->system_state = $object;
        $this->idsystem_state = $object->id;
    }
    
    /**
     * Method get_system_state
     * Sample of usage: $system_company->system_state->attribute;
     * @returns SystemState instance
     */
    public function get_system_state()
    {
        // loads the associated object
        if (empty($this->system_state))
            $this->system_state = new SystemState($this->idsystem_state);
    
        // returns the associated object
        return $this->system_state;
    }
    


}
