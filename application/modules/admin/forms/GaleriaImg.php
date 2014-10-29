<?php

class Admin_Form_GaleriaImg extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        //$this->_dataFormat = new MisLibrerias_View_Helper_FormatDate();
        
        $this->setAction($this->getView()->baseUrl() . '/admin/galeria/crear/')->setMethod('post')->setName('myDrop')->setAttrib('class', 'dropzone')->setAttrib('enctype', 'multipart/form-data');
        
    }
}