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
 * @property string $title
 * @property int $type
 * @property string $content
 * @property int $sort
 * @property int $score
 * @property int $is_correct
 *
 * @package app\model
 */
class question extends baseModel
{
	
	const Type_Select = 1; //单选
	const Type_Select_Multiple = 2; //多选
//	const Type_Select_Judge = 3; //判断
//	const Type_Select_Blank = 4; //填空
	const Type_Select_Text = 5; //简答
	
	const Status_Null = 0; //状态
	const Status_Published = 1; //发布
	
	public static $TypeNames = [
		self::Type_Select => '单选题',
		self::Type_Select_Multiple => '多选题',
//		self::Type_Select_Judge => '判断题',
//		self::Type_Select_Blank => '填空题',
		self::Type_Select_Text => '简答题',
	];
	
	public static $StatusNames = [
		self::Status_Null => '无',
		self::Status_Published => '添加到试卷',
	];
	
	protected function __construct($id)
	{
		$this->DAO = $this->questionDAO;
		parent::__construct($id);
	}
	
	public static function getTypeName($type) {
		return isset(self::$TypeNames[$type]) ? self::$TypeNames[$type] : '未定义';
	}
}