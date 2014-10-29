<?php

class Admin_Form_Login extends Zend_Form {

    public function init() {
        $this->setAction($this->getView()->baseUrl() . '/admin/login/autenticar/')->setMethod('post')->setName('login')->setAttrib('class', 'form-horitzontal');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');
        
        // Crear y configurar el elemento email:
        $email = $this->createElement('text', 'email', array('class'=>'input-block-level','placeholder'=>'Usuario'));
        
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

        //Agregar los elementos al form:
        $this->addElement($email)
                ->addElement($password)
                ->addElement('button', 'login', array('type'=>'submit','label'=>'Entrar','class'=>'btn-block btn_login'));//creo el button

        //crearemos estilos CSS
        $this->clearDecorators();
        
        $this->addDecorator('FormElements')
                //->addDecorator('HtmlTag',array('tag'=>'<div>','class'=>'generic-form'))
                ->addDecorator('Form');
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),//això mostra els errors
            array('Description'),
            //array('Label',array('requiredSuffix'=>'*','escape'=>false,'separator'=>'','class'=>'label_title')),
            array('HtmlTag', array('tag'=>'div','class'=>'control-group')),
        ));

        /*$this->login->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array('tag'=>'button','class'=>'btn-block btn_login', 'type'=>'submit')),
        ));
        */
    }
}
