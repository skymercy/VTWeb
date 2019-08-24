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
use app\dao\examClassesDAO;
use app\dao\examDAO;
use app\dao\examResultDAO;
use app\model\examResult;

class examAction extends baseAction
{
	public function init() {
		parent::init();
	}
	
	public function action_index() {
		$this->setBreadcrumb('我的');
		$this->setBreadcrumb('课程列表', true);
		
		$student = App::$model->student(App::$model->user->id);
		$this->searchData['classes_id'] = $student->classes_id;
		$this->searchData['uid'] = $student->uid;
		
		$result = examClassesDAO::searchStudentExam($this->searchData);
		
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('student/exam/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_edit() {
		$id = $this->param('id', 0);
		$resultData = [];
		if (empty($id)) {
			$examId = $this->param('examId', 0);
			if (empty($examId)) {
				return $this->error('出错了, 数据错误');
			}
			$existInstance = App::$model->examResult(['exam_id'=>$examId, 'uid'=>App::$model->user->id]);
			if ($existInstance->exist()) {
				return $this->error('出错了, 已参加过考试');
			}
		} else {
			$instance = App::$model->examResult($id);
			if (!$instance->exist()) {
				return $this->error('出错了，数据错误');
			}
			$resultData = $instance->attributes();
			$examId = $instance->exam_id;
		}
		$examInstance = App::$model->exam($examId);
		if (!$examInstance->exist()) {
			return $this->error('出错了, 数据错误');
		}
		//exam result
		if (empty($id)) {
			$tsNow = time();
			$expiredAt = $tsNow + $examInstance->duration;
			$resultData = [
				'exam_id' => $examId,
				'uid' => App::$model->user->id,
				'status' => examResult::Status_Doing,
				'start_at' => $tsNow,
				'end_at' => 0,
				'content' => json_encode([]),
				'expired_at' => $expiredAt,
				'created_at' => $tsNow,
			];
			$instanceId =  examResultDAO::newInstance()->add($resultData);
			if (empty($instanceId)) {
				return $this->error('出错了');
			}
			$resultData['id'] = $instanceId;
		}
		$this->setBreadcrumb('考试');
		$this->setBreadcrumb($examInstance->title, true);
		$examData = examDAO::getExamData($examId);
		$resultData['content'] = json_decode($resultData['content'], true);
		
		return $this->display('student/exam/form', ['exam'=>$examData,'result'=>$resultData]);
	}
	
	public function action_ajax_save() {
		if (self::request()->isPost()) {
			$examFormData = $this->param('ExamResult');
			$examContentData = $this->param('ExamContent');
			if ($examFormData['id']) {
				$instance = App::$model->examResult($examFormData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$instance->content = json_encode($examContentData);
				$instance->updated_at = time();
				$instance->save();
				return $this->json(['error'=>0, 'message'=>'Success!']);
			}
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
	
	public function action_ajax_submit() {
		if (self::request()->isPost()) {
			$examFormData = $this->param('ExamResult');
			$examContentData = $this->param('ExamContent');
			if ($examFormData['id']) {
				$instance = App::$model->examResult($examFormData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$instance->content = json_encode($examContentData);
				$instance->updated_at = time();
				$instance->end_at = time();
				$instance->status = examResult::Status_Submit;
				$instance->save();
				return $this->json(['error'=>0, 'message'=>'Success!','redirectUri'=>'/exam']);
			}
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}