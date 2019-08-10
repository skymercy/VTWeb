<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:45
 */

namespace app\controller\manage;

use APP;
use app\controller\manage\base\baseAction;
use app\dao\classesCourseDAO;
use app\dao\courseDAO;
use app\dao\collegeDAO;
use app\dao\questionDAO;
use app\dao\teacherDAO;
use app\dao\userDAO;
use app\model\user;

class courseAction extends baseAction
{
	public function init() {
		parent::init();
		$this->setBreadcrumb('实验课程管理');
	}
	
	public function action_index() {
		$this->setBreadcrumb('实验课程列表', true);
		$result = courseDAO::searchCourse($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/course/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_create() {
		$this->setBreadcrumb('添加实验课程', true);
		return $this->display('manage/course/form', ['allColleges' => collegeDAO::allColleges()]);
	}
	
	public function action_edit() {
		$this->setBreadcrumb('编辑实验课程', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->course($id);
		if (!$instance->exist()) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		return $this->display('manage/course/form',['course'=>$renderData ]);
	}
	
	public function action_questions() {
		$this->setBreadcrumb('编辑题库', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->course($id);
		if (!$instance->exist()) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		$searchData = $this->searchData;
		$searchData['course_id'] = $instance->id;
		$result = questionDAO::searchQuestion($searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		
		return $this->display('manage/course/questions',['course'=>$renderData, 'items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_classes() {
		$this->setBreadcrumb('编辑课程班级', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->course($id);
		if (!$instance->exist()) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		$searchData = $this->searchData;
		$searchData['course_id'] = $instance->id;
		$result = classesCourseDAO::searchClassesCourse($searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		
		return $this->display('manage/course/classes',['course'=>$renderData, 'items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('Course');
			$redirectUri = '';
			if ($formData['id']) {
				$instance = App::$model->course($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$numExist = courseDAO::newInstance()->filter(['!='=>['id'=>$formData['id']], 'title'=>$formData['title']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'实验课程名重复']);
				}
				$instance->title = $formData['title'];
				$instance->updated_at = time();
				$instance->save();
			} else {
				$numExist = courseDAO::newInstance()->filter(['title'=>$formData['title']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'实验课程名重复']);
				}
				$data = [
					'title' => $formData['title'],
					'created_by' => App::$model->user->id,
					'created_at' => time(),
					'updated_at' => time(),
				];
				$instanceId = courseDAO::newInstance()->add($data);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
				$redirectUri = $this->routerRoot() . "/course/edit/" . $instanceId;
			}
			return $this->json(['error'=>0, 'message'=>'Success!', 'redirectUri'=>$redirectUri]);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}