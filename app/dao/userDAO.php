<?php

namespace app\dao;

/**
 * 用户表
 */
class userDAO extends baseDAO
{
    protected $table = 'user';
    protected $_pk = 'id';
    protected $_pkCache = true;
    
    public static function searchUsers($searchData) {
		$filters = $searchData;
		
		$page = $filters['page'];
		$pageSize = $filters['pageSize'];
	
		unset($filters['page']);
		unset($filters['pageSize']);
	
		$offset = max(0, ($page-1)*$pageSize);
	
		$cnt = self::newInstance()->filter($filters)->count();
	
		$rows = self::newInstance()->filter($filters)
			->order(['id'=>'desc'])
			->limit($pageSize, $offset)
			->query();
 
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
}