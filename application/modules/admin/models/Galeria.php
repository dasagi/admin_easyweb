<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="galeries")
 */

class Admin_Model_Galeria //esta es la clase entidad que suele llevar los campos de la bbdd
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
         /** @Column(name="descripcio", type="text") */
        private $_descripcio;
        /** @Column(name="idioma", type="string", length=2) */
        private $_idioma;
        /** @Column(name="publicar", type="integer", length=1) */
        private $_publicar;   
        /** @Column(name="tipo", type="integer", length=1) */  
        private $_tipo;
        ////////////////////ATRIBUTS REFERENCIATS D'ALTRES CLASSES /////////////////////////
        
        /**
        * @OneToOne(targetEntity="Admin_Model_Metatags", mappedBy="_galeriaId", cascade={"persist", "remove"})
        */
        private $_metas;
        
        /**
        * @OneToMany(targetEntity="Admin_Model_Imagen", mappedBy="_galeriaId", cascade={"persist"})
        */
        private $_imatges;
        
        public function __construct($id,$data,$titol,$descripcio,$idioma,$publicar,$tipo) {
            
            $this->_id = $id;
            $this->_data = $data;
            $this->_titol = $titol;
            $this->_descripcio = $descripcio;
            $this->_idioma = $idioma;
            $this->_publicar = $publicar;
            $this->_imatges = new ArrayCollection();
            $this->_tipo = $tipo;
        }
        
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
        
        public function getDescripcio()
        {
            return $this->_descripcio;
        }
                
        public function getIdioma()
        {
            return $this->_idioma;
        }
                
        public function getPublicar()
        {
            return $this->_publicar;
        }
        
        public function getImagen()
        {
            return $this->_imatges;
        }
        
        public function getMetas()
        {
            return $this->_metas;
        }
        public function addImagen(Admin_Model_Imagen $imagen)
        {
            $this->_imatges->add($imagen);
        }
        public function getTipo(){
            return $this->_tipo;
        }
}