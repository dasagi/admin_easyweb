<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="xsocial")
 */

class Admin_Model_Xsocial{
    
    /**
    * @Id
    * @Column(name="id", type="integer")
    * @GeneratedValue(strategy="AUTO")
    */
    private $_id;
    
    /** @Column(name="nom", type="string", length=255) */
    private $_nom;
    
    /** @Column(name="url", type="string", length=255) */
    private $_url;
    
    /** @Column(name="codi", type="text") */
    private $_codi;
    
    /** @Column(name="icon", type="string", length=255) */
    private $_icon;
    

    /** @OneToOne(targetEntity="Admin_Model_Corporativa", cascade={"persist"})
     * @JoinColumn(name="corporativa_id", referencedColumnName="id")
     */
    private $_xSocialId;
        
    public function __construct($id,$nom,$url,$codi,$icon,$xSocialId) {
        
        $this->_id = $id;
        $this->_nom = $nom;
        $this->_url = $url;
        $this->_codi = $codi;
        $this->_icon = $icon;
        $this->_xSocialId = $xSocialId;
    }     
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getNom()
    {
        return $this->_nom;
    }
    
    public function getUrl()
    {
        return $this->_url;
    }
    
    public function getCodi()
    {
        return $this->_codi;
    }
    
    public function getXsocialId()
    {
        return $this->_xSocialId;
    }
}