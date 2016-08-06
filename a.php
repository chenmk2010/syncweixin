<?php

date_default_timezone_set('UTC');

require 'vendor/autoload.php';

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;


use GuzzleHttp\Promise;

$client = new Client(['base_uri' => 'http://httpbin.org/']);

// Initiate each request but do not block
$promises = [
    'image' => $client->getAsync('/image'),
    'png'   => $client->getAsync('/image/png'),
    'jpeg'  => $client->getAsync('/image/jpeg'),
    'webp'  => $client->getAsync('/image/webp')
];

// Wait on all of the requests to complete. Throws a ConnectException
// if any of the requests fail
$results = Promise\unwrap($promises);

// Wait for the requests to complete, even if some of them fail
//$results = Promise\settle($promises)->wait();

// You can access each result using the key provided to the unwrap
// function.
//echo $results['image']->getHeader('Content-Length');
//echo $results['png']->getHeader('Content-Length');

print_r($results);


use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

$promise = $client->requestAsync('GET', 'http://httpbin.org/get');
$promise->then(
    function (ResponseInterface $res) {
        echo $res->getStatusCode() . "\n";
    },
    function (RequestException $e) {
        echo $e->getMessage() . "\n";
        echo $e->getRequest()->getMethod();
    }
);

$promise = $client->getAsync('http://httpbin.org/get');