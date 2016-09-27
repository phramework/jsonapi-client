<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr7Middlewares\Middleware\TrailingSlash;

require __DIR__ . '/../../bootstrap.php';

$adapter = new \Phramework\Database\SQLite($settings->db);

//Set global adapter
\Phramework\Database\Database::setAdapter(
    $adapter
);

class Ctrl
{
    use \Phramework\JSONAPI\Controller\Get;
    use \Phramework\JSONAPI\Controller\GetById;
    use \Phramework\JSONAPI\Controller\Post;
}

$c = new \Slim\Container();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, \Exception $exception) use ($c) {
        switch (get_class($exception)) {
            case \Phramework\Exceptions\NotFoundException::class:
                return $c['errors']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode((object) [
                        'errors' => [(object) [
                            'title'   => $exception->getMessage(),
                            'details' => $exception->getMessage(),
                            'status'  => (string) $exception->getCode(),
                        ]]
                    ]));
            case \Phramework\Exceptions\MissingParametersException::class:
                return $c['errors']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    //->write($exception->getMessage())
                    ->write(json_encode((object) [
                        'errors' => [(object) [
                            'meta'     => (object) [
                                'parameters' => $exception->getParameters(),
                            ], //todo
                            'source'  => $exception->getSource(),
                            'title'   => $exception->getMessage(),
                            'details' => $exception->getMessage(),
                            'status'  => (string) $exception->getCode(),
                        ]]
                    ]));
            case \Phramework\Exceptions\IncorrectParameterException::class:
                return $c['errors']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    //->write($exception->getMessage())
                    ->write(json_encode((object) [
                        'errors' => [(object) [
                            'failure' => $exception->getFailure(),
                            'source'  => $exception->getSource(),
                            'detail'  => $exception->getDetail(),
                            'title'   => $exception->getMessage(),
                            'status'  => (string) $exception->getCode(),
                        ]]
                    ]));
            case \Phramework\Exceptions\IncorrectParametersException::class:
                return $c['errors']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    //->write($exception->getMessage())
                    ->write(json_encode((object) [
                        //todo
                        'exceptions' => $exception->getExceptions()
                    ]));
            case \Exception::class:
            default:
                return $c['errors']->withStatus(400)
                    ->withHeader('Content-Type', 'application')
                    //->write($exception->getMessage())
                    ->write($exception);
        }
    };
};

$app = new \Slim\App($c);

$app->add(new TrailingSlash(true)); // true adds the trailing slash (false removes it)

$app->get('/article/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGet(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Article::getResourceModel()
    );
});

$app->get('/article/{id}/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGetById(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Article::getResourceModel(),
        [],
        $request->getAttribute('id')
    );
});

$app->post('/article/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handlePost(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Article::getResourceModel()
    );
});

$app->get('/tag/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGet(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Tag::getResourceModel()
    );
});

$app->get('/tag/{id}/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGetById(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Tag::getResourceModel(),
        [],
        $request->getAttribute('id')
    );
});

$app->post('/tag/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handlePost(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Tag::getResourceModel()
    );
});

$app->get('/user/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGet(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\User::getResourceModel()
    );
});

$app->get('/user/{id}/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGetById(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\User::getResourceModel(),
        [],
        $request->getAttribute('id')
    );
});

$app->post('/user/', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handlePost(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\User::getResourceModel()
    );
});

$app->run();