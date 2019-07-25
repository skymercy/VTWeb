<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:26
 */

namespace app\controller;


use app\controller\base\baseAction;
use App;
use app\model\user;

class indexAction extends baseAction
{
	public function action_index() {
		$user = App::$model->user;
		if (!$user->exist()) {
			self::redirectToLoginPage();
		}
		switch ($user->role) {
			case user::Role_Student:
				break;
			case user::Role_Teacher:
				self::redirectToTeacherIndexPage();
				break;
			case user::Role_Administrator:
				self::redirectToManageIndexPage();
				break;
			default:
				exit("非法请求");
		}
	}
}