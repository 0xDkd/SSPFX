<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/12
 * Time: 下午10:09
 */

namespace App\Controllers;

use App\Services\View;

class BaseController
{
    protected $view;
    protected $mail;
    protected $smarty;

    public function __construct()
    {
    }

    public function smarty()
    {
        $this->smarty = View::getSmarty();
        return $this->smarty;
    }

    public function view()
    {
        return $this->smarty();
    }

    public function __destruct()
    {
    }
}