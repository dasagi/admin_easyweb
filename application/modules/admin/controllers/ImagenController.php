<?php
class Admin_ImagenController extends Zend_Controller_Action {

    private $_config;
    private $_imagenDoctrineDao;
    private $_galeriaDoctrineDao;
    private $_login;
    private $_numPage = 16;
    private $_usuarioDao;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                
                //creem objecte ImagenDoctrineDao
                $this->_imagenDoctrineDao = new Admin_Model_ImagenDoctrineDao();
                //creem objecte GaleriaDoctrineDao
                
                $this->_galeriaDoctrineDao = new Admin_Model_GaleriaDoctrineDao();
                
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");  
                
                //instanciem view helper NetejaTags
                $this->view->neteja = new MisLibrerias_View_Helper_NetejaTags();
                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/jquery.fancybox.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/css/jquery.fancybox.css');
                //afegeixo el dropzone i checbox
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/checkbox.js', 'text/javascript');
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/dropzone.min.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/dropzone.css');

                // conto totes les files de entrades
                $numeroImagenes = count($this->_imagenDoctrineDao->obtenerTodos());
                
                //passo el javascript del tooltip
                $this->view->headScript()->appendScript("
                            var num = $numeroImagenes;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {		
                                $('#borra'+i).tooltip();
                                }
                                //alert(i);
                            });
                        "
                        );
                //passo el javascript del fancybox
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
                //passo el javascript del dropzone
                $this->view->headScript()->appendScript("
                            Dropzone.options.myDrop = {

                            maxFilesize: 0.80, // MB
                            parallelUploads: 20,
                            maxFiles: 20,
                            acceptedFiles:'.jpg,.png,.jpeg,.gif',
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
                                                alert('Este archivo no está permitido. Sube solo archivos .jpg, .png y .gif y de 800 kb');
                                                //$(\"#cuerpo\").html('<div class=\"alert alert_lila\"></div>');
                                                location.reload();
                                            }
                                            setTimeout(function() {location.assign('".$this->_baseUrl."/admin/imagen/listado');}, 1000);
                                }
                              });	
                            }
                            };                    
                        "
                        );
                
                //passo javascript al body del layout.phtml amb variable "jquery" 
                //assigno la variable al layout
                $this->_helper->layout()->assign("jquery","
                            <script>
                                $('#btnSnorkel').on('click',function(){

                                    var strListaMarcados='';
                                    var objMarcados=$('li.clsMarcado');
                                    var coma = ',';
                                    var iCantidad=objMarcados.length

                                    $(objMarcados).each(function(){
                                            //obtenemos el texto del elemento y lo agregamos a la variable

                                            //strListaMarcados+=$.trim($(this).text())+coma;
                                            var cadena = $('img', this).attr('src');
                                            strListaMarcados+=cadena.replace(\"".$this->_baseUrl."/files/img/\",'')+coma;
                                    });
                                    alert(strListaMarcados);
                                    if(strListaMarcados==''){
                                        //alert('lalalal');
                                        location.assign('".$this->_baseUrl."/admin/imagen/galeria');
                                    }else{
                                        //passar el valor a post amb el camp hidden variable
                                        document.getElementById(\"variable\").value=strListaMarcados;
                                    }
                                 });
                            </script>"
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
   
    private function _getFormImagen(){
        
        return new Admin_Form_Imagen();
    }

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás almacenar imágenes';
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
        $paginator = Zend_Paginator::factory($this->_imagenDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage($this->_numPage);
        
        $this->view->paginator = $paginator;      
        $this->view->listImagenes = $paginator;
        
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás almacenar imágenes';
        $this->_helper->layout->assign("welcome", $frase);      
        
        $this->view->form = $this->_getFormImagen();
        
        $neteja = new MisLibrerias_View_Helper_NetejaTags();
        //$neta = $neteja->netejaNomImatges($string);
        
        try

            {
            //instancia de zend file 
            $upload = new Zend_File_Transfer_Adapter_Http();
            // això equival a $_FILE
            $files = $upload->getFileInfo();
            
            //cridem la ruta per fer l'upload (está al config)
            $ruta = $this->_config->parametros->mvc->admin->imagen->index->upload;      
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
                        $descripcio = null;
                        $galeria = null;
                        $entradaId = null;
                        $ordre = null;
                        
                        //aquí farem l'insert
                        
                        $imatges = new Admin_Model_Imagen($id, $data, $titol, $ruta, $descripcio, $galeria, $entradaId, $ordre);
                        $this->_imagenDoctrineDao->guardarImagenes($imatges);
                    }
                }
            }
            
        catch (Exception $ex)
        {
           echo '¡Ups! Tenemos problemas al subir la imágen.';
           echo $ex->getMessage();
        }
    }
     
    //aquesta ja no la faig servir però com a experiment la deixo
    public function guardarAction()
    {    
        $gal = (int) $this->getRequest()->getParam("id");
          
        //al formulari de la vista galeria.phtml li em de passar l'id de galeria
        
        $this->view->galId = $gal;
        
        //repetim el codi de galeria perquè fa un render a galeria
        
        $listImatges = $this->_imagenDoctrineDao->obtenerTodos();
            
        $this->view->listImagenes = $listImatges;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>,aquí podrás almacenar imágenes';
        $this->_helper->layout->assign("welcome", $frase);
        
        try
        {
            $imgs  = substr($this->_request->getPost('variable'), 0, -1);//he de treure la darrera coma
            $img = explode(",", $imgs);        
            $galeriaId = $this->_galeriaDoctrineDao->obtenerGaleriaPorId($gal);
                  
            //$id = $this->_entradaDoctrineDao->obtenerParentPorIdSelectEditar($menuId);
            
            //si la variable post està buida
            if(!$imgs===FALSE){
                foreach ($img as $valor)
                    {
                        //print_r($valor);

                        $id = null;
                        $data = new \DateTime(date("Y-m-d H:i:s"));
                        $titol = null;
                        $ruta = $valor;
                        $descripcio = null;
                        //com és una relació li em de passar un objecte galeria
                        $galeria = new Admin_Model_Galeria($galeriaId->getId(), $galeriaId->getData(), $galeriaId->getTitol(), $galeriaId->getDescripcio(), $galeriaId->getIdioma(), $galeriaId->getPublicar());
                        $entradaId = null;
                        $ordre = null;
                        
                        $imatges = new Admin_Model_Imagen($id, $data, $titol, $ruta, $descripcio, $galeria, $entradaId, $ordre);
                        $this->_imagenDoctrineDao->updateImagenes($imatges);
                    }  
                    
               $this->view->alert="Acabas de crear una galeria con imágenes";
           }else{
               $this->view->alert="Tienes que escoger alguna imágen";
           }
        }
        catch (Exception $ex)
        {
           $this->view->alert="¡Ups! No hemos podido crear la galeria ".$ex->getMessage();         
        }
        
        return $this->render('galeria');
    }

    public function galeriaAction()
    {
        $imatges = $this->_imagenDoctrineDao->obtenerTodos();
        
            
        $this->view->listImagenes = $imatges;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>,aquí podrás almacenar imágenes';
        $this->_helper->layout->assign("welcome", $frase);

    } 

    public function eliminarAction()
    {
        //passo paràmetre up per eliminar si ve del upload
        
        $up = (int) $this->getRequest()->getParam("up",0);
        
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        
        $imagen = new Admin_Model_Imagen($id);
        //$imagen->setId($id);
        
        $query = $this->_imagenDoctrineDao->obtenerImagenPorId($id); 
        
        $getRuta = $query->getRuta();

        $ruta = $this->_config->parametros->mvc->admin->imagen->index->upload.$getRuta;       
        
        unlink($ruta);
        
        //eliminar imatge de la bbdd
        $this->_imagenDoctrineDao->eliminar($imagen);
        
        if($up===1){
            $this->_redirect('/admin/imagen/index/page/'.$page);         
        }else{
            $this->_redirect('/admin/imagen/listado/page/'.$page.'/a/1');
        }
    }
    
    public function listadoAction()
    { 
         //total número imatges
        $this->view->total = count($this->_imagenDoctrineDao->obtenerTodos());
        
        $paginator = Zend_Paginator::factory($this->_imagenDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage($this->_numPage);
        
        $this->view->paginator = $paginator;      
        $this->view->listImagenes = $paginator;
         
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de imagenes de la web';
        $this->_helper->layout->assign("welcome", $frase);
        
        $a = (int) $this->getRequest()->getParam("a",0);
        
        if($a===1){
            $this->view->alert="Imágen eliminada correctamente";
        }

    }
}