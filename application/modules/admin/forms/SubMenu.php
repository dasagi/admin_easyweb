<?php
class Admin_Form_SubMenu extends Zend_Form {

    //private $_dataFormat;
    
    public function init() {
        
        $this->setAction($this->getView()->baseUrl() . '/admin/menu/guardarsubmenu/')->setMethod('post')->setName('login')->setAttrib('class', 'form-horitzontal');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');

        //crear el campos ocultosdate('Y.m.d')
        $id = $this->createElement('hidden', 'id');
        $data = $this->createElement('hidden', 'data');        
        $editar = $this->createElement('hidden', 'update',array('value'=>'0'));
        $publicar = $this->createElement('hidden', 'publicar',array('value'=>'0'));
        $predet = $this->createElement('hidden', 'predet',array('value'=>'0'));
        $subMenu = $this->createElement('hidden', 'subMenu');
        $idioma = $this->createElement('hidden', 'idioma');
        
        
        //Crear i configurar element ordre
        
        $orden = $this->createElement('text', 'orden', array('class'=>'input-block-level','placeholder'=>'Orden'));
        
        $orden->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))
              ->addValidator('alnum',false,array('allowWhiteSpace'=>true))
              ->setRequired(false);
        

        // Crear y configurar el elemento título:
        $titulo = $this->createElement('text', 'titulo', array('class'=>'input-block-level','placeholder'=>'Título del menú'));
        
        $titulo->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->setRequired(true);

           
        // Crear y configurar el elemento entrada:
        $entrada = $this->createElement('select', 'entrada', array('class'=>'span12'));
        $entrada->addMultiOption("", "Asigna una entrada")
                ->setRequired(false);
        
        // Crear y configurar el elemento Url:
        $url = $this->createElement('text', 'url', array('class'=>'input-block-level','placeholder'=>'Url'));
        $url->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
            ->setRequired(false);
        
        // Crear y configurar el elemento zonas:
        $zona = $this->createElement('select', 'zona', array('class'=>'span12'));
        $zona->addMultiOption("", "Asigna una zona")
                ->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->setRequired(false); 
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($data)
                ->addElement($editar)
                ->addElement($publicar)
                ->addElement($predet)
                ->addElement($subMenu)
                ->addElement($idioma)
                ->addElement($orden)
                ->addElement($titulo)
                ->addElement($url)
                ->addElement($entrada)
                ->addElement($zona)
                ->addElement('button', 'crear', array('type'=>'submit','label'=>'Crear','class'=>' btn-block btn_public'))
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
            'viewScript' => 'forms/formSubMenu.phtml',

            // the module that contains our view templates
            'viewModule' => 'admin'))              
       ));
    }
}