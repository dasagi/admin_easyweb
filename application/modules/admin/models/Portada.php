<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="portada")
 */

class Admin_Model_Portada{
    
    /**
    * @Id
    * @Column(name="id", type="integer")
    * @GeneratedValue(strategy="AUTO")
    */
    private $_id;    
    
    /** @OneToOne(targetEntity="Admin_Model_Entrada", cascade={"persist"})
    * @JoinColumn(name="entrada_id", referencedColumnName="id")
    */
    private $_entradaId;
    
    /** @OneToOne(targetEntity="Admin_Model_Galeria", cascade={"persist"})
     * @JoinColumn(name="galeria_id", referencedColumnName="id")
     */
    private $_galeriaId;
    
    /** @OneToOne(targetEntity="Admin_Model_Documento", cascade={"persist"})
     * @JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $_documentId;
    
    /** @OneToOne(targetEntity="Admin_Model_Usuario", cascade={"persist"})
     * @JoinColumn(name="usuari_id", referencedColumnName="id")
     */
    private $_usuariId;
    
    /** @OneToOne(targetEntity="Admin_Model_Menu", cascade={"persist"})
     * @JoinColumn(name="menu_id", referencedColumnName="id")
     */
    /*
       @column(name="menu_id", type="integer", length=11)
    */
    private $_menuId;

    /** @Column(name="tipus", type="string", length=100) */
    private $_tipus;
        
    public function __construct($id,$entradaId,$galeriaId,$documentId,$usuariId,$menuId,$tipus) {
        
        $this->_id = $id;
        $this->_entradaId = $entradaId;
        $this->_galeriaId = $galeriaId;
        $this->_documentId = $documentId;
        $this->_usuariId = $usuariId;
        $this->_menuId = $menuId;
        $this->_tipus = $tipus;
    }     
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getEntrada()
    {
        return $this->_entradaId;
    }
    
    public function getGaleria()
    {
        return $this->_galeriaId;
    }
    public function getUsuari()
    {
        return $this->_usuariId;
    }
    
    public function getDocument()
    {
        return $this->_documentId;
    }        
    
    public function getMenu()
    {
        return $this->_menuId;
    }
    public function getTipus()
    {
        return $this->_tipus;
    }
}