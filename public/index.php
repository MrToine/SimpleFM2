<?php

require '../vendor/autoload.php';

/**
 * @var mixed $renderer
 */
$renderer = new \Framework\Renderer\TwigRenderer(dirname(__DIR__) . '/app/views');


/**
 * @var mixed $app
 */
$app = new \Framework\App([
    App\News\NewsModule::class
], [
    'renderer' => $renderer
]);

$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);
