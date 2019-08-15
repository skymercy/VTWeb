<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:45
 */

namespace app\controller;

use APP;
use app\controller\base\baseAction;
use app\dao\classesCourseDAO;
use app\model\user;

class courseAction extends baseAction
{
	public function init() {
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
		parent::init();
		$this->setBreadcrumb('我的');
	}
	
	public function action_index() {
		$this->setBreadcrumb('课程列表', true);
		
		$student = App::$model->student(App::$model->user->id);
		$this->searchData['classes_id'] = $student->classes_id;
		$result = classesCourseDAO::searchStudentCourse($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('student/course/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
}