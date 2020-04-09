ALTER TABLE `cars` ADD `motor` VARCHAR(5) NOT NULL DEFAULT '1.8' AFTER `brand`;
ALTER TABLE `car_services` ADD `warranty` VARCHAR(512) NOT NULL DEFAULT '' AFTER `comment`;