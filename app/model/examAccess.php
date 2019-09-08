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
 * @property string $token
 * @property int $course_id
 * @property int $exam_id
 * @property int $uid
 * @property int $status
 * @property int $expired_at
 *
 * @package app\model
 */
class examAccess extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->examAccessDAO;
		parent::__construct($id);
	}
}