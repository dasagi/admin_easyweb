<?php
class Admin_GaleriaController extends Zend_Controller_Action {

    private $_config;
    private $_galeriaDoctrineDao;
    private $_imagenDoctrineDao;
    private $_login;
    private $_usuarioDao;
    private $_portadaDoctrineDao;
    //private $_galeria;
    
    public function init()
    {
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                $this->_usuarioDao = new Admin_Model_UsuarioDao();
                
                //creem objecte galeriaDoctrineDao
                $this->_galeriaDoctrineDao = new Admin_Model_GaleriaDoctrineDao();
                //creem objecte imagenDoctrineDao
                $this->_imagenDoctrineDao = new Admin_Model_ImagenDoctrineDao();
                //creem objecte portadaDoctrineDao
                $this->_portadaDoctrineDao = new Admin_Model_PortadaDoctrineDao();
                //instanciem view helper NetejaTags
                $this->view->neteja = new MisLibrerias_View_Helper_NetejaTags();
                //$this->_galeria = new Admin_Model_Galeria();
                
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");      
                                
                //afegeixo el jquery.fancybox.js i el jquery.fancybox.css
                $this->_baseUrl = $this->_request->getBaseUrl();
                
                //passo l'enllaç del tiny a la vista
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/tiny_mce/tiny_mce.js', 'text/javascript');
               
                $this->view->headScript()->appendFile($this->_baseUrl.'/js/jquery.fancybox.js', 'text/javascript');
                
                
                
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/css/jquery.fancybox.css');
                
                //passo el datapicker (calendari)
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/picker.js', 'text/javascript');
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/picker.date.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/classic.css');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/classic.date.css');
                
                //afegeixo el dropzone i checbox
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/checkbox.js', 'text/javascript');
                $this->view->headScript()->appendFile($this->_baseUrl.'/administrador/js/dropzone.min.js', 'text/javascript');
                $this->view->headLink()->appendStylesheet($this->_baseUrl.'/administrador/css/dropzone.css');
                
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
                
                //passo id de la galeria per paràmetre per tal de retornar la url correcta
                $id = (int) $this->getRequest()->getParam("id",0);
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
                                            setTimeout(function() {location.assign('".$this->_baseUrl."/admin/galeria/crear/id/".$id."');}, 1000);
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

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás crear galerías de imágenes';
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
        
        $form = new Admin_Form_Galeria();
        
        //creo multioptions per idioma
        $form->idioma->addMultiOptions($this->_galeriaDoctrineDao->obtenerIdiomasSelect());
         
