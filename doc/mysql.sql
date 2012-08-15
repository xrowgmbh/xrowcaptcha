CREATE TABLE xrowcaptcha_result (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `hash` VARCHAR(255) NOT NULL ,
  `result` INT(11) NOT NULL ,
  `createtime` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) );