<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/12
 * Time: 下午10:17
 */
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Home;
class HomeController extends BaseController
{
    public function __construct()
    {

    }

    public function index(Home $home,Home $test,Home $hello)
    {
      return $this->view()->display('home/index.tpl');
    }

    public function test(Home $home)
    {
        $data = $home->testData();
        var_dump($data);
    }


}