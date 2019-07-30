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
use app\dao\collegeDAO;
use app\dao\userDAO;
use app\model\user;

class collegeAction extends baseAction
{
	public function init() {
		parent::init();
		$this->setBreadcrumb('院系管理');
	}
	
	public function action_index() {
		$this->setBreadcrumb('院系列表', true);
		$result = collegeDAO::searchColleges($this->searchData);
		$pages = [
			'total' => $result['total'],
			'num' => $result['num'],
		];
		return $this->display('manage/college/index', ['items' => $result['rows'], 'pages'=>$pages]);
	}
	
	public function action_create() {
		$this->setBreadcrumb('添加院系', true);
		return $this->display('manage/college/form', ['topColleges'=>collegeDAO::topColleges()]);
	}
	
	public function action_edit() {
		$this->setBreadcrumb('编辑院系', true);
		$id = $this->param('id', 0);
		if (empty($id)) {
			return $this->error('出错了, 数据错误');
		}
		$instance = App::$model->college($id);
		if (!$instance->exist()) {
			return $this->error('出错了，数据错误');
		}
		
		$renderData = $instance->attributes();
		
		return $this->display('manage/college/form',['college'=>$renderData,'topColleges'=>collegeDAO::topColleges()]);
	}
	
	public function action_ajax_edit_post() {
		if (self::request()->isPost()) {
			$formData = $this->param('College');
			$redirectUri = '';
			if ($formData['id']) {
				$instance = App::$model->college($formData['id']);
				if (!$instance->exist()) {
					return $this->json(['error'=>'-1','message'=>'出错了，数据不存在或已删除']);
				}
				$numExist = collegeDAO::newInstance()->filter(['!='=>['id'=>$formData['id']], 'title'=>$formData['title']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'院系名重复']);
				}
				$instance->title = $formData['title'];
				$instance->pid = $formData['pid'];
				$instance->updated_at = time();
				$instance->save();
			} else {
				$numExist = collegeDAO::newInstance()->filter(['title'=>$formData['title']])->count();
				if ($numExist > 0) {
					return $this->json(['error'=>'-1','message'=>'院系名重复']);
				}
				$data = [
					'title' => $formData['title'],
					'pid' => $formData['pid'],
					'created_by' => App::$model->user->id,
					'created_at' => time(),
					'updated_at' => time(),
				];
				$instanceId = collegeDAO::newInstance()->add($data);
				if (!$instanceId) {
					return $this->json(['error'=>'-1','message'=>'操作失败']);
				}
				$redirectUri = $this->routerRoot() . "/college/edit/" . $instanceId;
			}
			return $this->json(['error'=>0, 'message'=>'Success!', 'redirectUri'=>$redirectUri]);
		}
		return $this->json(['error'=>'-1','message'=>'非法数据']);
	}
}