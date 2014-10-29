<?php

class Admin_Form_ImagenEditar extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        $this->setAction($this->getView()->baseUrl() . '/admin/galeria/guardarimagen')->setMethod('post')->setName('login')->setAttrib('class', 'form-horitzontal');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');
        
        //crear el campos ocultos date('Y.m.d')
        $id = $this->createElement('hidden', 'id');        

        //Crear y configurar el elemento título:
        $titulo = $this->createElement('text', 'titol', array('class'=>'input-block-level','placeholder'=>'Título de la imágen'));
            $titulo->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->setRequired(true);

        // Crear y configurar el elemento descripcion:
        $description = $this->createElement('textarea','descripcio', array('class'=>'input-block-level','placeholder'=>'Descripción','style'=>'width:100%;height:130px;'));
         
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($titulo)
                ->addElement($description)
                ->addElement('button', 'editar', array('type'=>'submit','label'=>'Editar','class'=>' btn-block btn_public'))
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
            'viewScript' => 'forms/formImagenEdit.phtml',

            // the module that contains our view templates
            'viewModule' => 'admin'))              
       ));
    }
}