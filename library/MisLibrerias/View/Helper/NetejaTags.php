<?php
class MisLibrerias_View_Helper_NetejaTags extends Zend_View_Helper_Abstract{
    
    private $_taqsOk;
    private $_atributs;
    private $_num;
    private $_taqsImg;
    
    public function __construct() {
        
        $this->_taqsImg = array("-"," ","\ñ","@","#","%","*","&","¿","¡","!","\/");
        $this->_taqsOk = '';
        $this->_atributs = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|class';
        $this->_num = 10;
    }
    
    public function netejaAtributs($origen)
    {
        $atributs = $this->_atributs;
        return stripcslashes(preg_replace("/$atributs/i", "nopermitido", $origen));
    }

    public function tagsNets($origen)
    {
        $tagsOk = $this->_taqsOk;
        $origen = strip_tags($origen,$tagsOk);
        
        return $this->netejaAtributs($origen);
    }
    
    public function limitaText($text,$limit){
        
        if(strlen($text)>$limit){
            $text = substr($text,0, $limit);
            $text = substr( $text,0,-(strlen(strrchr($text,' '))) );
        }
        return $text;
    }
    public function limitaNomImg($nom)
    {
        //me dona tot el que tinc després del punt
        $format = substr($nom,strpos($nom,'.'), strlen($nom));
        
        $img = substr($nom,0,$this->_num);
    
        if (strlen($nom)>$this->_num){
            return $img.'..'.$format;
        }else{ 
            return $img;
        }
    }
    
    public function netejaNomImatges($string)
    {
       $taqsImg = $this->_taqsImg;
       $formata = str_replace($taqsImg, "", $string);
       
       return $formata;
    }
}