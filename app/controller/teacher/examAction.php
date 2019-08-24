<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/24
 * Time: 09:54
 */

namespace app\controller\teacher;


use app\model\user;

class examAction extends \app\controller\manage\examAction
{
	protected $limitRole = user::Role_Teacher;
}