<?php

class Admin_Model_Login //esta es la clase entidad que suele llevar los campos de la bbdd
{
    const NOT_IDENTITY ='notIdentity';
    const INVALID_CREDENTIAL = 'invalidCredential';
    const INVALID_USER = 'invalidUser';
    const INVALID_LOGIN = 'invalidLogin';

    /*
     * Mensaje de validaciones por defecto
     */

    protected $_messages = array(

        self::NOT_IDENTITY=>"Not existent identity. A redord with the suplied identity could not be found.",
        self::INVALID_CREDENTIAL=>"Invalid credential. Suplied credentials is invalid",
        self::INVALID_USER=>"Invalid User. Suplied credential is invalid",
        self::INVALID_LOGIN=>"Invalid Login. Fields are empty"
    );

    public function setMessage($messageString, $messageKey=null)
    {
        if($messageKey===null){
            $keys = array_keys($this->_messages);
            $messageKey = current($keys);
        }
        
        if(!isset ($this->_messages[$messageKey])){

            throw new Exception("No message exist for key '$messageKey'");
            
        }
        
        $this->_messages[$messageKey] = $messageString;
        return $this;
    }

    public function setMessages(array $messages)
    {
        foreach($messages as $key=>$messages){
            $this->setMessage($message, $key);
        }
        return $this;
    }

    public static function getIdentity()
    {
        $auth = Zend_Auth::getInstance();

        if($auth->hasIdentity()){
            
            return $auth->getIdentity();
        }
        return null;
    }

    public static function isLoggedIn()
    {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    public function login($nick,$password)
    {
        if(!empty($nick) && !empty($password)){

            $autAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
            $autAdapter->setTableName('usuaris');
            $autAdapter->setIdentityColumn('mail');
            $autAdapter->setCredentialColumn('password');
            $autAdapter->setIdentity($nick);
            $autAdapter->setCredential($password);

            $aut = Zend_Auth::getInstance();
            $result = $aut->authenticate($autAdapter);

            switch ($result->getCode())
            {
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    throw new Exception($this->_messages[self::NOT_IDENTITY]);
                    break;

                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    throw new Exception($this->_messages[self::INVALID_CREDENTIAL]);
                    break;

                case Zend_Auth_Result::SUCCESS:
                    if($result->isValid()){

                        $data = $autAdapter->getResultRowObject();
                        $aut->getStorage()->write($data);

                    }else{
                        
                        throw new Exception($this->_messages[self::INVALID_USER]);
                    }
                    break;

                 default:
                        throw new Exception($this->_messages[self::INVALID_LOGIN]);

                    break;
            }

        }else{

                throw new Exception($this->_messages[self::INVALID_LOGIN]);
        }

        return $this;
    }

    public function logout()
    {
        Zend_Auth::getInstance()->clearIdentity();
        return $this;
    } 
}