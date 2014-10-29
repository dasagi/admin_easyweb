<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="menus_zona")
 */

class Admin_Model_MenuZona //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="id", type="integer")
        * @GeneratedValue(strategy="AUTO")
        */
        private $_id;      
        
        /** @Column(name="nom", type="string", length=255) */
        private $_nom;        
        
        public function __construct($id,$nom) {
            
            $this->_id = $id;
            $this->_nom = $nom;
            
        }
        
        public function getId() 
	{
            return $this->_id;
	}

        
        public function getNom(){
            return $this->_nom;
        }

}