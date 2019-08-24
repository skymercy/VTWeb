<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:26
 */

namespace app\controller;


use app\controller\base\baseAction;

class indexAction extends baseAction
{
	public function action_index() {
		return $this->display('student/index/index');
	}
}