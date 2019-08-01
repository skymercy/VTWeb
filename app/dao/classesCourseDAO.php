<?php

namespace app\dao;

class classesCourseDAO extends baseDAO
{
    protected $table = 'classes_course';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
//	public static function searchClasses($searchData) {
//		$filters = $searchData;
//
//		$page = $filters['page'];
//		$pageSize = $filters['pageSize'];
//
//		unset($filters['page']);
//		unset($filters['pageSize']);
//
//		$offset = max(0, ($page-1)*$pageSize);
//
//		$cnt = self::newInstance()
//			->leftJoin(collegeDAO::newInstance(), ['college_id' => 'id'])
//			->leftJoin(collegeDAO::newInstance(), [['college_pid' => 'id']])
//			->filter([$filters, [], []])
//			->count();
//
//		$rows = self::newInstance()
//			->leftJoin(collegeDAO::newInstance(), ['college_id' => 'id'])
//			->leftJoin(collegeDAO::newInstance(), [['college_pid' => 'id']])
//			->filter([$filters, [], []])
//			->order( [['id' => 'desc']] )
//			->limit($pageSize, $offset)
//			->query('classes.*,college.title title1,college2.title title2');
//
//		return [
//			'total' => $cnt,
//			'rows' => $rows,
//			'num' => ceil($cnt/$pageSize),
//		];
//	}
}