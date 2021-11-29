<?php
    //Id recebido via ajax
    $id = $_POST["idPokemon"];

    //Função feita inteiramente para a aquisição dos dados da API pokeapi 
    function getContent($id){
        try{
            $url = "https://pokeapi.co/api/v2/pokemon/" . $id;

            $response = file_get_contents($url);
            $resp = json_decode($response, true);
            $types = "";

            if($response !== false){
                for($i = 0; $i < count($resp["types"]); $i++){
                    if(count($resp["types"]) - 1 == $i){
                        $types .= $resp["types"][$i]["type"]["name"];
                    }else{
                        $types .= $resp["types"][$i]["type"]["name"] . ", ";
                    }
                }

                $content = array(
                    "ID" => $resp["id"],
                    "nome" => $resp["name"],
                    "tipos" => $types,
                    "imagemConvertida" => base64_encode($resp["sprites"]["front_default"]),
                );

                return $content;

            }

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    $contentArray = getContent($id);

    echo json_encode($contentArray);

?>