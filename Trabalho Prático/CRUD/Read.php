<?php

    require_once'DB-Connect.php';

    class Read{
        private $connection;

        public function __Construct(){
            $connect = new Connection();
            $this->connection = $connect->get_con();
        }

        //Função para ler e retornar todo o conteúdo da tabela no banco de dados
        public function readStatement(){
            try{
                $sql = $this->connection->prepare('SELECT * FROM tbpokemon');
                $sql->execute();
                $FetchPokemons = $sql->fetchAll();
                return $FetchPokemons;
            }catch(PDOException $e){
                echo "erro";
            }
        }
    }
    
    $reader = new Read();

    echo json_encode($reader->readStatement());

?>