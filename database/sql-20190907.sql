ALTER TABLE `course` ADD COLUMN `unique_no` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '课程的唯一编号' AFTER `id`;
UPDATE `course` set `unique_no` = concat('TMP_NO_', `id`);
ALTER TABLE `course` ADD UNIQUE INDEX `idx_no` (`unique_no`);


/**直接访问考试页面的秘钥*/
DROP TABLE IF EXISTS `exam_access`;
CREATE TABLE `exam_access` (
  `token` VARCHAR(32) NOT NULL COMMENT 'id',
  `course_id` INT(11) NOT NULL DEFAULT '0',
  `exam_id` INT(11) NOT NULL DEFAULT '0',
  `uid` INT(11) NOT NULL DEFAULT '0',
  `status` SMALLINT NOT NULL DEFAULT '0',
  `created_at` INT(11) NOT NULL DEFAULT '0',
  `expired_at` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`),
  INDEX `idx-course` (`course_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;