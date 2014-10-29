<?php
abstract class Template_AbstractCss extends Template_AbstractTemplate{
    
    private $_linkRel;
    private $_script;
    private $_selectors;
    private $_classes;
    
    public function __construct($doc,$metas,$titol,$head,$body,$contenidor,$footer,$linkRel,$script,$selectors,$classes){
        parent::__construct($doc,$metas,$titol,$head,$body,$contenidor,$footer);
        
        $this->_linkRel = $linkRel;
        $this->_script = $script;
        $this->_selectors = $selectors;
        $this->_classes = $classes;
    }
    
    public function getLinkRel(){
        return $this->_linkRel;
    }
    public function getScript(){
        return $this->_script;
    }
    public function getSelectors(){
        return $this->_selectors;
    }
    public function getClasses(){
        return$this->_classes;
    }
}
