<?php

class Admin_IndexController extends Zend_Controller_Action
{
    private $_config;
    private $_usuarioDao;
    private $_login;
    private $_entradaDoctrineDao;
    private $_portadaDoctrineDao;
    private $_menuDoctrineDao;
    //private $_imagenDoctrineDao;
    

    public function init()
    {
        //START :: declaració de les classes del DAO
        $this->_usuarioDao = new Admin_Model_UsuarioDao();
        $this->_login = new Admin_Model_Login();
        $this->_entradaDoctrineDao = new Admin_Model_EntradaDoctrineDao();
        $this->_portadaDoctrineDao = new Admin_Model_PortadaDoctrineDao();
        $this->_menuDoctrineDao = new Admin_Model_MenuDoctrineDao();
        //$this->_imagenDoctrineDao = new Admin_Model_ImagenDoctrineDao();
        //END :: declaració deles classes del DAO
        //
        //instanciem view helper NetejaTags
        $this->view->neteja = new MisLibrerias_View_Helper_NetejaTags();
        //instanciem el view helper
        $this->view->mostraImgDoc = new MisLibrerias_View_Helper_Utilitats();
        
        $this->view->baseUrl = $this->getRequest()->getBaseUrl();
        $this->_config = Zend_Registry::get('config');
        //canviem el layout d'admin
        $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin");
        
        //aquí passem codi javascript al layout amb headScript i renderitzant la vista phtml
        $view = clone $this->view;
        $jsScript = $view->render("js/alerts.phtml");
        $this->view->headScript()->appendScript($jsScript, 'text/javascript');
        
        //passo el codi javascript fancybox. ULL perquè si el fiquem al ini() es duplica perquè te un forward a crearAction
                $this->view->headScript()->appendScript("
                                                        $(document).ready(function() {
                                                                    $('.fancybox').fancybox({
                                                                    'autoDimensions':true,
                                                                    'scrolling':'no',
                                                                    'transitionIn'	:	'elastic',
                                                                    'transitionOut'	:	'elastic',
                                                                    'speedIn'		:	600, 
                                                                    'speedOut'		:	200, 
                                                                    'overlayShow'	:	true,
                                                                    'type'          : 'iframe',
                                                                    afterClose: function () {
                                                                    parent.location.reload(true);
                                                                    }
                                                             });
                                                         });
                     
                                        "
                                        );   
                //passo el javascript del fancybox (aquest és el de les imatges)
                $this->view->headScript()->appendScript("
                             $(document).ready
                             (
                                function() 
                                {
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
        //codi per fer desapareixer l'alert
                $this->view->headScript()->appendScript("

                                $(document).ready
                                (
                                        function()
                                        {
                                            setTimeout(function() { $('.alert_lila').fadeOut('slow')}, 2500);
                                        }
                                );

                                "
                                );
    }
    
    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = 'Hola <b><em>'.$this->view->user->nom.'</b></em> ¿Qué te gustaría hacer?';
            $this->_helper->layout->assign("welcome", $frase);
            
            //assignem l'id al layout
            $id =  $this->view->user->id;
            $this->_helper->layout->assign("id",$id);
            
          //passo variable al layout per després mostrar el menú icons  
            $this->_helper->layout->assign("header",true); 
          
          //assignem l'avatar al layout (ho fem així perquè sinó s'ha de refrescar la sessió)
            $usuario = $this->_usuarioDao->obtenerPorId($id);
            $this->_helper->layout->assign('avatar',$usuario->getAvatar());
            
          //assignem ruta al fancybox de l'avatar           
            $ruta = $this->_config->parametros->urlRelativa;
            $this->_helper->layout->assign('ruta',$ruta);
            
        }else{
            
             $this->_forward('index','login','admin');
        }
    }

    public function indexAction()
    {
        //carrego titles
        $this->view->head = "Easyweb Corp 2.0";
        
        //conto totes les portades
        $numeroPortades = count($this->_portadaDoctrineDao->obtenerTodos());
        
        //conto les imatges
        //$numeroImatges = count($this->_imagenDoctrineDao->obtenerTodasImagenesDeGalerias());
        
        //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $numeroPortades;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {
                                    $('#edita'+i).tooltip();
                                    $('#publica'+i).tooltip();
                                    $('#despublica'+i).tooltip();
                                    $('#borra'+i).tooltip();
                                    //$('#borraThumbs'+i).tooltip();
                                    $('#sube'+i).tooltip();
                                }
                                //alert(i);
                            });                          
                            "
                        );
        
        $portades = $this->_portadaDoctrineDao->obtenerTodos();
        $this->view->listaPortades = $portades;
        
        //mostro l'aler passant-li per get
        $a = (int) $this->getRequest()->getParam("a",0);
        
        if($a===1){
            $this->view->alert="Entrada eliminada correctamente";
        }else if($a===2){
            $this->view->alert="Galería eliminada correctamente";
        }else if($a===3){
            $this->view->alert="Imágen eliminada correctamente";
        }else if($a===4){
            $this->view->alert="Usuario eliminado correctamente";
        }else if($a===5){
            $this->view->alert="Documento eliminado correctamente";
        }else if($a===6){
            $this->view->alert="El Menú y sus secciones dependientes se han eliminado correctamente";
        }else if($a===7){
            $this->view->alert="El Submenú se ha eliminado correctamente";
        }
    }    
        
    public function eliminarAction()
    {
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        $entradaId = (int) $this->getRequest()->getParam("e",0);
        //passo page per quedarnos a la pàgina d'on venim
        
        //obtinc l'id de portada;
        //$portada = new Admin_Model_Portada($id);
        
        //echo $portada;
        
        //obtinc l'id d'entrada
        $entrada = new Admin_Model_Entrada($entradaId);
        
        $this->_portadaDoctrineDao->eliminar($id);
        $this->_entradaDoctrineDao->eliminar($entrada);
        //$this->_entradaDoctrineDao->eliminar($entrada);
        
        $this->_redirect('/admin/index/index/a/1');
    }

}