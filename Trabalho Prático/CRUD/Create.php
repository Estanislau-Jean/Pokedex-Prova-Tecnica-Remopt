<?php

    require_once'DB-Connect.php';

    class Create{
        private $connection;

        public function __Construct(){
            $connect = new Connection();
            $this->connection = $connect->get_con();
        }

        //Função para a inserção de novos dados no banco, recebendo como parâmetro as informações de cada pokemon
        public function createStatement($nomePokemon, $ID, $imagem, $Tipo){
            try {
                $statement = "INSERT INTO tbpokemon VALUES (" . '"' .$nomePokemon . '"'.", ". '"' . $ID . '"'. ", ". '"'. $imagem . '"' .", " . '"' . $Tipo  . '"' .");";  
                $sql = $this->connection->prepare($statement);
                $sql->execute();
            } catch (PDOException $e) {
                echo "<b>Erro";
            }
        }
    }

    $create = new Create();
    $data = json_decode($_POST["infoPokemon"], true);

    $create->createStatement($data["nome"], $data["ID"], $data["imagemConvertida"], $data["tipos"]);
    
    echo "Success";
?>