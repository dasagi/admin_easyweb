<?php

class Admin_Model_MenuDoctrineDao
{
        //retornem el llistat de menu
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('SELECT m FROM Admin_Model_Menu m WHERE m._id >0 and m._menuParent = 0')
                            ->getResult();
        }
        
        
        public function obtenerMenuPorId($id)
        {
             return $this->getEntityManager()
                        ->find('Admin_Model_Menu', $id);
        }
        
        public function obtenerTodosSubMenus()
        {
            return $this->getEntityManager()
                        ->createQuery('SELECT s FROM Admin_Model_Menu s WHERE s._menuParent > 0')
                        ->getResult();
        }
        public function obtenerUltimoId()
        {
            return $this->getEntityManager()
                        ->createQuery('SELECT MAX(m._id) AS id FROM Admin_Model_Menu m')
                        ->getResult();
        }
        /*public function obtenerTodosSubMenus($idParent)
        {
            return $this->getEntityManager()
                        ->createQuery('SELECT s FROM Admin_Model_Menu s WHERE s._menuParent = ?1')
                        ->setParameter(1,$idParent)
                        ->getResult();
        }*/
        
        public function obtenerMenuZonasSelect(){
            
            $menuZonas = $this->getEntityManager()
                              ->createQuery('SELECT mz FROM Admin_Model_MenuZona mz')
                              ->getResult();
            $result = array();
            
                foreach ($menuZonas as $menuZona){
                    $result[$menuZona->getId()] = $menuZona->getNom();
                }
                return $result;
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

        public function obtenerEntradasSelect(){
            
            $entrades=$this->getEntityManager()
                        ->createQuery('select e from Admin_Model_Entrada e WHERE e._id>0')
                        ->getResult();
        
            $result = array();
                
                foreach ($entrades as $entrada){
                    $result[$entrada->getId()] = $entrada->getTitol();
                }
                return $result;
            
            
        }
        
        public function obtenerUltimoOrden(){
            
            $ordre = $this->getEntityManager()
                                 ->createQuery('SELECT MAX(o._ordre) AS ordre FROM Admin_Model_Menu o WHERE o._menuParent=0')
                                 ->getSingleResult();
            //return $ordre;
            
            $ordreMax = null;
            
                foreach ($ordre as $ord)
                {
                    $ordreMax = $ord;
                }
            
                return $ordreMax+1;
        }
        
        
        public function obtenerUltimoOrdenSubMenu($idParent){
            
            $ordre = $this->getEntityManager()
                                 ->createQuery('SELECT MAX(o._ordre) AS ordre FROM Admin_Model_Menu o WHERE o._menuParent=?1')
                                 ->setParameter(1,$idParent)
                                 ->getSingleResult();
            //return $ordre;
            
            $ordreMax = null;
            
                foreach ($ordre as $ord)
                {
                    $ordreMax = $ord;
                }
            
                return $ordreMax+1;
        }
        
        public function guardar(Admin_Model_Menu $menu)
        {   
            //persist updateja o inserta
            $this->getEntityManager()->merge($menu);
            $this->getEntityManager()->flush();
        }
        
        public function guardarSubMenu(Admin_Model_SubMenu $menu)
        {   
            //persist updateja o inserta
            $this->getEntityManager()->persist($menu);
            $this->getEntityManager()->flush();
        }

        public function eliminar(Admin_Model_Menu $menu)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $menuFind = $this->getEntityManager()->find('Admin_Model_Menu', $menu->getId());
            
            $this->getEntityManager()->remove($menuFind);
            $this->getEntityManager()->flush();
        }
        
        public function privacitat(Admin_Model_Menu $id,$valor)
        {
            return $this->getEntityManager()
                            ->createQuery("UPDATE Admin_Model_Menu g SET g._publicar=?1 WHERE g._id=?2")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                            ->setParameter(1,$valor)
                            ->setParameter(2,$id)
                            ->getResult();
        }

        public function updateMenuDeEntrada($id,$entradaId){
            
             return $this->getEntityManager()
                            ->createQuery("UPDATE Admin_Model_Menu g SET g._entradaId=?2 WHERE g._id=?1")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                            ->setParameter(1,$id)
                            ->setParameter(2,$entradaId)
                            //->setParameter(3,$menuParent)
                            ->getResult();
        }
        
        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }
}