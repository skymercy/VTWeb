<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/31
 * Time: 23:45
 */

namespace app\controller\api;


use app\controller\api\base\baseAction;

class userAction extends baseAction
{
	public function action_index() {
		$user = $this->getUserByAccessToken();
		if (empty($user)) {
			return $this->errorJson("登录状态已过期");
		}
		
		return $this->successJson([
			"uid" => $user->id,
			'username' => $user->username,
			'nickname' => $user->nickname,
			'online' => $user->ts_online,
		]);
	}
}