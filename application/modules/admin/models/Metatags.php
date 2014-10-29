<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="metatags")
 */

class Admin_Model_Metatags //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="id", type="integer")
        * @GeneratedValue(strategy="AUTO")
        */
        private $_id;
        /** @Column(name="tittle", type="string", length=255) */
        private $_tittle;
        /** @Column(name="description", type="text") */
        private $_description;
         /** @Column(name="keywords", type="text") */
        private $_keywords;
        /** @Column(name="robots", type="string", length=10) */
        private $_robots;
        /** @Column(name="autor", type="string", length=180) */
        private $_autor;

        /** @Column(name="idioma", type="string", length=2) */
        private $_idioma;
        
        ////////////////////ATRIBUTS REFERENCIATS D'ALTRES CLASSES /////////////////////////
        
        
        /** @OneToOne(targetEntity="Admin_Model_Entrada", cascade={"persist", "remove"})
        * @JoinColumn(name="entrada_id", referencedColumnName="id")
        */
        private $_entradaId;
        
        /** @OneToOne(targetEntity="Admin_Model_Galeria", cascade={"persist", "remove"})
        * @JoinColumn(name="galeria_id", referencedColumnName="id")
        */
        private $_galeriaId;
        
        public function __construct($id,$tittle,$description,$keywords,$robots,$autor,$idioma,$entradaId,$galeriaId) {
            
            $this->_id = $id;
            $this->_tittle = $tittle;
            $this->_description = $description;
            $this->_keywords = $keywords;
            $this->_robots = $robots;
            $this->_autor = $autor;
            $this->_idioma = $idioma;
            $this->_entradaId = $entradaId;
            $this->_galeriaId = $galeriaId;
            
        }
        
        public function getId() 
	{
            return $this->_id;
	}

        
        public function getTittle(){
            
            return $this->_tittle;
        }
        public function getDescription()
        {
            return $this->_description;
        }
        
        public function getKeywords()
        {
            return $this->_keywords;
        }
        
        public function getRobots()
        {
            return $this->_robots;
        }
        
        
        public function getAutor()
        {
            return $this->_autor;
        }
        
        
        public function getIdioma()
        {
            return $this->_idioma;
        }
        
        public function getEntradaId()
        {
            return $this->_entradaId;
        }
        
        public function getGaleriaId()
        {
            return $this->_galeriaId;
        }
}