<?php
namespace app\shell;

use app\dao\userDAO;
use app\model\user;
use biny\lib\Shell;
use biny\lib\Logger;

/**
 * Class initShell
 *
 * @property userDAO userDAO
 *
 * @package app\shell
 */
class initShell extends Shell
{
    public function init()
    {
        Logger::addLog('initShell');
        return 0;
    }
	
	public function action_admin() {
		$user = $this->userDAO->filter(array('role'=>99))->find();
		if (empty($user)) {
			$this->userDAO->add([
				'username' => 'super',
				'nickname' => '超级管理员',
				'role' => user::Role_Administrator,
				'email' => 'super@super.com',
				'phone' => '18688888888',
				'password_hash' => \YiiSecurity::generatePasswordHash('super'),
				'status' => user::Status_Normal,
				'created_at' => time(),
				'updated_at' =>time()
			]);
			return $this->correct("Success! 管理员账号创建成功");
		} else {
			return $this->correct("Success! 管理员账号已存在");
		}
	}
}