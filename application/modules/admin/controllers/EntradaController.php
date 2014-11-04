<?php
class Admin_EntradaController extends Zend_Controller_Action {

    private $_config;
    private $_entradaDoctrineDao;
    private $_login;
    private $_documentoDoctrineDao;
    private $_imagenDoctrineDao;
    private $_menuDoctrineDao;
    private $_usuarioDao;
    private $_portadaDoctrineDao;
    
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                
                //creem objecte EntradaDoctrineDao
                $this->_entradaDoctrineDao = new Admin_Model_EntradaDoctrineDao();
                //creem objecte Login            
                $this->_login = new Admin_Model_Login();
                //creem objecte Documento
                $this->_documentoDoctrineDao = new Admin_Model_DocumentoDoctrineDao();
                //creem objecte Documento
                $this->_imagenDoctrineDao = new Admin_Model_ImagenDoctrineDao();
                //creem objecte Menu
                $this->_menuDoctrineDao = new Admin_Model_MenuDoctrineDao();
                //creem objecte portada
                $this->_portadaDoctrineDao = new Admin_Model_PortadaDoctrineDao();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");
                
                //instanciem view helper NetejaTags
                $this->view->neteja = new MisLibrerias_View_Helper_NetejaTags();
                
                
                $this->_baseUrl = $this->_request->getBaseUrl();
                //passo l'enllaç del tiny a la vista
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/tiny_mce/tiny_mce.js', 'text/javascript');
                
