<?php

namespace app\dao;

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
}