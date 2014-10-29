<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="documents")
 */

class Admin_Model_Documento //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="id", type="integer")
        * @GeneratedValue(strategy="AUTO")
        */
        private $_id;
        /** @Column(name="data", type="datetime") */
        private $_data;
        /** @Column(name="titol", type="string", length=255) */
        private $_titol;
        /** @Column(name="ruta", type="string", length=255) */
        private $_ruta;
        
        
        /** @OneToOne(targetEntity="Admin_Model_Entrada", cascade={"persist"})
        * @JoinColumn(name="entrada_id", referencedColumnName="id")
        */
        private $_entradaId;
        
        public function __construct($id,$data,$titol,$ruta,$entradaId) {
            
            $this->_id = $id;
            $this->_data = $data;
            $this->_titol = $titol;
            $this->_ruta = $ruta;
            $this->_entradaId = $entradaId;
        }
        
        /*public function __construct($id){
            
            $this->_id = $id;
        }
        */
       	public function getId() 
	{
		return $this->_id;
	}
        
        public function getData(){
            
            return $this->_data;
        }
        
	public function getTitol()
	{
		return $this->_titol;
	}

        
        public function getRuta(){
            
            return $this->_ruta;
        }
        public function getEntradaId()
        {
            return $this->_entradaId;
        }
}

