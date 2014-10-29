<?php

class Admin_Form_Corporativa extends Zend_Form {

    public function init() {
        
        
        $this->setAction($this->getView()->baseUrl() . '/admin/corporativa/guardar/')->setMethod('post');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');
        $mensajeNum = 'Los carácteres tienen que ser numéricos';
        //crear el campos ocultos
        $id = $this->createElement('hidden', 'id');
       
        //$categoria = $this->createElement('hidden', 'cat',array('value'=>'admin'));
        $editar = $this->createElement('hidden', 'update',array('value'=>'0'));
        
        //crear element empresa
        $empresa = $this->createElement('text', 'empresa', array('class'=>'input-block-level','placeholder'=>'Nombre Empresa'));
        $empresa->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
             ->setRequired(true);
        
        //crear element adreca
        $adreca = $this->createElement('text', 'adreca', array('class'=>'input-block-level','placeholder'=>'Dirección'));
        $adreca->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
             ->setRequired(true);
        
        //crear element població
        $poblacio = $this->createElement('text', 'poblacio',array('class'=>'input-block-level','placeholder'=>'Población'));
        $poblacio->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
             ->setRequired(true);
        
        //crear element cp
        $cp = $this->createElement('text', 'cp',array('class'=>'input-block-level','placeholder'=>'Código Postal'));
        $cp->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
           ->addValidator('alnum',true,array('allowWhiteSpace'=>FALSE))
           ->setRequired(true);
        
        //crear element telefon
        $telefon = $this->createElement('text', 'telefon',array('class'=>'input-block-level','placeholder'=>'Teléfono'));
        $telefon->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
           ->addValidator('regex',true, array('/^[0-9 ]+$/','messages'=>$mensajeNum))     
           ->setRequired(true);
        
        //crear element Fax
        $fax = $this->createElement('text', 'fax',array('class'=>'input-block-level','placeholder'=>'Fax'));
        $fax->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
           ->addValidator('regex',true, array('/^[0-9 ]+$/','messages'=>$mensajeNum))
           ->setRequired(false);
        
        //crear element Movil
        $movil = $this->createElement('text', 'movil',array('class'=>'input-block-level','placeholder'=>'Móbil'));
        $movil->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
           ->addValidator('regex',true, array('/^[0-9 ]+$/','messages'=>$mensajeNum))
           ->setRequired(false);
        
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
        
        //crear element web
        $web = $this->createElement('text', 'web',array('class'=>'input-block-level','placeholder'=>'Página web'));
        $web->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
            ->setRequired(true);
        
        // Crear y configurar el elemento Legal:
        $gmaps = $this->createElement('textarea', 'gmaps', array('class'=>'mceNoEditor span12','placeholder'=>'Codigo de google maps'));
        
        // Crear y configurar el elemento Legal:
        $legal = $this->createElement('textarea', 'legal', array('class'=>'span12','placeholder'=>'Texto legal','style'=>'width:100%;height:220px;'));
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($editar)
                ->addElement($empresa)
                ->addElement($adreca)
                ->addElement($poblacio)
                ->addElement($cp)
                ->addElement($telefon)
                ->addElement($fax)
                ->addElement($movil)
                ->addElement($email)
                ->addElement($web)
                ->addElement($gmaps)
                ->addElement($legal)
                ->addElement('button', 'aceptar', array('type'=>'submit','label'=>'Aceptar','class'=>' btn-block btn_public'))
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
            'viewScript' => 'forms/formCorporativa.phtml',
            // the module that contains our view templates
            'viewModule' => 'admin'))              
       ));
    }
}