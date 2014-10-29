<?php

class Default_IndexController extends Zend_Controller_Action
{
	private $_config;

    public function init()
    {
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
		$this->_config = Zend_Registry::get('config');
    }

    public function indexAction()
    {
        $this->view->titulo = $this->_config->parametros->mvc->index->index->titulo;
        $this->view->tema = $this->_config->parametros->mvc->index->index->tema;
        $this->view->footer = $this->_config->parametros->footer;
    }
}