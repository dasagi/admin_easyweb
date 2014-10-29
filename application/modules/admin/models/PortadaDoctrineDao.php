<?php

class Admin_Model_PortadaDoctrineDao
{
	

        //retornem totes les portades
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('select p from Admin_Model_Portada p WHERE p._id!=0 ORDER BY p._id DESC')
                            ->getResult();
        }

        
        public function eliminar($portada)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $portadaFind = $this->getEntityManager()->find('Admin_Model_Portada', $portada);
            
            $this->getEntityManager()->remove($portadaFind);
            $this->getEntityManager()->flush();

        }
               
        public function guardar($portada,$valor){
                       
            if($valor==true){
                $this->getEntityManager()->merge($portada);
                $this->getEntityManager()->flush();
            }else{
                $this->getEntityManager()->persist($portada);
                $this->getEntityManager()->flush();
            }
        }
                
        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }
}