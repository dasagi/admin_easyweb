<?php

class Admin_Model_BannerDoctrineDao
{
        //retornem el llistat de entrades
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('select b from Admin_Model_Banner b')
                            ->getResult();
        }
        //obtiente un usuario según el Id recibido
	/*
        public function obtenerPorId($id)
	{
		$usuario = null;

                    $row = $this->_table->find($id)->current();

                    //si devuelve resultado y creamos el usuario
                    if($row){

                            $usuario = new Admin_Model_Usuario();
                            $usuario->setId((int)$row->id);
                            $usuario->setData($row->data);
                            $usuario->setNom($row->nom);
                            $usuario->setCognoms($row->cognoms);
                            $usuario->setPassword($row->password);
                            $usuario->setMail($row->mail);
                            $usuario->setCategoria($row->categoria);
                    }
                    return $usuario;
	}

	public function buscarPorNombre($nombre)
	{	
		$usuariosEncontrados = new ArrayObject();
		
                $select = $this->_table->select()->where('nom = ?', $nombre);
                $rows = $this->_table->fetchAll($select);

               // var_dump($select->__toString()); //con __toString podemos fer la consulta Select
                //die();
                
		foreach ($rows as $row) {

                        $usuario = new Admin_Model_Usuario();
                        $usuario->setId((int)$row->id);
                        $usuario->setData($row->data);
                        $usuario->setNom($row->nom);
                        $usuario->setCognoms($row->cognoms);
                        $usuario->setPassword($row->password);
                        $usuario->setMail($row->mail);
                        $usuario->setCategoria($row->categoria);
          
			if($usuario->getNom($row->nom) ==  $nombre) {
				$usuariosEncontrados->append($usuario);
			}
		}
		return $usuariosEncontrados;
	}
        
        
	public function obtenerCuenta($email, $clave)
	{
		$usuario = null;

                $row = $this->_table->fetchAll($this->_table->select()->where('mail = ?', $email));
                 
		foreach ($row as $usr) {

                        $user_obj = new Admin_Model_Usuario();
                        $user_obj->setMail($usr->mail);
                        $user_obj->setPassword($usr->password);
                        
			if($user_obj->getMail($usr->mail) ==  $email && $user_obj->getPassword($usr->password) ==  $clave){
				$usuario = $usr;
				break;
			}
		}
		return $usuario;
	}
        
        
        public function ifExistEmail($email)
        {
            $usuario = null;
            
            $select = $this->_table->select()->where('mail = ?', $email);
            $row = $this->_table->fetchAll($select);
             
            //var_dump($select->__toString()); //con __toString podemos fer la consulta Select
            //die();
            
            foreach ($row as $user){
                
                $user_obj = new Admin_Model_Usuario();
                $user_obj->setMail($user->mail);
                
                if($user_obj->getMail($user->mail) == $email){
                    
                    $usuario = $user;
                    break;
                }
            }
            return $usuario;           
        }

        public function guardar(Admin_Model_Usuario $usuario)//hacemos referencia de la clase Usuario y le preguntamos si el ID está a 0
        {
            if($usuario->getId()==0){

                $row = $this->_table->createRow();
                            
            }else{
                
                $row = $this->_table->find($usuario->getId())->current();
            }
            //$row->id = $usuario->getId();
            $row->nom = $usuario->getNom();
            $row->data = $usuario->getData();
            $row->cognoms = $usuario->getCognoms();
            $row->password = sha1($usuario->getPassword());
            $row->mail = $usuario->getMail();
            $row->categoria = $usuario->getCategoria();
            $row->save();
        }
        */
        
        public function guardar(Admin_Model_Banner $banner)
        {
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $bannerFind = $this->getEntityManager()->find('Admin_Model_Banner', $banner->getId());
            
            //persist updateja
            $this->getEntityManager()->persist($bannerFind);
            $this->getEntityManager()->flush();
        }
        
        public function privacitat(Admin_Model_Banner $id,$valor)
        {
            return $this->getEntityManager()
                            ->createQuery("UPDATE Admin_Model_Banner b SET b._actiu=?1 WHERE b._id=?2")//valor te el número 1 i id el 2 a l'hora de substituir les variables a les consultes
                            ->setParameter(1,$valor)
                            ->setParameter(2,$id)
                            ->getResult();
        }
        
        public function eliminar(Admin_Model_Banner $banner)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $bannerFind = $this->getEntityManager()->find('Admin_Model_Banner', $banner->getId());
            
            $this->getEntityManager()->remove($bannerFind);
            $this->getEntityManager()->flush();
        }
        
        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }
}