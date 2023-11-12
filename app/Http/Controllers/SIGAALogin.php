<?php

namespace App\Http\Controllers;

use App\Exceptions\SigaaLoginTimeOutException;

class SIGAALogin{


    /**
     * @throws \Exception
     */
    public static function login_sigaa(string $login, string $password){
        $response = self::make_request(env("API_AUTHENTICATION_SIGAA") . '/sso-server/login');
        $lt_execution = self::get_lt_and_execution($response["body"]);
        $jsession = self::get_jsession($response);

        return self::validate($jsession, $lt_execution["lt"], $lt_execution["execution"], $login, $password);
    }

    /**
     * @throws \Exception
     */
    private static function validate(string $jsession, string $lt, string $execution, string $login, string $password){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, env("API_AUTHENTICATION_SIGAA") . "/sso-server/login;jsessionid=" . $jsession);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=".$login."&password=".urldecode($password)."&lt=".$lt."&execution=".$execution."&_eventId=submit&submit=Submit");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $headers = array();
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Origin: https://autenticacao.ufopa.edu.br';
        $headers[] = 'Referer: https://autenticacao.ufopa.edu.br/sso-server/login';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36';
        $headers[] = 'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"';
        $headers[] = 'sec-ch-ua-mobile: ?0';
        $headers[] = 'sec-ch-ua-platform: "Linux"';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) {

            return throw new SigaaLoginTimeOutException("Timeout");
        }
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $header = substr($result, 0, $header_size);

        $re_castgc = '/(?<=Set-Cookie: CASTGC=).+?(?=;)/m';
        preg_match_all($re_castgc, $header, $matches, PREG_SET_ORDER, 0);

        //Se encontrou o cabeçaçho de autenticação
        return count($matches) > 0 and count($matches[0]);
    }

    private static function get_lt_and_execution(string $html_login_page){
        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($html_login_page);
        libxml_use_internal_errors(false);

        $inputs = $doc->getElementsByTagName('input');
        $lt = $inputs->item(2)->getAttribute('value');
        $execution = $inputs->item(3)->getAttribute('value');

        return [
            "lt" => $lt,
            "execution" => $execution
        ];
    }

    private static function get_jsession(array $response){
        $re_jsession = '/(?<=Set-Cookie: JSESSIONID=).+?(?=;)/m';
        preg_match_all($re_jsession, $response["header"], $matches, PREG_SET_ORDER, 0);
        return $matches[0][0];
    }

    private static function make_request(string $url, $headers = []){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $header = substr($result, 0, $header_size);
        $body = substr($result, $header_size);

        return array(
                "status" => $status_code,
                "body" => $body,
                "header" => $header
              );
    }

}
