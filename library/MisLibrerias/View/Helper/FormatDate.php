<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormatDate
 *
 * @author Zsamer
 */
class MisLibrerias_View_Helper_FormatDate extends Zend_View_Helper_Abstract{
    public function formatDate($fecha = null, $patron = Zend_Date::DATE_MEDIUM) {

        if (is_null($fecha)) {
            $fecha = Zend_Date::now();

        } else if(is_string($fecha)){
            $fecha = new Zend_Date($fecha);
        }
        
        return $fecha->toString($patron);
        
    }
}