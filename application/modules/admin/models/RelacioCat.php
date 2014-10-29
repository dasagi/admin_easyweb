<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="relacio_cat")
 */

class Admin_Model_RelacioCat //esta es la clase entidad que suele llevar los campos de la bbdd
{
    
      
    private $_entradaId;
    
    private $_etiquetaId;
    
    private $_valor;
    
    public function __construct() {
        
        $this->_entradaId = new ArrayCollection;
        $this->_etiquetaId = new ArrayCollection();
    }
    
    public function addEntrada(Admin_Model_Entrada $entrada)
    {
        $this->_entradaId->add($entrada);
        $entrada->setProducto($this);
    }

}