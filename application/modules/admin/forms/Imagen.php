<?php
//aquest formulari es pot esborrar no serveix de res
class Admin_Form_Imagen extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        //$this->_dataFormat = new MisLibrerias_View_Helper_FormatDate();
        
        $this->setAction($this->getView()->baseUrl() . '/admin/imagen/index/')->setMethod('post')->setName('myDrop')->setAttrib('class', 'dropzone')->setAttrib('enctype', 'multipart/form-data');
        
    }
}