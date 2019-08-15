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
use app\dao\classesDAO;
use app\dao\studentDAO;
use app\dao\userDAO;
use app\model\user;

class studentAction extends baseAction
{
	public function init() {
		parent::init();
		$this->setBreadcrumb('学生管理');
	}
	
	public function action_index() {
		$this->setBreadcrumb('学生列表', true);
		$this->searchData['role'] = user::Role_Student;
		$result = userDAO::searchUsers($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/student/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_create() {
		$this->setBreadcrumb('添加学生', true);
		return $this->display('manage/student/form',['classes'=>classesDAO::getAllClassesData()]);
	}
	
	public function action_edit() {
		$this->setBreadcrumb('编辑学生', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->user($id);
		if (!$instance->exist() || $instance->role != user::Role_Student) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		$student = App::$model->student($id);
		$renderData['student_no'] = $student->student_no;
		$renderData['classes_id'] = $student->classes_id;
		
		return $this->display('manage/student/form',['student'=>$renderData, 'classes'=>classesDAO::getAllClassesData()]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('Student');
			$redirectUri = '';
			if ($formData['id']) {
				$instance = App::$model->user($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$numExist = userDAO::newInstance()->filter(['!='=>['id'=>$formData['id']], 'username'=>$formData['username']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'用户名重复']);
				}
				$instance->username = $formData['username'];
				$instance->nickname = $formData['nickname'];
				$instance->phone = $formData['phone'];
				$instance->email = $formData['email'];
				$instance->updated_at = time();
				$instance->save();
				
				$student = App::$model->student($instance->id);
				$student->classes_id = $formData['classes_id'];
				$student->student_no = $formData['student_no'];
				$student->updated_at = time();
				$student->save();
			} else {
				$numExist = userDAO::newInstance()->filter(['username'=>$formData['username']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'用户名重复']);
				}
				$data = [
					'username' => $formData['username'],
					'nickname' => $formData['nickname'],
					'phone' => $formData['phone'],
					'email' => $formData['email'],
					'role' => user::Role_Student,
					'status' => user::Status_Normal,
					'created_by' => App::$model->user->id,
					'password_hash' => \YiiSecurity::generatePasswordHash($formData['password']),
					'created_at' => time(),
					'updated_at' => time(),
				];
				$instanceId = userDAO::newInstance()->add($data);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
				studentDAO::newInstance()->add(['uid'=>$instanceId, 'student_no'=>$formData['student_no'] , 'classes_id' => $formData['classes_id'] ,'updated_at'=>time()]);
				$redirectUri = $this->routerRoot() . "/student/edit/" . $instanceId;
			}
			return $this->json(['error'=>0, 'message'=>'Success!', 'redirectUri'=>$redirectUri]);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}