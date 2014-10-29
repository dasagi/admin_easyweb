<?php

namespace Proxies\__CG__;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Admin_Model_Documents extends \Admin_Model_Documents implements \Doctrine\ORM\Proxy\Proxy
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

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function getData()
    {
        $this->__load();
        return parent::getData();
    }

    public function setData($data)
    {
        $this->__load();
        return parent::setData($data);
    }

    public function getTitol()
    {
        $this->__load();
        return parent::getTitol();
    }

    public function setTitol($titol)
    {
        $this->__load();
        return parent::setTitol($titol);
    }

    public function getRuta()
    {
        $this->__load();
        return parent::getRuta();
    }

    public function setRuta($ruta)
    {
        $this->__load();
        return parent::setRuta($ruta);
    }

    public function getEntrades()
    {
        $this->__load();
        return parent::getEntrades();
    }

    public function setEntrades(\Admin_Model_Entrada $entrades)
    {
        $this->__load();
        return parent::setEntrades($entrades);
    }

    public function addEntrada(\Admin_Model_Entrada $entrada)
    {
        $this->__load();
        return parent::addEntrada($entrada);
    }


    public function __sleep()
    {
        return array('__isInitialized__', '_id', '_data', '_titol', '_ruta', '_entrades');
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