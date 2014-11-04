<?php

class MisLibrerias_Controller_Plugin_Head extends Zend_Controller_Plugin_Abstract
{

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
        
        //$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer(); no hace falta crear objeto
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

        //$viewRenderer->initView(); no hace falta iniciar la vista
        
        $view = $viewRenderer->view;
        $view->headTitle();
        
        $view->doctype('XHTML1_STRICT');

        $view->setEncoding("UTF-8");
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')->appendHttpEquiv('Content-Language', 'en-US');
        $view->headLink()->appendStylesheet('css/styles.css');
        $view->headScript();
    }    
}