<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 18:14
 */

namespace app\model;

use App;

/**
 * Class user
 *
 * @property int $id
 * @property string $username
 * @property string $nickname
 * @property string $phone
 * @property string $email
 * @property int $role
 *
 * @property int $ping_at
 * @property int $ts_online
 * @property string $access_token
 * @property int $access_token_expired_at
 *
 * @package app\model
 */
class user extends baseModel
{
	const Role_Student = 0;
	const Role_Teacher = 1;
	const Role_Administrator = 99;
	
	const Status_Normal = 0;
	const Status_Locked = 1;
	
	/**
	 * @var array 单例对象
	 */
	protected static $_instance = [];
	
	public static function init($id=null)
	{
		$id = is_null($id) ? App::$base->session->uid : $id;
		return parent::init($id);
	}
	
	protected function __construct($id)
	{
		$this->DAO = $this->userDAO;
		parent::__construct($id);
	}
	
	/**
	 * 登录
	 */
	public function login()
	{
		$this->DAO->updateByPk($this->_pk, ['login_at'=>time(), 'num_login'=>['+'=>1]]);
		App::$base->session->uid = $this->_pk;
	}
	
	/**
	 *  API登录
	 * @return array
	 */
	public function apiLogin()
	{
		$accessToken = \YiiSecurity::generateRandomString();
		$accessTokenExpiredAt = time()+24*3600;
		$this->DAO->updateByPk($this->_pk, ['login_at'=>time(), 'num_login'=>['+'=>1],'access_token'=>$accessToken,'access_token_expired_at'=>$accessTokenExpiredAt,'ping_at'=>time()]);
		App::$base->session->uid = $this->_pk;
		return [
			"uid" => $this->id,
			'username' => $this->username,
			'nickname' => $this->nickname,
			'access_token' => $accessToken,
			'online' => $this->ts_online,
		];
	}
	
	/**
	 * 登出
	 */
	public function loginOut()
	{
		unset(App::$base->session->uid);
	}
}