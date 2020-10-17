<?php

namespace App\Services\Domain;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Http;

class HttpChecker
{
    private $url;

    public function __construct($config)
    {
        $this->url  = $config['url'];
    }

    public function check()
    {
        $response = Http::get($this->url);

        return [
            'statusCode' => $response->status(),
            'body'       => $response->body(),
        ];
    }
}
