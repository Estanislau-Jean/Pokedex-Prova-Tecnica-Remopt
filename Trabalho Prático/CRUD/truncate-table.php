<?php

    $request = $_POST["State"];

    $host = "localhost";
    $user = "root";
    $password = "";
    $BD = "tbpokemon";

    $DBH = new PDO('mysql:host='. $host .';dbname='.$BD, $user, $password);

    //Se tiver sido requisitado a tabela sofrerá um truncate
    if($request == "truncate"){
        $PDOStatement = $DBH->prepare("TRUNCATE TABLE tbpokemon");
        $PDOStatement->execute();
    }

    echo "success";

?>