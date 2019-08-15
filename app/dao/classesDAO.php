<?php

namespace app\dao;

class classesDAO extends baseDAO
{
    protected $table = 'classes';
    protected $_pk = 'id';
    protected $_pkCache = true;
	
	public static function searchClasses($searchData) {
		$filters = $searchData;
		
		$page = $filters['page'];
		$pageSize = $filters['pageSize'];
		
		unset($filters['page']);
		unset($filters['pageSize']);
		
		$offset = max(0, ($page-1)*$pageSize);
		
		$cnt = self::newInstance()
			->leftJoin(collegeDAO::newInstance(), ['college_id' => 'id'])
			->leftJoin(collegeDAO::newInstance(), [['college_pid' => 'id']])
			->filter([$filters, [], []])
			->count();
		
		$rows = self::newInstance()
			->leftJoin(collegeDAO::newInstance(), ['college_id' => 'id'])
			->leftJoin(collegeDAO::newInstance(), [['college_pid' => 'id']])
			->filter([$filters, [], []])
			->order( [['id' => 'desc']] )
			->limit($pageSize, $offset)
			->query('classes.*,college.title title1,college2.title title2');
		
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
	
	public static function getUnbindClassesData($courseId, $createdBy = 0) {
		$existClassesRows = classesCourseDAO::newInstance()->filter(['course_id'=>$courseId])->query();
		$existClassesIdList = [];
		foreach ($existClassesRows as $row) {
			$existClassesIdList[] = $row['classes_id'];
		}
		$filters = [
			'__not_in__' => ['id'=>$existClassesIdList],
		];
		if($createdBy > 0) {
			$filters['created_by'] = $createdBy;
		}
		$unbindRows = self::newInstance()
			->filter($filters)
			->order(['id'=>'desc'])
			->query();
		$result = [];
		foreach ($unbindRows as $row) {
			$result[$row['id']] = $row['title'];
		}
		return $result;
	}
	
	public static function getAllClassesData() {
		$rows = self::newInstance()->order(['id'=>'desc'])->query('id,title');
		$result = [];
		foreach ($rows as $row) {
			$result[$row['id']] = $row['title'];
		}
		return $result;
	}
}