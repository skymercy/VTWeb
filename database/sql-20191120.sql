ALTER TABLE `exam_result` ADD COLUMN `auto_score` INT(11) NOT NULL DEFAULT 0 COMMENT '自动计算的分数:选择题、判断题等' AFTER `score`;
ALTER TABLE `exam_result` ADD COLUMN `manual_score` INT(11) NOT NULL DEFAULT 0 COMMENT '手动计算的分数:简答、填空等' AFTER `auto_score`;
ALTER TABLE `exam_result` ADD COLUMN `lab_score` INT(11) NOT NULL DEFAULT 0 COMMENT '实验室分数' AFTER `manual_score`;