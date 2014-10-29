<?php

class Admin_Model_CorporativaDoctrineDao
{
        //retornem el llistat corporativa
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('SELECT c FROM Admin_Model_Corporativa c WHERE c._id!=0')
                            ->getResult();
        }
                
        public function obtenerCorporativaPorId($id)
        {
           /*return $this->getEntityManager()
                            ->createQuery('SELECT g FROM Admin_Model_Galeria g WHERE g._id =?1')
                            ->setParameter(1,$id)
                            ->getResult(); 
           */
            return $this->getEntityManager()
                        ->find('Admin_Model_Corporativa', $id);
        }
                        

         public function guardar(Admin_Model_Corporativa $corporativa,$update) //en contes de $corporativa podria guardar $id però com que fem entitiats complertes ho fem així
        {   
                         
           if($update==0){
               
                $this->getEntityManager()->persist($corporativa);
                $this->getEntityManager()->flush();
          
           }else{
               
                $this->getEntityManager()->merge($corporativa);
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