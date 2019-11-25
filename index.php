<?php
// 如果你使用php的依赖安装。可以使用以下方法自动载入
require 'vendor/autoload.php';

define('APP_DIR', __DIR__ . '/app');

/**
 * autoload
 */
spl_autoload_register(function($sClass) {
    $aPath = explode('\\', $sClass);
    $sFile = APP_DIR . '/' . implode('/', $aPath) . '.php';
    if (is_file($sFile)) {
        include_once $sFile;
    }
});

/**
 * exec
 */
$container = new \Slim\Container();

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        /** @var \Slim\Http\Response $resp */
        $resp = $c['response'];
        return $resp->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    };
};
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(APP_DIR . '/view', [
        'cache' => APP_DIR . '/tmp/tpl/'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};
$app = new \Slim\App($container);
include APP_DIR . '/router.php';
try {
    $app->run();
} catch (Throwable $e) {
}
