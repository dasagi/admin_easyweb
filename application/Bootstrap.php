<?php

use Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\DBAL\DriverManager,
    Doctrine\Common\Cache\ApcCache,
    Doctrine\Common\Cache\ArrayCache;

//use Doctrine\ORM\Tools\Setup;
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initConfig()
    {
        $config = new Zend_Config_Ini('config.ini', 'default');
        Zend_Registry::set("config", $config);
            return $config;
    }

    protected function _initEnvironment()
    {
        if ($this->getEnvironment() == "development") {
             error_reporting(E_ALL | E_STRICT);
             ini_set("display_errors",true);
        }

        $timezone = (string)Zend_Registry::get('config')->parametros->timezone;

		if(empty($timeZone)){
			$timeZone = "Europe/Madrid";
		}

        date_default_timezone_set($timezone);
        return null;
    }

     protected function _initView() {
        // Inicializar la vista
        $resource = $this->getPluginResource("view");
        $view = $resource->init();

        // Añadir al ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                        'ViewRenderer'
        );

        $viewRenderer->setView($view);

        $view->doctype('XHTML1_STRICT');

        $view->setEncoding("UTF-8");

        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')
                ->appendHttpEquiv('Content-Language', 'en-US');

        return $view;
    }
    protected function _initDoctrine() {

        $config = new Configuration();

        $cache = null;

        if ($this->getEnvironment() == "development") {
            $cache = new ArrayCache();
        } else {
            $cache = new ApcCache();
        }

        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        $paths = array(APPLICATION_PATH . '/modules/usuarios/models',);

        $driverImpl = $config->newDefaultAnnotationDriver($paths);
        $config->setMetadataDriverImpl($driverImpl);

        $config->setProxyDir(APPLICATION_PATH . "/../library" . DIRECTORY_SEPARATOR . 'Proxies');
        $config->setProxyNamespace('Proxies');

        if ($this->getEnvironment() == "development") {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        $connPDO = array();
        $this->bootstrap('db');
        $connPDO['pdo'] = $this->getResource('db')->getConnection();
        $conn = DriverManager::getConnection($connPDO, $config);
        $em = EntityManager::create($conn, $config);

        /* $paths = array(APPLICATION_PATH . '/modules/usuarios/models',);
          $isDevMode = false;
          if ($this->getEnvironment() == "development") {
          $isDevMode = true;
          }
          $connPDO = array();
          $this->bootstrap('db');
          $connPDO['pdo'] = $this->getResource('db')->getConnection();

          $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
          $em = EntityManager::create($connPDO, $config); */

        Zend_Registry::set('em', $em);

        return $em;
    }
    
}