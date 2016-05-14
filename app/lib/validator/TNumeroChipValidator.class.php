<?php

class TNumeroChipValidator extends TFieldValidator
{
    
    public function validate($label, $value, $parameters=NULL)
    {
        $sql = "select numeroChipMatriz from matriz where numeroChipMatriz =".$value."and status= '".$value."'";
        
        if(mysql_num_rows($sql)!=0)
        {
            throw new Exception("O $label já se encontra cadastrado no sistema!");
        }
    }

}

?>
