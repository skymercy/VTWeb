<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/24
 * Time: 09:57
 */

namespace app\controller\teacher;


use app\model\user;

class studentAction extends \app\controller\manage\studentAction
{
	protected $limitRole = user::Role_Teacher;
}