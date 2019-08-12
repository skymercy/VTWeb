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
 * @property int $src_id
 * @property string $items
 * @property int $sort
 * @property int $score
 * @property int $is_correct
 *
 * @package app\model
 */
class examQuestion extends baseModel
{
	protected function __construct($id)
	{
		$this->DAO = $this->examQuestionDAO;
		parent::__construct($id);
	}
}