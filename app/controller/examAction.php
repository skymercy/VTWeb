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
use app\dao\examClassesDAO;
use app\dao\examDAO;
use app\model\user;

class examAction extends baseAction
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
	}
	
	public function action_index() {
		$this->setBreadcrumb('我的');
		$this->setBreadcrumb('课程列表', true);
		
		$student = App::$model->student(App::$model->user->id);
		$this->searchData['classes_id'] = $student->classes_id;
		
		$result = examClassesDAO::searchStudentExam($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('student/exam/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_edit() {
		$id = $this->param('id', 0);
		if (empty($id)) {
			$examId = $this->param('examId', 0);
			if (empty($examId)) {
				return $this->error('出错了, 数据错误');
			}
		} else {
			$instance = App::$model->examResult($id);
			if (!$instance->exist()) {
				return $this->error('出错了，数据错误');
			}
			$examId = $instance->exam_id;
		}
		$examInstance = \App::$model->exam($examId);
		if (!$examInstance->exist()) {
			return $this->error('出错了, 数据错误');
		}
		$this->setBreadcrumb('考试');
		$this->setBreadcrumb($examInstance->title, true);
		$examData = examDAO::getExamData($examId);
		
		return $this->display('student/exam/form', ['exam'=>$examData]);
	}
}