<?php

namespace app\dao;

class questionItemDAO extends baseDAO
{
    protected $table = 'question_item';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
	public static function searchQuestionItem($searchData) {
		$filters = $searchData;

		$page = max(1, intval($filters['page']));
		$pageSize = max(1, intval($filters['pageSize']));

		unset($filters['page']);
		unset($filters['pageSize']);

		$offset = max(0, ($page-1)*$pageSize);

		$cnt = self::newInstance()
			->filter($filters)
			->count();

		$rows = self::newInstance()
			->filter($filters)
			->order(['sort'=>'asc','id' => 'desc'])
			->limit($pageSize, $offset)
			->query();

		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
}