<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 22:45
 */

namespace app\controller\manage;

use APP;
use app\controller\base\baseAction;
use app\dao\classesDAO;
use app\dao\examClassesDAO;
use app\dao\examDAO;
use app\dao\studentDAO;
use app\model\user;

class examAction extends baseAction
{
	protected $limitRole = user::Role_Administrator;
	
	public function init() {
		parent::init();
	}
	
	public function action_ajax_info() {
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		$instance =$instance = App::$model->exam($id);
		if (!$instance->exist()) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		$renderData = $instance->attributes();
		$renderData['start_date'] = date('Y-m-d', $instance->start_at);
		$renderData['start_time'] = date('H:i',$instance->start_at);
		$renderData['end_date'] = date('Y-m-d', $instance->end_at);
		$renderData['end_time'] = date('H:i',$instance->end_at);
		$renderData['classes'] = examClassesDAO::getExistClassesData($instance->id);
		return $this->json(['error'=>0,'data'=>$renderData,'classes'=>examClassesDAO::getClassesData($instance->course_id, $instance->id)]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('Exam');
			if ($formData['id'] && $formData['id'] > 0) {
				$instance = App::$model->exam($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$start_at = strtotime(sprintf("%s %s:00", $formData['start_date'], $formData['start_time']));
				$end_at = strtotime(sprintf("%s %s:00", $formData['end_date'], $formData['end_time']));
				if ($start_at < 0 || $end_at < $start_at) {
					return $this->json(['error'=>'-1','message'=>'发布失败，考试时间起止错误']);
				}
				if ($end_at - $start_at < $formData['duration']) {
					return $this->json(['error'=>'-1','message'=>'发布失败，考试时间起止时间间隔小于设置的考试时长']);
				}
				$instance->duration = $formData['duration'];
				$instance->start_at = $start_at;
				$instance->end_at = $end_at;
				$instance->title = $formData['title'];
				$instance->save();
				
				$classes = $formData['classes'];
				examClassesDAO::newInstance()->filter(['exam_id'=>$instance->id])->delete();
				foreach ($classes as $_row) {
					examClassesDAO::newInstance()->add([
						'classes_id' => $_row,
						'exam_id' => $instance->id,
						'created_by' => App::$model->user->id,
						'created_at' => time(),
					]);
				}
			} else {
				return $this->json(['error'=>'-1','message'=>'非法数据']);
			}
			return $this->json(['error'=>0, 'message'=>'Success!']);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
	
	
	public function action_index() {
		$this->setBreadcrumb('考试统计', true);
		$result = examDAO::searchExam($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/course/exam2', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	
	public function action_chart() {
		
		$examId = $this->get('examId', 0);
		if (empty($examId)) {
			return $this->error('出错了, 数据错误');
		}
		$exam = $instance = App::$model->exam($examId);
		if (!$exam->exist()) {
			return $this->error('出错了, 数据错误');
		}
		
		$classesRows = examClassesDAO::newInstance()
			->leftJoin(classesDAO::newInstance(), ['classes_id'=>'id'])
			->filter([['exam_id'=> $examId]])
			->query("classes.title");
		
		$titles = [];
		foreach ($classesRows as $row) {
			$titles[] = $row['title'];
		}
		
		$examData = $exam->attributes();
		
		$examData['classes'] = implode(',', $titles);
		
		$this->searchData['examId'] = $examId;
		
		$result = studentDAO::searchStudentExamResult($this->searchData);
		
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		
		return $this->display('manage/course/exam_chart',['exam'=> $examData, 'items'=>$result['rows'], 'pages'=>$pages]);
	}
}