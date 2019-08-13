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
use app\dao\examClassesDAO;

class examAction extends baseAction
{
	public function init() {
		parent::init();
	}
	
	public function action_ajax_info() {
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		$instance = App::$model->exam($id);
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
}