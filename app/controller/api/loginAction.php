<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/31
 * Time: 00:44
 */

namespace app\controller\api;

use App;
use app\controller\api\base\baseAction;

class loginAction extends baseAction
{
	public function action_index() {
		if (self::request()->isPost()) {
			$username = $this->param('username');
			$pwd = $this->param('password');
			
			if (empty($username)) {
				return $this->errorJson('缺少用户名');
			}
			if (empty($pwd)) {
				return $this->errorJson('缺少密码');
			}
			
			$user = $this->userDAO->filter(['username'=>$username])->find();
			if (empty($user)) {
				return $this->errorJson('用户不存在');
			}
			
			$validateResult = \YiiSecurity::validatePassword($pwd, $user['password_hash']);
			if (!$validateResult) {
				return $this->errorJson('密码错误');
			}
			$result = App::$model->user($user['id'])->apiLogin();
			
			return $this->successJson($result);
		}
		return $this->errorJson('仅POST请求');
	}
}