ALTER TABLE `cars` ADD `motor` VARCHAR(16) NOT NULL DEFAULT '' AFTER `brand`;
ALTER TABLE `cars` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `motor`;


ALTER TABLE `car_services` ADD `warranty` VARCHAR(512) NOT NULL DEFAULT '' AFTER `comment`;
ALTER TABLE `car_services` ADD `exchange_rate` FLOAT NOT NULL AFTER `warranty`;


ALTER TABLE `car_service_items` ADD `usd_price` FLOAT NOT NULL DEFAULT '0' AFTER `comment`;


ALTER TABLE `items` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `name`;


ALTER TABLE `car_service_items` ADD `image` VARCHAR(512) NOT NULL DEFAULT '' AFTER `item_id`;

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
ALTER TABLE `clients` ADD `reminder_date` timestamp NOT NULL DEFAULT '2020-01-01 00:00:00' AFTER `reminder`;


CREATE TABLE `cleanings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `start` timestamp NOT NULL DEFAULT '2020-01-01 00:00:00',
  `cleaning` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `breakfast_start` timestamp NOT NULL DEFAULT '2020-01-01 00:00:00',
  `breakfast_end` timestamp NOT NULL DEFAULT '2020-01-01 00:00:00',
  `lunch_start` timestamp NOT NULL DEFAULT '2020-01-01 00:00:00',
  `lunch_end` timestamp NOT NULL DEFAULT '2020-01-01 00:00:00',
  `done` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `cleanings`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `cleanings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

-- tipo de servicio: A/C, Mecanico, Electrico
ALTER TABLE `sales` ADD `service_type` INT NOT NULL DEFAULT '0' AFTER `method`;
ALTER TABLE `sales` ADD `next_service` TIMESTAMP AFTER `last_service`;