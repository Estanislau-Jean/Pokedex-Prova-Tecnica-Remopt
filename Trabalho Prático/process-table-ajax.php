<?php

    //Dados da conexão com o Banco de Dados "tbpokemon"
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tbpokemon";

    //Conexão criada com o Banco
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //Receber a requisão da pesquisa 
    $requestData= $_REQUEST;


    //Indice da coluna na tabela visualizar resultado => nome da coluna no banco de dados
    $columns = array( 
        0 => 'idRefAPIPokemon', 
        1 => 'namePokemon',
        2 => 'typesPokemon',
        3 => 'button'
    );

    //Obtendo registros de número total sem qualquer pesquisa
    $all_record = "SELECT idRefAPIPokemon, namePokemon, typesPokemon FROM tbpokemon";
    $result_all_record = mysqli_query($conn, $all_record);
    $count_lines = mysqli_num_rows($result_all_record);

    //Obter os dados a serem apresentados
    $obtain_data = "SELECT idRefAPIPokemon, namePokemon, typesPokemon FROM tbpokemon WHERE 1=1";
    
    if( !empty($requestData['search']['value']) ) {   // se houver um parâmetro de pesquisa, $requestData['search']['value'] contém o parâmetro de pesquisa
        $obtain_data.=" AND ( idRefAPIPokemon LIKE '".$requestData['search']['value']."%' ";    
        $obtain_data.=" OR namePokemon LIKE '".$requestData['search']['value']."%' ";
        $obtain_data.=" OR typesPokemon LIKE '".$requestData['search']['value']."%' )";
    }

    $record_Filtered = mysqli_query($conn, $obtain_data);
    $totalFiltered = mysqli_num_rows($record_Filtered);
    //Ordenar o resultado
    $obtain_data.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
    $record_Filtered = mysqli_query($conn, $obtain_data);

    // Ler e criar o array de dados
    $data = array();
    while( $row =mysqli_fetch_array($record_Filtered) ) {  
        $dat = array(); 
        $dat[] = $row["idRefAPIPokemon"];
        $dat[] = $row["namePokemon"];
        $dat[] = $row["typesPokemon"];
        $dat[] = "<button type='button' class='btn btn-primary search' id = ". $row['idRefAPIPokemon'] . "><span class='material-icons'>search</span></button>";

        $data[] = $dat;
    }


    //Cria o array de informações a serem retornadas para o Javascript
    $json_data = array(
        "draw" => intval( $requestData['draw'] ),//para cada requisição é enviado um número como parâmetro
        "recordsTotal" => intval( $count_lines ),  //Quantidade de registros que há no banco de dados
        "recordsFiltered" => intval( $totalFiltered ), //Total de registros quando houver pesquisa
        "data" => $data   //Array de dados completo dos dados retornados da tabela 
    );

    echo json_encode($json_data);  //enviar dados como formato json

?>