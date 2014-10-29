<?php

class Admin_Form_LoginForget extends Zend_Form {

    public function init() {
        $this->setAction($this->getView()->baseUrl() . '/admin/login/forgetres/')->setMethod('post')->setName('login')->setAttrib('class', 'form-horitzontal');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');
        
        // Crear y configurar el elemento email:
        $email = $this->createElement('text', 'email', array('class'=>'input-block-level','placeholder'=>'Email'));
        
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
         

        //Agregar los elementos al form:
        $this->addElement($email)
                ->addElement('button', 'login', array('type'=>'submit','label'=>'Enviar','class'=>'btn-block btn_login'));//creo el button

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
