<?php
// 首页
$app->get('/', function($request, $response, $args) {
    return $this->view->render($response, 'index.html');
});


