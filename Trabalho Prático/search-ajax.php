<?php

    //Arquivo utilizado para a requisição dos dados de um pokemon, a fim de passar os mesmos para um swal

    $id = $_POST["idSearch"];

    $host = "localhost";
    $user = "root";
    $password = "";
    $BD = "tbpokemon";

    $DBH = new PDO('mysql:host='. $host .';dbname='.$BD, $user, $password);

    $STH = $DBH -> prepare( "select * from tbpokemon WHERE idRefAPIPokemon = {$id} LIMIT 1");

    $STH -> execute();
    $result = $STH -> fetch();

    echo json_encode($result);


?>