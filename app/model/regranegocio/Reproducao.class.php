<?php
/**
 * Reproducao Active Record
 * @author  <your-name-here>
 */
 

class Reproducao extends TRecord
{
    const TABLENAME = 'reproducao';
    const PRIMARYKEY= 'idReproducao';
    const IDPOLICY =  'max'; // {max, serial}
    
    private $incubadoras;
    private $repmatrizes = array();
    private $pesoMatMacho = 0;
    private $pesoMatFemea = 0;
    private $qtdeFemea = 0;
    private $qtdeMacho = 0;
    private $pesoTotalMatriz = 0;
    
    /**
     * Constructor method
     */
    public function __construct($idReproducao = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($idReproducao, $callObjectLoad);
        parent::addAttribute('codigo');
        parent::addAttribute('dataInicioReproducao');
        parent::addAttribute('temperatura');
        parent::addAttribute('equipeReproducao');
        parent::addAttribute('climaDia');
        parent::addAttribute('pesoTotMatFemea');
        parent::addAttribute('pesoTotMatMacho');
        parent::addAttribute('qtdeMatFemea');
        parent::addAttribute('qtdeMatMacho');
        parent::addAttribute('pesoGeralMatriz');
        parent::addAttribute('totalGeralHormonio');
        parent::addAttribute('dataFinalReproducao');
        parent::addAttribute('txEclosao');
        parent::addAttribute('txFecundacao');
    }
    public function get_pesotMatMacho(){
    
        return $this->pesoMatMacho;
    
    }
    public function get_pesoMatFemea(){
    
        return $this->pesoMatFemea;
    
    }
    public function get_qtdeFemea(){
    
        return $this->qtdeFemea;
    
    }
    public function get_qtdeMacho(){
    
        $this->qtdeMacho;
    
    }
    public function get_pesoTotalMatriz(){
    
       return $this->pesoTotalMatriz; 
    
    }
    public function clearParts()
    {
        $this->incubadoras = array();
        $this->matrizes = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID*/
    public function addIncubadora(Incubadora $incubadora)
    {
        $this->incubadoras[]= $incubadora;
        
    }
    
    
    /**
     * Method getIncubadora
     * Return the Incubadora' Reproducao's
     * @return Collection of Reproducao
     */
    public function getIncubadoras()
    {
        $reproducao_incubadoras = array();
        
        // load the related IncRep objects
        $repository = new TRepository('IncRep');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idReproducao', '=', $this->idReproducao));
        $reproducao_incubadoras = $repository->load($criteria);
        $incubadoras_reproducao = array();
                if ($reproducao_incubadoras)
        {
            foreach ($reproducao_incubadoras as $reproducao_incubadora)
            {
                $incubadoras_reproducao[] = new Incubadora( $reproducao_incubadora->idIncubadora );
            }
        }
        return $incubadoras_reproducao;
        //return $this->incubadoras;
        
             
    }
    public function getIncRepDescIncubadora()
    {
    
        $incRepDescIncubadoras = array();
        $incubadoras = $this->getIncubadoras();
        var_dump($incubadoras);
        if($incubadoras){
            foreach($incubadoras as $incubadora){
            
                $incRepDescIncubadoras[] = $incubadora->descIncubadora;
            
            }
        }
        return implode(',',$incRepDescIncubadoras);
    
    }

     
    public function load($id)
    {
        $reproducao_incubadoras = array();
        $reproducao_matrizes = array();    
        $repository = new TRepository('IncRep');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idReproducao','=',$this->idReproducao));
        $reproducao_incubadoras = $repository->load($criteria);
        if($reproducao_incubadoras){
        
            foreach($reproducao_incubadoras as $reproducao_incubadora){
            
                $incubadora = new Incubadora($reproducao_incubadora->idIncubadora);
                $this->addIncubadora($incubadora);
            }
        
        }
     return parent::load($id);   
    }
    
        //$this->incubadoras = parent::loadAggregate('Incubadora', 'IncRep', 'idReproducao', 'idIncubadora', $id);
        // load the object itself
        
    

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('idReproducao', '=', $this->idReproducao));
        
        $repository = new TRepository('IncRep');
        $repository->delete($criteria);
        
