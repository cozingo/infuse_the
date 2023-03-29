-- laravel.visits definition

CREATE TABLE `visits` (
                          `ip_address` int unsigned NOT NULL,
                          `user_agent` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                          `view_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `page_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
                          `views_count` int unsigned NOT NULL DEFAULT '1',
                          PRIMARY KEY (`ip_address`,`user_agent`,`page_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;