ALTER TABLE `cars` ADD `motor` VARCHAR(16) NOT NULL DEFAULT '' AFTER `brand`;
ALTER TABLE `cars` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `motor`;


ALTER TABLE `car_services` ADD `warranty` VARCHAR(512) NOT NULL DEFAULT '' AFTER `comment`;
ALTER TABLE `car_services` ADD `exchange_rate` FLOAT NOT NULL AFTER `warranty`;


ALTER TABLE `car_service_items` ADD `usd_price` FLOAT NOT NULL DEFAULT '0' AFTER `comment`;


ALTER TABLE `items` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `name`;


ALTER TABLE `car_service_items` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `item_id`;