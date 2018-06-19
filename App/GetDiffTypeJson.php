<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/12
 * Time: 下午9:27
 */

namespace App;

use Beanbun\Beanbun;
use Beanbun\Lib\Helper;

class GetDiffTypeJson
{
    public $json_data = [];
    public $type;

    public function __construct($type = null)
    {
        if (!$type) {
            $arr = array(
                3 => 'sankaku',
                4 => 'yandere',
                5 => 'pixiv_male',
                6 => 'pixiv_male_r18',
            );
        } else {
            $this->type = $type;
        }
    }

    public function getJson()
    {
        $beanbun = new Beanbun;
        $beanbun->count = 5;
        $beanbun->seed = [
            'https://www.nyadora.com/ranking?type=3',
            'https://www.nyadora.com/ranking?type=4',
            'https://www.nyadora.com/ranking?type=5',
            'https://www.nyadora.com/ranking?type=6',
        ];
        $beanbun->afterDownloadPage = function ($beanbun) {
            $this->json_data[] = $beanbun->page;
            file_put_contents(__DIR__ . '/' . md5($beanbun->url), $beanbun->page);
        };
        $beanbun->start();
    }
}