<?php

class TelegramBot
{
    protected $token = 'TelegramToken';

    function sendRequest($method, $params = [])
    {
        $url = 'https://api.telegram.org/bot';
        $url .= $this->token;
        $url .= '/' . $method;
        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        };
        return json_decode(
            file_get_contents($url),
            JSON_OBJECT_AS_ARRAY
        );
    }
}