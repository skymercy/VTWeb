<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/31
 * Time: 23:20
 */

namespace app\controller\api;


use app\controller\api\base\baseAction;

class examAction extends baseAction
{
	public function action_index() {
		return $this->successJson();
	}
	
	public function action_uri() {
		return $this->successJson(['uri'=>'vt.skyoho.com']);
	}
}