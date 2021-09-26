<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller{
    function getData(Request $dados){
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

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "dbcoinconverter";

         // Criando a conexao
        $conn = mysqli_connect($servername, $username, $password, $database);

        $valor = $dados->input("valor");
        $moeda_from = $dados->input("moeda_from");
        $moeda_to = $dados->input("moeda_to");
      
        switch ($moeda_from){
            case ('Dólar Americano - (USD)'):
                $USD = $cotacao['rates'][$dol_americano];
                break;
            
            case('Dólar Canadense - (CAD)'):
                $CAD = $cotacao['rates'][$dol_canadense];
                break;

            case('Real - (BRL)'):
                $BRL = $cotacao['rates'][$real];
                break;
        }

        switch ($moeda_to){
            case ('Dólar Americano - (USD)'):
                $USD = $cotacao['rates'][$dol_americano];
                break;
            
            case('Dólar Canadense - (CAD)'):
                $CAD = $cotacao['rates'][$dol_canadense];
                break;

            case('Real - (BRL)'):
                $BRL = $cotacao['rates'][$real];
                break;
        }

        if (($moeda_from == 'Real - (BRL)') && ($moeda_to == 'Dólar Americano - (USD)')){
            $total = ($valor * $USD)/$BRL;
        } else if (($moeda_from == 'Real - (BRL)') && ($moeda_to == 'Dólar Canadense - (CAD)')){
            $total = ($valor * $CAD)/$BRL;
        } else if (($moeda_from == 'Real - (BRL)') && ($moeda_to == 'Real - (BRL)')){
            $total = $valor * 1;
        } else if (($moeda_from == 'Dólar Americano - (USD)') && ($moeda_to == 'Real - (BRL)')){
            $total = ($valor * $BRL)/$USD;
        } else if (($moeda_from == 'Dólar Americano - (USD)') && ($moeda_to == 'Dólar Canadense - (CAD)')){
            $total = ($valor * $CAD)/$USD;
        } else if (($moeda_from == 'Dólar Americano - (USD)') && ($moeda_to == 'Dólar Americano - (USD)')){
            $total = $valor * 1;
        } else if (($moeda_from == 'Dólar Canadense - (CAD)') && ($moeda_to == 'Real - (BRL)')){
            $total = ($valor * $BRL)/$CAD;
        } else if (($moeda_from == 'Dólar Canadense - (CAD)') && ($moeda_to == 'Dólar Americano - (USD)')){
            $total = ($valor * $USD)/$CAD;
        } else{
            $total = $valor * 1;
        }

        $string_sql = "INSERT INTO conversor (valor, moeda_from, moeda_to, total) VALUES ('$valor','$moeda_from','$moeda_to','$total')";
        $resultado_string_sql=mysqli_query($conn, $string_sql); 

        //header('Location: {{ route(cadastrar) }}');

        return redirect()->route('home');
        
        
    }
}