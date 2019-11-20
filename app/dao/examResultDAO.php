<?php

namespace app\dao;

class examResultDAO extends baseDAO
{
    protected $table = 'exam_result';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
	const STATUS_Null = -1;
	const STATUS_Normal = 0;
	const STATUS_UnScore = 1;
	const STATUS_Score = 2;
	
	static $StatusNames = [
		self::STATUS_Null => '未开始',
		self::STATUS_Normal => '答题中',
		self::STATUS_UnScore => '已交卷-待评分',
		self::STATUS_Score => '已交卷-已评分',
	];
	
	/**
	 * 返回状态字符串
	 * @param $status
	 * @return mixed
	 */
	public static function getStatusName($status) {
		return isset(self::$StatusNames[$status]) ? self::$StatusNames[$status] : self::$StatusNames[self::STATUS_Null];
	}
}