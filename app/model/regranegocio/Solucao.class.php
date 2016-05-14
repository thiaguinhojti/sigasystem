<?php
/**
 * Solucao Active Record
 * @author  <your-name-here>
 */
class Solucao extends TRecord
{
    const TABLENAME = 'solucao';
    const PRIMARYKEY= 'idSolucao';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $soros;
    private $hormonios;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('totalSoro');
        parent::addAttribute('totalHipofise');
        parent::addAttribute('pVolTotalAplicado');
        parent::addAttribute('sVolTotalAplicado');
        parent::addAttribute('idReproducao');
    }

    
    /**
     * Method addSoro
     * Add a Soro to the Solucao
     * @param $object Instance of Soro
     */
    public function addSoro(Soro $object)
    {
        $this->soros[] = $object;
    }
    
    /**
     * Method getSoros
     * Return the Solucao' Soro's
     * @return Collection of Soro
     */
    public function getSoros()
    {
        return $this->soros;
    }
    
    /**
     * Method addHormonio
     * Add a Hormonio to the Solucao
     * @param $object Instance of Hormonio
     */
    public function addHormonio(Hormonio $object)
    {
        $this->hormonios[] = $object;
    }
    
    /**
     * Method getHormonios
     * Return the Solucao' Hormonio's
     * @return Collection of Hormonio
     */
    public function getHormonios()
    {
        return $this->hormonios;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->soros = array();
        $this->hormonios = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Soro objects
        $repository = new TRepository('SolucaoSoro');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $solucao_soros = $repository->load($criteria);
        if ($solucao_soros)
        {
            foreach ($solucao_soros as $solucao_soro)
            {
                $soro = new Soro( $solucao_soro->idsoro );
                $this->addSoro($soro);
            }
        }
    
        // load the related Hormonio objects
        $repository = new TRepository('SolucaoHormonio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $solucao_hormonios = $repository->load($criteria);
        if ($solucao_hormonios)
        {
            foreach ($solucao_hormonios as $solucao_hormonio)
            {
                $hormonio = new Hormonio( $solucao_hormonio->idhormonio );
                $this->addHormonio($hormonio);
            }
        }
    
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
    
        // delete the related SolucaoSoro objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $this->id));
        $repository = new TRepository('SolucaoSoro');
        $repository->delete($criteria);
        // store the related SolucaoSoro objects
        if ($this->soros)
        {
            foreach ($this->soros as $soro)
            {
                $solucao_soro = new SolucaoSoro;
                $solucao_soro->idsoro = $soro->id;
                $solucao_soro->idsolucao = $this->id;
                $solucao_soro->store();
            }
        }
        // delete the related SolucaoHormonio objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $this->id));
        $repository = new TRepository('SolucaoHormonio');
        $repository->delete($criteria);
        // store the related SolucaoHormonio objects
        if ($this->hormonios)
        {
            foreach ($this->hormonios as $hormonio)
            {
                $solucao_hormonio = new SolucaoHormonio;
                $solucao_hormonio->idhormonio = $hormonio->id;
                $solucao_hormonio->idsolucao = $this->id;
                $solucao_hormonio->store();
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
        // delete the related SolucaoSoro objects
        $repository = new TRepository('SolucaoSoro');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $repository->delete($criteria);
        
        // delete the related SolucaoHormonio objects
        $repository = new TRepository('SolucaoHormonio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idsolucao', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
