<?php
declare(strict_types=1);

require __DIR__ . '/../../bootstrap.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

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
            case \Phramework\Exceptions\MissingParametersException::class:
                return $c['response']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    //->write($exception->getMessage())
                    ->write(json_encode((object) [
                        'parameters' => $exception->getParameters(),
                        'source' => $exception->getSource()
                    ]));
            case \Phramework\Exceptions\IncorrectParameterException::class:
                return $c['response']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    //->write($exception->getMessage())
                    ->write(json_encode((object) [
                        'errors' => [(object) [
                            'failure' => $exception->getFailure(),
                            'source'  => $exception->getSource(),
                            'detail'  => $exception->getDetail()
                        ]]
                    ]));
            case \Phramework\Exceptions\IncorrectParametersException::class:
                return $c['response']->withStatus($exception->getCode())
                    ->withHeader('Content-Type', 'application/json')
                    //->write($exception->getMessage())
                    ->write(json_encode((object) [
                        'exceptions' => $exception->getExceptions()
                    ]));
            case \Exception::class:
            default:
                return $c['response']->withStatus(400)
                    ->withHeader('Content-Type', 'application')
                    //->write($exception->getMessage())
                    ->write($exception);
        }
    };
};

$app = new \Slim\App($c);

$app->get('/group', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGet(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Group::getResourceModel()
    );
});

$app->get('/group/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGetById(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Group::getResourceModel(),
        [],
        $request->getAttribute('id')
    );
});

$app->post('/group', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handlePost(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Group::getResourceModel()
    );
});

$app->get('/tag', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGet(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Tag::getResourceModel()
    );
});

$app->get('/tag/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGetById(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Tag::getResourceModel(),
        [],
        $request->getAttribute('id')
    );
});

$app->post('/tag', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handlePost(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\Tag::getResourceModel()
    );
});

$app->get('/user', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGet(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\User::getResourceModel()
    );
});

$app->get('/user/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handleGetById(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\User::getResourceModel(),
        [],
        $request->getAttribute('id')
    );
});

$app->post('/user', function (ServerRequestInterface $request, ResponseInterface $response) {
    return Ctrl::handlePost(
        $request,
        $response,
        \Phramework\JSONAPI\APP\Models\User::getResourceModel()
    );
});

$app->run();