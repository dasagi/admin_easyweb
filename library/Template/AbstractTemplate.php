<?php
abstract class Template_AbstractTemplate{
    
    private $_doc;
    private $_titol;
    private $_metas;
    private $_head;
    private $_body;
    private $_contenidor;
    private $_footer;
    
    
    public function __construct($doc,$metas,$titol,$head,$body,$contenidor,$footer){
        
        $this->_doc = $doc;
        $this->_metas = $metas;
        $this->_titol = $titol;
        $this->_head = $head;
        $this->_body = $body;
        $this->_contenidor = $contenidor;
        $this->_footer = $footer;
    }
    
    /////////// mètodes abstractes que s'han de complir/////////
    
    abstract public function crearHead();
    abstract public function crearBody();
    abstract public function crearContenidor();
    abstract public function crearFooter();
    
     /////////// fi /////////
    
    public function getDoc(){
        return $this->_doc;
    }
    public function getTitol(){
        return $this->_titol;
    }
    public function getMetas(){
        return $this->_metas;
    }
}
