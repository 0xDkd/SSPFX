<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/13
 * Time: 上午9:14
 */

namespace App\Services;

class View
{

    const VIEW_BASE_PATH = BASE_PATH . '/resource/view/';

    public $view;
    public $data;

    public function __construct($view)
    {
        $this->view = $view;
    }

    /**
     * @desc Smarty 单独出来
     * @return \Smarty
     */
    public static function getSmarty()
    {

        $smarty = new \Smarty;

        $smarty->setTemplateDir(BASE_PATH . '/resource/views/'); //模板所在的地方
        $smarty->setCompileDir(BASE_PATH . '/storage/framework/smarty/compile/');//编译文件所在的地方
        $smarty->setCacheDir(BASE_PATH . '/storage/framework/smarty/cache/');//设置缓存的目录
        /**
         * todo: 常用视图文件直接在这里分配给 smarty
         */

        return $smarty;
    }


    public static function make($viewName = null)
    {
        if ( ! $viewName ) {
            throw new InvalidArgumentException("视图名称不能为空！");
        } else {
            $viewFilePath = self::getFilePath($viewName);
            if ( is_file($viewFilePath) ) {
                return new View($viewFilePath);
            } else {
                throw new UnexpectedValueException("视图文件不存在！");
            }
        }
    }

    public function with($key, $value = null)
    {
        $this->data[$key] = $value;
        return $this;
    }

    private static function getFilePath($viewName)
    {
        $filePath = str_replace('.', '/', $viewName);
        return BASE_PATH . self::VIEW_BASE_PATH . $filePath.'.php';
    }

    /**
     * @desc
     * @param $method
     * @param $parameters
     * @return View
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'with'))
        {
            return $this->with(snake_case(substr($method, 4)), $parameters[0]);
        }

        throw new BadMethodCallException("方法 [$method] 不存在！.");
    }








}