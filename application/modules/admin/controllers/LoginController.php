<?php

class Admin_LoginController extends Zend_Controller_Action
{
	private $_config;
	private $_usuarioDao;
        private $_login;
        private $_domini;
        private $_template;

    public function init()
    {
		$this->_usuarioDao = new Admin_Model_UsuarioDao();
		$this->_config = Zend_Registry::get('config');
		$this->view->baseUrl = $this->getRequest()->getBaseUrl();
                
                $this->_baseUrl = $this->_request->getBaseUrl();
                
                $this->_login = new Admin_Model_Login();
                
                //canviem el layout d'admin
                $this->_helper->layout()->setViewScriptPath(APPLICATION_PATH."/layouts/scripts/admin/");      
                              
                //agafo el domini
                $this->_domini = $_SERVER['SERVER_NAME'];
    }

    public function preDispatch()//se llama antes de caualquier acción
    {
        if(Admin_Model_Login::isLoggedIn()){
            $this->view->loggedIn = true;
            $this->view->user = Admin_Model_Login::getIdentity();
            
            $frase = 'Hola <b><em>'.$this->view->user->nom.'</b></em> ¿Qué te gustaría hacer?';
            $this->_helper->layout->assign("welcome", $frase);
            
             //assignem l'avatar al layout
            $avatar = $this->view->user->avatar;
            $this->_helper->layout->assign("avatar", $avatar);
            
            $this->_helper->layout->assign("header",true); //passo variable al layout per després mostrar el menú icons
            
        }
    }

    private function _getForm(){

        return new Admin_Form_Login();
    }
    
    private function _getFormForget(){
        
        return new Admin_Form_LoginForget();
    }
    public function indexAction()
    {
              //Ponemos los titulos y el texto del footer
        $this->view->titulo = $this->_config->parametros->mvc->admin->login->index->titulo;
        $this->view->footer = $this->_config->parametros->footer;
        $this->view->form = $this->_getForm();
                    
           if (!Admin_Model_Login::isLoggedIn()){ //si no està logejat posam layout login
            
            //carrego un el layout del login
            $this->_helper->layout->setLayout('layoutLogin');
            
           }else{
               
               //si està autenticat redireccionem a la principal admin/index
               return $this->_redirect('/admin/index');
           }
    }
    
    public function forgetAction()
    {
        //carrego un el layout del login
        $this->_helper->layout->setLayout('layoutLogin');
        
        //passo el titol a la vista
        $this->view->titulo = $this->_config->parametros->mvc->admin->login->forget->titulo;
              
        //obtenemos parámetros del formulario es similar a $_POST
        
        $form = $this->_getFormForget();
        $this->view->form = $form;       
        
    }
    
    public function autenticarAction()
    {
            //Ponemos los titulos y el texto del footer
        $this->view->titulo = $this->_config->parametros->mvc->admin->login->index->titulo;
        
        if(!$this->getRequest()->isPost()){

            return $this->_forward('index');
            
        }

        //obtenemos parámetros del formulario es similar a $_POST

        $postParams = $this->_request->getPost();

            $form = $this->_getForm();
            if(!$form->isValid($postParams)){
                //falla la validación y volvemos a generar el formulario
                //poblamos el formulario con los datos
                $form->populate($postParams);
                $this->view->form = $form;
                
                //carrego un el layout del login
                $this->_helper->layout->setLayout('layoutLogin');
              
                return $this->render('index');           
            }

            $email = $form->email->getValue();
            $clave = $form->password->getValue();
            
            try{

                $this->_login->setMessage('El nombre de usuario y password no coinciden.', Admin_Model_Login::NOT_IDENTITY);
                $this->_login->setMessage('La contraseña ingresada es incorrecta', Admin_Model_Login::INVALID_CREDENTIAL);
                $this->_login->setMessage('Los campos de Usuario y Password no puede dejarse en blanco',Admin_Model_Login::INVALID_LOGIN);
                $this->_login->login($email, sha1($clave));
               
                return $this->_forward("index","index","admin");
                
                
            }catch (Exception $e){
                $this->view->responseLogin = $e->getMessage();
                
                $this->_helper->layout->assign("mensaje",$this->view->responseLogin);
                              
                //carrego un el layout del login
                $this->_helper->layout->setLayout('layoutLogin');
                
                return $this->_forward('index');
               
            }
    }
    
