<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/24
 * Time: 09:56
 */

namespace app\controller\teacher;


use app\model\user;

class questionAction extends \app\controller\manage\questionAction
{
	protected $limitRole = user::Role_Teacher;
}