<?php

class Admin_Form_EditAvatar extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        $this->setAction($this->getView()->baseUrl() . '/admin/usuario/editavatar/')->setMethod('post')->setName('avatar')->setAttrib('class', 'form-horitzontal')->setAttrib('enctype', 'multipart/form-data');
        //$this->setAction($this->getView()->baseUrl() . '/admin/imagen/index/')->setMethod('post')->setName('myDrop')->setAttrib('class', 'dropzone')->setAttrib('enctype', 'multipart/form-data');
        
        //$mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');

        $id = $this->createElement('hidden', 'id');
        $size = $this->createElement('hidden','MAX_FILE_SIZE',array('value'=>'20000'));
        
        // Crear y configurar el elemento avatar:
        /*
        $avatar = $this->createElement('file', 'avatar', array('class'=>'btn_login btn-file'));
        $avatar->addValidator('Size', false, 102400)
               ->addValidator('Extension', false, 'jpg,png,gif');
        */
        
        $avatar = $this->createElement('file', 'avatar', array('class'=>'btn_login btn-file'));
        $avatar->addValidator('NotEmpty')
            ->addValidator('Count', false, 1);
        //$avatar->attachment->setDestination(APPLICATION_PATH . "/tmp/");
        //$avatar->addValidator('NotExists', false);
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($avatar)
                ->addElement($size)
                ->addElement('button', 'crear', array('type'=>'submit','label'=>'Crear','class'=>' btn-block btn_public'));//creo el button
        
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
                    'viewScript' => 'forms/formAvatar.phtml',

                    // the module that contains our view templates
                    'viewModule' => 'admin'))              
       ));
    }
}
         