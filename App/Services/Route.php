<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/17
 * Time: 下午11:37
 */

namespace App\Services;

use FastRoute;
class Route
{
    public $dispatcher;
    public $prefix = '';

    public function dispatch()
    {
        // 获取请求的方法和 URI
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // 去除查询字符串( ? 后面的内容) 和 解码 URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found 没找到对应的方法
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed  方法不允许
                break;
            case FastRoute\Dispatcher::FOUND: // 找到对应的方法
                $handler = $routeInfo[1]; // 获得处理函数
                $vars = $routeInfo[2]; // 获取请求参数
                $this->call($handler);
                break;
        }
    }

    private function call($handler)
    {
        if (!is_object($handler)) {
            //
            // Grab all parts based on a / separator
            $parts = explode('/', $handler);
            // 将数组的指针调到最后的元素,返回数组中最后一个元素的值
            $last = end($parts);
            // 使用 @ 符号分割，让他成为数组，方便实例化控制器并执行方法
            $segments = explode('@', $last);
            $segments[0] = $this->prefix.$segments[0];
            $container = new Container();
            // 实例化对象
            $controller = new $segments[0]();
            //
            /*
             * 通过反射机制找到参数个数，并且实例化模型对象
             * $reflection = new \ReflectionMethod($segments[0],$segments[1]);
            $paras = $reflection->getParameters();
            $para_list = [];
            foreach ($paras as $para){
                $para_list[] = $para->name;
            }*/
            // 调用设定好的方法
            $controller->{$segments[1]}();
        } else {
            call_user_func($handler);
        }
    }
}