<?php
class Admin_MenuController extends Zend_Controller_Action {

    private $_config;
    private $_menuDoctrineDao;
    private $_entradaDoctrineDao;
    private $_login;
    private $_numPage = 5;
    private $_usuarioDao;
    private $_portadaDoctrineDao;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                
                //creem objecte usuarioDao
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                //creem objecte menuDoctrineDao
                $this->_menuDoctrineDao = new Admin_Model_MenuDoctrineDao();
                //creem objecte EntradaDoctrineDao
                $this->_entradaDoctrineDao = new Admin_Model_EntradaDoctrineDao();
                //creem objecte PortadaDoctrineDao
                $this->_portadaDoctrineDao = new Admin_Model_PortadaDoctrineDao();
                //creem objecte login
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");      
                                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/jquery.fancybox.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/css/jquery.fancybox.css');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/accordion.css');
                                
                // conto totes les files de Menus
                $numeroMenus = count($this->_menuDoctrineDao->obtenerTodos());
                //conto totes les files de subMenus
                $numeroSubMenus = count($this->_menuDoctrineDao->obtenerTodosSubMenus());
                
                //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $numeroMenus; 
                            var numMenu = $numeroSubMenus;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {		
                                    $('#edita'+i).tooltip();
                                    $('#publica'+i).tooltip();
                                    $('#despublica'+i).tooltip();
                                    $('#borra'+i).tooltip();
                                    
                                    for(n=1; n<=numMenu;n++)//aquest per recorre els submenus
                                    {
                                        $('#editaLt'+i+n).tooltip();
                                        $('#publicaLt'+i+n).tooltip();
                                        $('#despublicaLt'+i+n).tooltip();
                                        $('#borraLt'+i+n).tooltip();
                                    }
                                }
                                //alert(i);
                            });"
                        );
                //passo el javascript del fancybox
                $this->view->headScript()->appendScript("
                            $(function() {

                                    $('.accordion').on('show', function (e) {
                                            $(e.target).prev('.accordion-heading').addClass('accordion-opened');
                                    });

                                    $('.accordion').on('hide', function (e) {
                                            $(this).find('.accordion-heading').not($(e.target)).removeClass('accordion-opened');
                                    });	
                            });

                            $(document).ready(function() {
                                    $('.fancybox').fancybox();
                            });                          
                        "
                        );
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
    
    public function preDispatch()//se llama antes de cualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás gestionar las diferentes secciones de la web';
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
            $this->view->head = "Easyweb :: Secciones";
            
            $this->view->form = $this->_getForm();
          
            
            //obtenim tots
            $menus = $this->_menuDoctrineDao->obtenerTodos();

            //$subMneus = $this->_menuDoctrineDao->obtenerTodosSubmenus();
            //paginatoooorrrr
            $paginator = Zend_Paginator::factory($menus);
            $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
            //llistem 7 files
            $paginator->setItemCountPerPage($this->_numPage);
        
            $this->view->paginator = $paginator;      
            $this->view->listMenus = $paginator;      
            
            $alert = (int) $this->getRequest()->getParam('a',0);
            
            if($alert==1){
                $this->view->alert = 'Acabas de crear un menú. Puedes seguir editando menús o hacer qualquier otra acción';
            }elseif($alert==2){
                $this->view->alert = 'Acabas de modificar un menú. Puedes seguir editando menús o hacer qualquier otra acción';
            }

            
            //passo submenus a la vista
            //$this->view->subMenus = $subMneus;
            
            //print_r($subMneus);
            
    }
    
    public function indexsubmenuAction()
    {
        //deshabilitem el layout perquè el creem a la vista guardarsubmenu.phtml
        $this->_helper->getHelper('layout')->disableLayout();
        //passo a la vista el formulari submenu
        $form = $this->_getFormSubMenu();
        $this->view->form = $form;
        //capturo el get 
        $id = (int) $this->getRequest()->getParam('id',0);
        
        //capturo l'alert
           
        $alert = (int) $this->getRequest()->getParam('a',0);
        
        if($alert==3){
            $this->view->alert = 'Acabas de crear un submenú. Puedes seguir creando menús o hacer qualquier otra acción';
        }elseif($alert==4){
             $this->view->alert = 'Acabas de modificar un submenú. Puedes seguir creando menús o hacer qualquier otra acción';
        }
       
        
        //li passo l'id al action guardarsubmenu per mantenir la id
        $form->setAction($this->_request->getBaseUrl().'/admin/menu/guardarsubmenu/id/'.$id);                       
    }
        

    private function _getForm(){
        
        $form = new Admin_Form_Menu();
        
         //creu multioptions per idioma
        $form->idioma->addMultiOptions($this->_menuDoctrineDao->obtenerIdiomasSelect());
        
        //creo multioptions del select Menú Secció
  
        $form->entrada->addMultiOptions($this->_menuDoctrineDao->obtenerEntradasSelect());   
        
        //creo multioptions del select Menú zona
  
        $form->zona->addMultiOptions($this->_menuDoctrineDao->obtenerMenuZonasSelect());
        
        //assignem l'ordre al menú
        
        $ordre =  $this->_menuDoctrineDao->obtenerUltimoOrden();
        
        $form->orden->setValue($ordre);
        
        return $form;
    }
    
    private function _getFormSubMenu(){
        
        //capturo el get de la id per passar al mètode obtenerUltimoOrdenSubMenu
        $idParent = (int) $this->getRequest()->getParam('id',0);
        
        $form = new Admin_Form_SubMenu();
        
        //creo multioptions del select Entradas
  
        $form->entrada->addMultiOptions($this->_menuDoctrineDao->obtenerEntradasSelect());   
        
        //instància del mètode 
        $ordre =  $this->_menuDoctrineDao->obtenerUltimoOrdenSubMenu($idParent);
        //assigno idParent
        $form->orden->setValue($ordre);
        
        return $form;
    }

    public function guardarAction()
    {
        //creem el formulari menu
        $form = $this->_getForm();
        //assignerm dades del form
        $formData = $this->_request->getPost();
        
        if ($form->getValue('update')=='1'){
                    
                    $form->getElement('crear')->setLabel('Modificar'); 
                   
           }
                    
        //paginatoooorrrr (com que fa renders a index la vista de de instanciar sempre el paginator )
        $paginator = Zend_Paginator::factory($this->_menuDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 7 files
        $paginator->setItemCountPerPage($this->_numPage);

        if(!$form->isValid($formData)){
            
            //assignem paginator a la vista
            $paginator->setItemCountPerPage($this->_numPage);
            
            $this->view->paginator = $paginator;      
            $this->view->listMenus = $paginator;      
            
            $this->view->form = $form;
            $form->populate($formData);
            
            return $this->render('index');
            
        }
        try{
                                      
            // si es un update poso l i sinó null           
            $form->getValue('update')!=0 ? $id=$form->getValue('id'):$id=null;

            $data = new \DateTime(date("Y-m-d H:i:s"));
            $idioma = $form->getValue('idioma');
            $entradaId = $form->getValue('entrada');
        //crido el mètode per obtenir les intrades amb l'id de la entrada
            $entradaData = $this->_entradaDoctrineDao->obtenerEntradasPorId($entradaId);    
            $entradaObject = new Admin_Model_Entrada($entradaData->getId(), $entradaData->getData(),$entradaData->getTitol(),$entradaData->getText(),$entradaData->getGaleriaId(),$entradaData->getPublicar(),$entradaData->getIdioma(), $entradaData->getTipo());
            
            $zona = $form->getValue('zona');
            $orden = $form->getValue('orden');
            $titulo = $form->getValue('titulo');
            $url = $form->getValue('url');
            $publicar = $form->getValue('publicar');
            $predet = $form->getValue('predet');
            $menuParent = new Admin_Model_Menu(0, $data, " ", null, 0, null,null, 0, 0, $idioma, null, 0);
            $galeria = null;
           //$galeria = new Admin_Model_Galeria($galeriaId->getId(), $galeriaId->getData(), $galeriaId->getTitol(), $galeriaId->getDescripcio(), $galeriaId->getIdioma(), $galeriaId->getPublicar());
            
            $tipus = "menu";
            
            if($entradaId >null && $url!=null){

                $this->view->alert = 'Si escojes una entrada para esta seccion no puedes asignar una URL';      

                //assignem el form i el poblem
                $this->view->form = $form;
                $form->populate($formData);

                //assignem paginator a la vista
                $this->view->paginator = $paginator;      
                $this->view->listMenus = $paginator; 
                
                return $this->render('index');
                

            }else{

                $menu = new Admin_Model_Menu($id,$data,$titulo,$url,$entradaObject,$galeria,$zona,$menuParent,$publicar,$idioma,$orden,$predet);
            //guardem
                $this->_menuDoctrineDao->guardar($menu);
           
                $this->view->form = $form;
                
                //assignem paginator a la vista
                $this->view->paginator = $paginator;      
                $this->view->listMenus = $paginator;
                
            //canvia l'alert a modificar en funció de l'update
                
                if ($form->getValue('update')!=1){
                                      
                    $ultimId = $this->_menuDoctrineDao->obtenerUltimoId();
                    //la consulta em retorna un array per accedir al cam id ho faig de la seguent manera:
                    $idMenu = $ultimId[0]['id'];
                                      
                    // a portada li he de passar un objecte referencial $entrada per la relació a les clases entitats i a galeria null (els camps de la bbdd seràn null)
                    $menu2 = new Admin_Model_Menu($idMenu,$data,$titulo,$url,$entradaObject,$galeria,$zona,$menuParent,$publicar,$idioma,$orden,$predet);
                    $portada = new Admin_Model_Portada(null, null, null, null, null, $menu2, $tipus); 
                    
                    $this->_portadaDoctrineDao->guardar($portada,true);

                    return $this->_redirect('admin/menu/index/a/1');
                    
                }else{
                    
                    return $this->_redirect('admin/menu/index/a/2');
                }            
            }
             
        }catch (Exception $e){
            
            $this->view->alert = $e->getMessage();
            
            //passem el form a la vista i el poblem
            $this->view->form = $form;
            $form->populate($formData);
            
            //assignem paginator a la vista
            $paginator->setItemCountPerPage($this->_numPage);
            
            $this->view->paginator = $paginator;      
            $this->view->listMenus = $paginator;

            return $this->render('index');   
        }
        
    }
      public function guardarsubmenuAction()
      {
                
         //deshabilitem el layout perquè el creem a la vista guardarsubmenu.phtml
        $this->_helper->getHelper('layout')->disableLayout();
        
        //passem per paràmetre l'id del menú
        $id = (int) $this->getRequest()->getParam('id',0);     
        
        //echo 'id = '.$id;
        
        //assignem el formulari SubMenu 
        $form = $this->_getFormSubMenu();
        $this->view->form = $form;
        
        //assigno l'id a l'action
        $form->setAction($this->_request->getBaseUrl().'/admin/menu/guardarsubmenu/id/'.$id);
        
        //assignem el post del formulari
        $formData = $this->_request->getPost();
        
        //print_r($form);
        
        if(!$form->isValid($formData)){
            
         //assigno l'id a l'action
            $form->setAction($this->_request->getBaseUrl().'/admin/menu/guardarsubmenu/id/'.$id);
            
            $this->view->form = $form;
            $form->populate($formData);
            
            return $this->render('indexsubmenu');
        } 
        try{
            
            
        //obtinc el menú per ID per poblar els camps que son comuns al submenú
            $menu =  $this->_menuDoctrineDao->obtenerMenuPorId($id);
            
            //echo 'id->'.$menu->getId();

        //variables del formulari
            
            $form->getValue('update')!=0 ? $id=$form->getValue('id'):$id=null;
            
        //$id=$form->getValue('id');
            $data = new \DateTime(date("Y-m-d H:i:s"));
            $idioma = $menu->getIdioma();   
            $entradaId = $form->getValue('entrada');
        //crido el mètode per obtenir les intrades amb l'id de la entrada
            $entradaData = $this->_entradaDoctrineDao->obtenerEntradasPorId($entradaId);    
            $entradaObject = new Admin_Model_Entrada($entradaData->getId(), $entradaData->getData(),$entradaData->getTitol(),$entradaData->getText(),$entradaData->getGaleriaId(),$entradaData->getPublicar(),$entradaData->getIdioma(), $entradaData->getTipo());
            
            $zona = $menu->getZonasId();
            $orden = $form->getValue('orden');
            $titulo = $form->getValue('titulo');
            $url = $form->getValue('url');
            $publicar = $form->getValue('publicar');
            $predet = $form->getValue('predet');
            //si es un update passa la id amb $menu->getParent() si no passo l'objecte $menu que és l'entitat
            $form->getValue('update')!=0 ? $menuParent = $menu->getParent():$menuParent = $menu; 
            $galeria = null;
            //variables de portada
            $tipus = "menu";
         
            if($entradaId >null && $url!=null){

                    $this->view->alert = 'Si escojes una entrada para esta seccion no puedes asignar una URL';      
                    
                //assignem el form i el poblem
                    $this->view->form = $form;
                    $form->populate($formData);
                    
                    return $this->render('indexsubmenu');
                    
           }else{

                    $menu = new Admin_Model_Menu($id,$data,$titulo,$url,$entradaObject,$galeria,$zona,$menuParent,$publicar,$idioma,$orden,$predet);
                //guardem
                    $this->_menuDoctrineDao->guardar($menu);  
                    
                    //$this->view->form = $form;

                //canvia l'alert a modificar en funció de l'update

                    if ($form->getValue('update')!=1){
                        
                        $ultimId = $this->_menuDoctrineDao->obtenerUltimoId();
                        //la consulta em retorna un array per accedir al cam id ho faig de la seguent manera:
                        $idMenu = $ultimId[0]['id'];
                                      
                        // a portada li he de passar un objecte referencial $entrada per la relació a les clases entitats i a galeria null (els camps de la bbdd seràn null)
                        $menu2 = new Admin_Model_Menu($idMenu,$data,$titulo,$url,$entradaObject,$galeria,$zona,$menuParent,$publicar,$idioma,$orden,$predet);
                        
                        $portada = new Admin_Model_Portada(null, null, null, null, null, $menu2, $tipus);

                        $this->_portadaDoctrineDao->guardar($portada,true);   
                        
                        
                        return $this->_redirect('admin/menu/indexsubmenu/id/'.$menuParent->getId().'/a/3');

                    }else{

                        return $this->_redirect('admin/menu/indexsubmenu/id/'.$menuParent->getId().'/a/4');
                    }
           }
            
        }catch (Exception $e){
            
            $this->view->alert = $e->getMessage();
            
            //passem el form a la vista i el poblem
            $this->view->form = $form;
            $form->populate($formData);

            return $this->render('indexsubmenu');   
        }    
      }

    public function editarmenuAction()
    {
        $paginator = Zend_Paginator::factory($this->_menuDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 7 files
        $paginator->setItemCountPerPage($this->_numPage);
        
        $this->view->paginator = $paginator; 
        
        
        //assignem l'id passat per get
        $id = (int) $this->getRequest()->getParam("id",0);
       
        //passem el form
        $form = $this->_getForm();
        
        // cambiem el label del botó a modificar perquè estic editant
        $form->getElement('crear')->setLabel('Modificar'); 
                       
        if(empty($id)){
             
           $form = $this->_getForm();
           $this->view->form = $form;
           $this->view->alert = 'Puedes crear menús en este formulario';
           $this->render('index');  
        
        }else{
            
            //instància de l'objecte per extreure les dades de menu
        
            $menu = $this->_menuDoctrineDao->obtenerMenuPorId($id);

            $form->populate(array('id'=>$menu->getId(),
                                  'data'=>$menu->getData(),
                                  'orden'=>$menu->getOrdre(),
                                  'titulo'=>stripslashes($menu->getTitol()),
                                  'url'=>$menu->getUrl(),
                                  'entrada'=>$menu->getEntradaId()->getId(),
                                  'zona'=>$menu->getZonasId(),
                                  'menuParent'=>'0',
                                  'publicar'=>'0',
                                  'idioma'=>$menu->getIdioma(),
                                  'predet'=>'0',
                                  'update'=>'1'
            ));
            
            //passem l'alert a la vista
            $this->view->alert = "Estás a punto de modificar este menú";
            //assignem paginator a la vista
            $paginator->setItemCountPerPage($this->_numPage);
            
            $this->view->paginator = $paginator;      
            $this->view->listMenus = $paginator; 
            //passem form a la vista
            $this->view->form = $form;
            
            $this->render('index');
        }
        
    }
    public function editarsubmenuAction()
    {
        //deshabilitem el layout perquè el creem a la vista guardarsubmenu.phtml
        $this->_helper->getHelper('layout')->disableLayout();
        
        //assignem l'id passat per get
        $id = (int) $this->getRequest()->getParam("sub",0);
       
        //passem el form
        $form = $this->_getFormSubMenu();
        
        // cambiem el label del botó a modificar perquè estic editant
        $form->getElement('crear')->setLabel('Modificar'); 
                       
        if(empty($id)){
             
           $form = $this->_getFormSubMenu();
           $this->view->form = $form;
           $this->view->alert = 'Puedes crear submenús en este formulario';
           $this->render('indexsubmenu');  
        
        }else{
            
            //instància de l'objecte per extreure les dades de menu
        
            $menu = $this->_menuDoctrineDao->obtenerMenuPorId($id);

            $form->populate(array('id'=>$menu->getId(),
                                  'data'=>$menu->getData(),
                                  'orden'=>$menu->getOrdre(),
                                  'titulo'=>stripslashes($menu->getTitol()),
                                  'url'=>$menu->getUrl(),
                                  'entrada'=>$menu->getEntradaId()->getId(),
                                  'zona'=>$menu->getZonasId(),
                                  'menuParent'=>$menu->getSubMenus(),
                                  'publicar'=>$menu->getPublicar(),
                                  'idioma'=>$menu->getIdioma(),
                                  'predet'=>'0',
                                  'update'=>'1'
            ));
            
            //passem l'alert a la vista
            $this->view->alert = "Estás a punto de modificar este Submenú";

            //passem form a la vista
            $this->view->form = $form;
            
            $this->render('indexsubmenu');
        }

    }
    
    public function eliminarAction()
    {
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $controlador = $this->getRequest()->getParam("c",0);
        
        $menuId = new Admin_Model_Menu($id);
        
        $this->_menuDoctrineDao->eliminar($menuId);
        
        if($controlador === 'index'){
            $this->_redirect('/admin/index/index/a/6');
        }else if($controlador ==='indexsub'){
            $this->_redirect('/admin/index/index/a/7');
        }else{
            $this->_redirect('/admin/menu/index/page/'.$page);
        }
        
    }
    
    public function listadoAction()
    { 
        $paginator = Zend_Paginator::factory($this->_menuDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->paginator = $paginator;      
        $this->view->listMenus = $paginator;
        
        //$frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de Menus de la web';
        //$this->_helper->layout->assign("welcome", $frase);
    }  
    
    public function publicacioAction()
    {
        //passo l'id per updatejar
        $id = (int) $this->getRequest()->getParam("id",0);
        
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $valor = (int) $this->getRequest()->getParam("val",0);
        
        //creem objecte menu
        $menuId = new Admin_Model_Menu($id);
        
        $this->_menuDoctrineDao->privacitat($menuId,$valor);

        $this->_redirect('/admin/menu/index/page/'.$page); 
    }
}