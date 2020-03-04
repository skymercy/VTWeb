<?php

namespace app\dao;

class classesCourseDAO extends baseDAO
{
    protected $table = 'classes_course';
    protected $_pk = 'id';
    protected $_pkCache = true;
    
    public static function searchStudentCourse($searchData) {
		$filters = $searchData;
	
		$page = $filters['page'];
		$pageSize = $filters['pageSize'];
	
		unset($filters['page']);
		unset($filters['pageSize']);
	
		$offset = max(0, ($page-1)*$pageSize);
	
		$cnt = self::newInstance()
			->leftJoin(courseDAO::newInstance(), ['course_id' => 'id'])
			->leftJoin(userDAO::newInstance(), [[],['created_by' => 'id']])
			->filter([$filters, [], []])
			->count();
	
		$rows = self::newInstance()
			->leftJoin(courseDAO::newInstance(), ['course_id' => 'id'])
			->leftJoin(userDAO::newInstance(),  [[],['created_by' => 'id']])
			->filter([$filters, [], []])
			->order( [['id' => 'desc']] )
			->limit($pageSize, $offset)
			->query('classesCourse.*,course.title,user.nickname teacherName');
	
		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
	
	public static function searchClassesCourse($searchData) {
		$filters = $searchData;

		$page = $filters['page'];
		$pageSize = $filters['pageSize'];

		unset($filters['page']);
		unset($filters['pageSize']);

		$offset = max(0, ($page-1)*$pageSize);

		$cnt = self::newInstance()
			->leftJoin(classesDAO::newInstance(), ['classes_id' => 'id'])
			->leftJoin(collegeDAO::newInstance(), [[],['college_id' => 'id']])
			->leftJoin(collegeDAO::newInstance(), [[],['college_pid' => 'id']])
			->filter([$filters, [], [],[]])
			->count();

		$rows = self::newInstance()
			->leftJoin(classesDAO::newInstance(), ['classes_id' => 'id'])
			->leftJoin(collegeDAO::newInstance(), [[],['college_id' => 'id']])
			->leftJoin(collegeDAO::newInstance(), [[],['college_pid' => 'id']])
			->filter([$filters, [], [], []])
			->order( [['id' => 'desc']] )
			->limit($pageSize, $offset)
			->query('classesCourse.*,classes.title,college.title title1,college3.title title2');

		return [
			'total' => $cnt,
			'rows' => $rows,
			'num' => ceil($cnt/$pageSize),
		];
	}
}