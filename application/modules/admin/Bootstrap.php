<?php
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initEnvironment() {
        
        // configurem el getBaseUrl, assignem amb getInstance i si no està definida guardem amb setBaseUrl
        $this->_burl = Zend_Controller_Front::getInstance()->getBaseUrl();
        if (!$this->_burl) {
        $this->_burl = rtrim(preg_replace( '/([^\/]*)$/', '', $_SERVER['PHP_SELF'] ), '/\\');
        Zend_Controller_Front::getInstance()->setBaseUrl($this->_burl);
        } 
    }

    protected function _initView() {
           
        //creem objecte
        $view = new Zend_View();
        
        
        // Añadir al ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                        'ViewRenderer'
        );
        
        $viewRenderer->setView($view);
        
        //registrem la ruta del view helper
        $view->addHelperPath('MisLibrerias/View/Helper/','MisLibrerias_View_Helper');
        
        //var_dump( $baseUrl);
        
        //afegim llibreries javascript 
        $view->headScript()->appendFile($this->_burl.'/js/jquery.js', 'text/javascript');
        $view->headScript()->appendFile($this->_burl.'/js/jquery-1.8.2.min.js', 'text/javascript');
        $view->headScript()->appendFile($this->_burl.'/js/bootstrap.js', 'text/javascript');
        $view->headScript()->appendFile($this->_burl.'/js/modernizr.js', 'text/javascript');
        $view->headScript()->appendFile($this->_burl.'/js/funcions.js', 'text/javascript');
        $view->headScript()->appendFile($this->_burl.'/js/bootstrap-tooltip.js', 'text/javascript');
        //$view->headScript()->appendFile($this->_burl.'/js/jasny-bootstrap.min.js', 'text/javascript');
            
        $view->headScript()->appendFile($this->_burl.'/js/jquery.fancybox.js', 'text/javascript');    
        
        //css
        $view->headLink()->appendStylesheet($this->_burl.'/css/bootstrap.css');
        $view->headLink()->appendStylesheet($this->_burl.'/css/bootstrap-responsive.css');
        $view->headLink()->appendStylesheet($this->_burl.'/css/jquery.fancybox.css');
        //$view->headLink()->appendStylesheet($this->_burl.'/css/jasny-bootstrap.min.css');
        $view->headLink()->appendStylesheet('http://www.google.com/fonts#ChoosePlace:select/Collection:Maven+Pro');
        
        return $view;
    }
}