<?php

class Admin_Form_Entrada extends Zend_Form {

    
    public function init() {
        
        
        $this->setAction($this->getView()->baseUrl() . '/admin/entrada/guardar/')->setMethod('post');
        
        $mensajeEsp = array('isEmpty' => 'El campo no puede quedar vacío.');

        //crear el campos ocultos
        $id = $this->createElement('hidden', 'id');
       
        //$categoria = $this->createElement('hidden', 'cat',array('value'=>'admin'));
        $editar = $this->createElement('hidden', 'update',array('value'=>'0'));
        $idMetas = $this->createElement('hidden', 'idMetas',array('value'=>'0'));
        $idImatge = $this->createElement('hidden', 'idImatge',array('value'=>'0'));
        $idDoc = $this->createElement('hidden', 'idDoc',array('value'=>'0'));
        $idSeccio = $this->createElement('hidden','idSeccio',array('value'=>'0'));
        
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

        //crear i configurar element noticia
        
        $noticia = $this->createElement('checkbox', 'noticia', array('onClick'=>'disabledChecbox()'));
        
        // Crear y configurar el elemento títol:
        $titol = $this->createElement('text', 'titol', array('class'=>'input-block-level','placeholder'=>'Título de la entrada'));
        
        $titol->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->setRequired(true);

        // Crear y configurar el elemento contingut:
        $contingut = $this->createElement('textarea', 'contingut', array('class'=>'span12','placeholder'=>'Contenido','style'=>'width:100%; height:280px;'));
        $contingut->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                ->setRequired(true);
           
        // :::::::::::::::::::::::: CREAR METAS:::::::::::::::::::::::::
        
        $title = $this->createElement('text','title', array('class'=>'input-block-level','placeholder'=>'Etiqueta title')); 
        $description = $this->createElement('textarea','description', array('class'=>'mceNoEditor span12','placeholder'=>'Descripción'));
        $keywords = $this->createElement('textarea','keywords', array('class'=>'mceNoEditor span12','placeholder'=>'Palabras clave'));
        $robots = $this->createElement('text','robots', array('class'=>'input-block-level','placeholder'=>'Robots (te recomendamos que pongas "all")'));
        $autor = $this->createElement('text','autor', array('class'=>'input-block-level','placeholder'=>'Autor'));
       
        // :::::::::::::::::::::::: END METAS:::::::::::::::::::::::::
        
        
         //crear i configurar element seccio
        $seccio = $this->createElement('select', 'seccio', array('class'=>'span12'));
        $seccio->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
               ->addMultiOption("-1", "Menú sección")//El -1 és perquè sinó m'updateja l'entrada 0 i no pot ser.
               ->setAttrib("onchange", "javascript:cargarSelectMenus()")
               ->setRequired(true)
               ->setRegisterInArrayValidator(false);//si no dona error no reconeix el value
        
        //crear i configurar element subseccio
        $subSeccio = $this->createElement('select', 'subSeccio', array('class'=>'span12'));
        $subSeccio->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
                  ->addMultiOption("-1", "Menú Subsección")//El -1 és perquè sinó m'updateja l'entrada 0 i no pot ser.
                  ->setRequired(FALSE)
                  ->setRegisterInArrayValidator(false);//si no dona error no reconeix el value
        
         //crear i configurar element imatge seccio
        $imagen = $this->createElement('select', 'imagen', array('class'=>'span12'));
        $imagen->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
               ->addMultiOption("", "Imagen de portada")
               ->setRequired(FALSE);
        
       /* $galeria = $this->createElement('select', 'galeria', array('class'=>'span12'));
        $galeria->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
               ->addMultiOption("", "Adjuntar una imágen")
               ->setRequired(FALSE);
        */
        
        //crear i configurar element documentos
        $documents = $this->createElement('select', 'documents', array('class'=>'span12'));
        $documents->addValidator('NotEmpty', true, array('messages' => $mensajeEsp))
               ->addMultiOption("", "Adjuntar un documento")
               ->setRequired(FALSE);
        
        //Agregar los elementos al form:
        $this->addElement($id)
                ->addElement($data)
                ->addElement($idioma)
                ->addElement($noticia)
                ->addElement($titol)
                ->addElement($contingut)
                ->addElement($title)
                ->addElement($description)
                ->addElement($keywords)
                ->addElement($robots)
                ->addElement($autor)
                ->addElement($seccio)
                ->addElement($subSeccio)
                ->addElement($imagen)
                ->addElement($documents)
                ->addElement($editar)
                ->addElement($idMetas)
                ->addElement($idImatge)
                ->addElement($idDoc)
                ->addElement($idSeccio)
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
            'viewScript' => 'forms/formEntrada.phtml',

            // the module that contains our view templates
            'viewModule' => 'admin'))              
       ));
    }
}