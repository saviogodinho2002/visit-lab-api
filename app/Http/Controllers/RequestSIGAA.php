<?php

namespace App\Http\Controllers;

class RequestSIGAA{

    private static string $token = "Bearer 7de913d7-1e3b-4eef-bd17-889fb39611e4";

    private static function update_token(){
        $base_url = env("API_SIGAA_BASE_URL");
        $response = file_get_contents($base_url . '/authz-server/oauth/token');
        RequestSIGAA::$token = "Bearer " . json_decode($response, true)["access_token"];
    }

    public static function get_info_user(string $login){
            return self::make_request('usuario/v1/usuarios?login=' . $login)["body"][0];
    }

    public static function make_request(string $url, int $time = 0){
        $base_url = env("API_SIGAA_BASE_URL");
        $api_key = env("API_KEY_SIGAA");
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $base_url . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Authorization: ' . self::$token;
        $headers[] = 'X-Api-Key: ' . $api_key;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($time < 3 && $status_code == 401/*Erro de Token Invalido*/){
            self::update_token();
            return self::make_request($url, $time + 1);
        }

        return array(
                "status" => $status_code,
                "body" => json_decode($result, true)
              );
    }

}
