<?php

function enviarWhatsApp($numero, $mensagem)
{
    $instance = "SEU_INSTANCE";
    $token = "SEU_TOKEN";

    $url = "https://api.ultramsg.com/$instance/messages/chat";

    $data = [
        "token" => $token,
        "to" => $numero,
        "body" => $mensagem
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}