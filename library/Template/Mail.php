<?php
class Template_Mail extends Template_AbstractCss{
    
    private $_text;
    
    public function __construct($doc,$metas,$titol,$head,$body,$contenidor,$footer,$linkRel,$script,$selectors,$classes,$text){
        parent::__construct($doc,$metas,$titol,$head,$body,$contenidor,$footer,$linkRel,$script,$selectors,$classes);
        
        $this->_text = $text;
    }
        
    /////////// contracte que implementa Template/////////
    
    public function crearHead(){
        //codi
    }
    public function crearBody(){
        
    }
    public function crearContenidor(){
        
    }
    public function crearFooter(){
        
    }
    
     /////////// fi /////////
    
    public function getLinkRel(){
        return $this->_text;
    }
}