        $repository1 = new TRepository('RepMatriz');
        $repository1->delete($criteria);
        // store the related CustomerSkill objects
        if ($this->incubadoras)
        {
            foreach ($this->incubadoras as $incubadora)
            {
                $reproducao_incubadora = new IncRep;
                $reproducao_incubadora->idIncubadora = $incubadora->idIncubadora;
                $reproducao_incubadora->idReproducao = $this->idReproducao;
                $reproducao_incubadora->store();
            }
        }
        if($this->repmatrizes)
        {
                
            foreach($this->repmatrizes as $repmatriz)
            {
                $reproducao_matriz = new RepMatriz;
                $reproducao_matriz->idReproducao = $this->idReproducao;
                $reproducao_matriz->idMatriz = $repmatriz->matriz->idMatriz;
                $reproducao_matriz->pesoMatriz = $repmatriz->pesoMatriz;
                $reproducao_matriz->identMatriz = $repmatriz->identMatriz; 
                $reproducao_matriz->store();
            
            }
        
        
        }
        
       // parent::saveAggregate('IncRep','idReproducao', 'idIncubadora', $this->idReproducao, $this->incubadoras);
       
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->idReproducao;
        parent::deleteComposite('IncRep', 'idReproducao', $id);
    
        // delete the object itself
        parent::delete($id);
    }
    public function calcular(Reproducao $obj){
        
        try{
        
            $this->pesoTotMatMacho = $obj->pesoTotMatFemea;
            $this->pesoTotMatFemea = $obj->pesoTotMatFemea;
            $this->qtdeMatFemea = $obj->qtdeMatFemea;
            $this->qtdeMatMacho = $obj->qtdeMatMacho;
            $this->pesoGeralMatriz = $obj->pesoGeralMatriz;
            TTransaction::open('dbwf');
            $repositorio = new TRepository('RepMatriz');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('idReproducao','=',$this->idReproducao));
            $rep_matrizes = array();
            $rep_matrizes = $repositorio->load($criteria);
            if($rep_matrizes){
                foreach($rep_matrizes as $result){
                
                    $matriz = new Matriz($result->matriz->idMatriz);
                    
                    if($matriz->sexoMatriz=='M'){
                    
                        $this->pesoTotMatMacho += $matriz->pesoMatriz;
                        $this->qtdeMatMacho++;
                    }
                    else{
                        
                        $this->pesoTotMatFemea += $matriz->pesoMatriz;
                        $this->qtdeMatFemea++;    
                            
                    }
                    
                }
            }
            $this->pesoGeralMatriz = $this->pesoTotMatFemea + $this->pesoTotMatMacho;
            TTransaction::close();
        }
        catch(Exception $e){
         
         new TMessage('ERRO AO GRAVAR O REGISTRO',$e->getMessage());    
        
        }
        
    }
    
    public function set_repMatriz(RepMatriz $reproducao_matriz)
    {
    
        $this->repmatrizes[] = $reproducao_matriz;
       
    }
    public function get_repMatriz()
    {
        
        if(empty($this->repmatrizes))
        {
            $reproducao_matriz = array();
            TTransaction::setLogger(new TLoggerTXT('C:\carga-log.txt'));
            TTransaction::log('Inserir Reproducao ');
            $repository = new TRepository('RepMatriz');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('idReproducao', '=', $this->idReproducao));
            $reproducao_repMatrizes = $repository->load($criteria);
            //var_dump($reproducao_repMatrizes);
            if($reproducao_repMatrizes)
            {
                 
                foreach($reproducao_repMatrizes as $reproducao_repMatriz)
                {
                   
                    $repMatriz = $reproducao_repMatriz;
                   // TTransaction::setLogger(new TLoggerTXT('C:\carga-log.txt'));
                    //TTransaction::log('Inserir Reproducao ');
                    $repMatriz->matriz = new Matriz($repMatriz->idMatriz);
                    $this->repmatrizes[] = $repMatriz;
                    //var_dump($repMatriz);
                }
               // var_dump($this->repmatrizes);
            } 
        
        }
        
        return $this->repmatrizes;
         
    }
    public function checkInIncubadora( Incubadora $incubadora )
    {
        $reproducao_incubadoras = array();
        foreach( $this->getIncubadoras() as $incubadora )
        {
            $reproducao_incubadoras[] = $incubadora->idIncubadora;
        }
    
        return in_array($incubadora->idIncubadora, $reproducao_incubadoras);
    }
      

}
