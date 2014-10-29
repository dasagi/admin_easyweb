<?php

class Admin_Form_Usuario extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        //$this->_dataFormat = new MisLibrerias_View_Helper_FormatDate();
        
        $this->setAction($this->getView()->baseUrl() . '/admin/usuario/guardar/')->setMethod('post')->setName('login')->setAttrib('class', 'form-horitzontal');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');

        //crear el campos ocultos
        $id = $this->createElement('hidden', 'id');
        //$data = $this->createElement('hidden', 'data', array('value'=>$this->_dataFormat->formatDate(date('Y-m-d'), "dd.MM.yyyy")));
        $data = $this->createElement('hidden', 'data', array('value'=>date('Y-m-d H:i:s')));
        $categoria = $this->createElement('hidden', 'cat',array('value'=>'admin'));
        $editar = $this->createElement('hidden', 'update',array('value'=>'0'));
        $avatar = $this->createElement('hidden', 'avatar',array('value'=>'avatar.jpg'));

        // Crear y configurar el elemento nombre:
        $nombre = $this->createElement('text', 'nombre', array('class'=>'input-block-level','placeholder'=>'Nombre'));
        
        $nombre->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))
                ->addValidator('alnum',false,array('allowWhiteSpace'=>true))
                ->setRequired(true);

        // Crear y configurar el elemento apellido:
        $apellido = $this->createElement('text', 'apellido', array('class'=>'input-block-level','placeholder'=>'Apellidos'));
        $apellido->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))
                ->addValidator('alnum',false,array('allowWhiteSpace'=>true))
                ->setRequired(true);
        
        
        
        // Crear y configurar el elemento email:
        $email = $this->createElement('text', 'email', array('class'=>'input-block-level','placeholder'=>'E-mail'));
        $email->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
        ->addValidator('EmailAddress',true,array('messages' =>array(
            'emailAddressInvalid'=>'Esto no es un E-mail válido',
            'emailAddressInvalidFormat'=>'Formato incorrecto para el E-mail',
            'emailAddressInvalidHostname'=>'Host no válido',
            'hostnameInvalidHostnameSchema'=>'hostnameInvalidHostnameSchema',
        )))
        ->setRequired(true);
        
        // Obtenemos el validador EmailAddress, desde el campo email
        $emailVal = $email->getValidator('EmailAddress');

        // Obtenemos el validador Hostname, desde el validador EmailAddress
        $hostVal = $emailVal->getHostnameValidator();
            // Modificamos los mensajes
        $hostVal->setMessage('Invalido Hostname', 'hostnameInvalidHostname');
        $hostVal->setMessage('No permitido local name', 'hostnameLocalNameNotAllowed');
        $hostVal->setMessage('Tld Invalido', 'hostnameUndecipherableTld');
        $hostVal->setMessage('Tld desconocido', 'hostnameUnknownTld');


         // Crear y configurar el elemento password:
        $password = $this->createElement('password', 'password', array('class'=>'input-block-level','placeholder'=>'Password'));
        $password->setRequired(true)
        ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'El campo no puede quedar vacío.')))
        ->addValidator('stringLength', true, array(array('min' => 4, 'max' =>8), 'messages' =>
            array('stringLengthTooShort' =>'El campo clave debe tener tener al menos 4 caracteres','stringLengthTooLong' =>
        'El campo clave debe tener un máximo de 8 caracteres')))
        ->addValidator('alnum', true, array('messages' => array('alnumInvalid' =>
        'El valor introducido debe contener únicamente letras y números',
        'notAlnum' =>'El valor introducido debe contener únicamente letras y números')));
        
        // Crear y configurar el elemento repeat password:
        $repeat_pass = $this->createElement('password', 'repeat', array('class'=>'input-block-level','placeholder'=>'Repetir Password'));
        $repeat_pass->setRequired(true)
        ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'El campo no puede quedar vacío.')))
        ->addValidator('stringLength', true, array(array('min' => 4, 'max' =>8), 'messages' =>
            array('stringLengthTooShort' =>'El campo clave debe tener tener al menos 4 caracteres','stringLengthTooLong' =>
        'El campo clave debe tener un máximo de 8 caracteres')))
        ->addValidator('alnum', true, array('messages' => array('alnumInvalid' =>
        'El valor introducido debe contener únicamente letras y números',
        'notAlnum' =>'El valor introducido debe contener únicamente letras y números')));
       
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($data)
                ->addElement($nombre)
                ->addElement($apellido)
                ->addElement($email)
                ->addElement($password)
                ->addElement($repeat_pass)
                ->addElement($categoria)
                ->addElement($editar)
                ->addElement($avatar)
                ->addElement('button', 'usuario', array('type'=>'submit','label'=>'Crear','class'=>' btn-block btn_public'))
                ->addElement('button', 'cancelar', array('type'=>'reset','label'=>'Cancelar','class'=>' btn-block btn_cancel'));//creo el button
        
       //crearemos estilos CSS
       //setegem els decorators perquè sinó per defecte posa elements htmml dd dt
        
       $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
        ));
        
       $this->setDecorators(array(
 
       array('ViewScript', array(
            // the view template script
            'viewScript' => 'forms/formUser.phtml',

            // the module that contains our view templates
            'viewModule' => 'admin'))              
       ));
    }
}