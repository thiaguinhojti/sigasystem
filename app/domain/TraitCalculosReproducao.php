<?php
namespace App\Domain;

use Exception;

trait TraitCalculosReproducao

{
    public function pesoMatrizMacho($object)
    {
       $totalMacho = 0;
       TTransaction::open($this->database);
       $criteria = new TCriteria;
       $criteria->add(new TFilter('sexoMatriz','=','M'));
       $matrizes = Matriz::getObjects($criteria);
       foreach($matrizes as $matriz)
       {
           $totalMacho += $matriz->pesoMatriz;
       }
       
       return $this->totalMacho;
    
    }

}

?>