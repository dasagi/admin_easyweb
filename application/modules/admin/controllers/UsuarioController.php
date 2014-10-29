<?php
class Admin_UsuarioController extends Zend_Controller_Action {

    private $_config;
    private $_usuarioDao;
    private $_login;
    private $_numeroUsuaris;
    private $_portadaDoctrineDao;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                $this->_login = new Admin_Model_Login();
                $this->_portadaDoctrineDao = new Admin_Model_PortadaDoctrineDao(); 
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");
                               
                // conto totes les files de usuaris i necessito critar al zend paginator
                $paginator = Zend_Paginator::factory($this->_usuarioDao->getTable()->select());
                $this->_numeroUsuaris = count($this->_usuarioDao->obtenerTodos($paginator));
                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
    }

    public function preDispatch()//se llama antes de caualquier acción
    {
              
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
          //assignem el nom al loyout
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás dar de alta usuarios para gestionar la web';
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
        
        return new Admin_Form_Usuario();
    }
    
    private function _getFormAvatar(){
        
        return new Admin_Form_EditAvatar();
        
    }
    public function indexAction(){
        
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

            $this->_forward('crear'); //despachamos al controlador crear que presenta el render de la vista index.phtml
    }
    public function crearAction(){
        
        $this->view->form = $this->_getForm();
        $this->view->titulo = $this->_config->parametros->mvc->admin->usuarios->index->titulo;
        $this->view->footer = $this->_config->parametros->footer;

        $this->render('index');
    }
    
    public function guardarAction()
    {
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

        if(!$this->getRequest()->isPost()){

                   $this->_redirect('/usuario/index/listado/');
                   return;
        }

        $form = $this->_getForm();
        $formData = $this->_request->getPost();
        
        //var_dump($formData);
        
        if(!$form->isValid($formData)){

            $this->view->form = $form;
            $form->populate($formData);
            return $this->render('index');
        }
        
        $email = null;
        $pass1 = null;
        $pass2 = null;
        
        $usuario = new Admin_Model_Usuario();
        
            $usuario->setId($form->getValue('id'));
            $usuario->setData($form->getValue('data'));
            $usuario->setNom($form->getValue('nombre'));
            $usuario->setCognoms($form->getValue('apellido'));
            $usuario->setMail($form->getValue('email'));
            $usuario->setAvatar($form->getValue('avatar'));
            $usuario->setPassword($form->getValue('password'));
            $usuario->setCategoria($form->getValue('cat'));
            
          
            //echo $email = $usuario->setMail($form->getValue('email'));

            $email = $form->getValue('email');
            $pass1 = $form->getValue('password');
            $pass2 = $form->getValue('repeat');
            $tipus = "usuari";
        
            if($this->_usuarioDao->ifExistEmail($email) && $form->getValue('update')==='0' ){


                $this->view->alert = 'Ya existe un usuario con este correo electrónico, tendrás que intentar de nuevo';
                $this->view->form = $form;
                $form->populate($formData);
                    
                
                return $this->render('index');

            }elseif ($pass1!= $pass2) {

                $this->view->alert = 'La contraseña repetida no coincide';
                $this->view->form = $form;
                $form->populate($formData);
                
                //modifico el botó 
                if ($form->getValue('update')==='1'){
                    
                    $form->getElement('usuario')->setLabel('Modificar'); 
                   
                }
                return $this->render('index');          
            }
            else{

                $this->_usuarioDao->guardar($usuario);
                
                
               
                if($form->getValue('update')==='0'){
                 
                        // a portada li he de passar un objecte referencial $usuario per la relació a les clases entitats i a la resta null (els camps de la bbdd seràn null)
 
                       /*
                        $portada = new Admin_Model_Portada(null, null, null, null, $usuario, null, $tipus);

                        $this->_portadaDoctrineDao->guardar($portada);
                       */
                 
                    $this->view->alert = 'Acabas de crear un usuario. Puedes seguir editando usuarios o qualquier otra acción';
                }else{
                    
                     $this->view->alert = 'Acabas de editar un usuario. Puedes seguir editando usuarios o qualquier otra acción';
                                    
                }
                return $this->forward('index','usuario','admin');
            }
        
    }

    public function editarAction()
    {   
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
                
        $id = (int) $this->getRequest()->getParam("id",0);
        
        $form = $this->_getForm();
        
        
        if(empty($id)){
            
            $form = $this->_getForm();
            $this->view->alert = 'Crea un usuario nuevo para administrar el web';
            
            return $this->forward('index','usuario','admin');
            
        }else{
        
            $usuario = $this->_usuarioDao->obtenerPorId($id);

            $form->populate(array('id'=>$usuario->getId(),'data'=>$usuario->getData(),'nombre'=>$usuario->getNom(),'apellido'=>$usuario->getCognoms(),
                'email'=>$usuario->getMail(),'avatar'=>$usuario->getAvatar(),'update'=>'1','password'=>$usuario->getPassword()));
            
            //li diem al botó usuari que canvii el nom a editar
            $form->getElement('usuario')->setLabel('Modificar'); 
            
            $this->view->alert = 'Estás apunto de modificar este usuario';

            $this->view->form = $form;

            $this->render('index');
        }
    }
    
    public function editavatarAction(){
   
        //deshabilitem el layout perquè el creem a la vista guardarsubmenu.phtml
        $this->_helper->getHelper('layout')->disableLayout();
        
        //$postParams =  $this->_request->getPost('avatar');
        
        
        
        //assignem l'id passat per get
        $id = (int) $this->getRequest()->getParam("id",0);
        
        //passem el form
        $form = $this->_getFormAvatar();
        
        $this->view->form = $form;
        
        //assigno l'id a l'action
        $form->setAction($this->_request->getBaseUrl().'/admin/usuario/editavatar/id/'.$id);
        
        //cridem la ruta per fer l'upload (está al config)
        $ruta = $this->_config->parametros->mvc->admin->imagen->avatar->index->upload;
        
        try
        {
            //instancia de zend file 
            $upload = new Zend_File_Transfer_Adapter_Http();

            //afegim la ruta on anirà
            $upload->setDestination($ruta);

            // això equival a $_FILE
            $files = $upload->getFileInfo();
            $nom = $upload->getFileName();
            $size = $upload->getFileSize();
            
            //print_r($size);
            
            //recorro files per agafar només el nom de la imatge que pujo
            foreach($files as $fileName=>$fileInfo){
                $nomFoto =  $fileInfo['name'];
                //print_r($nombre);
                //exit();
                if($upload->isUploaded($nomFoto)){
                    
                    //filtre de 200 kb
                    $upload->addValidator('Size', false, array('min' => 20, 'max' => 200000));
                    //filtre formats
                    $upload->addValidator('Extension', false, 'jpg,png');
                    
                    if (!$upload->isValid()) {
                        
                        $this->view->alert="¡Ups! El archivo ha de ser jpg o png y no puede ser superior a 200 KB";
                        
                    }else{
                        //això és el move_uploaded_file
                        $upload->receive();

                        //obtenim les dades de l'usuari per ID
                        $usuarioId = $this->_usuarioDao->obtenerPorId($id);

                        //variables classe Usuari per fer l'insert
                        $usuario = new Admin_Model_Usuario();

                            $usuario->setId($id);
                            $usuario->setData($usuarioId->getData());
                            $usuario->setNom($usuarioId->getNom());
                            $usuario->setCognoms($usuarioId->getCognoms());
                            $usuario->setMail($usuarioId->getMail());
                            $usuario->setAvatar($nomFoto);
                            $usuario->setPassword($usuarioId->getPassword());
                            $usuario->setCategoria($usuarioId->getCategoria());

                            //guardem amb el mètode guardar
                            $this->_usuarioDao->guardarAvatar($usuario);

                            $this->view->alert="¡Bien! acabas de cambiar tu avatar";
                    }
                }
            }
        }
        catch (Exception $ex)
        {
                $this->view->alert="¡Ups! Algún error ha ocurrido porque no se ha podido subir la imágen ".$ex->getMessage();         
        }
            
        $this->render('avatar');
    }

    public function eliminarAction()
    {
        //capturo els gets
        $id = (int) $this->getRequest()->getParam("id",0);
        
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $controlador = $this->getRequest()->getParam("c",0);
        
        $usuario = new Admin_Model_Usuario();
        $usuario->setId($id);

        $this->_usuarioDao->eliminar($usuario);
        
        if($controlador === 'index'){
            $this->_redirect('/admin/index/index/a/4');
        }else{
            $this->_redirect('/admin/usuario/listado/page/'.$page);
        }
    }

    public function listadoAction()
    { 
        
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
        //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $this->_numeroUsuaris;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {		
                                $('#editar'+i).tooltip();
                                $('#borrar'+i).tooltip();
                                }
                                //alert(i);
                            });"
                        );
                
        //El DAO debe tener un método getTable que retorne la clase Db_Table para pasar el select():
        $paginator = Zend_Paginator::factory($this->_usuarioDao->getTable()->select()->order('data DESC'));
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->listaUsuario = $this->_usuarioDao->obtenerTodos($paginator);
        $this->view->paginator = $paginator;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de tus usuarios administradores';
        $this->_helper->layout->assign("welcome", $frase);
        
    }
}