ALTER TABLE `exam_result` ADD COLUMN `error_questions` VARCHAR(255) NOT NULL DEFAULT '' AFTER `content`;
ALTER TABLE `exam_result` ADD COLUMN `correct_questions` VARCHAR(255)  NOT NULL DEFAULT '' AFTER `error_questions`;