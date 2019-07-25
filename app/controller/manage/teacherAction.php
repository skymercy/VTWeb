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
use app\dao\teacherDAO;
use app\dao\userDAO;
use app\model\user;

class teacherAction extends baseAction
{
	public function init() {
		parent::init();
		$this->setBreadcrumb('教师管理');
	}
	
	public function action_index() {
		$this->setBreadcrumb('教师列表', true);
		$this->searchData['role'] = user::Role_Teacher;
		$result = userDAO::searchUsers($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/teacher/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_create() {
		$this->setBreadcrumb('添加教师', true);
		return $this->display('manage/teacher/form');
	}
	
	public function action_edit() {
		$this->setBreadcrumb('编辑教师', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->user($id);
		if (!$instance->exist() || $instance->role != user::Role_Teacher) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		return $this->display('manage/teacher/form',['teacher'=>$renderData]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('Teacher');
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
					'role' => user::Role_Teacher,
					'status' => user::Status_Normal,
					'password_hash' => \YiiSecurity::generatePasswordHash($formData['password']),
					'created_at' => time(),
					'updated_at' => time(),
				];
				$instanceId = userDAO::newInstance()->add($data);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
				teacherDAO::newInstance()->add(['uid'=>$instanceId, 'updated_at'=>time()]);
				$redirectUri = $this->routerRoot() . "/teacher/edit/" . $instanceId;
			}
			return $this->json(['error'=>0, 'message'=>'Success!', 'redirectUri'=>$redirectUri]);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}