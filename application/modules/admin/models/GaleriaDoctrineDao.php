<?php

class Admin_Model_GaleriaDoctrineDao
{
        //retornem el llistat de galerias
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('SELECT g FROM Admin_Model_Galeria g WHERE g._id!=0')
                            ->getResult();
        }
                
        public function obtenerGaleriaPorId($id)
        {
            
           /*return $this->getEntityManager()
                            ->createQuery('SELECT g FROM Admin_Model_Galeria g WHERE g._id =?1')
                            ->setParameter(1,$id)
                            ->getResult(); 
           */
            return $this->getEntityManager()
                        ->find('Admin_Model_Galeria', $id);
        }
        
        public function obtenerIdiomasSelect() {

        $idiomas=$this->getEntityManager()
                        ->createQuery('select i from Admin_Model_Idioma i')
                        ->getResult();
        
        $result = array();
                
                foreach ($idiomas as $idioma){
                    $result[$idioma->getCodi()] = $idioma->getNom();
                }
                return $result;
        }
        
        public function obtenerFotoPortada($idGaleria)
        {
            return $this->getEntityManager()
                            ->createQuery('SELECT i._ordre, i._titol, i._ruta 
                                           FROM Admin_Model_Imagen i
                                           WHERE i._galeriaId = ?1
                                           ORDER BY i._ordre')
                            ->setParameter(1,$idGaleria)
                            ->setMaxResults(1)
                            ->getResult();
            
        }        
        /*
        public function guardar(Admin_Model_Galeria $galeria)
        {
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $galeriaFind = $this->getEntityManager()->find('Admin_Model_Galeria', $galeria->getId());
            
            //persist updateja
            $this->getEntityManager()->persist($galeriaFind);
            $this->getEntityManager()->flush();
        }
        */
        

         public function guardar(Admin_Model_Galeria $galeria,$update)
        {   
                         
           if($update==0){
               
                $this->getEntityManager()->persist($galeria);
                $this->getEntityManager()->flush();
                
            }else{
                
                //merge per fer l'update
                $this->getEntityManager()->merge($galeria);
                $this->getEntityManager()->flush();
           }
        }
        public function guardarMetas($metatags,$idMetas)
        {

            if($idMetas==0){
                
                $this->getEntityManager()->persist($metatags);
                $this->getEntityManager()->flush();   
                
            }else{
                
                $this->getEntityManager()->merge($metatags);
                $this->getEntityManager()->flush();                
            }
        }
        
        public function privacitat($id,$valor)
        {
            return $this->getEntityManager()
                            ->createQuery("UPDATE Admin_Model_Galeria g SET g._publicar=?1 WHERE g._id=?2")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                            ->setParameter(1,$valor)
                            ->setParameter(2,$id)
                            ->getResult();
        }
        
        public function eliminar($galeria)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $galeriaFind = $this->getEntityManager()->find('Admin_Model_Galeria', $galeria);
            
            $this->getEntityManager()->remove($galeriaFind);
            $this->getEntityManager()->flush();
        }
        
        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }
}