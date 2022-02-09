<?php
    class M_generique
    {
        private $bd;
        public function GetBd()
        {
            return $this->bd;
        }
        public function Connexion() // En local
        {
            $dsn="mysql:host=127.0.0.1;port=3306;dbname=meh;charset=utf8";
            $this->bd=new PDO($dsn, "root", "");
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
        }
        // public function Connexion() // Chez OVH
        // {
        //     $dsn="mysql:host=musiquenlgadmin.mysql.db;port=3306;dbname=musiquenlgadmin;charset=utf8";
        //     $this->bd=new PDO($dsn, "musiquenlgadmin", "MusiquE80");
        //     $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
        // }
        public function Deconnexion()
        {
            $this->bd=null;
        }
        public function DernierId()
        {
            return $this->bd->lastInsertId();
        }
    }
?>
