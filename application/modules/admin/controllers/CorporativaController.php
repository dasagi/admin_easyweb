<?php
class Admin_CorporativaController extends Zend_Controller_Action {

    private $_config;
    private $_corporativaDoctrineDao;
    private $_login;
    private $_usuarioDao;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                
                //creem objecte corporativaDoctrineDao
                $this->_corporativaDoctrineDao = new Admin_Model_CorporativaDoctrineDao();
                
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");      
                                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
                
                //passo l'enllaç del tiny a la vista
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/tiny_mce/tiny_mce.js', 'text/javascript');
               
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/jquery.fancybox.js', 'text/javascript');
                    
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/css/jquery.fancybox.css');
               
                //passo el javascript del fancybox
                $this->view->headScript()->appendScript("
                             $(document).ready(function() {
			
                                $('.imgs').fancybox({
                                    'showCloseButton'	: false,
                                    'titlePosition' 	: 'inside',
                                    'titleFormat'	: 'formatTitle'
                                });
                  
                            });
                        "
                        );
                
                            

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


    }

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, es importante actualizar los datos de tu empresa';
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
        
        $form = new Admin_Form_Corporativa();
        return $form;
    }
       
    public function indexAction()
    {
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
      
         //objecte form a la vista    
        $this->view->form = $this->_getForm();
    }
    
    public function guardarAction()
    {
            
        
        
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
        
        //trec l'update del try per fer una condició, si es 1 printam les dades a la pantalla
         $update = $form->getValue('update');
         
            try{

                $empresa = $form->getValue('empresa');
                $adreca = $form->getValue('adreca');
                $poblacio = $form->getValue('poblacio');
                $cp = $form->getValue('cp');     
                $telefon = $form->getValue('telefon');
                $fax = $form->getValue('fax');
                $movil = $form->getValue('movil');
                $email = $form->getValue('email');
                $web = $form->getValue('web');
                $gmaps = $form->getValue('gmaps');
                $legal = $form->getValue('legal');

                //els camps hidden
                $id = $form->getValue('id');
                
                //creem objecte Corporativa
                $corporativa = new Admin_Model_Corporativa($id,$empresa,$adreca,$cp,$poblacio, $telefon,$fax,$movil,$email,$web,$legal,$gmaps);

                $this->_corporativaDoctrineDao->guardar($corporativa,$update);
                
                //alert a la vista
                $this->view->alert = 'Datos de empresa Guardados';

                //passem el form a la vista
                $this->view->form = $this->_getForm();
                
                if($update==0){
                    return $this->render('index');
                }else{
                    return $this->_redirect('admin/corporativa/editar/id/1/a/1');
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

       //assignem l'id passat per get
        $id = (int) $this->getRequest()->getParam("id",0);
        //assignem l'alert que ve de guardar
        $a = (int) $this->getRequest()->getParam("a",0);
        
        //passem el form
        $form = $this->_getForm();
        
        //si està buida la id podem insertar
        if(empty($id)){
            
           $form = $this->_getForm();
           $this->view->form = $form;
           $this->view->alert = 'Estos datos se verán en tu web, es necesario rellenar todos los campos';
           $this->render('index');
           
         //si no podem updatejar
        }else{
            
            //instancia del mètode
            $corporativa = $this->_corporativaDoctrineDao->obtenerCorporativaPorId($id);
            
            //poblem els formulari amb la galeria corresponent
            $form->populate(array('id'=>$corporativa->getId(),
                                  'empresa'=>$corporativa->getEmpresa(),
                                  'adreca'=>$corporativa->getAdreca(),
                                  'poblacio'=>$corporativa->getPoblacio(),
                                  'cp'=>$corporativa->getCp(),
                                  'telefon'=>$corporativa->getTelefon(),
                                  'fax'=>$corporativa->getFax(),
                                  'movil'=>$corporativa->getMovil(),
                                  'email'=>$corporativa->getEmail(),
                                  'web'=>$corporativa->getWeb(),
                                  'gmaps'=>$corporativa->getGmaps(),
                                  'legal'=>$corporativa->getLegal(),
                                  'update'=>'1'
                            ));
            if($a==1){
                $this->view->alert = 'Datos de empresa Guardados';
            }else{
                
                $this->view->alert = 'Estos datos se verán en tu web, es necesario rellenar todos los campos';
            }
            $this->view->form = $form;

            $this->render('index');
        }   
    } 
}