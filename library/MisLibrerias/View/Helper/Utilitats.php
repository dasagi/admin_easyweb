<?php
class MisLibrerias_View_Helper_Utilitats extends Zend_View_Helper_Abstract{

    private $_doc;
    private $_imagenDoctrineDao;
    
    public function __construct() {
        
        //inicialitzem la variable doc dient-li els formats que tindran
        $this->_doc = array(".pdf",".doc",".docx",".xls");
        //inicialitzem la variable _imagenDoctrineDao creant l'objecte
        $this->_imagenDoctrineDao = new Admin_Model_ImagenDoctrineDao();
        
    }
    
    public function mostraImgDoc($ruta)
    {
        $donamFormat = substr($ruta,strpos($ruta,'.'), strlen($ruta));
        $format = $this->_doc;
        
        switch ($donamFormat) {
            case $format[0]:
                echo "<i class=\"icon2-pdf\">";
                break;
            case $format[1]:
                echo "<i class=\"icon2-word\">";
                break;
            case $format[2]:
                echo "<i class=\"icon2-word\">";
                break;
            case $format[3]:
                echo "<i class=\"icon2-excel\">";
                break;
        }        
    }
    
    public function editarImagen($id)
    {
    
        $imatges =  $this->_imagenDoctrineDao->obtenerImagenPorId($id);
        
        return $this->view->escape($imatges);
    }

    
}