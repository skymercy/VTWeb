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
use app\dao\questionItemDAO;
use app\dao\teacherDAO;
use app\dao\userDAO;
use app\model\question;
use app\model\user;

class questionItemAction extends baseAction
{
	public function init() {
		parent::init();
	}
	
	public function action_ajax_info() {
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		$instance = App::$model->questionItem($id);
		if (!$instance->exist()) {
			return $this->json(['error'=>-1, 'message'=>'出错了, 数据错误']);
		}
		return $this->json(['error'=>0,'data'=>$instance->attributes()]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('QuestionItem');
			$questionId =  $formData['question_id'];
			$question = App::$model->question($questionId);
			if(!$question->exist()) {
				return $this->json(['error'=>'-1','message'=>'非法数据']);
			}
			if ($formData['id'] && $formData['id'] > 0) {
				$instance = App::$model->questionItem($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$instance->title = $formData['title'];
				$instance->content = $formData['content'];
				$instance->sort = $formData['sort'];
				$instance->is_correct = intval($formData['is_correct']);
				if ($formData['is_correct'] == 1 && $question->type == question::Type_Select) {
					if (questionItemDAO::hasExistCorrectItem($questionId, $instance->id)) {
						return $this->json(['error'=>'-1','message'=>'单选题只允许有一个正确答案']);
					}
				}
				$instance->updated_at = time();
				$instance->save();
				$attributes = $instance->attributes();
			} else {
				$attributes = [
					'title' => $formData['title'],
					'content' => $formData['content'],
					'question_id' => $formData['question_id'],
					'sort' => $formData['sort'],
					'is_correct' =>  intval($formData['is_correct']),
					'created_by' => App::$model->user->id,
					'created_at' => time(),
					'updated_at' => time(),
				];
				if ($attributes['is_correct'] == 1 && $question->type == question::Type_Select) {
					if (questionItemDAO::hasExistCorrectItem($questionId)) {
						return $this->json(['error'=>'-1','message'=>'单选题只允许有一个正确答案']);
					}
				}
				$instanceId = questionItemDAO::newInstance()->add($attributes);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
				$attributes['id'] = $instanceId;
			}
			return $this->json(['error'=>0, 'message'=>'Success!', 'items' => [$attributes]]);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}