<?php


/**
 * MultiField Widget: Takes a group of input fields and gives them the possibility to register many occurrences
 *
 * @version    2.0
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */

class TMultiCampo extends TMultiField
{
   private $contador = 0; 
    public function show()
    {
        
        $this->contador = $this->contador + 1;
         parent::show();
         var_dump($this->contador);
        
       
    }
}
