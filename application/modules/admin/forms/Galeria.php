<?php

class Admin_Form_Galeria extends Zend_Form {

    
    public function init() {
        
        
        $this->setAction($this->getView()->baseUrl() . '/admin/galeria/guardar/')->setMethod('post');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');

        //crear el campos ocultos
        $id = $this->createElement('hidden', 'id');
       
        //$categoria = $this->createElement('hidden', 'cat',array('value'=>'admin'));
        $editar = $this->createElement('hidden', 'update',array('value'=>'0'));
        $idMetas = $this->createElement('hidden', 'idMetas',array('value'=>'0'));
        
        //crear element data
        $data = $this->createElement('text', 'data', array('class'=>'input-block-level','placeholder'=>'Fecha','value'=>date('d-m-Y'), "dd.MM.yyyy"));
        $data->addValidator('NotEmpty', true,array('messages'=>$mensajeEsp))
             //->addValidator('alnum',false,array('allowWhiteSpace'=>true))
             ->setRequired(true);
        
        //crar i configurar element idioma
        $idioma = $this->createElement('select', 'idioma', array('class'=>'span12'));
        $idioma->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
               ->addMultiOption("", "Selecciona un idioma")
               ->setRequired(true);

        //crear i configurar element banner
        
        $banner = $this->createElement('checkbox', 'banner');
        
        // Crear y configurar el elemento títol:
        $titol = $this->createElement('text', 'titol', array('class'=>'input-block-level','placeholder'=>'Título de la galería'));
        
        $titol->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->setRequired(true);

        // Crear y configurar el elemento contingut:
        $contingut = $this->createElement('textarea', 'contingut', array('class'=>'span12','placeholder'=>'Contenido','style'=>'width:100%;height:220px;'));
           
        // :::::::::::::::::::::::: CREAR METAS:::::::::::::::::::::::::
        
        $title = $this->createElement('text','title', array('class'=>'input-block-level','placeholder'=>'Etiqueta title')); 
        $description = $this->createElement('textarea','description', array('class'=>'mceNoEditor span12','placeholder'=>'Descripción'));
        $keywords = $this->createElement('textarea','keywords', array('class'=>'mceNoEditor span12','placeholder'=>'Palabras clave'));
        $robots = $this->createElement('text','robots', array('class'=>'input-block-level','placeholder'=>'Robots (te recomendamos que pongas "all")'));
        $autor = $this->createElement('text','autor', array('class'=>'input-block-level','placeholder'=>'Autor'));
       
        // :::::::::::::::::::::::: END METAS:::::::::::::::::::::::::
        
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($data)
                ->addElement($idioma)
                ->addElement($banner)
                ->addElement($titol)
                ->addElement($contingut)
                ->addElement($title)
                ->addElement($description)
                ->addElement($keywords)
                ->addElement($robots)
                ->addElement($autor)
                ->addElement($editar)
                ->addElement($idMetas)
                ->addElement('button', 'public', array('type'=>'submit','label'=>'Público','class'=>' btn-block btn_public'))
                ->addElement('button', 'privat', array('type'=>'submit','label'=>'Privado','class'=>' btn-block btn_privat'))
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
            'viewScript' => 'forms/formGaleria.phtml',

            // the module that contains our view templates
            'viewModule' => 'admin'))              
       ));
    }
}