                //passo el datapicker (calendari)
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/picker.js', 'text/javascript');
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/picker.date.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/classic.css');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/classic.date.css');
                
                $numeroEntrades = count($this->_entradaDoctrineDao->obtenerTodos());
                $this->view->headScript()->appendScript("
                                    var num = $numeroEntrades;
                                    $(function () {
                                        for(i=1;i<=num;i++)
                                        {		
                                        $('#editar'+i).tooltip();
                                        $('#borra'+i).tooltip();
                                        }
                                        //alert(i);
                                    });                 
                                "
                                );
                
                //passo el javascript
                $this->view->headScript()->appendScript("

                        // Notice: The simple theme does not use all options some of them are limited to the advanced theme

                                tinyMCE.init({
                                mode : 'textareas',
                                theme : 'advanced',
                                theme_advanced_buttons1 : 'bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink',
                                theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,outdent,indent,blockquote,anchor,cleanup,code,image,preview,tablecontrols,|,hr,removeformat',
                                theme_advanced_buttons3 : '',
                                theme_advanced_toolbar_location : 'top',
                                theme_advanced_statusbar_location : '',
                                extended_valid_elements : 'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]'
                                });                 
                            "
                            );                   
                //passo aquí també el codi del datapicker
                $this->view->headScript()->appendScript("

                    $(function () {
                        $( '#data' ).pickadate({
                                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],					  
                                weekdaysShort: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
                                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                showMonthsShort: true,
                                today: 'Hoy',
                                clear: 'Borrar',
                                format: 'dd-mm-yyyy',
                                })
                        })");                   
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

                $view = clone $this->view;
                $jsScript = $view->render("js/selectcascade-menu.phtml");
                $this->view->headScript()->appendScript($jsScript, 'text/javascript');

                $this->_helper->getHelper('AjaxContext')->addActionContext('cargar', 'html')->initContext();

                $this->_em = Zend_Registry::get('em');
    }

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás crear entradas y contenido de la web';
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
    
    private function _getForm(){
        
        $form = new Admin_Form_Entrada();
        
        //creo multioptions dinàmics del select Menú Secció
  
        $form->seccio->addMultiOptions($this->_entradaDoctrineDao->obtenerMenusSelect());   
        
        //creo multioptions dinàmics per idioma
        $form->idioma->addMultiOptions($this->_entradaDoctrineDao->obtenerIdiomasSelect());
        
        //creo multioptions dinàmics per imatges
        $form->imagen->addMultiOptions($this->_imagenDoctrineDao->obtenerImagenesSelect());
        
        //creo multioptions dinàmics per documents
        $form->documents->addMultiOptions($this->_documentoDoctrineDao->obtenerDocumentosSelect());
        
        //$form->idioma->setRequired(FALSE);
         
        return $form;
    }
   
    public function indexAction(){
        
        //carrego titles
        $this->view->head = "Easyweb :: Contenido";

        //objecte form a la vista    
        $this->view->form = $this->_getForm();
           
    }

    public function cargarAction()
    {
        
        $menuId = (int)$this->getRequest()->getParam("menuId", 0);
        
        //echo 'lalal'.$menuId;

        $subMenu = new Zend_Form_Element_Select('subSeccio');
        $subMenu->addMultiOption("-1", "Menú Subsección")
                ->setAttrib("class", "span12");
        
        //echo 'asdfasd'.$subMenu->getValue('subSeccio');
        
            $id = $this->_entradaDoctrineDao->obtenerParentPorIdSelectEditar($menuId);
            
        //si no te parent li diem que es disabled
        if (!empty($id)){
            
             $subMenu->addMultiOptions($this->_entradaDoctrineDao->obtenerParentPorIdSelectEditar($menuId));  
             //$subMenu->addMultiOptions(array('No hay subsecciones asignadas'))->setValue('0')->setAttribs(array('disabled' => true));
         }else{
             $subMenu->addMultiOption("-1", "No hay subsecciones")->setAttrib("class", "span12");
         }
         
        $subMenu->setDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label'),
            array('HtmlTag', array('tag' => 'div', 'id' => 'cargar_menuSubSeccion')),
        ));
       
        $this->view->selectMenuZonas = $subMenu;
    }  
    
    public function guardarAction(){
        
        //carrego titles
        $this->view->head = "Easyweb :: Contenidos";
        //creem el formulari d'entrada
        $form = $this->_getForm();
        //assignerm dades del form
        $formData = $this->_request->getPost();
        
        if(!$form->isValid($formData)){
            
            $this->view->form = $form;
            //echo $form->getValue('noticia');
            
            $form->populate($formData);
            
            return $this->render('index');
        }
        
        try{
            //assignem variables als camps del form
            $id = $form->getValue('id');
            $data = new \DateTime($form->getValue('data'));
            $titol = $form->getValue('titol');
            $text = $form->getValue('contingut');
            $documento = $form->getValue('documents');
            $galeria = null;
            $imatge = $form->getValue('imagen');
            $publicar = $form->getValue('public');
            $privat = $form->getValue('privat');     
            $idioma = $form->getValue('idioma');
            $tipo = $form->getValue('noticia');
            
            //els camps hidden
            $update = $form->getValue('update');
            $idMetas = $form->getValue('idMetas');
            $idImatge = $form->getValue('idImatge');
            $idDoc = $form->getValue('idDoc');
            $idSeccio = $form->getValue('idSeccio');
            //camps metas
            
            $title = $form->getValue('title');
            $description = $form->getValue('description');
            $keywords = $form->getValue('keywords');
            $robots = $form->getValue('robots');
            $autor = $form->getValue('autor');    
            //camps menus
            
            $menuSeccio = $form->getValue('seccio');
            $menuSubSeccio = $form->getValue('subSeccio');
            
            //variables de portada
            
            $tipus = "entrada";
            
            //si li donem a publicar
            if(isset($publicar)){
                
                $entrada = new Admin_Model_Entrada($id,$data,$titol,$text,$galeria,$publicar,$idioma,$tipo);
                //guardem el formulari
                     
                    $this->_entradaDoctrineDao->guardar($entrada,$update);
                
                //guardem portada si no ve d'editar
                if($update==0){   
                
                    // a portada li he de passar un objecte referencial $entrada per la relació a les clases entitats i a galeria null (els camps de la bbdd seràn null)

                    $portada = new Admin_Model_Portada(null, $entrada, null, null, null, null, $tipus);  
                
                    $this->_portadaDoctrineDao->guardar($portada);
                 }
                 
                //creem instancia de l'objecte metatags per guardar posteriorment
                
                $metas = new Admin_Model_Metatags($idMetas,$title,$description,$keywords,$robots,$autor,$idioma,$entrada,$galeria);//$entrada és l'id de metatags NO ÉS $entrada->getId() perquè està relacionat amb Doctrine OneToOne;

                //guardem els metas en la taula metatags associada al ID d'entrada
                
                    $this->_entradaDoctrineDao->guardarMetas($metas,$idMetas);
                
                    //això és un update a la taula imatges amb entrada_id
                    $this->_entradaDoctrineDao->guardarImatge($imatge,$entrada->getId());     
                    
                    //si la foto canvia l'anterior me posa un 0 tejero
                    if($imatge != $idImatge){
                        $this->_entradaDoctrineDao->guardarImatge($idImatge,0);
                    }
                    
                    //fem update de document
                    $this->_entradaDoctrineDao->guardarDocument($documento,$entrada->getId());
                    
                    if($documento != $idDoc){
                        
                        $this->_entradaDoctrineDao->guardarDocument($idDoc,0);
                        
                        //echo "idDoc = ".$idDoc;
                    }
                //updatejem els menus si hi han
                                
                //si és una noticia no pot estar a un menú
                if($tipo==1){
                    
                     //echo 'id notica'.$tipo;
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,0);
                            
                }else{
                    
                    if ($menuSeccio!=-1 && $menuSubSeccio==-1){

                        //si ha canviat d'id de seccio
                        if($menuSeccio!=$idSeccio){

                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,$entrada->getId());
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);

                        //no ha canviat
                        }else{

                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,$entrada->getId());

                        }

                        //echo '--UNO seccio seleccionada '.$menuSeccio.' subseccio buida '.$menuSubSeccio;

                    }
                    elseif($menuSeccio!=-1 && $menuSubSeccio!=-1){

                        //si ha canviat d'id de subsecció
                        if ($menuSubSeccio!=$idSeccio){


                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,$entrada->getId());
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);

                        }
                        else{

                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,$entrada->getId());
                        }
                        //echo '--DOS seccio seleccionada '.$menuSeccio.' subseccio Seleccionada o canviada '.$menuSubSeccio;
                    }
                    else{

                            //echo 'id notica'.$tipo;
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,0);

                        //echo '--TRES seccio seleccionada '.$menuSeccio.' subseccio canviada '.$menuSubSeccio;
                    }
                }
                //end update menus
                $this->view->alert = 'Entrada publicada';
                
                //passem el form a la vista
                $this->view->form = $this->_getForm();
                return $this->render('index');
            }
            //si li donem a privat
            if(isset($privat)){
               $entrada = new Admin_Model_Entrada($id,$data,$titol,$text,$galeria,$privat,$idioma,$tipo);
                //guardem el formulari

                    $this->_entradaDoctrineDao->guardar($entrada,$update);
                    
                //guardem portada si no ve d'editar
                if($update==0){   
                
                    // a portada li he de passar un objecte referencial $entrada i un altre $galeria per la relació a les clases entitats
                    $galeria = new Admin_Model_Galeria(null, $data, null, null, null,0, 0);
                
                    $portada = new Admin_Model_Portada(null,$entrada,$galeria, $tipus);  
                
                    $this->_portadaDoctrineDao->guardar($portada);
                 }
                 
                //guardem els metas en la taula metatags associat al ID d'entrada
                $metas = new Admin_Model_Metatags($idMetas,$title,$description,$keywords,$robots,$autor,$idioma,$entrada,$galeria);//$entrada és l'id de metatags NO ÉS $entrada->getId() perquè està relacionat amb Doctrine OneToOne;
                
                //guardem els metas en la taula metatags associada al ID d'entrada
                    $this->_entradaDoctrineDao->guardarMetas($metas,$idMetas);
                
                    //això és un update a la taula imatges amb entrada_id
                    $this->_entradaDoctrineDao->guardarImatge($imatge,$entrada->getId());     
                    
                    //si la foto canvia l'anterior me posa un 0 tejero
                    if($imatge != $idImatge){
                        $this->_entradaDoctrineDao->guardarImatge($idImatge,0);
                    }
                    
                    //fem update de document
                    $this->_entradaDoctrineDao->guardarDocument($documento,$entrada->getId());
                    
                    if($documento != $idDoc){
                        
                        $this->_entradaDoctrineDao->guardarDocument($idDoc,0);
                        
                        //echo "idDoc = ".$idDoc;
                    }
                 
                //updatejem els menus si hi han
                                
                //si és una noticia no pot estar a un menú
                if($tipo==1){
                    
                     //echo 'id notica'.$tipo;
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,0);
                            
                }else{
                    
                    if ($menuSeccio!=-1 && $menuSubSeccio==-1){

                        //si ha canviat d'id de seccio
                        if($menuSeccio!=$idSeccio){

                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,$entrada->getId());
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);

                        //no ha canviat
                        }else{

                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,$entrada->getId());


                        }

                        //echo '--UNO seccio seleccionada '.$menuSeccio.' subseccio buida '.$menuSubSeccio;

                    }
                    elseif($menuSeccio!=-1 && $menuSubSeccio!=-1){

                        //si ha canviat d'id de subsecció
                        if ($menuSubSeccio!=$idSeccio){


                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,$entrada->getId());
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);

                        }
                        else{

                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,$entrada->getId());
                        }
                        //echo '--DOS seccio seleccionada '.$menuSeccio.' subseccio Seleccionada o canviada '.$menuSubSeccio;
                    }
                    else{

                            //echo 'id notica'.$tipo;
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($idSeccio,0);
                            $this->_menuDoctrineDao->updateMenuDeEntrada($menuSubSeccio,0);

                        //echo '--TRES seccio seleccionada '.$menuSeccio.' subseccio canviada '.$menuSubSeccio;
                    }
                }
                //end update menus

                $this->view->alert = 'Entrada guardada';
                //passem el form a la vista
                $this->view->form = $this->_getForm();
                return $this->render('index');
            }
        }catch (Exception $e){
            
            $this->view->alert = $e->getMessage();
            
            //passem el form a la vista i el poblem
            $this->view->form = $form;
            $form->populate($formData);
            return $this->render('index');
        }
    }
    
    public function editarAction()
    {
        //carrego titles
        $this->view->head = "Easyweb :: Editar Contenido";
        
        //assignem l'id passat per get
        $id = (int) $this->getRequest()->getParam("id",0);
        $metaId = (int) $this->getRequest()->getParam("m",0);
        $idImatge = (int)$this->getRequest()->getParam('i',0);
        $idDocument = (int)$this->getRequest()->getParam('d',0);
        $idSeccio = (int) $this->getRequest()->getParam('s',0);
       
        //passo el formulari
        $form = $this->_getForm();
        
        //creo multioptions del select Menú Secció i aquí m'ha de mostrar tots els menús menys el zero
        
        $form->seccio->addMultiOptions($this->_entradaDoctrineDao->obtenerMenusSelect()); 
        
        //si està buida la id podem insertar
        if(empty($id)){
            
           $form = $this->_getForm();
           $this->view->form = $form;
           $this->view->alert = 'Puedes crear el contenido de tus entradas en este formulario';
           $this->render('index');
           
        //si no podem updatejar   
        }else{
            
            //instancia del metode
            $entradas =  $this->_entradaDoctrineDao->obtenerEntradasPorId($id);
            
            //passo parametre hidden idSeccio si és 0 el combo seccio te un value 0 i les variables les setejo a 0
            if($idSeccio==0 ){
                
              $form->seccio->setValue('0'); 
              $valueSeccio = 0;
              $valueSubSeccio = 0;
            
            //si no assignem valors de parentesc al valor de la secció i Id del menú que en aquest cas será el menú fill
            }else{
                
                //aquesta linea significa; trec la relació menu de entradas amb $entradas->getMenu(), li demano el parent de Admin_Model_Menu getParent() i també l'id getId()
                $valueSeccio = $entradas->getMenu()->getParent()->getId();
                $valueSubSeccio = $entradas->getMenu()->getId();
                
                }
            
                //si no hi ha valor de parentesc el combo no ha de llistar res per això li passem un null al mètode que ens dona el parentesc amb la id de la select. Tot això és fa en AJAX
                if($valueSeccio == null){
                    
                    $form->subSeccio->addMultiOptions($this->_entradaDoctrineDao->obtenerParentPorIdSelect(null));
                    
                    //si es dona NULL la secció de parentesc és l'id del menú fill. Tant si és menu com submenú sempre és un menú amb un id que penja d'un altre per això aquesta igualtat
                    $valueSeccio = $valueSubSeccio;
                //si no doncs m'ha de retornar l'id del menú parent     
                }else{
                    
                     $form->subSeccio->addMultiOptions($this->_entradaDoctrineDao->obtenerParentPorIdSelectEditarSelected($valueSeccio));

                }
            
            $form->populate(array('id'=>$entradas->getId(),
                                  'data'=>$entradas->getData()->format('d-m-Y'),
                                  'titol'=>stripslashes($entradas->getTitol()),
                                  'idioma'=>$entradas->getIdioma(),
                                  'noticia'=>$entradas->getTipo(),
                                  'contingut'=>stripslashes($entradas->getText()),
                                  'seccio'=>$valueSeccio,
                                  'subSeccio'=>$valueSubSeccio,
                                  'imagen'=>$idImatge,
                                  'documents'=>$idDocument,
                                  'title'=>stripslashes($entradas->getMetas()->getTittle()),
                                  'description'=>stripslashes($entradas->getMetas()->getDescription()),
                                  'keywords'=>$entradas->getMetas()->getKeywords(),
                                  'robots'=>$entradas->getMetas()->getRobots(),
                                  'autor'=>$entradas->getMetas()->getAutor(),
                                  'idMetas'=>$metaId,
                                  'idImatge'=>$idImatge,
                                  'idSeccio'=>$idSeccio,
                                  'idDoc'=>$idDocument,
                                  'update'=>'1'
                            ));
           
            
            $this->view->alert = 'Estás apunto de modificar esta entrada';

            $this->view->form = $form;

            $this->render('index');
        }
    }
    public function eliminarAction()
    {
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        $menu = (int) $this->getRequest()->getParam("s",0);
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $entrada = new Admin_Model_Entrada($id);
        
        $this->_entradaDoctrineDao->eliminar($entrada);
        
        //després d'esborrar updatejo a 0 el camp entrada del menú
        $this->_menuDoctrineDao->updateMenuDeEntrada($menu,0);
        
        $this->_redirect('/admin/entrada/listado/page/'.$page);
    }
    
    public function listadoAction()
    { 
         //carrego titles
        $this->view->head = "Easyweb :: Listado Contenido";
        
        $entradas = $this->_entradaDoctrineDao->obtenerTodos();
        $paginator = Zend_Paginator::factory($entradas);
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->paginator = $paginator;      
        $this->view->listEntrades = $paginator;
        
        //print $this->_entradaDoctrineDao->obtenerTodos();
              
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de entradas en la web';
        $this->_helper->layout->assign("welcome", $frase);
    }
    
    public function publicacioAction()
    {
        //passo l'id per updatejar
        $idEntrada = (int) $this->getRequest()->getParam("id",0);
        

        $valor = (int) $this->getRequest()->getParam("val",0);
        
        
        $this->_entradaDoctrineDao->privacitat($idEntrada,$valor);

        $this->_redirect('/admin/index');
    }
}