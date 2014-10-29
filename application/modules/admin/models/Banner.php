<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="banners")
 */

class Admin_Model_Banner //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="id", type="integer")
        * @GeneratedValue(strategy="AUTO")
        */
        private $_id;
        
        /** @Column(name="posicio", type="integer")*/
        private $_posicio;        
        
        /** @Column(name="titol", type="string", length=255) */
        private $_titol;
        
        /** @Column(name="data", type="datetime") */
        private $_data;
        
        /** @Column(name="ruta", type="string", length=255) */ 
        private $_ruta;
        
        /** @Column(name="actiu",  type="integer") */ 
        private $_actiu;
        
        /** @Column(name="link", type="string", length=255) */ 
        private $_link;
        
        /** @ManyToOne(targetEntity="Admin_Model_Imagen")
        * @JoinColumn(name="imatge_id", referencedColumnName="id")
        */
        private $_imatgeId;

        /**
        * @OneToMany(targetEntity="Admin_Model_Imagen", mappedBy="_id")
        */
        private $_imatges;
        
        public function __construct() {
            $this->_imatges = new ArrayCollection();
        }
        
        public function getId() 
	{
            return $this->_id;
	}

        public function setId($id)
        { 
            $this->_id = $id;
        }
        
        public function getPosicio(){
            
            return $this->_posicio;
        }
        
        public function setPosicio($posicio){
            
            $this->_posicio = $posicio;
        }


        public function getData(){
            
            return $this->_data;
        }
	
        public function setData($data){
            
            $this->_data=$data;
        }
        public function getTitol()
        {
            return $this->_titol;
        }
        public function setTitol($titol)
        {
            $this->_titol = $titol;
        }
        
        public function getRuta()
        {
            return $this->_ruta;
        }
        public function setRuta($ruta)
        {
            $this->_titol = $ruta;
        }        
        
        public function getActiu()
        {
            return $this->_actiu;
        }
        public function setActiu($actiu)
        {
            $this->_actiu = $actiu;
        }
        
        public function getLink()
        {
            return $this->_link;
        }
        
        public function setLink($link)
        {
            $this->_link = $link;
        }

        public function getImatgeId()
        {
            return $this->_imatgeId;
        }
        
        public function setImatgeId($imatgeId)
        {
            $this->_imatgeId = $imatgeId;
        }
        public function getImatges()
        {
            return $this->_imatges;
        }
        public function addImatge(Admin_Model_Imagen $imatge)
        {
            $this->_imatges->add($imatge);
            $imatge->setId($this);
        }
}