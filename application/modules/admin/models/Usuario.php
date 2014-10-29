<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="usuaris")
 */

class Admin_Model_Usuario //esta es la clase entidad que suele llevar los campos de la bbdd
{
        /**
        * @Id
        * @Column(name="id", type="integer")
        * @GeneratedValue(strategy="AUTO")
        */
	private $_id;	
        /** @Column(name="data", type="datetime") */
        private $_data;
        /** @Column(name="nom", type="string", length=180) */
	private $_nom;
        /** @Column(name="cognoms", type="string", length=180) */
	private $_cognoms;
        /** @Column(name="password", type="string", length=255) */
        private $_password;
        /** @Column(name="mail", type="string", length=40) */
	private $_mail;
        /** @Column(name="avatar", type="string", length=80) */
        private $_avatar;
        /** @Column(name="categoria", type="string", length=50) */
        private $_categoria;
	
	public function getId()
	{
		return $this->_id;
	}

        public function setId($id)
        {
                $this->_id = $id;
        }
        
        public function getData(){
            
            return $this->_data;
        }
	
        public function setData($data){
            
            $this->_data=$data;
        }
        
	public function getNom()
	{
		return $this->_nom;
	}

        public function setNom($nom)
        {
                $this->_nom = $nom;
        }

	public function getCognoms()
	{
		return $this->_cognoms;
	}

        public function setCognoms($cognoms)
        {
                $this->_cognoms = $cognoms;
        }
        
	public function getMail()
	{
		return $this->_mail;
	}

        public function setMail($mail)
        {
                $this->_mail = $mail;
        }
        public function getAvatar()
        {
            return $this->_avatar;
        }
        public function setAvatar($avatar)
        {
            $this->_avatar=$avatar;
            
        }
        public function getPassword()
        {
                return $this->_password;
        }

        public function setPassword($password)
        {
                $this->_password = $password;
        }
        
        public function getCategoria()
        {
            return $this->_categoria;
        }
        
        public function setCategoria($cat)
        {
            $this->_categoria = $cat;
        }
}

