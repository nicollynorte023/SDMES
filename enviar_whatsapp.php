<?php

function enviarWhatsApp($numero, $mensagem)
{

    $token = "6j8935vjwpr1s15p";
    $instance = "instance181145";


    $dados = [

        "token" => $token,

        "to" => $numero,

        "body" => $mensagem

    ];



    $curl = curl_init();



    curl_setopt_array($curl,[


        CURLOPT_URL =>
        "https://api.ultramsg.com/$instance/messages/chat",


        CURLOPT_RETURNTRANSFER => true,


        CURLOPT_POST => true,


        CURLOPT_POSTFIELDS =>
        http_build_query($dados),


        CURLOPT_HTTPHEADER => [

            "Content-Type: application/x-www-form-urlencoded"

        ]

    ]);



    $resposta = curl_exec($curl);



    if(curl_errno($curl)){

        $resposta =
        "Erro CURL: ".curl_error($curl);

    }



    curl_close($curl);



    return $resposta;

}

?>