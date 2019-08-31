<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/31
 * Time: 00:44
 */

namespace app\controller\api;


use app\controller\api\base\baseAction;

class logoutAction extends baseAction
{
	public function action_index() {
		$accessToken = $this->param('access_token');
		$user = \App::$model->user(['access_token'=>$accessToken]);
		return $this->json($user->attributes());
	}
}