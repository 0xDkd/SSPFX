<?php
/**
 * Route Config
 */
use App\Services\Route;
$route = new Route;
$route->prefix = 'App\\Controllers\\';
$route->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r){
    $r->addRoute('GET','/','HomeController@index');
    $r->addRoute('GET','/test','HomeController@test');
});
$route->dispatch();





///**
// * Add Router
// */
//$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
//
//    $r->addRoute('GET', '/users', 'UserController');
//    // {id} 必须是一个数字 (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    //  /{title} 后缀是可选的
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
//    $r->addRoute('GET','/test','App\Controllers\HomeController@index');
//    $r->addRoute('GET','/' , 'App\Controllers\HomeController@index');
//});
//
//// 获取请求的方法和 URI
//$httpMethod = $_SERVER['REQUEST_METHOD'];
//$uri = $_SERVER['REQUEST_URI'];
//
//// 去除查询字符串( ? 后面的内容) 和 解码 URI
//if (false !== $pos = strpos($uri, '?')) {
//    $uri = substr($uri, 0, $pos);
//}
//$uri = rawurldecode($uri);
//
///**
// * @desc Call Function Or instance Controller
// * @param $handler
// * @throws Exception
// */
//function call($handler)
//{
//    if (!is_object($handler)) {
//        // Grab all parts based on a / separator
//        $parts = explode('/', $handler);
//        var_dump($parts);
//        // 将数组的指针调到最后的元素,返回数组中最后一个元素的值
//        $last = end($parts);
//        // 使用 @ 符号分割，让他成为数组，方便实例化控制器并执行方法
//        $segments = explode('@', $last);
//        // 实例化对象
//        $controller = new $segments[0]();
//        // 调用设定好的方法
//        $controller->{$segments[1]}();
//    } else {
//        call_user_func($handler);
//    }
//}
//
//$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
//switch ($routeInfo[0]) {
//    case FastRoute\Dispatcher::NOT_FOUND:
//        // ... 404 Not Found 没找到对应的方法
//        break;
//    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//        $allowedMethods = $routeInfo[1];
//        // ... 405 Method Not Allowed  方法不允许
//        break;
//    case FastRoute\Dispatcher::FOUND: // 找到对应的方法
//        $handler = $routeInfo[1]; // 获得处理函数
//        $vars = $routeInfo[2]; // 获取请求参数
//        call($handler);
//        break;
//}
//
//
//
