<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="corporativa")
 */

class Admin_Model_Corporativa{
    
    /**
    * @Id
    * @Column(name="id", type="integer")
    * @GeneratedValue(strategy="AUTO")
    */
    private $_id;
    
    /** @Column(name="empresa", type="string", length=255) */
    private $_empresa;
    
    /** @Column(name="adreca", type="string", length=255) */
    private $_adreca;
    
    /** @Column(name="cp",  type="integer") */ 
    private $_cp;
    
    /** @Column(name="poblacio", type="string", length=255) */ 
    private $_poblacio;
    
    /** @Column(name="telefon",  type="string", length=255) */ 
    private $_telefon;
    
    /** @Column(name="fax",  type="string", length=255) */ 
    private $_fax;
    
    /** @Column(name="movil",  type="string", length=255) */ 
    private $_movil;
    
    /** @Column(name="email", type="string", length=255) */
    private $_email;
    
    /** @Column(name="web", type="string", length=255) */
    private $_web;
    
    /** @Column(name="legal", type="text") */
    private $_legal;
    
    /** @Column(name="gmaps", type="text") */
    private $_gmaps;
    
    ////////////////////ATRIBUTS REFERENCIATS D'ALTRES CLASSES /////////////////////////
        
    /**
     * @OneToOne(targetEntity="Admin_Model_Xsocial", mappedBy="_xSocialId", cascade={"persist", "remove"})
     */
        private $_xsocial;  
        
    public function __construct($id,$empresa,$adreca,$cp,$poblacio,$telefon,$fax,$movil,$email,$web,$legal,$gmaps) {
        
        $this->_id = $id;
        $this->_empresa = $empresa;
        $this->_adreca = $adreca;
        $this->_cp = $cp;
        $this->_poblacio = $poblacio;
        $this->_telefon = $telefon;
        $this->_fax = $fax;
        $this->_movil = $movil;
        $this->_email = $email;
        $this->_web = $web;
        $this->_legal = $legal;
        $this->_gmaps = $gmaps;
    }     
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getEmpresa()
    {
        return $this->_empresa;
    }
    
    public function getAdreca()
    {
        return $this->_adreca;
    }
    
    public function getCp()
    {
        return $this->_cp;
    }
    
    public function getPoblacio()
    {
        return $this->_poblacio;
    }
    
    public function getTelefon()
    {
        return $this->_telefon;
    }
    
    public function getFax()
    {
        return $this->_fax;
    }
    
    public function getMovil()
    {
        return $this->_movil;
    }
    public function getEmail()
    {
        return $this->_email;
    }
    public function getWeb()
    {
        return $this->_web;
    }
    
    public function getLegal()
    {
        return $this->_legal;
    }
    
    public function getGmaps()
    {
        return $this->_gmaps;
    }
}