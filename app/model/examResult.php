<?php
/**
 * Created by PhpStorm.
 * User: laozhou
 * Date: 2019/7/23
 * Time: 21:43
 */

namespace app\model;

/**
 * Class classes
 *
 * @property int $uid
 * @property int $exam_id
 * @property int $score
 * @property int $start_at
 * @property int $end_at
 * @property int $content
 * @property int $expired_at
 *
 * @package app\model
 */
class examResult extends baseModel
{
	const Status_Doing = 0;
	const Status_Submit = 1;
	const Status_Check = 2;
	
	static $StatusNames = [
		self::Status_Doing => '答题中',
		self::Status_Submit => '已交卷',
		self::Status_Check => '已阅卷',
	];
	
	protected function __construct($id)
	{
		$this->DAO = $this->examResultDAO;
		parent::__construct($id);
	}
	
	public static function getStatusName($status) {
		return isset(self::$StatusNames[$status]) ? self::$StatusNames[$status] : '未答题';
	}
}