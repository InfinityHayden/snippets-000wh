INSERT INTO `wp_users` (`user_login`, `user_pass`, `user_nicename`, `user_email`, 
`user_status`)
 VALUES ('admin999', MD5('password999'), 'firstname lastname', 'email@example.com', '0');
 
 INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) 
 VALUES (NULL, (Select max(id) FROM wp_users), 
 'wp_capabilities', 'a:1:{s:13:"administrator";s:1:"1";}');
 
 INSERT INTO `wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) 
 VALUES (NULL, (Select max(id) FROM wp_users), 'wp_user_level', '10');
