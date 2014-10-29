<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="entrades")
 */
class Admin_Model_Entrada //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="id", type="integer")
        * @GeneratedValue(strategy="AUTO")
        */
	private $_id;	
        
        /** @Column(name="data", type="datetime") */
        
        private $_data;
        
        /** @Column(name="titol", type="string", length=180) */
	
        private $_titol;
        
        /** @Column(name="text", type="text") */
	
        private $_text;
        
        /** @ManyToOne(targetEntity="Admin_Model_Galeria",cascade={"persist"})
        * @JoinColumn(name="galeria_id", referencedColumnName="id")
        */
	private $_galeriaId;
             
        /** @Column(name="categoria_id", type="integer", length=1) */
        private $_categoriaId;
        /** @Column(name="publicar", type="integer", length=1) */
        private $_publicar;
        /** @Column(name="idioma", type="string", length=2) */
        private $_idioma;
        /** @Column(name="tipo", type="integer", length=1) */  
        private $_tipo;
        
        ////////////////////ATRIBUTS REFERENCIATS D'ALTRES CLASSES /////////////////////////
        
        /**
        * @OneToOne(targetEntity="Admin_Model_Metatags", mappedBy="_entradaId", cascade={"persist", "remove"})
        */
        private $_metas;
        
        /**
        * @OneToOne(targetEntity="Admin_Model_Imagen", mappedBy="_entradaId", cascade={"persist"})
        */
        private $_imatges;
        
        /**
        * @OneToOne(targetEntity="Admin_Model_Documento", mappedBy="_entradaId", cascade={"persist"})
        */
        private $_documents;
        
        /**
        * @OneToOne(targetEntity="Admin_Model_Menu", mappedBy="_entradaId", cascade={"persist"})
        */
        private $_menu;
        

        public function __construct($id,$data,$titol,$text,$galeria,$publicar, $idioma,$tipo) {
            
            $this->_id = $id;
            $this->_data = $data;
            $this->_titol = $titol;
            $this->_text = $text;
            $this->_galeriaId = $galeria;
            $this->_publicar = $publicar;
            $this->_idioma = $idioma;
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


	public function getText()
	{
		return $this->_text;
	}
        
        public function getGaleriaId()
        {
                return $this->_galeriaId;
        }
        
        /*public function addImatge(Admin_Model_Imagen $imgId){

            $this->_imatgeId = $imgId;
        }
        */        
        public function getCategoriaId()
        {
            return $this->_categoriaId;
        }
                
        public function getPublicar()
        {
            return $this->_publicar;
        }
                
        public function getIdioma()
        {
            return $this->_idioma;
        }
        public function getTipo()
        {
            return $this->_tipo;
        }
        public function getMetas()
        {
            return $this->_metas;
        }
                
        public function getImatges()
        {
            return $this->_imatges;
        }
        	
        public function getDocuments()
	{
            return $this->_documents;
	}
        
               
        public function getMenu()
        {
            return $this->_menu;
        }
        /* 
        public function getSubMenu()
        {
            return $this->_subMenu;
                    ;
        }
        
        public function addMenu(Admin_Model_Menu $menu)
        {
            $this->_menus->add($menu);
            $menu->setMenu($this); //es crea dins de funció no ve del model Menu
        }*/
}