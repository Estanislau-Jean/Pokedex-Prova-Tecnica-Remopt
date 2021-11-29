<?php
    //Class inteiramente feita para a conexão no banco de dados tbpokemon

    class Connection{
        static $host = "localhost";
        static $user = "root";
        static $password = "";
        static $BD = "tbpokemon";
        private $connection;

        public function get_con(){
            return $this->set_con();
        }

        private function set_con(){
            try{
                $this -> connection = new PDO('mysql:host='. Connection::$host .';dbname='.Connection::$BD, Connection::$user, Connection::$password);
            
                $this -> connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('ERROR: '.$e -> getMessage());
            }
            
            return $this->connection;

        }
    }
?>