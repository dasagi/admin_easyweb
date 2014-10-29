<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="idiomes")
 */

class Admin_Model_Idioma //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="codi", type="string", length=2)
        * @GeneratedValue(strategy="AUTO")
        */
        //private $_id;
        
        
        private $_codi;
        
        /** @Column(name="nom", type="string", length=20) */
        private $_nom;
        
        public function __construct($codi,$nom) {
            
            $this->_codi = $codi;
            $this->_nom = $nom;
        }
        public function getCodi(){
            
            return $this->_codi;
        }
        
        public function getNom(){
            
            return $this->_nom;
        }

}