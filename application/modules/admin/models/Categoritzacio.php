<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="categoritzacio")
 */

class Admin_Model_Categoritzacio //esta es la clase entidad que suele llevar los campos de la bbdd
{
    
    /**
    * @Id
    * @Column(name="id", type="integer")
    * @GeneratedValue(strategy="AUTO")
    */    
    private $_id;
    /** @Column(name="nom", type="string", length=100) */
    private $_nom;
    /** @Column(name="data", type="datetime") */
    private $_data;
    /** @Column(name="tipo", type="string", length=100) */
    private $_tipo;
    /** @Column(name="idioma", type="string", length=2) */
    private $_idioma; 

}