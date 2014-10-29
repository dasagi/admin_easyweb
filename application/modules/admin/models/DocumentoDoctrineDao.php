<?php

class Admin_Model_DocumentoDoctrineDao
{
	
        public function obtenerTodos() {
            return $this->getEntityManager()
                            ->createQuery('select d from Admin_Model_Documento d WHERE d._id!=0 ORDER BY d._data DESC')
                            ->getResult();
        }

        public function obtenerDocumentosSelect() {

        $docs=$this->getEntityManager()
                        ->createQuery('select d from Admin_Model_Documento d')
                        ->getResult();
        
        $result = array();
                
                foreach ($docs as $doc){
                    $result[$doc->getId()] = $doc->getRuta();
                }
                return $result;
        }
        
        public function obtenerDocumentoPorId($id)
        {
            return $this->getEntityManager()->find('Admin_Model_Documento', $id);
        }
        
        public function guardarDocumentos(Admin_Model_Documento $doc)
        {
            //persist updateja o inserta
            
            $this->getEntityManager()->persist($doc);
            $this->getEntityManager()->flush();
        }
        
        public function eliminar(Admin_Model_Documento $doc)
        {
            
            //per eliminar un objecte primer s'ha de recuperar en la sessió doctrine
            $docFind = $this->getEntityManager()->find('Admin_Model_Documento', $doc->getId());
            
            $this->getEntityManager()->remove($docFind);
            $this->getEntityManager()->flush();

        }

        public function getEntityManager() {
            if (Zend_Registry::isRegistered('em')) {
                return Zend_Registry::get('em');
            }
            return null;
        }
}