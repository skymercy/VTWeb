<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/24
 * Time: 09:53
 */

namespace app\controller\teacher;


use app\model\user;

class classesAction extends \app\controller\manage\classesAction
{
	protected $limitRole = user::Role_Teacher;
}