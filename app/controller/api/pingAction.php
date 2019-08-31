<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/31
 * Time: 23:20
 */

namespace app\controller\api;


use app\controller\api\base\baseAction;

class pingAction extends baseAction
{
	public function action_index() {
		$user = $this->getUserByAccessToken();
		if (empty($user)) {
			return $this->errorJson("登录状态已过期");
		}
		$ts = time();
		$duration = max(0,  $ts - $user->ping_at);
		//如果心跳间隔超过2分钟，认为无效的在线时间
		if ( $duration > 120) {
			$duration = 0;
		}
		$user->ping_at = time();
		$user->ts_online = $user->ts_online + $duration;
		$user->save();
		return $this->successJson(['online'=>$user->ts_online]);
	}
}