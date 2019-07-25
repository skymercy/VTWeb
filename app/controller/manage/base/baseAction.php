<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/25
 * Time: 15:43
 */

namespace app\controller\manage\base;

use App;
use app\model\user;

class baseAction extends \app\controller\base\baseAction
{
	public function init() {
		if (!APP::$model->user->exist()){
			if (self::request()->isAjax() || self::request()->isPost()) {
				exit('非法访问');
			} else {
				self::redirectToLoginPage();
			}
		}
		if (App::$model->user->role != user::Role_Administrator) {
			if (self::request()->isAjax() || self::request()->isPost()) {
				exit('非法访问');
			} else {
				if (App::$model->user->role == user::Role_Student) {
					self::redirectToStudentIndexPage();
				} else if (App::$model->user->role == user::Role_Teacher) {
					self::redirectToTeacherIndexPage();
				} else {
					exit('非法访问');
				}
			}
		}
		parent::init();
	}
}