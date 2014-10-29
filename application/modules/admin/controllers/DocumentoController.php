<?php
class Admin_DocumentoController extends Zend_Controller_Action {

    private $_config;
    private $_login;
    private $_documentoDoctrineDao;
    private $_numPage = 16;
    private $_usuarioDao;
    private $_portadaDoctrineDao;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                
                //creem objecte bannerDoctrineDao
                $this->_documentoDoctrineDao = new Admin_Model_DocumentoDoctrineDao();
                //creem objecte portadaDoctrineDao
                $this->_portadaDoctrineDao = new Admin_Model_PortadaDoctrineDao();
                            
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");      
                 
                //instanciem view helper NetejaTags
                $this->view->neteja = new MisLibrerias_View_Helper_NetejaTags();
                $this->view->mostraImgDoc = new MisLibrerias_View_Helper_Utilitats();
                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
                //$this->view->headScript()->appendFile($this->_baseUrl.'/js/jquery.fancybox.js', 'text/javascript');
                //$this->view->headLink()->appendStylesheet($this->_baseUrl.'/css/jquery.fancybox.css');

                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/dropzone.min.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/dropzone_docs.css');
                
                // conto totes les files dels docs
                $numeroDocs = count($this->_documentoDoctrineDao->obtenerTodos());
                
                //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $numeroDocs;
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
                //codi per fer desapareixer l'alert
                $this->view->headScript()->appendScript("
                    
                        $(document).ready
                        (
                                function()
                                {
                                    setTimeout(function() { $('.alert').fadeOut('slow')}, 2500);
                                }
                        );

                        "
                        );
                //passo el javascript del dropzone
                $this->view->headScript()->appendScript("
                            Dropzone.options.myDrop = {

                            maxFilesize: 2, // MB
                            parallelUploads: 20,
                            maxFiles: 20,
                            acceptedFiles:'.pdf,.doc,.docx,.xls',
                            init: function() {
                                    //no mostrem els que passin de maxfiles
                                    this.on(\"maxfilesexceeded\", function(file) { this.removeFile(file);});
                                    //quan ha acabat mostra un alert i recarrega
                                this.on(\"complete\", function() {
                                if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                                            //recarrega quan acabi	
                                            //alert('ya ta');
                                            //location.reload();
                                            if(this.getAcceptedFiles()==0){
                                                alert('Este archivo no está permitido. Sube solo archivos .pdf, .doc, .docx , y de 2 MB');
                                                //$(\"#cuerpo\").html('<div class=\"alert alert_lila\"></div>');
                                                location.reload();
                                            }
                                            setTimeout(function() {location.assign('".$this->_baseUrl."/admin/documento/listado');}, 1000);
                                }
                              });	
                            }
                            };                    
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
    
    private function _getFormDoc(){
        
        return new Admin_Form_Documento();
    }

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
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

    public function indexAction()
    {
        $paginator = Zend_Paginator::factory($this->_documentoDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage($this->_numPage);
        
        $this->view->paginator = $paginator;      
        $this->view->listImagenes = $paginator;
        
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás almacenar documentos';
        $this->_helper->layout->assign("welcome", $frase);      
        
        $this->view->form = $this->_getFormDoc();
        
        $neteja = new MisLibrerias_View_Helper_NetejaTags();
        //$neta = $neteja->netejaNomImatges($string);
        
        $tipus = "document";
        try

            {
            //instancia de zend file 
            $upload = new Zend_File_Transfer_Adapter_Http();
            // això equival a $_FILE
            $files = $upload->getFileInfo();
            
            //cridem la ruta per fer l'upload (está al config)
            $ruta = $this->_config->parametros->mvc->admin->documento->index->upload;      
            //recorem les files per treure en name
            foreach ($files as $fileName=>$fileInfo)
                {   
                                         
                    $nom = $neteja->netejaNomImatges($fileInfo['name']);
                      
                    $time = substr(time(),6);
                    
                    //agafo l'extensió o format després del '.'
                    $extensio = substr($nom, strpos($nom,'.') + 1);
                    
                    //separo el nom abans del punt
                    $nomAnt = substr($nom,0, strpos($nom,'.'));
                        
                    $nomFinal = $nomAnt.$time.'.'.$extensio;
                    
                    if($upload->isUploaded($fileInfo['name'])){
                        
                        //afegim un filtre per renombrar les imatges
                        $upload->addFilter('Rename', array('target' => $ruta.$nomFinal,
                        'overwrite' => true));
                        //això és el move_uploaded_file
                        $upload->receive();
                        
                        //variables classe Imagen insert
                        $id = null;
                        $data = new \DateTime(date("Y-m-d H:i:s"));
                        $titol = null;
                        $ruta = $nomFinal;
                        $entradaId = null;
                        
                        //aquí farem l'insert
                        
                        $docs = new Admin_Model_Documento($id, $data, $titol, $ruta, $entradaId);
                        $this->_documentoDoctrineDao->guardarDocumentos($docs);
                        
                        // a portada li he de passar un objecte referencial $docs per la relació a les clases entitats (els altres camps de la bbdd seràn null)
                    
                        $portada = new Admin_Model_Portada(null, null, null, $docs, null, null, $tipus);  
                    
                        //print_r($portada);
                        //exit();                    
                        $this->_portadaDoctrineDao->guardar($portada);
                        
                    }
                }
            }
            
        catch (Exception $ex)
        {
           echo '¡Ups! Tenemos problemas al subir el archivo.';
           echo $ex->getMessage();
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
        //passo paràmetre up per eliminar si ve del upload
        
        $up = (int) $this->getRequest()->getParam("up",0);
        
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $controlador = $this->getRequest()->getParam("c",0);
        
        $documento = new Admin_Model_Documento($id);
        //$imagen->setId($id);
        
        $query = $this->_documentoDoctrineDao->obtenerDocumentoPorId($id); 
        
        $getRuta = $query->getRuta();

        $ruta = $this->_config->parametros->mvc->admin->documento->index->upload.$getRuta;       
        
        unlink($ruta);
        
        //eliminar imatge de la bbdd
        $this->_documentoDoctrineDao->eliminar($documento);
        
        if($up===1){
            $this->_redirect('/admin/documento/index/page/'.$page);         
        }else if($controlador === 'index'){
            $this->_redirect('/admin/index/index/a/5');
        }else{
            $this->_redirect('/admin/documento/listado/page/'.$page.'/a/1');
        }
    }
    
    public function listadoAction()
    { 
        $paginator = Zend_Paginator::factory($this->_documentoDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->paginator = $paginator;      
        $this->view->listDocs = $paginator;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de banners de la web';
        $this->_helper->layout->assign("welcome", $frase);
        
        $a = (int) $this->getRequest()->getParam("a",0);
            
        if($a===1){
            $this->view->alert="Documento eliminado correctamente";
        }
    }
}