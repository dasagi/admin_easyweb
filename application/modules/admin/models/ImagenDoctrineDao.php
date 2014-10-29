<?php

class Admin_Model_ImagenDoctrineDao
{
	        
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('select i from Admin_Model_Imagen i WHERE i._id!=0 AND i._galeriaId IS NULL OR i._galeriaId=0 ORDER BY i._data DESC')//consulta les que no tenen galeries
                            //->createQuery('select i from Admin_Model_Imagen i WHERE i._id!=0 AND i._galeriaId=0 ORDER BY i._data DESC')
                            ->getResult();
        }
        
        public function obtenerTodasImagenesDeGalerias(){
            return $this->getEntityManager()
                            ->createQuery('select i from Admin_Model_Imagen i WHERE i._id!=0 AND i._galeriaId IS NOT NULL')                            
                            ->getResult();
        }
        
        public function obtenerTodosDeGaleriaPorId($galId){
            return $this->getEntityManager()
                            ->createQuery('select i from Admin_Model_Imagen i WHERE i._galeriaId=?1 ORDER BY i._data DESC')
                            ->setParameter(1,$galId)
                            ->getResult();
        }
        
        public function obtenerImagenesSelect() {

        $imagenes=$this->getEntityManager()
                        //->createQuery('select i from Admin_Model_Imagen i ORDER BY i._id DESC') //aquesta select mostra tot
                          ->createQuery('select i from Admin_Model_Imagen i WHERE i._id!=0 AND i._galeriaId IS NULL OR i._galeriaId=0 ORDER BY i._data DESC')//aquesta select mostra només les imatges del contenidor no de galeries
                        ->getResult();
        
        $result = array();
                
                foreach ($imagenes as $img){
                    $result[$img->getId()] = $img->getRuta();
                }
                return $result;
        }
        public function obtenerImagenMaxId($galId)
        {
             return $this->getEntityManager()
                            ->createQuery('select i from Admin_Model_Galeria i WHERE  i._galeriaId=?1')
                            ->setParameter(1,$galId)
                            ->getResult();
                            
        }
        public function obtenerImagenPorId($id)
        {
           /* return $this->getEntityManager()
                        ->createQuery('SELECT i FROM Admin_Model_Imagen i WHERE i._id = ?1')
                        ->setParameter(1,$id)
                        ->getResult();
            
           */
            return $this->getEntityManager()
                        ->find('Admin_Model_Imagen', $id);
        }
        
        public function guardarImagenes(Admin_Model_Imagen $imagen)
        {
            //persist inserta
            
            $this->getEntityManager()->persist($imagen);
            $this->getEntityManager()->flush();
        }
        
        public function updateImagenes(Admin_Model_Imagen $imagen)
        {
            //merge updateja
            
            $this->getEntityManager()->merge($imagen);
            $this->getEntityManager()->flush();
        }
         
        public function updateImgDeGaleria($id,$titol,$descripcio)
        {
            return $this->getEntityManager()
                            ->createQuery("UPDATE Admin_Model_Imagen i SET i._titol=?2,i._descripcio=?3 WHERE i._id=?1")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                            ->setParameter(1,$id)
                            ->setParameter(2,$titol)
                            ->setParameter(3,$descripcio)
                            ->getResult();
        }
        
        public function eliminar(Admin_Model_Imagen $imagen)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $imagenFind = $this->getEntityManager()->find('Admin_Model_Imagen', $imagen->getId());
            
            $this->getEntityManager()->remove($imagenFind);
            $this->getEntityManager()->flush();

        }
        
        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }

}