<?php
/**
 * Created by aimer.
 * User: aimer
 * Date: 2018/6/13
 * Time: 下午1:50
 */

namespace App\Services;

use Nette\Mail\Message;
class Mail extends Message
{
    public $config;

    protected $from;

    protected $to;
    protected $title;
    protected $body;

    public function __construct($to)
    {
        $this->config = require BASE_PATH . '/config/mail.php';

        $this->setFrom($this->config['username']);

        if (is_array($to)) {
            foreach ($to as $email) {
                $this->addTo($email);
            }
        } else {
            $this->addTo($to);
        }
    }

    public function from($from = null)
    {
        if (!$from) {
            throw new InvalidArgumentException("邮件发送地址不能为空！");
        }
        $this->setFrom($from);

        return $this;
    }

    public static function to($to = null)
    {
        if (!$to) {
            throw new InvalidArgumentException("邮件接收地址不能为空！");
        }
        return new Mail($to);
    }

    public function title($title = null)
    {
        if (!$title) {
            throw new InvalidArgumentException('邮件标题不能为空');
        }

        $this->setSubject($title);

        return $this;
    }


    public function content($content = null)
    {
        if (!$content){
            throw new InvalidArgumentException('邮件内容不能为空');
        }
        $this->setHTMLBody($content);

        return $this;
    }


}