<?php
class Admin_BannerController extends Zend_Controller_Action {

    private $_config;
    private $_bannerDoctrineDao;
    private $_login;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                
                
                //creem objecte bannerDoctrineDao
                $this->_bannerDoctrineDao = new Admin_Model_BannerDoctrineDao();
                            
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");      
                                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/jquery.fancybox.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/css/jquery.fancybox.css');

               
                // conto totes les files de banners
                $numeroBanners = count($this->_bannerDoctrineDao->obtenerTodos());
                
                //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $numeroBanners;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {		
                                $('#publica'+i).tooltip();
                                $('#despublica'+i).tooltip();
                                $('#borra'+i).tooltip();
                                }
                                //alert(i);
                            });"
                        );
                //passo el javascript del fancybox
                $this->view->headScript()->appendScript("
                             $(document).ready(function() {
			
                                $('.imgs').fancybox({
                                    closeBtn  : true,
                                    openEffect  : 'none',
                                    closeEffect	: 'none',
                                    helpers : {
					title : {
						type : 'inside'
					}
                                    }
                                });

                            });
                        "
                        );

    }

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás dar de alta usuarios para gestionar la web';
            $this->_helper->layout->assign("welcome", $frase);
            
             //assignem l'avatar al layout
            $avatar = $this->view->user->avatar;
            $this->_helper->layout->assign("avatar", $avatar);
            
            $this->_helper->layout->assign("header",true); //passo variable al layout per després mostrar el menú icons
            
          //assignem ruta al fancybox de l'avatar           
            $ruta = $this->_config->parametros->urlRelativa;
            $this->_helper->layout->assign('ruta',$ruta);
            
        }else{
             $this->_forward('index','login','admin');
        }
    }

    public function publicacioAction()
    {
        //passo l'id per updatejar
        $id = (int) $this->getRequest()->getParam("id",0);
        
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $valor = (int) $this->getRequest()->getParam("val",0);
        
        //creem objecte Banner
        $banner = new Admin_Model_Banner();
        $banner->setId($id);
        
        $this->_bannerDoctrineDao->privacitat($banner,$valor);

        $this->_redirect('/admin/banner/listado/page/'.$page);
    }
    
    public function eliminarAction()
    {
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $banner = new Admin_Model_Banner();
        $banner->setId($id);
        
        $this->_bannerDoctrineDao->eliminar($banner);
        
        $this->_redirect('/admin/banner/listado/page/'.$page);
        
    }
    
    public function listadoAction()
    { 
        $paginator = Zend_Paginator::factory($this->_bannerDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->paginator = $paginator;      
        $this->view->listBanners = $paginator;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de banners de la web';
        $this->_helper->layout->assign("welcome", $frase);
    }
}