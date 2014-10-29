<?php

namespace Proxies\__CG__;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Admin_Model_Entrada extends \Admin_Model_Entrada implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        $this->__load();
        return parent::getId();
    }

    public function getData()
    {
        $this->__load();
        return parent::getData();
    }

    public function getTitol()
    {
        $this->__load();
        return parent::getTitol();
    }

    public function getText()
    {
        $this->__load();
        return parent::getText();
    }

    public function getGaleriaId()
    {
        $this->__load();
        return parent::getGaleriaId();
    }

    public function getCategoriaId()
    {
        $this->__load();
        return parent::getCategoriaId();
    }

    public function getPublicar()
    {
        $this->__load();
        return parent::getPublicar();
    }

    public function getIdioma()
    {
        $this->__load();
        return parent::getIdioma();
    }

    public function getTipo()
    {
        $this->__load();
        return parent::getTipo();
    }

    public function getMetas()
    {
        $this->__load();
        return parent::getMetas();
    }

    public function getImatges()
    {
        $this->__load();
        return parent::getImatges();
    }

    public function getDocuments()
    {
        $this->__load();
        return parent::getDocuments();
    }

    public function getMenu()
    {
        $this->__load();
        return parent::getMenu();
    }


    public function __sleep()
    {
        return array('__isInitialized__', '_id', '_data', '_titol', '_text', '_categoriaId', '_publicar', '_idioma', '_tipo', '_galeriaId', '_metas', '_imatges', '_documents', '_menu');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}