<?php
/**
 * AplicacaoHormonio Active Record
 * @author  <your-name-here>
 */
class AplicacaoHormonio extends TRecord
{
    const TABLENAME = 'aplicacaohormonio';
    const PRIMARYKEY= 'idAplicacaoHormonio';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $solucao;
    private $rep_matriz;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('idSolucao');
        parent::addAttribute('idRepMat');
        parent::addAttribute('volApliInd');
        parent::addAttribute('flagAplicacao');
    }

    
    /**
     * Method set_solucao
     * Sample of usage: $aplicacao_hormonio->solucao = $object;
     * @param $object Instance of Solucao
     */
    public function set_solucao(Solucao $object)
    {
        $this->solucao = $object;
        $this->idsolucao = $object->id;
    }
    
    /**
     * Method get_solucao
     * Sample of usage: $aplicacao_hormonio->solucao->attribute;
     * @returns Solucao instance
     */
    public function get_solucao()
    {
        // loads the associated object
        if (empty($this->solucao))
            $this->solucao = new Solucao($this->idsolucao);
    
        // returns the associated object
        return $this->solucao;
    }
    
    
    /**
     * Method set_rep_matriz
     * Sample of usage: $aplicacao_hormonio->rep_matriz = $object;
     * @param $object Instance of RepMatriz
     */
    public function set_rep_matriz(RepMatriz $object)
    {
        $this->rep_matriz = $object;
        $this->idrep_matriz = $object->id;
    }
    
    /**
     * Method get_rep_matriz
     * Sample of usage: $aplicacao_hormonio->rep_matriz->attribute;
     * @returns RepMatriz instance
     */
    public function get_rep_matriz()
    {
        // loads the associated object
        if (empty($this->rep_matriz))
            $this->rep_matriz = new RepMatriz($this->idrep_matriz);
    
        // returns the associated object
        return $this->rep_matriz;
    }
    


}
