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
use app\dao\collegeDAO;
use app\dao\teacherDAO;
use app\dao\userDAO;
use app\model\user;

class classesAction extends baseAction
{
	public function init() {
		parent::init();
		$this->setBreadcrumb('班级管理');
	}
	
	public function action_index() {
		$this->setBreadcrumb('班级列表', true);
		$result = classesDAO::searchClasses($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/classes/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_create() {
		$this->setBreadcrumb('添加班级', true);
		return $this->display('manage/classes/form', ['allColleges' => collegeDAO::allColleges()]);
	}
	
	public function action_edit() {
		$this->setBreadcrumb('编辑班级', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->classes($id);
		if (!$instance->exist()) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		return $this->display('manage/classes/form',['classes'=>$renderData, 'allColleges' => collegeDAO::allColleges()]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('Classes');
			$redirectUri = '';
			if ($formData['id']) {
				$instance = App::$model->classes($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$numExist = classesDAO::newInstance()->filter(['!='=>['id'=>$formData['id']], 'title'=>$formData['title']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'班级名重复']);
				}
				$instance->title = $formData['title'];
				$instance->college_id = 0;
				$instance->college_pid = 0;
				if ($formData['college_id'] > 0) {
					$college = App::$model->college($formData['college_id']);
					if ($college->exist()) {
						$instance->college_id = $college->id;
						$instance->college_pid = $college->pid;
					}
				}
				
				$instance->updated_at = time();
				$instance->save();
			} else {
				$numExist = classesDAO::newInstance()->filter(['title'=>$formData['title']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'班级名重复']);
				}
				$data = [
					'title' => $formData['title'],
					'created_by' => App::$model->user->id,
					'college_id' => 0,
					'college_pid' => 0,
					'created_at' => time(),
					'updated_at' => time(),
				];
				if ($formData['college_id'] > 0) {
					$college = App::$model->college($formData['college_id']);
					if ($college->exist()) {
						$data['college_id'] = $college->id;
						$data['college_pid'] = $college->pid;
					}
				}
				$instanceId = classesDAO::newInstance()->add($data);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
				$redirectUri = $this->routerRoot() . "/classes/edit/" . $instanceId;
			}
			return $this->json(['error'=>0, 'message'=>'Success!', 'redirectUri'=>$redirectUri]);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}