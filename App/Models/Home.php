<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/12
 * Time: 下午11:10
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $table = 'test1';

    public function testData()
    {
        $data = $this->first();
        return $data;
    }

}