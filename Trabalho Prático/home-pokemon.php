<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link href='https://fonts.googleapis.com/css?family=Livvic' rel='stylesheet'>

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        <style>
            body {
                background-image: url('https://mocah.org/uploads/posts/4548246-pokmon-pixel-art.png');
            }

            .swal2-popup {
                font-size: 1.3rem !important;
                font-family: Roboto;
            }
        </style>
        
        <script>
            //Função para geração do ID aleatório da POKEDEX e atribuição do valor gerado ao form input.
            $(document).ready(function () {
                $('#randomButton').on('click', function(){
                    random = Math.floor(Math.random() * 898 + 1);
                    $("#pokedexID").val(random);
                    readDatabaseToVerify(random);
                   // }
                });
  
            });
            
            //Função de requisição ajax para truncate da tabela de pokemons
            function truncateFunction(){
                    $.ajax({
                        url: "CRUD/truncate-table.php",
                        type: "POST",
                        data: {State: "truncate"},
                        success: function(result){
                            $('#poke').DataTable().search("").draw();
                        }
                    });  
            }

            //Incorporação do datatable com conteúdo ajax
            $(document).ready(function() {
                $('#poke').DataTable( {
                    "processing" : true,
                    "serverSide" : true,
                    "ajax":{
                        "url": "process-table-ajax.php",
                        "type": "POST"
                    }
                    
                } );
            } );

            //Botão para truncate que utiliza de modal
            $(document).ready(function() {
                $('#TrashButton').on('show.bs.modal', function (event){
                    var button = $(event.relatedTarget) 
                    var recipient = button.data('whatever')
                    var modal = $(this)
                });
            });

            //Função para a mostra de dados quando clicado no botão 'search'
            $(document).on('click', '.search', function(){
                var search = $(this).attr("id");

                $.ajax({
                    url: "search-ajax.php",
                    type: "POST",
                    data: {idSearch: search},
                    success: function(result){
                        console.log(result);
                        var SearchData = JSON.parse(result);
                        console.log(SearchData);
                        Swal.fire({
                            title: SearchData["namePokemon"],
                            imageUrl: decodeURIComponent(escape(window.atob(SearchData["imagePokemon"]))),
                            imageHeight: 250,
                            html: "<h4>Tipo: " + SearchData["typesPokemon"] + "<br> ID Pokemon: " + SearchData["idRefAPIPokemon"] + "</h4>",
                            confirmButtonText: 'Fechar',
                        });
                    }
                });
            });

            //Função para a verificação da existência do registro no banco de dados com base em um ID passado
            function verifyPokemonRecord(dataReaded, ID){
                for(let i = 0; i < dataReaded.length; i++){
                    if(dataReaded[i]["idRefAPIPokemon"] == ID){
                        console.log("Registro já inserido");
                        return true;
                    }
                }
                console.log("Registro não encontrado");
                return false;
            }
            
            //Função para a verificação da existência do registro no banco de dados, além da requisição na API e a inserção no banco de dados caso não exista lá
            function readDatabaseToVerify(random){
                $.ajax({
                    url: "CRUD/Read.php",
                    type: "POST",
                    success: function(res){
                        data = JSON.parse(res);
                        console.log(data);
                        if(verifyPokemonRecord(data, random) == false){
                            $.ajax({
                            url: "api-request.php",
                            type: "POST",
                            data: {idPokemon: random},
                            success: function(res){
                                console.log(res);
                                data2 = JSON.parse(res);
                                console.log(data2)
                                $.ajax({
                                    url: "CRUD/Create.php",
                                    type: "POST",
                                    data: {infoPokemon: res},
                                    success: function(re){
                                        console.log(re);
                                        $('#poke').DataTable().search(random).draw();
                                        Swal.fire({
                                            title: data2["nome"] + ' foi registrado!',
                                            text: "GOTTA CATCH 'EM ALL!",
                                            imageUrl: decodeURIComponent(escape(window.atob(data2["imagemConvertida"]))),
                                            imageHeight: 250,
                                            confirmButtonText: 'Fechar',
                                        });
                                    }
                                });
                            }
                            });
                        }else{
                            //Caso o registro já esteja no banco, ele é filtrado na tabela
                            $('#poke').DataTable().search(random).draw();
                        }
                    }
                });
            }



        </script>
    </head>

    <body>
        
        <button type = "button" class = "btn btn-primary" id = "TrashButton" data-toggle = "modal" data-target = "#model"><span class = "material-icons">delete</span></button>

        <div class = "container-fluid my-5">
            <h1><p class="text-xl-center display-4 "> POKÉDEX </p></h1>
        </div>
        <div class = "container bg-light my-5 pt-2 pr-4 pl-4" style = "width: 50%">
            <div class="row">
                <div class="col-">
                    <h2><p class="">Número Randômico</p></h2>
                </div>
                <div class="col">
                    <div class="form-group">
                    <input type="ID" class="form-control" id="pokedexID" placeholder="O ID gerado aparecerá aqui." readonly>
                    </div>
                </div>

                <div class="col-">
                    <button type="submit" class="btn btn-primary" title = "Botão randômico" id = "randomButton"><span class="material-icons">autorenew</span></button>
                </div>

            </div>
        </div>
        <div class = "container text-xl-center">
            <table id="poke" class="table table-bordered"  width = "100%">
                <thead class = "thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome Pokemon</th>
                        <th>Tipo</th>
                        <th>#</th>
                    </tr>
                </thead>
            </table>
        </div>
    
        <!-- Modal!-->
        <div class = "modal" id = "model" tabindex = "-1" role = "dialog">
            <div class = "modal-dialog" role ="document">
                <div class = "modal-content">
                    <div class = "modal-header">
                        <h5 class = "modal-title">Deletar todos os registros.</h5>
                    </div>
                    <div class = "modal-body">
                        <p>DESEJA MESMO APAGAR TODOS OS REGISTROS?</p>
                    </div>
                    <div class = "modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary" id = "truncate" onClick = "truncateFunction()" data-dismiss="modal">Sim</button>
                        <button type="submit" class="btn btn-danger" data-dismiss="modal">Não</button>
                    </div>
                </div>
            </div>
        </div>

    <body>
