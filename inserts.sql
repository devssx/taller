ALTER TABLE `cars` ADD `motor` VARCHAR(16) NOT NULL DEFAULT '' AFTER `brand`;
ALTER TABLE `cars` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `motor`;


ALTER TABLE `car_services` ADD `warranty` VARCHAR(512) NOT NULL DEFAULT '' AFTER `comment`;
ALTER TABLE `car_services` ADD `exchange_rate` FLOAT NOT NULL AFTER `warranty`;


ALTER TABLE `car_service_items` ADD `usd_price` FLOAT NOT NULL DEFAULT '0' AFTER `comment`;
ALTER TABLE `car_service_items` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `item_id`;

ALTER TABLE `items` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `name`;


-- phonenumber2
-- email
-- information
-- details
-- reminder
-- reminder_date
ALTER TABLE `clients` ADD `phonenumber2` VARCHAR(512) NOT NULL DEFAULT '' AFTER `phonenumber`;
ALTER TABLE `clients` ADD `email` VARCHAR(64) NOT NULL DEFAULT '' AFTER `phonenumber2`;
ALTER TABLE `clients` ADD `information` text COLLATE utf8_unicode_ci AFTER `reference`;
ALTER TABLE `clients` ADD `details` text COLLATE utf8_unicode_ci AFTER `information`;
ALTER TABLE `clients` ADD `reminder` text COLLATE utf8_unicode_ci AFTER `details`;
ALTER TABLE `clients` ADD `reminder_date` date DEFAULT NULL AFTER `reminder`;


CREATE TABLE `cleanings` (
  `id` int(10) UNSIGNED NOT NULL,
  `day` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `start` datetime DEFAULT NULL,
  `cleaning` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `breakfast_start` timestamp NULL DEFAULT NULL,
  `breakfast_end` timestamp NULL DEFAULT NULL,
  `lunch_start` timestamp NULL DEFAULT NULL,
  `lunch_end` timestamp NULL DEFAULT NULL,
  `done` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cleanings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cleanings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

-- tipo de servicio: A/C, Mecanico, Electrico
ALTER TABLE `sales` ADD `service_type` INT NOT NULL DEFAULT '0' AFTER `method`;
ALTER TABLE `sales` ADD `next_service` date DEFAULT NULL AFTER `last_service`;