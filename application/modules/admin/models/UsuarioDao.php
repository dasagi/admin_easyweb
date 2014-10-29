<?php
class Admin_Model_UsuarioDao
{
	private $_table;
	
	public function __construct()
	{
		$this->_table = new Admin_Model_DbTable_Usuario();
	}

        //retornamos el listado de usuarios
	public function obtenerTodos(Zend_Paginator $paginator)
	{
		$listaUsuario = new ArrayObject();
                $select = $this->_table->select();
                //$rows = $this->_table->fetchAll($select);
                
                    foreach ($paginator->getIterator() as $row){
                        //treim tots els camps de la BBDD
                        $usuario = new Admin_Model_Usuario();
                        $usuario->setId((int)$row->id);
                        $usuario->setData($row->data);
                        $usuario->setNom($row->nom);
                        $usuario->setCognoms($row->cognoms);
                        $usuario->setPassword($row->password);
                        $usuario->setMail($row->mail);
                        $usuario->setAvatar($row->avatar);
                        $usuario->setCategoria($row->categoria);
                        
                        //afegim camps a l'array
                        $listaUsuario->append($usuario);
                        
                       
                    }
                    //retornem array
                    return $listaUsuario; 
                    
	}
        
        //obtiente un usuario según el Id recibido
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
                            $usuario->setAvatar($row->avatar);
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
                        $usuario->setAvatar($row->avatar);
                        $usuario->setCategoria($row->categoria);
          
			if($usuario->getNom($row->nom) ==  $nombre) {
				$usuariosEncontrados->append($usuario);
			}
		}
		return $usuariosEncontrados;
	}
        
        /*
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
        */
        
        public function ifExistEmail($email)
        {
            $usuario = null;
            
            $select = $this->_table->select()->where('mail = ?', $email);
            $row = $this->_table->fetchAll($select);
             
            //var_dump($select->__toString()); //con __toString podem fer la consulta Select
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
        
       /* public function formatearPassword(Admin_Model_Usuario $usuario,$email,$password)
        {
            
            $select = $this->_table->select()->where('mail = ?',$email);
            $row = $this->_table->fetchAll($select);
            
            foreach ($row as $user){
                $userObj = new Admin_Model_Usuario();
                $userObj->setMail($user->mail);
                
                if($userObj->getMail($user->mail) == $email){
                    
                    //insertar una nova contrasenya
                    $row = $this->_table->find($user->getId())->current();     
                    //$row->id = $usuario->getId();
                    
                    $row->nom = $usuario->getNom();
                    $row->data = $usuario->getData();
                    $row->cognoms = $usuario->getCognoms();
                    $row->password = sha1($usuario->getPassword());
                    $row->mail = $usuario->getMail();
                    $row->avatar = $usuario->getAvatar();
                    $row->categoria = $usuario->getCategoria();
                    $row->save();                                        
                }
            }           
        }*/
        
        public function obtenerIdPorEmail($email)
        {
            $id = null;
            
            $select = $this->_table->select()->where('mail = ?',$email);
            $row = $this->_table->fetchAll($select);
            
            foreach ($row as $valor){
                
                //creem instancia entitat usuari i setejem el camp mail
                $usuario = new Admin_Model_Usuario();
                $usuario->setMail($valor->mail);
                
                if($usuario->getMail($valor->mail) == $email){
                    
                    $id = $valor->id;
                }
            }
            //retornem l'id
            return $id;
        }
        
        public function formatPassword($id,$password)
        {
            //busquem per id passat per paràmetre
            $row = $this->_table->find($id)->current();
            
            //al camp password li passem el password i el guardem
            $row->password = sha1($password);
            $row->save();
 
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
            $row->avatar = $usuario->getAvatar();
            $row->categoria = $usuario->getCategoria();
            $row->save();
        }
        
        //aquesta funció és igual guardar() però no guarda el password en sha1()
        
        public function guardarAvatar(Admin_Model_Usuario $usuario)//hacemos referencia de la clase Usuario y le preguntamos si el ID está a 0
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
            $row->password = $usuario->getPassword();
            $row->mail = $usuario->getMail();
            $row->avatar = $usuario->getAvatar();
            $row->categoria = $usuario->getCategoria();
            $row->save();
        }
        
        public function eliminar(Admin_Model_Usuario $usuario)
        {
            $row = $this->_table->find($usuario->getId())->current();
            if(null !== $row){
                $row->delete();
                return true;
            }
            return false;
        }
        
        public function getTable()
        {
            //aquest métode ens retorna la taula que s'inicialitza al constructor amb la classe ...DbTable_Producto
            return $this->_table;
        }
}