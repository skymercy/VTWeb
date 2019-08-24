<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:45
 */

namespace app\controller\manage;


use app\controller\base\baseAction;
use app\model\user;

class indexAction extends baseAction
{
	protected $limitRole = user::Role_Administrator;
	
	public function action_index() {
		return $this->display('manage/index/index');
	}
}