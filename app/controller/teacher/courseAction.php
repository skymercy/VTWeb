<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/24
 * Time: 09:54
 */

namespace app\controller\teacher;


use app\model\user;

class courseAction extends \app\controller\manage\courseAction
{
	protected $limitRole = user::Role_Teacher;
}