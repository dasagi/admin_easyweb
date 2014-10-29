<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="menus")
 */

class Admin_Model_Menu //esta es la clase entidad que suele llevar los campos de la bbdd
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
        
        /** @Column(name="url", type="string", length=255) */ 
        private $_url;
        
        /** @OneToOne(targetEntity="Admin_Model_Entrada", cascade={"persist"})
        * @JoinColumn(name="entrada_id", referencedColumnName="id")
        */
        /*
         @column(name="entrada_id", type="integer", length=11)
         */
        private $_entradaId;
        
        /* @OneToOne(targetEntity="Admin_Model_Galeria", cascade={"persist"})
        * @JoinColumn(name="galeria_id", referencedColumnName="id")
        */
        /**
         @column(name="galeria_id", type="integer", length=11) 
         */
        private $_galeriaId;
        
        /*
        * @OneToOne(targetEntity="Admin_Model_MenuZona", mappedBy="_id", cascade={"persist"})
        */
        /**
         @column(name="menu_zonas_id", type="integer", length=11) 
         */
        private $_menuZonasId;
        
        /** @ManyToOne(targetEntity="Admin_Model_Menu")
        * @JoinColumn(name="menu_parent", referencedColumnName="id")
        */ 
        /*
         @column(name="menu_parent", type="integer", length=11) 
         */
        private $_menuParent;
        
        /** @Column(name="publicar", type="integer", length=1) */
        private $_publicar;
        
        /** @Column(name="idioma", type="string", length=2) */
        private $_idioma;
        
        /** @Column(name="ordre", type="integer", length=2) */
        private $_ordre;
        
        /** @Column(name="predet", type="integer", length=1) */
        private $_predet;
        
        ////////////////////ATRIBUTS REFERENCIATS D'ALTRES CLASSES /////////////////////////
        
        /**
        * @OneToMany(targetEntity="Admin_Model_Menu", mappedBy="_menuParent", cascade={"persist"    })
        */
        private $_subMenu;
        
        public function __construct($id,$data,$titol,$url,$entradaId,$galeriaId,$menuZonasId,$menuParent,$publicar,$idioma,$ordre,$predet) {
            
           $this->_id = $id;
           $this->_data = $data;
           $this->_titol = $titol;
           $this->_url = $url;
           $this->_entradaId = $entradaId;
           $this->_galeriaId = $galeriaId;
           $this->_menuZonasId = $menuZonasId;
           $this->_menuParent = $menuParent;
           $this->_publicar = $publicar;
           $this->_idioma = $idioma;
           $this->_ordre = $ordre;
           $this->_predet = $predet;
           $this->_subMenu = new ArrayCollection();
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
        
        public function getUrl()
        {
            return $this->_url;
        }
        
        public function getEntradaId()
        {
            return $this->_entradaId;
        }
        
        public function getZonasId()
        {       
            return $this->_menuZonasId;
        }
        
        public function getParent()
        {
            return $this->_menuParent;
        }
        
        public function getPublicar(){
            return $this->_publicar;
        }
        
        public function getIdioma(){
            return $this->_idioma;
        }
        
        public function getOrdre(){
            return $this->_ordre;
        }
                
        public function getPredet(){
            return $this->_predet;
        }
        
        public function getSubMenus()
        {
            return $this->_subMenu;
        }
}