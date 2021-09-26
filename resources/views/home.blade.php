@extends('layouts.app')

<?php 

//conexão com o banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbcoinconverter";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$resultado = ("SELECT * FROM conversor");

$conversao = mysqli_query($conn, $resultado);

 
//configuração da API
$endpoint = 'latest';
$access_key = 'ccd4fad93a0242a8c5d1aa6785ebbda5';

$real = 'BRL';
$dol_americano = 'USD';
$dol_canadense = 'CAD';

// initialize CURL:
$ch = curl_init('http://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'&symbols='.$real.','.$dol_americano.','.$dol_canadense.'&format=1');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// get the JSON data:
$json = curl_exec($ch);
curl_close($ch);

// Decode JSON response:
$cotacao = json_decode($json, true);

/*
echo $cotacao['rates'][$dol_americano];*/

//data e horário
$timezone = new DateTimeZone('America/Sao_Paulo');
$hoje = new DateTime('now', $timezone);

?>

@section('content') 

<table>
    <tr>
        <td>
            <section style="width:280px; height:150px; margin-left:20px; color:black; box-shadow: 4px 4px 4px black; background-color:white">
                <div>
                    <strong>
                        <div style="text-align:center">
                            <label for="">Cotações em <?php echo $hoje->format('d/m/Y    H:i ') ?></label>
                        </div>
                    
                        <div style="padding-left:5px;">
                            <strong>
                            <label style="padding-top:20px;">BRL - R$ <?php echo $cotacao['rates'][$real]?></label>
                            <label>CAD - C$ <?php echo $cotacao['rates'][$dol_canadense]?></label>
                            <label >USD - US$ <?php echo $cotacao['rates'][$dol_americano]?></label> </strong>
                           
                           
                        </div>
                    
                </div>
            </section>
        </td>
        <td>
            <div style="width:1000px; padding-left:50px; ">
                <form method="POST" action="{{ route('cadastrar') }}">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <label for="formGroupExampleInput" style="color:white">Valor</label>
                            <input type="number" class="form-control" name="valor" min="0" step="0.0001" placeholder="0,00" style="text-align:right" required>
                        </div>
                        <div class="col">
                            <label for="inputState" style="color:white" >Converter de</label>
                            <select id="inputState" class="form-control" name="moeda_from" >
                                <option selected>Real - (BRL)</option>
                                <option>Dólar Canadense - (CAD)</option>
                                <option>Dólar Americano - (USD)</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="inputState" style="color:white">Para</label>
                            <select id="inputState" class="form-control" name="moeda_to" >
                            <option selected>Dólar Americano - (USD)</option>
                                <option>Real - (BRL)</option>
                                <option>Dólar Canadense - (CAD)</option>                                
                            </select>
                        </div>            
                        <div class="col">
                            <label >&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <button type="submit" class="btn btn-primary" style="width: 200px; background-color:#69BE28;">
                                                {{ __('Converter') }}
                            </button>
                        </div>
                    </div>
                </form>    
            </div>
        </td>
    </tr>
</table>

<hr>

<div style="background-color:#69BE28; margin:10px; font-weight:bold;"> 

    <table class="table table-sm  table-striped " style="color:black; text-align:center; " id="tabela"  >

        <thead class="thead-dark">        

            <tr>
                <th scope="col" >VALOR ENTRADA</th>

                <th scope="col" >CONVERTER DE</th>

                <th scope="col" >CONVERTIDO PARA</th>

                <th scope="col">VALOR CONVERTIDO</th>  
                
                <th scope="col">DATA/HORÁRIO</th>
            </tr>

        </thead>

        <tbody>
            <?php while($row = $conversao->fetch_assoc()): 
                  //retornar o valor formatado do valor de entrada e valor total
                  $row['valor'] = number_format($row['valor'],4,',', '.');   
                  $row['total'] = number_format($row['total'],4,',', '.');     
            ?> 
                  

                <tr class="table table-bordered " style="color:black" >

                    <td style="font-size:18px;"><?php echo $row['valor'];?></td>

                    <td style="font-size:18px;"><?php echo $row['moeda_from'];?></td>

                    <td style="font-size:18px;"><?php echo $row['moeda_to'];?></td>

                    <td style="font-size:18px;"><?php echo $row['total'];?></td>

                    <td style="font-size:18px;"><?php echo date ('d/m/Y - H:i:s', strtotime($row['horario']));?></td>    
                </tr>         
              
            <?php endwhile; ?>   

        </tbody>

    </table>        

</div>  

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <script>
        $('#tabela').dataTable({
            "order": [[ 4, "desc" ]],
            "oLanguage": {
                "sProcessing": "Aguarde enquanto os dados são carregados ...",
                "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                "sZeroRecords": "Nenhum registro correspondente ao criterio encontrado",
                "sInfoEmtpy": "Exibindo 0 a 0 de 0 registros",
                "sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
                "sInfoFiltered": "",
                "sSearch": "Procurar",
                "oPaginate": {
                    "sFirst":    "Primeiro",
                    "sPrevious": "Anterior",
                    "sNext":     "Próximo",
                    "sLast":     "Último"
                }
            }                              
        });   
    </script>
@endsection

