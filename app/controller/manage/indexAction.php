<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:45
 */

namespace app\controller\manage;


use app\controller\manage\base\baseAction;

class indexAction extends baseAction
{
	public function action_index() {
		return $this->display('manage/index/index');
	}
}