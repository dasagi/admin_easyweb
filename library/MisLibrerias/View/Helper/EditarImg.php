<?php
class MisLibrerias_View_Helper_EditarImg extends Zend_View_Helper_Abstract{

        
    public function editarImg($imatges)
    {
        foreach ($imatges as $imatge){
            
            return $imatge->getId();              
        }
    }    
}