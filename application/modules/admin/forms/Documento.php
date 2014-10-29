<?php

class Admin_Form_Documento extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        //$this->_dataFormat = new MisLibrerias_View_Helper_FormatDate();
        
        $this->setAction($this->getView()->baseUrl() . '/admin/documento/index/')->setMethod('post')->setName('myDrop')->setAttrib('class', 'dropzone')->setAttrib('enctype', 'multipart/form-data');
        
    }
}