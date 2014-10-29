<?php

class Admin_Model_EntradaDoctrineDao
{
	

        //retornem el llistat de entrades
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('select e from Admin_Model_Entrada e WHERE e._id!=0 ORDER BY e._data DESC')
                            ->getResult();
        }
        
        public function obtenerEntradasPorId($id){
            
            return $this->getEntityManager()
                        ->find('Admin_Model_Entrada', $id);
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

        public function obtenerMenusSelect(){
            $menus=$this->getEntityManager()
                        ->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=0 AND m._id!=0')
                        ->getResult();
            
            $result = array();

                    foreach ($menus as $menu){
                        $result[$menu->getId()] = $menu->getTitol();
                    }
                    return $result;
        }
        
        public function obtenerMenusSelectEditar(){
            $menus=$this->getEntityManager()
                        ->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=0 AND m._id!=0 AND (m._entradaId=0 OR m._entradaId IS NULL)')
                        ->getResult();
            
            $result = array();

                    foreach ($menus as $menu){
                        $result[$menu->getId()] = $menu->getTitol();
                    }
                    return $result;
        }

        public function obtenerMenuId($menuId){
            return $this->getEntityManager()
                        ->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=?1 AND m._id!=0')//conte perquè els noms ara són els atributs de la entitat
                          //->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=?1 AND m._entradaId=0')//conte perquè els noms ara són els atributs de la entitat
                        ->setParameter(1, $menuId)
                        ->getResult();
        }
        
        public function obtenerParentPorIdSelect($menuId) 
            {
                $menus = $this->obtenerMenuId($menuId);
                //var_dump($productos);
                $result = array();
                    
                    foreach ($menus as $menu){

                        $result[$menu->getId()] = $menu->getTitol();
                    }
                
                return $result;
            }
            
        public function obtenerMenuIdEditar($menuId){
            return $this->getEntityManager()
                        ->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=?1 AND m._entradaId=0')//conte perquè els noms ara són els atributs de la entitat
                      //->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=?1 AND m._entradaId=0')//conte perquè els noms ara són els atributs de la entitat
                        ->setParameter(1, $menuId)
                        ->getResult();
        }

        public function obtenerParentPorIdSelectEditar($menuId) 
            {
                $menus = $this->obtenerMenuIdEditar($menuId);
                //var_dump($productos);
                $result = array();
                    
                    foreach ($menus as $menu){

                        $result[$menu->getId()] = $menu->getTitol();
                    }
                
                return $result;
            }
 
        public function obtenerMenuIdEditarSelected($menuId){
            return $this->getEntityManager()
                        ->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._menuParent=?1 AND m._entradaId!=0')//conte perquè els noms ara són els atributs de la entitat
                        ->setParameter(1, $menuId)
                        ->getResult();
        }

        public function obtenerParentPorIdSelectEditarSelected($menuId) 
            {
                $menus = $this->obtenerMenuIdEditarSelected($menuId);
                //var_dump($productos);
                $result = array();
                    
                    foreach ($menus as $menu){

                        $result[$menu->getId()] = $menu->getTitol();
                    }
                
                return $result;
            }

         public function guardar(Admin_Model_Entrada $entrada,$update)
        {   
                         
           if($update==0){
               
                $this->getEntityManager()->persist($entrada);
                $this->getEntityManager()->flush();
                
            }else{
                
                //merge per fer l'update
                $this->getEntityManager()->merge($entrada);
                $this->getEntityManager()->flush();
                /*
                return $this->getEntityManager()
                                ->createQuery("UPDATE Admin_Model_Entrada e SET e._titol='gena', e._text='caracol treu banya' WHERE e._id=?1")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                                ->setParameter(1,$entrada->getId())
                                ->getResult();
               */
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
        
        public function guardarImatge($imatge,$entrada)
        {
            
            return $this->getEntityManager()
                                ->createQuery("UPDATE Admin_Model_Imagen i SET i._entradaId=?2 WHERE i._id=?1")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                                ->setParameter(1,$imatge)
                                ->setParameter(2,$entrada)
                                ->getResult();
            
        }
        
        public function guardarDocument($document,$entrada)
        {   
            return $this->getEntityManager()
                                ->createQuery("UPDATE Admin_Model_Documento d SET d._entradaId=?2 WHERE d._id=?1")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                                ->setParameter(1,$document)
                                ->setParameter(2,$entrada)
                                ->getResult();
        }
        
        
        public function eliminar(Admin_Model_Entrada $entrada)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $entradaFind = $this->getEntityManager()->find('Admin_Model_Entrada', $entrada->getId());
            
            $this->getEntityManager()->remove($entradaFind);
            $this->getEntityManager()->flush();

        }
        
        public function privacitat($id,$valor)
        {
            return $this->getEntityManager()
                            ->createQuery("UPDATE Admin_Model_Entrada g SET g._publicar=?1 WHERE g._id=?2")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                            ->setParameter(1,$valor)
                            ->setParameter(2,$id)
                            ->getResult();
        }

        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }
}