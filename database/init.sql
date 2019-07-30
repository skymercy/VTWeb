/** 用户表 */
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
  `classes_id` VARCHAR(50) NOT NULL COMMENT '所属班级',
  `updated_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
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
  PRIMARY KEY (`id`)
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
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;