<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/24
 * Time: 09:55
 */

namespace app\controller\teacher;


use app\controller\base\baseAction;
use app\model\user;

class indexAction extends baseAction
{
	protected $limitRole = user::Role_Teacher;
	
	public function action_index() {
		return $this->display('teacher/index/index');
	}
}