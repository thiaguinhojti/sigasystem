<?php
/**
 * Especie Active Record
 * @author  <your-name-here>
 */
class Especie extends TRecord
{
    const TABLENAME = 'especie';
    const PRIMARYKEY= 'idEspecie';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $familia_especie;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nomePopularEspecie');
        parent::addAttribute('nomeCientificoEspecie');
        parent::addAttribute('tamanhoMaximo');
        parent::addAttribute('horaGrauInicioReproducao');
        parent::addAttribute('qtdeSoroKgPv1');
        parent::addAttribute('qtdeSoroKgPv2');
        parent::addAttribute('QtdeMaximaAplicacoes');
        parent::addAttribute('idFamiliaEspecie');
        parent::addAttribute('tipoEspecie');
    }

    
    /**
     * Method set_familia_especie
     * Sample of usage: $especie->familia_especie = $object;
     * @param $object Instance of FamiliaEspecie
     */
    public function set_familia_especie(FamiliaEspecie $object)
    {
        $this->familia_especie = $object;
        $this->idfamilia_especie = $object->id;
    }
    
    /**
     * Method get_familia_especie
     * Sample of usage: $especie->familia_especie->attribute;
     * @returns FamiliaEspecie instance
     */
   
   	public function get_familia_especie_descricao(){
		
			if(empty($this->familia_especie)){
				
				$this->familia_especie = new FamiliaEspecie($this->idFamiliaEspecie);
			}
			return $this->familia_especie->descricaoFamilia;
	}
    


}
