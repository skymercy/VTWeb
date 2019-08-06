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
use app\dao\courseDAO;
use app\dao\collegeDAO;
use app\dao\questionDAO;
use app\dao\teacherDAO;
use app\dao\userDAO;
use app\model\user;

class questionAction extends baseAction
{
	public function init() {
		parent::init();
	}
	
	public function action_ajax_info() {
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		$instance = App::$model->question($id);
		if (!$instance->exist()) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		return $this->json(['error'=>0,'data'=>$instance->attributes()]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('Question');
			if ($formData['id'] && $formData['id'] > 0) {
				$instance = App::$model->question($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$score = max(0, floatval($formData['score'])*100);
				$instance->score = $score;
				$instance->title = $formData['title'];
				$instance->content = $formData['content'];
				$instance->sort = $formData['sort'];
				$instance->status = $formData['status'];
				$instance->is_correct = intval($formData['is_correct']);
				$instance->updated_at = time();
				$instance->save();
			} else {
				$score = max(0, floatval($formData['score'])*100);
				$data = [
					'title' => $formData['title'],
					'content' => $formData['content'],
					'course_id' => $formData['course_id'],
					'score' => $score,
					'sort' => $formData['sort'],
					'is_correct' =>  intval($formData['is_correct']),
					'status' => $formData['status'],
					'created_by' => App::$model->user->id,
					'created_at' => time(),
					'updated_at' => time(),
				];
				$instanceId = questionDAO::newInstance()->add($data);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
			}
			return $this->json(['error'=>0, 'message'=>'Success!']);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}