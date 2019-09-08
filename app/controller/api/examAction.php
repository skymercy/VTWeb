<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/8/31
 * Time: 23:20
 */

namespace app\controller\api;


use app\controller\api\base\baseAction;
use app\dao\examAccessDAO;
use app\dao\examDAO;
use App;
use app\model\examResult;

class examAction extends baseAction
{
	public function action_index() {
		return $this->successJson();
	}
	
	public function action_uri() {
		$user = $this->getUserByAccessToken();
		if (empty($user)) {
			return $this->errorJson("登录状态已过期");
		}
		$courseNo = $this->param('course_no', '');
		$course = App::$model->course(['unique_no'=>$courseNo]);
		if (!$course->exist()) {
			return $this->errorJson("课程编号错误");
		}
		$examData = examDAO::getCurrentExam($user->id, $course->id);
		if (empty($examData)) {
			return $this->errorJson("考试未开始或已结束");
		}
		if (isset($examData['result_id']) && isset($examData['status']) && $examData['status'] != examResult::Status_Doing) {
			return $this->errorJson("已交卷，无需重复答题");
		}
		
		$examToken = \YiiSecurity::generateRandomString(32);
		examAccessDAO::newInstance()->add([
			'token' => $examToken,
			'course_id' => $course->id,
			'exam_id' => $examData['id'],
			'uid' => $user->id,
			'status' => 0,
			'created_at' => time(),
		]);
		
		return $this->successJson(['uri'=> self::request()->getHostInfo() .  "/exam/access/{$examToken}"]);
	}
}