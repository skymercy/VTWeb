/** 用户表 **/
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `nickname` VARCHAR(50) NOT NULL,
  `gendar` SMALLINT NOT NULL DEFAULT '0',
  `role` SMALLINT NOT NULL DEFAULT '0' COMMENT '角色:0学生, 1教师, 99超级管理员',
  `email` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `password_hash` VARCHAR(60) NOT NULL,
  `num_login` INT(11) NOT NULL DEFAULT '0',
  `login_at` INT(11) NOT NULL DEFAULT '0',
  `status` SMALLINT NOT NULL DEFAULT '1' COMMENT '状态:0正常 1锁定',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_day` INT(11) NOT NULL DEFAULT '0',
  `created_month` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY (`username`),
  KEY `idx-created` (`created_day`,`created_month`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/** 学生表 */
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `uid` INT(11) NOT NULL COMMENT 'user表的id',
  `student_no` VARCHAR(50) NOT NULL COMMENT '学生学号',
  `classes_id` INT(11) NOT NULL COMMENT '所属班级',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  INDEX `idx-classes` (`classes_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/** 教师表 */
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `uid` INT(11) NOT NULL COMMENT 'user表的id',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/**院系表*/
DROP TABLE IF EXISTS `college`;
CREATE TABLE `college` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `pid` INT(11) NOT NULL DEFAULT '0' COMMENT '父节点id',
  `title` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '院系名',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-pid` (`pid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/**班级表*/
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `title` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '班级名',
  `college_id` INT(11) NOT NULL DEFAULT '0' COMMENT '所属院系',
  `college_pid` INT(11) NOT NULL DEFAULT '0' COMMENT '所属院系',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-college` (`college_id`,`college_pid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/**课程表*/
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `title` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '班级名',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/**课程-班级表*/
DROP TABLE IF EXISTS `classes_course`;
CREATE TABLE `classes_course` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `classes_id` INT(11) NOT NULL ,
  `course_id` INT(11) NOT NULL,
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_classes_course` (`classes_id`,`course_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/**题库*/
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `course_id` INT(11) NOT NULL DEFAULT '0',
  `type` INT(11) NOT NULL DEFAULT '0' COMMENT '1单选题 2多选题 3判断 4填空 5简答题',
  `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` TEXT,
  `is_correct` SMALLINT NOT NULL DEFAULT '0' COMMENT '判断题该字段有效',
  `score` SMALLINT NOT NULL DEFAULT '0' COMMENT '问题分数',
  `sort` INT(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` SMALLINT NOT NULL DEFAULT '0' COMMENT '0不发布到试卷, 1发布到试卷',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-course` (`course_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `question_item`;
CREATE TABLE `question_item` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `question_id` INT(11) NOT NULL DEFAULT '0',
  `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` TEXT,
  `is_correct` SMALLINT NOT NULL DEFAULT '0' COMMENT '是否正确答案',
  `sort` INT(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-question` (`question_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/**考试*/
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `course_id` INT(11) NOT NULL DEFAULT '0',
  `title` VARCHAR(100) NOT NULL DEFAULT '',
  `total` INT(11) NOT NULL DEFAULT '0',
  `start_at` INT(11) NOT NULL DEFAULT '0',
  `end_at` INT(11) NOT NULL DEFAULT '0',
  `duration` INT(11) NOT NULL DEFAULT '0' COMMENT '持续时间,单位秒',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-course` (`course_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `exam_classes`;
CREATE TABLE `exam_classes` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `exam_id` INT(11) NOT NULL,
  `classes_id` INT(11) NOT NULL,
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_exam_classes` (`exam_id`,`classes_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `exam_question`;
CREATE TABLE `exam_question` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `exam_id` INT(11) NOT NULL DEFAULT '0',
  `src_id` INT(11) NOT NULL DEFAULT '0' COMMENT '来源id',
  `type` INT(11) NOT NULL DEFAULT '0' COMMENT '1单选题 2多选题 3判断 4填空 5简答题',
  `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` TEXT,
  `is_correct` SMALLINT NOT NULL DEFAULT '0' COMMENT '判断题该字段有效',
  `items` TEXT,
  `score` SMALLINT NOT NULL DEFAULT '0' COMMENT '问题分数',
  `sort` INT(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_by` INT(11) NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-exam` (`exam_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `exam_result`;
CREATE TABLE `exam_result` (
  `id` INT(11) NOT NULL  AUTO_INCREMENT COMMENT 'id',
  `uid` INT(11) NOT NULL  DEFAULT '0',
  `exam_id` INT(11) NOT NULL  DEFAULT '0',
  `content` TEXT,
  `score` INT(11) NOT NULL DEFAULT '0' COMMENT '得分',
  `start_at` INT(11) NOT NULL DEFAULT '0',
  `end_at` INT(11) NOT NULL DEFAULT '0',
  `status` SMALLINT NOT NULL DEFAULT '0' COMMENT '0未交卷 1交卷未打分 2交卷已打分',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `expired_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx-uid` (`uid`),
  INDEX `idx-exam` (`exam_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;