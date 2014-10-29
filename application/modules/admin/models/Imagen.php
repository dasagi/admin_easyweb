<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="imatges")
 */

class Admin_Model_Imagen //esta es la clase entidad que suele llevar los campos de la bbdd
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
        
        /** @Column(name="descripcio", type="text") */ 
       
       
        private $_descripcio;
                
        /** @Column(name="ordre", type="integer", length=3) */
        
        private $_ordre;
        
                ////////////////////ATRIBUTS REFERENCIATS D'ALTRES CLASSES /////////////////////////
        
        
        /** @OneToOne(targetEntity="Admin_Model_Entrada", cascade={"persist"})
        * @JoinColumn(name="entrada_id", referencedColumnName="id")
        */
        private $_entradaId;
        
        /** @ManyToOne(targetEntity="Admin_Model_Galeria",cascade={"persist"})
        * @JoinColumn(name="galeria_id", referencedColumnName="id")
        */
        private $_galeriaId;


       public function __construct($id,$data,$titol,$ruta,$descripcio,$galeria,$entradaId,$ordre) {
           $this->_id = $id;
           $this->_data = $data;
           $this->_titol = $titol;
           $this->_ruta = $ruta;
           $this->_descripcio = $descripcio;
           $this->_galeriaId = $galeria;
           $this->_entradaId = $entradaId;
           $this->_ordre = $ordre;
       }


        public function getId() 
	{
            return $this->_id;
	}
        
        public function getData()
        {    
            return $this->_data;
        }
	
        public function getTitol()
        {
            return $this->_titol;
        }
        
        public function getRuta()
        {
            return $this->_ruta;
        }
        
        public function getDescripcio()
        {
            return $this->_descripcio;
        }

        public function getGaleria()
        {
            return $this->_galeriaId;
        }
        
        public function getOrdre()
        {
            return $this->_ordre;
        }
        public function getEntradaId()
        {
            return $this->_entradaId;
        }
        /*public function getGaleria()
        {
            return $this->_galeria;
        }
        public function setGaleria(Admin_Model_Galeria $galeria)
        {
            if ($this->_galeria !==$galeria){
                $this->_galeria = $galeria;
                $galeria->addImagen($this);
            }  
        }
         */
}