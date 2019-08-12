<?php

namespace app\dao;

use app\model\question;

class courseDAO extends baseDAO
{
    protected $table = 'course';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
	public static function searchCourse($searchData) {
		$filters = $searchData;

		$page = $filters['page'];
		$pageSize = $filters['pageSize'];

		unset($filters['page']);
		unset($filters['pageSize']);

		$offset = max(0, ($page-1)*$pageSize);

		$cnt = self::newInstance()
			->filter($filters)
			->count();

		$rows = self::newInstance()
			->filter($filters)
			->order(['id' => 'desc'])
			->limit($pageSize, $offset)
			->query();

		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
	
	public static function checkCoursePublishInfo($courseId) {
		$questions = questionDAO::getAllPublishedQuestion($courseId);
		$total = 0;
		$errors = [];
		$warnings = [];
		foreach ($questions as $question) {
			$total += $question['score'];
			if ($question['score']<=0) {
				$errors[] = sprintf("<p><span style='color: red;'>ID=[%s]</span>的题目分值为0</p>", $question);
			}
			if ($question['type'] == question::Type_Select || $question['type'] == question::Type_Select_Multiple) {
				$numCorrect = questionItemDAO::getCorrectItemCount($question['id']);
				if ($numCorrect <= 0) {
					$errors[] = sprintf("<p><span style='color: red;'>ID=[%s]</span>的选择题没有设置正确答案</p>", $question);
				}
				if ($numCorrect > 1 && $question['type'] == question::Type_Select ) {
					$errors[] = sprintf("<p><span style='color: red;'>ID=[%s]</span>的单选题存才多个正确答案</p>", $question);
				}
				if ($numCorrect == 1 && $question['type'] == question::Type_Select_Multiple ) {
					$warnings[] = sprintf("<p><span style='color: yellow;'>ID=[%s]</span>的多选题仅设置了1个正确答案</p>", $question);
				}
			}
		}
		return [
			'errors' => $errors,
			'warnings' => $warnings,
			'total' => $total,
		];
	}
}