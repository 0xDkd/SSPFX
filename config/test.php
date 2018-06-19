<?php

namespace NoahBuscher\Macaw;

/**
 * @method static Macaw get(string $route, Callable $callback)
 * @method static Macaw post(string $route, Callable $callback)
 * @method static Macaw put(string $route, Callable $callback)
 * @method static Macaw delete(string $route, Callable $callback)
 * @method static Macaw options(string $route, Callable $callback)
 * @method static Macaw head(string $route, Callable $callback)
 */
class Macaw
{

    public static $halts = false;
    //储存设定的路由地址
    public static $routes = array();
    //储存设定的 http 方法
    public static $methods = array();
    //储存设定的回调函数
    public static $callbacks = array();

    public static $maps = array();
    //预存的正则
    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );

    public static $error_callback;

    /**
     * Defines a route w/ callback and method
     */
    public static function __callstatic($method, $params)
    {

        if ($method == 'map') {
            $maps = array_map('strtoupper', $params[0]);
            $uri = strpos($params[1], '/') === 0 ? $params[1] : '/' . $params[1];
            $callback = $params[2];
        } else {
            /**
             *Macaw::get('/', function() {
             * echo 'I'm a GET request!';
             * });
             *
             * Macaw::post('/', function() {
             * echo 'I'm a POST request!';
             * });
             *
             * Macaw::any('/', function() {
             * echo 'I can be both a GET and a POST request!';
             * });
             */
            $maps = null;
            //寻找路由地址的 '/'所在的位置，如果是带有 '/'则直接用这个，如果没有，就给他加上'/'
            $uri = strpos($params[0], '/') === 0 ? $params[0] : '/' . $params[0];
            //设置回调函数为 控制器字符串或者匿名函数
            $callback = $params[1];
        }
        //将 map 添加到静态属性里面
        array_push(self::$maps, $maps);
        //将设定好的路由地址添加到静态属性里面
        array_push(self::$routes, $uri);
        //将方法变为大写，添加到静态属性里面
        array_push(self::$methods, strtoupper($method));
        //把回调函数添加到静态属性里面
        array_push(self::$callbacks, $callback);
    }

    /**
     * Defines callback if route is not found
     */
    public static function error($callback)
    {
        self::$error_callback = $callback;
    }

    /**
     * 意义不明…… 暂停匹配？
     * @param bool $flag
     */
    public static function haltOnMatch($flag = true)
    {
        self::$halts = $flag;
    }

    /**
     * Runs the callback for the given request
     * 给相应的请求运行回调函数
     */
    public static function dispatch()
    {
        //截取根域名后面的请求链接,若http://123.com/admin/1 ,则$url = /admin/1
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //获取到 http 的请求方法
        $method = $_SERVER['REQUEST_METHOD'];
        //获取正则的意义 （keys）
        $searches = array_keys(static::$patterns);
        //获取正则表达式  (values)
        $replaces = array_values(static::$patterns);
        //设置 found_route 为 false
        $found_route = false;
        //对数组里面的路由进行正则替换
        self::$routes = preg_replace('/\/+/', '/', self::$routes);

        // Check if route is defined without regex
        if (in_array($uri, self::$routes)) {
            //返回我们设定的路有中的地址,加入设置了 admin 和 admin/1，则都返回
            var_dump(self::$routes);
            //返回我们设定好的并且有该 url 字符串的键值
            $route_pos = array_keys(self::$routes, $uri);
            var_dump($uri);
            var_dump($route_pos);
            foreach ($route_pos as $route) {
                // Using an ANY option to match both GET and POST requests
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY' || in_array($method, self::$maps[$route])) {
                    //找到路由
                    $found_route = true;

                    // If route is not an object
                    //如果路由不是对象
                    if (!is_object(self::$callbacks[$route])) {

                        // Grab all parts based on a / separator
                        var_dump(self::$callbacks[$route]);
                        $parts = explode('/', self::$callbacks[$route]);
                        var_dump($parts);
                        // 将数组的指针调到最后的元素,返回数组中最后一个元素的值
                        $last = end($parts);

                        // 使用 @ 符号分割，让他成为数组，方便实例化控制器并执行方法
                        $segments = explode('@', $last);

                        // 实例化对象
                        $controller = new $segments[0]();

                        // 调用设定好的方法
                        $controller->{$segments[1]}();
                        var_dump(self::$halts);
                        if (self::$halts) return;
                    } else {
                        // 执行 回调匿名/闭包函数
                        var_dump(self::$callbacks[$route]);
                        call_user_func(self::$callbacks[$route]);

                        if (self::$halts) return;
                    }
                }
            }
        } else {
            //如果没有匹配到我们设定的路由
            $pos = 0;
            var_dump(self::$routes);
            foreach (self::$routes as $route) {
                if (strpos($route, ':') !== false) {
                    //使用字符串替换，将我们设定中使用 :any , :num ,:all 的文字替换为正则表达式
                    var_dump($searches, $replaces, $route);
                    $route = str_replace($searches, $replaces, $route);
                    var_dump($route);
                }
                //拼接完整的正则，进行正则检验
                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    var_dump($matched);
                    //注意这里的$pos,会对所有的我们设定的情况进行匹配
                    var_dump(self::$methods[$pos]);
                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY' || (!empty(self::$maps[$pos]) && in_array($method, self::$maps[$pos]))) {
                        $found_route = true;
                        /**
                         * array (size=2)
                         * 0 => string '/fuckasd%E9%98%BF%E6%96%AF%E8%92%82%E8%8A%AC' (length=44)
                         * 1 => string 'fuckasd%E9%98%BF%E6%96%AF%E8%92%82%E8%8A%AC' (length=43)
                         */
                        // Remove $matched[0] as [1] is the first parameter.
                        array_shift($matched);
                        //判断是否是闭包/匿名函数
                        if (!is_object(self::$callbacks[$pos])) {
                            //如果不是就直接实例化控制器，方法同上
                            // Grab all parts based on a / separator
                            $parts = explode('/', self::$callbacks[$pos]);

                            // Collect the last index of the array
                            $last = end($parts);

                            // Grab the controller name and method call
                            $segments = explode('@', $last);

                            // Instanitate controller
                            $controller = new $segments[0]();

                            // Fix multi parameters
                            if (!method_exists($controller, $segments[1])) {
                                echo "controller and action not found";
                            } else {
                                //调用控制器中的多个方法,并且传入调用所需要的参数
                                call_user_func_array(array($controller, $segments[1]), $matched);
                            }

                            if (self::$halts) return;
                        } else {
                            //直接调用闭包函数,并且传入调用所需要的参数
                            call_user_func_array(self::$callbacks[$pos], $matched);

                            if (self::$halts) return;
                        }
                    }
                }
                //pos自增1，方便遍历所有的设定
                $pos++;
            }
        }

        // 如果没有匹配到设定的路由，并且也没有匹配到异常路由
        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function () {
                    header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
                    echo '404';
                };
            } else {
                if (is_string(self::$error_callback)) {
                    //调用 GET 假方法，并且传入错误回调
                    self::get($_SERVER['REQUEST_URI'], self::$error_callback);
                    //设置错误回调为空
                    self::$error_callback = null;
                    //调用 dispatch() 进行递归
                    self::dispatch();
                    return;
                }
            }
            //调用错误回调函数
            call_user_func(self::$error_callback);
        }
    }
}
