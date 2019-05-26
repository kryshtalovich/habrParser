<?php

use GuzzleHttp\Client;

require_once("vendor/autoload.php");

class Pars
{
    public function getPars()
    {
        $url = 'https://habr.com/ru/';
        $file = file_get_contents($url);

        $doc = phpQuery::newDocument($file);
        return $doc;


        $client = new Client([
            'base_uri' => $url
        ]);
        $result = $client->request('GET');
        return json_decode($result->getBody());
    }
}