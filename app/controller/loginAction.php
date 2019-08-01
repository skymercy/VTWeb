<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 19:00
 */

namespace app\controller;

use app\controller\base\baseAction;
use App;
use app\model\user;

class loginAction extends baseAction
{
	/**
	 * 登录
	 */
	public function action_index()
	{
		$user = App::$model->user;
		$this->roleRouter($user);
		return $this->display('login');
	}
	
	/**
	 * @param user $user
	 */
	private function roleRouter($user) {
		if ($user->exist()){
			switch ($user->role) {
				case user::Role_Administrator:
					self::redirectToManageIndexPage();
					break;
				case user::Role_Student:
					self::redirectToStudentIndexPage();
					break;
				case user::Role_Teacher:
					self::redirectToTeacherIndexPage();
					break;
			}
		}
	}
	
	public function action_login() {
		if (self::request()->isPost()) {
			$username = $this->param('username');
			$pwd = $this->param('password');
			
			if (empty($username) || empty($pwd)) {
				self::redirectToLoginPage();
			}
			
			$user = $this->userDAO->filter(['username'=>$username])->find();
			if (empty($user)) {
				self::redirectToLoginPage();
			}
			
			$validateResult = \YiiSecurity::validatePassword($pwd, $user['password_hash']);
			if (!$validateResult) {
				self::redirectToLoginPage();
			}
			App::$model->user($user['id'])->login();
			
			if ($lastUrl = App::$base->session->lastUrl){
				unset(App::$base->session->lastUrl);
				self::request()->redirect($lastUrl);
			} else {
				$this->roleRouter(App::$model->user);
			}
		}
	}
	
	public function action_logout() {
		if (App::$model->user && App::$model->user->exist()) {
			App::$model->user->loginOut();
		}
		self::redirectToLoginPage();
	}
}