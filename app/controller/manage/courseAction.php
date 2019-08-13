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
use app\dao\classesDAO;
use app\dao\courseDAO;
use app\dao\collegeDAO;
use app\dao\examClassesDAO;
use app\dao\examDAO;
use app\dao\questionDAO;
use app\dao\questionItemDAO;
use app\dao\teacherDAO;
use app\dao\userDAO;
use app\model\question;
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
		if (App::$model->user->role == user::Role_Administrator) {
			$unbindClasses = classesDAO::getUnbindClassesData($id);
		} else {
			$unbindClasses = classesDAO::getUnbindClassesData($id, App::$model->user->id);
		}
		return $this->display('manage/course/classes',['course'=>$renderData, 'items' => $result['rows'], 'pages'=>$pages, 'unbindClasses'=>$unbindClasses]);
	}
	
	public function action_exam() {
		$this->setBreadcrumb('考试管理', true);
		$result = examDAO::searchExam($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/course/exam', ['items' => $result['rows'], 'pages'=>$pages]);
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
	
	public function action_ajax_bind_classes() {
		if (self::request()->isPost()) {
			$formData = $this->param('ClassesCourse');
			$numExist = classesCourseDAO::newInstance()->filter(['course_id'=>$formData['course_id'], 'classes_id'=>$formData['classes_id']])->count();
			if ($numExist > 0) {
				return $this->json(['error'=>'-1','message'=>'不需要重复添加']);
			}
			$data = [
				'course_id' => $formData['course_id'],
				'classes_id'=>$formData['classes_id'],
				'created_by' => App::$model->user->id,
				'created_at' => time(),
			];
			$instanceId = classesCourseDAO::newInstance()->add($data);
			if (!$instanceId) {
				return $this->json(['error'=>'-1','message'=>'操作失败']);
			}
			return $this->json(['error'=>0, 'message'=>'Success!']);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
	
	public function action_ajax_check_publish() {
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->json(['error'=>'-1','message'=>'非法数据']);
		}
		$instance = App::$model->course($id);
		if (!$instance->exist()) {
			return $this->json(['error'=>'-1','message'=>'非法数据']);
		}
		
		$renderData = $instance->attributes();
		
		$checkInfo = courseDAO::checkCoursePublishInfo($id);

		$renderData['total_score'] = floatval($checkInfo['total']/100);
		
		$unbindClasses = examClassesDAO::getClassesData($id, -1);
		
		return $this->json(['error'=>'0','data'=>$renderData, 'errors'=>$checkInfo['errors'],'warnings'=>$checkInfo['warnings'],'classes' => $unbindClasses]);
	}
	
	/**
	 * @return \biny\lib\JSONResponse
	 */
	public function action_ajax_publish()
	{
		if (self::request()->isPost()) {
			$formData = $this->param('Exam');
			$courseId = $formData['course_id'];
			$courseInstance = App::$model->course($courseId);
			if (!$courseInstance->exist()) {
				return $this->json(['error'=>'-1','message'=>'非法数据']);
			}
			$checkInfo = courseDAO::checkCoursePublishInfo($courseId);
			if (count($checkInfo['errors']) > 0) {
				return $this->json(['error'=>'-1','message'=>'发布失败，题库存在错误数据']);
			}
			$start_at = strtotime(sprintf("%s %s:00", $formData['start_date'], $formData['start_time']));
			$end_at = strtotime(sprintf("%s %s:00", $formData['end_date'], $formData['end_time']));
			if ($start_at < 0 || $end_at < $start_at) {
				return $this->json(['error'=>'-1','message'=>'发布失败，考试时间起止错误']);
			}
			if ($end_at - $start_at < $formData['duration']) {
				return $this->json(['error'=>'-1','message'=>'发布失败，考试时间起止时间间隔小于设置的考试时长']);
			}
			
			$data = [
				'course_id' => $courseId,
				'title' => $formData['title'],
				'total' => $checkInfo['total'],
				'duration' => $formData['duration'],
				'start_at' => $start_at,
				'end_at' => $end_at,
				'created_by' => App::$model->user->id,
				'created_at' => time(),
			];
			
			$instanceId = examDAO::newInstance()->add($data);
			if (!$instanceId) {
				return $this->json(['error'=>'-1','message'=>'操作失败']);
			}
			
			examDAO::applyExamDetail($instanceId, $courseId);
			
			$classes = $formData['classes'];
			foreach ($classes as $_row) {
				examClassesDAO::newInstance()->add([
					'classes_id' => $_row,
					'exam_id' => $instanceId,
					'created_by' => App::$model->user->id,
					'created_at' => time(),
				]);
			}
			return $this->json(['error'=>0, 'message'=>'Success!']);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}