<?php
    Class MessageErreur
    {
        // MessageErreur représente une indication (message, valeurs incriminées) concernant une erreur de saisie.
        private $message;
        private $info;
        public function __construct($unMessage,$uneInfo)
        {
            $this->message=$unMessage;        
            $this->info=$uneInfo;
        }
        public function GetMessage()
        {
            return $this->message;
        }
        public function GetInfo()
        {
            return $this->info;
        }
    }
?>