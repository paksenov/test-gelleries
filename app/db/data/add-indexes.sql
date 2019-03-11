ALTER TABLE `test`.`gallery`
   ADD PRIMARY KEY (`id`);

ALTER TABLE `test`.`gallery`
   MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `test`.`image`
   ADD PRIMARY KEY (`id`),
   ADD KEY `gallery_id` (`gallery_id`);

ALTER TABLE `test`.`image`
   MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `test`.`image`
   ADD CONSTRAINT `image_fk_1` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;