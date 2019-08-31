<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/9/1
 * Time: 00:01
 */

namespace app\controller\api;


use app\controller\api\base\baseAction;

class experimentAction extends baseAction
{
	public function action_index() {
	
	}
	
	
	public function action_submit() {
		$user = $this->getUserByAccessToken();
		if (empty($user)) {
			return $this->errorJson("登录状态已过期");
		}
		return $this->successJson();
	}
}