    public function forgetresAction(){
        //carrego un el layout del login
        $this->_helper->layout->setLayout('layoutLogin');
        
        //passo el titol a la vista
        $this->view->titulo = $this->_config->parametros->mvc->admin->login->forget->titulo;
               
        //instanciem formulari d'entrada de dades
        $form = $this->_getFormForget();      
        //obtenemos parámetros del formulario es similar a $_POST
        $postParams = $this->_request->getPost();
        
        if(!$form->isValid($postParams)){
                //falla la validación y volvemos a generar el formulario
                //poblamos el formulario con los datos
                
                $this->view->form = $form;
                $form->populate($postParams);
                             
                return $this->render('forget');           
         }
         try{
             //camps del formulari
             $email = $form->email->getValue();

             //print_r($email);
             
             //tenim l'id de l'usuari
             $getId = $this->_usuarioDao->obtenerIdPorEmail(trim($email));
             
             //var_dump($getId);
             //exit();
             
             if (!$getId==NULL){ //si es diferent de NULL és a dir: si genera un id formateja password

                $password = $this->generaPass();
                $this->_usuarioDao->formatPassword($getId,$password);
                
                //enviem el mail 
                $this->sendEmail($getId,$password);
                //alert a la vista
                $this->view->alert = 'Te hemos enviado un email a tu correo';
                       
             }else{
                 
                  $this->view->alert = 'No hay ningún administrador con este correo';
             }
             
             //tornem a passar el form a la vista i renderitzem l'action
             $this->view->form = $form;       
             return $this->render('forget');
             
         }catch (Exception $e){
             
             $this->view->alert = $e->getMessage();
             //passem el form a la vista i el poblem
             $this->view->form = $form;
             $form->populate($postParams);
             
             return $this->render('forget'); 
         }
    }
    
    public function logoutAction()
    {
        $this->_login->logout();
        $this->_redirect('/admin/login/');
      
    }
    
    private function generaPass($tam=8,$may=FALSE)
    {
        
        //Tamaño Mínimo
        $min=4;
        //Tamaño Máximo
        $max=8;

        if($may === FALSE)
        {
            $cadena='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else
        {
            $cadena='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if(($tam >= $min)&&($tam <=$max))
        {
            //Generación aleatoria segun sea mayuscula o minuscula.
            for($i=0;$i<$tam;$i++)
            {
                //Guardamos en un arreglo.
                $pila[]=$cadena{rand(0,50)};
            }
            //Desordenamos el arreglo.
            shuffle($pila);
            //Mostramos la contraseña, suena ilógico, pero usualmente
            //es para enviarse a un correo, donde se supone el usuario
            //si puede verla con seguridad. 
            
            $cadena="";
            
            foreach($pila as $letra => $contrasena)
            {
                $cadena.=$contrasena;//by dasagi com que asigna resultats he d'anar concatenant perquè m'ha de donar 8
            }
            return ($cadena);

        }    
    }
    
    private function sendEmail($usuario,$password){
        
        //de la id passada per paràmetre obtinc el nom i els cognoms
        $userNom = $this->_usuarioDao->obtenerPorId($usuario);
        $link = $this->_domini.'/admin/login/index';
        //$logo = $this->_domini.$this->_baseUrl."/administrador/img/logo_mail.png";//l'adreça ha de ser web per a que es vegi el logo http://www.omniasolutions.es/imatges/logo.png
        $logo = 'http://omniasolutions.cat/img/logo_mail.png';
        //funció que em dona l'any
        $any = date('Y');
        $body="
            <!DOCTYPE html>
            <html lang='es'>
            <head>
            <meta charset='UTF-8'>
            <meta content='width=device-width, initial-scale=1' name='viewport'>
            <title>Template mail</title>
            </head>
                <body style='font-family:Arial, Helvetica, sans-serif;'>   
                    <table  align ='center' width='60%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td align='left' valign='top' bgcolor='#58C2DF' style='padding:10px; text-align:center;'>

                            <img src='{$logo}'>
                        </td>
                    </tr>
                    <tr>
                        <td align='left' valign='top' bgcolor='#58C2DF' style='background-color:#007DB8; padding:15px;font-size:14px; color:#ffffff;'>
                        <p>Hola<em> <b>{$userNom->getNom()} {$userNom->getCognoms()}</b></em>, </p>
                                            Hemos generado una contraseña aleatoria: {$password}</br>
                                            Te recomendamos volver a entrar a la aplicación y modificar por una contraseña que tú elijas en este enlace: <a style='color:#ffffff;  text-decoration: underline;' target='_blank' href='{$link}'>Administrador web</a> 
                                            <p>Gracias por utilizar <b>Easyweb Corp 2.0</b></p>
                                            Saludos cordiales.
                        </td>
                    </tr>
                    <tr>
                        <td align='right' valign='top' bgcolor='#1ba5db' style='padding:5px 10px 5px 5px; font-size:12px; color:#ffffff;'>
                        © easyweb {$any}
                        </td>
                    </tr>
                    </table>
                </body>
            </html>
        ";
        
        try{
            $mail = new Zend_Mail("UTF-8");
            $mail->setBodyHtml($body)  
                 ->addTo($userNom->getMail())
                 //->addTo('dasagi77@gmail.com')
                 //->addTo('dsg_tgn@hotmail.com')
                 ->setSubject('Cambios en tu cuenta de usuario')
                 ->send();   
            
        }  catch (Zend_Mail_Exception $e){
           
           $this->view->alert = "Hay algún problema con el envío del email a ".$userNom->getMail()." ".$e->getMessage();
        }
    }
}