        return $form;
    }
    
    private function _getFormImagen(){
        
        return new Admin_Form_GaleriaImg();
    }
       
    private function _getFormImagenEdit(){
        
        return new Admin_Form_ImagenEditar();
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
    
    public function indeximgAction()
    {
        //deshabilitem el layout perquè el creem a la vista guardarsubmenu.phtml
        $this->_helper->getHelper('layout')->disableLayout();
        //passo a la vista el formulari de la imatge
        $form = $this->_getFormImagenEdit();
        $this->view->form = $form;
        
        $id = (int) $this->getRequest()->getParam('img',0);
        
        //printo el que tenim a titol i descripció si están null
        $imagen = $this->_imagenDoctrineDao->obtenerImagenPorId($id);
        
        var_dump($imagen->getTitol()); 
        
        if($imagen->getTitol()!='NULL'){
            
            $form->populate(array(
                'titol'=>stripslashes($imagen->getTitol()),
                'descripcio'=>stripslashes($imagen->getDescripcio())
            ));            
            //passem l'alert a la vista
            $this->view->alert = "Edita el título y la descripción de la imágen";
        }  else {
            $this->view->alert = "Aquí puedes darle un título a la imágen y una descripción";
        }
        
                   
        //li passo l'id al action a imagenEditar per mantenir la id
        $form->setAction($this->_request->getBaseUrl().'/admin/galeria/guardarimagen/img/'.$id);      
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
        
        try{
            $id = $form->getValue('id');
            $data = new \DateTime($form->getValue('data'));
            $titol = $form->getValue('titol');
            $text = $form->getValue('contingut');
            $publicar = $form->getValue('public');
            $privat = $form->getValue('privat');     
            $idioma = $form->getValue('idioma');
            $tipo = $form->getValue('banner');
            $entrada = null;
            
            //els camps hidden
            $update = $form->getValue('update');
            $idMetas = $form->getValue('idMetas');
            
            //camps metas
            $title = $form->getValue('title');
            $description = $form->getValue('description');
            $keywords = $form->getValue('keywords');
            $robots = $form->getValue('robots');
            $autor = $form->getValue('autor');  
            
            //variables de portada
            
            $tipus = "galeria";
            
            //si li donem a publicar
            if(isset($publicar)){
                
                //creem objecte Galeria
                $galeria = new Admin_Model_Galeria($id,$data, $titol, $text, $idioma, $publicar,$tipo);

                    //el guardarem
                    $this->_galeriaDoctrineDao->guardar($galeria,$update);
                    
                    
                //guardem portada si no ve d'editar
                if($update==0){   
                
                    // a portada li he de passar un objecte referencial galeria per la relació a les clases entitats i $entrada null (els camps de la bbdd seràn null)
                                    
                    $portada = new Admin_Model_Portada(null, null, $galeria, null, null, null, $tipus);
                
                    $this->_portadaDoctrineDao->guardar($portada);
                 }

                //creem instancia de l'objecte metatags per guardar posteriorment
                
                $metas = new Admin_Model_Metatags($idMetas,$title,$description,$keywords,$robots,$autor,$idioma,$entrada,$galeria);//$entrada és l'id de metatags NO ÉS $entrada->getId() perquè està relacionat amb Doctrine OneToOne;

                //guardem els metas en la taula metatags associada al ID d'entrada
                
                    $this->_galeriaDoctrineDao->guardarMetas($metas,$idMetas);
                    
                //alert a la vista
                $this->view->alert = 'Galeria Publicada';
                
                //passem el form a la vista
                $this->view->form = $this->_getForm();
                
                //si venim d'una modificació li passo a/2 que es un alert que va a crearAction
                if($update==1){
                    
                    $this->_redirect('/admin/galeria/crear/id/'.$galeria->getId().'/a/2');
                
                }else {
                    
                    $this->_redirect('/admin/galeria/crear/id/'.$galeria->getId());              
                    
                }
            }
            //si li donem a privat
            if(isset($privat)){
                //creem objecte Galeria
                $galeria = new Admin_Model_Galeria($id, $data, $titol, $text, $idioma, $privat,$tipo);
                    
                    //el guardarem
                    $this->_galeriaDoctrineDao->guardar($galeria,$update);
                
                //creem instancia de l'objecte metatags per guardar posteriorment
                
                $metas = new Admin_Model_Metatags($idMetas,$title,$description,$keywords,$robots,$autor,$idioma,$entrada,$galeria);//$entrada és l'id de metatags NO ÉS $entrada->getId() perquè està relacionat amb Doctrine OneToOne;

                //guardem els metas en la taula metatags associada al ID d'entrada
                
                    $this->_galeriaDoctrineDao->guardarMetas($metas,$idMetas);
                    
                //alert a la vista
                $this->view->alert = 'Galeria Guardada';
                
                //passem el form a la vista
                $this->view->form = $this->_getForm();
                
                
                //si venim d'una modificació li passo a/2 que es un alert que va a crearAction
                if($update==1){
                    
                    $this->_redirect('/admin/galeria/crear/id/'.$galeria->getId().'/a/2');
                
                }else {
                    
                    $this->_redirect('/admin/galeria/crear/id/'.$galeria->getId());              
                    
                }
            }
        }catch (Exception $e){
            
            $this->view->alert = $e->getMessage();
            
            //passem el form a la vista i el poblem
            $this->view->form = $form;
            $form->populate($formData);
            return $this->render('index');
        }
    }
    
    public function guardarimagenAction()
    {
        //deshabilitem el layout perquè el creem a la vista guardarsubmenu.phtml
        $this->_helper->getHelper('layout')->disableLayout();
        
        //capturem l'img que ve de capturar l'action del indeximgAction
        $idImg = (int) $this->getRequest()->getParam("img",0);
       
        //creem el formulari
        $form = $this->_getFormImagenEdit();
        
        $formData = $this->_request->getPost();
        
        
        //si es valid el formulari, és a dir els paràmetres son correctes
        if(!$form->isValid($formData)){
            
            $this->view->form = $form;
            $form->populate($formData);
            
            //assigno l'id a l'action
            $form->setAction($this->_request->getBaseUrl().'/admin/galeria/guardarimagen/img/'.$idImg);
            
            $this->view->alert = "Aquí puedes darle un título a la imágen y una descripción";
            return $this->render('indeximg');
        }
            
            try{
                
                $titol = $form->getValue('titol');
                $descripcio = $form->getValue('descripcio');
                
                //fem update
                $this->_imagenDoctrineDao->updateImgDeGaleria($idImg,$titol,$descripcio);
                return $this->_redirect('admin/galeria/indeximg/img/'.$idImg);

            }catch (Exception $e){

                $this->view->alert = $e->getMessage();

                //passem el form a la vista i el poblem
                $this->view->form = $form;
                return $this->render('indeximg');
            }        
    }
    
    public function crearAction()
    {
        //passo el javascript del fancybox
        $this->view->headScript()->appendScript("
                                                 function donamId(id)
                                                 {
                                                    var valorId = document.getElementById(id).value;
                                                    document.getElementById('idValor').value = valorId;
						 }	 
                        "
                        );

        $idGal = (int) $this->getRequest()->getParam("id",0);
        
        //al formulari de la vista galeria.phtml li em de passar l'id de galeria
        
        $this->view->galId = $idGal;
        
        $imatges = $this->_imagenDoctrineDao->obtenerTodosDeGaleriaPorId($idGal);
        
        $numImg = count($imatges);
        
                //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $numImg;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {
                                $('#edita'+i).tooltip();
                                $('#publica'+i).tooltip();
                                $('#despublica'+i).tooltip();
                                $('#borra'+i).tooltip();
                                $('#sube'+i).tooltip();
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
                                    setTimeout(function() { $('.alert_lila').fadeOut('slow')}, 2500);
                                }
                        );

                        "
                        );
         
         
        
        //print_r($idGal);
        $galeriaId = $this->_galeriaDoctrineDao->obtenerGaleriaPorId($idGal);
        
        $this->view->galDades = $galeriaId;
           
        //var_dump($galeriaId);
        
        //////// CODI PER INSERTAR IMATGES A LA GALERIA N ///////////
        
        $form = $this->_getFormImagen();
        $formImg = $this->_getFormImagenEdit();
        
        /////// EDITAR LA IMATGE /////////
        /*
        foreach ($imatges as $imatge){
            
            echo $imatge->getTitol();
            echo $imatge->getDescripcio();
            
        }

        $formImg->populate(array('titol'=>$imatge->getTitol(),
                                  'descripcio'=>$imatge->getDescripcio()
                            ));
        */
        
        //assignem a la vista el form
        $this->view->form = $form;
        $this->view->formImg = $formImg;
        
        $neteja = new MisLibrerias_View_Helper_NetejaTags();
        
        try

            {
        //$neta = $neteja->netejaNomImatges($string);
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
                        
                        //variables classe Imagen 
                        $id = null;
                        $data = new \DateTime(date("Y-m-d H:i:s"));
                        $titol = null;
                        $ruta = $nomFinal;
                        $descripcio = null;
                        $galeria = new Admin_Model_Galeria($galeriaId->getId(), $galeriaId->getData(), $galeriaId->getTitol(), $galeriaId->getDescripcio(), $galeriaId->getIdioma(), $galeriaId->getPublicar(),$galeriaId->getTipo());
                        //$galeria = null;
                        $entradaId = null;
                        $ordre = null;
                        
                        //aquí farem l'insert
                        
                        $img = new Admin_Model_Imagen($id, $data, $titol, $ruta, $descripcio, $galeria, $entradaId, $ordre);
                        
                        //ha de ser update és a dir merge perquè sinó em crea una galeria i no ho volem
                        $this->_imagenDoctrineDao->updateImagenes($img);
                        
                    }
                }
            }
        catch (Exception $ex)
        {
           echo '¡Ups! Tenemos problemas al subir la imágen.';
           echo $ex->getMessage();
        }
        //print_r($galeriaId->getId());
        //passem a la vista el llistat de imatges per galeria
        $this->view->listImagenes = $imatges;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, aquí podrás subir imágenes a la galeria creada';
        $this->_helper->layout->assign("welcome", $frase);
        
        //passo paràmetre de l'alert
        $a = (int) $this->getRequest()->getParam("a",0);
        
        if($a===1){
            $this->view->alert="Imágen eliminada correctamente";
        }else if($a===2){
            $this->view->alert="Puedes modificar esta galeria insertando más imágenes";
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
        $metaId = (int) $this->getRequest()->getParam("m",0);
        
        //passem el form
        $form = $this->_getForm();
        
        //si està buida la id podem insertar
        if(empty($id)){
            
           $form = $this->_getForm();
           $this->view->form = $form;
           $this->view->alert = 'Puedes crear el contenido de tus entradas en este formulario';
           $this->render('index');
           
         //si no podem updatejar
        }else{
            
            //instancia del mètode
            $galerias = $this->_galeriaDoctrineDao->obtenerGaleriaPorId($id);
            
            //poblem els formulari amb la galeria corresponent
            $form->populate(array('id'=>$galerias->getId(),
                                  'data'=>$galerias->getData()->format('d-m-Y'),
                                  'titol'=>stripslashes($galerias->getTitol()),
                                  'idioma'=>  $galerias->getIdioma(),
                                  'contingut'=>stripslashes($galerias->getDescripcio()),
                                  'title'=>stripslashes($galerias->getMetas()->getTittle()),
                                  'description'=>stripslashes($galerias->getMetas()->getDescription()),
                                  'keywords'=>stripslashes($galerias->getMetas()->getKeywords()),
                                  'robots'=>$galerias->getMetas()->getRobots(),
                                  'autor'=>$galerias->getMetas()->getAutor(),
                                  'idMetas'=>$metaId,
                                  'banner'=>$galerias->getTipo(),
                                  'update'=>'1'
                            ));
            
            $this->view->alert = 'Estás apunto de modificar esta galería';

            $this->view->form = $form;

            $this->render('index');
        }   
    }
 
    public function publicacioAction()
    {
        //passo l'id per updatejar
        $idGal = (int) $this->getRequest()->getParam("id",0);
        
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        //valor de publicar o despublicar
        $valor = (int) $this->getRequest()->getParam("val",0);
        
        $controller = $this->getRequest()->getparam("c",0);
        
        
        $this->_galeriaDoctrineDao->privacitat($idGal,$valor);
        
        if($controller ==='index'){
            $this->_redirect('/admin/index');
        }else{
            $this->_redirect('/admin/galeria/listado/page/'.$page);
        }
    }
    
    public function eliminarAction()
    {
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);
        //passo page per quedarnos a la pàgina d'on venim
        $page = (int) $this->getRequest()->getParam("page",0);
        //passo d'on bé per redireccionar després
        $controller = $this->getRequest()->getparam("c",0);
        
        $this->_galeriaDoctrineDao->eliminar($id);
        
        if($controller ==='index'){
            
             $this->_redirect('/admin/index/index/a/2');
        }else{
            $this->_redirect('/admin/galeria/listado/page/'.$page.'/a/1');
        }
    }
    
    public function eliminarimagenAction()//no puc escriure camelcase als controllers només está així l'Action
    {
        //passo paràmetre galeria
        
        $gal = (int) $this->getRequest()->getParam("gal",0);
        
        //passo id per eliminar
        $id = (int) $this->getRequest()->getParam("id",0);

        //passo el alert
        
        $alert = (int) $this->getRequest()->getParam("a",0);
        
        $imagen = new Admin_Model_Imagen($id);
        //$imagen->setId($id);
        
        $query = $this->_imagenDoctrineDao->obtenerImagenPorId($id); 
        
        $getRuta = $query->getRuta();

        $ruta = $this->_config->parametros->mvc->admin->imagen->index->upload.$getRuta;       
        
        unlink($ruta);
        
        //eliminar imatge de la bbdd
        $this->_imagenDoctrineDao->eliminar($imagen);
        
        
        if($alert ===3){
            $this->_redirect('/admin/index/index/a/3');
        }else{
            $this->_redirect('/admin/galeria/crear/id/'.$gal.'/a/1');         
        }
    }

    public function listadoAction()
    { 
         // conto totes les files de galeries
                $numeroGalerias = count($this->_galeriaDoctrineDao->obtenerTodos());
                //passo el javascript
                $this->view->headScript()->appendScript("
                            var num = $numeroGalerias;
                            $(function () {
                                for(i=1;i<=num;i++)
                                {
                                $('#edita'+i).tooltip();
                                $('#publica'+i).tooltip();
                                $('#despublica'+i).tooltip();
                                $('#borra'+i).tooltip();
                                $('#sube'+i).tooltip();
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
                                    setTimeout(function() { $('.alert_lila').fadeOut('slow')}, 2500);
                                }
                        );

                        "
                        );
         
         
        $paginator = Zend_Paginator::factory($this->_galeriaDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->paginator = $paginator;      
        $this->view->listGalerias = $paginator;
        
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de galerias de la web';
        $this->_helper->layout->assign("welcome", $frase);
        
        //mostro l'aler passant-li per get
        $a = (int) $this->getRequest()->getParam("a",0);
        
        if($a===1){
            $this->view->alert="Imágen galeria eliminada correctamente";
        }
    }
    
        public function listAction()
    { 
        $paginator = Zend_Paginator::factory($this->_imagenDoctrineDao->obtenerTodos());
        $paginator->setCurrentPageNumber($this->_getParam('page',0));
        
        //llistem 8 files
        $paginator->setItemCountPerPage(10);
        
        $this->view->paginator = $paginator;      
        $this->view->listImagenes = $paginator;
               
        $frase = '<b><em>'.$this->view->user->nom.'</b></em>, estás en el listado de galerias de la web';
        $this->_helper->layout->assign("welcome", $frase);
    }
}