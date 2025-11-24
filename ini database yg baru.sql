

CREATE TABLE `artisans` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `region_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `lokasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artisans`
--

INSERT INTO `artisans` (`id`, `name`, `region_id`, `description`, `lokasi`, `photo_path`, `created_at`) VALUES
(1, 'Putu Dandy', 3, 'Pengrajin ukiran kayu tradisional dari Denpasar', 'jl raya ubung', 'public/uploads/artisans/1763814877-8851.png', '2025-11-22 12:32:46'),
(2, 'dania', 4, 'yahahahha', 'JL by pass', 'public/uploads/artisans/1763817196-9458.png', '2025-11-22 13:13:16');

-- --------------------------------------------------------

--
-- Table structure for table `crafts`
--

CREATE TABLE `crafts` (
  `id` int NOT NULL,
  `artisan_id` int DEFAULT NULL,
  `region_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `location_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crafts`
--

INSERT INTO `crafts` (`id`, `artisan_id`, `region_id`, `category_id`, `title`, `description`, `location_address`, `address`, `latitude`, `longitude`, `price`, `image_path`, `created_at`) VALUES
(1, 1, 1, 3, 'Patung Barong Kecil', 'Patung barong ukuran kecil, ukiran tangan', 'dimana aja boleh', NULL, NULL, NULL, '25000.00', 'public/uploads/crafts/1763814954-7773.png', '2025-11-22 12:32:46'),
(2, 2, 2, 1, '1', '', 'diamana aja ', NULL, NULL, NULL, '200000.00', 'public/uploads/crafts/1763817447-7983.png', '2025-11-22 13:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `craft_categories`
--

CREATE TABLE `craft_categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `craft_categories`
--

INSERT INTO `craft_categories` (`id`, `name`) VALUES
(1, 'Ukiran Kayu'),
(2, 'Tenun'),
(3, 'Perak & Emas'),
(4, 'Seni Patung'),
(5, 'Aksesori'),
(6, 'Keramik');

-- --------------------------------------------------------

--
-- Table structure for table `craft_reviews`
--

CREATE TABLE `craft_reviews` (
  `id` int NOT NULL,
  `craft_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `craft_reviews`
--

INSERT INTO `craft_reviews` (`id`, `craft_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 8, 5, 'keren banget jir', '2025-11-24 02:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `deletes_log`
--

CREATE TABLE `deletes_log` (
  `id` int NOT NULL,
  `table_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `deleted_by_name` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Denpasar'),
(2, 'Badung'),
(3, 'Gianyar'),
(4, 'Tabanan'),
(5, 'Bangli'),
(6, 'Klungkung'),
(7, 'Jembrana'),
(8, 'Buleleng'),
(9, 'Karangasem');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `setting_key` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_name', 'Kriya Bali'),
(2, 'site_description', 'Platform katalog dan penjualan kerajinan Bali'),
(3, 'items_per_page', '25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user','superadmin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `verification_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `verification_code`, `is_verified`, `verified_at`, `created_at`) VALUES
(1, 'Admin', 'admin@kriya.local', '$2y$10$EskE7qZ3qmPPV/ZqQuuQ1e4AaY6Tlo5lOfpCYGsbYV2z999WCDZCC', 'admin', NULL, 1, '2025-11-22 12:32:46', '2025-11-22 12:32:46'),
(5, 'dania', 'dania@gmail.com', '$2y$10$u/PZNuXL8oFndeHkADEbU.fvTNy1b.DLlK4zZtP68qv1XVN278Wiq', 'admin', NULL, 1, '2025-11-22 13:48:54', '2025-11-22 13:48:54'),
(6, 'dian', 'dian@gmail.com', '$2y$10$3hp3lPpgm6AL71rDpx/rLOPM0U/gWA0S51UbxgQ6N/O2VLzX9v8cy', 'user', NULL, 1, '2025-11-22 16:00:18', '2025-11-22 16:00:18'),
(7, '123', '123@gmail.com', '$2y$10$/h100VdfyeeOKjzYeHkPp.Oocvic/Y02rb3ND/8TmRsb4ccrjN1ZW', 'user', NULL, 1, '2025-11-22 16:04:49', '2025-11-22 16:04:49'),
(8, 'tino', 'tino@gmail.com', '$2y$10$/eYrK2ovwxa4tttl2eCwbO07u2fYOlfTcviXdSLGcLKG3Mfx3M7UW', 'user', NULL, 1, '2025-11-24 02:16:01', '2025-11-24 02:16:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artisans`
--
ALTER TABLE `artisans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_artisans_region` (`region_id`);

--
-- Indexes for table `crafts`
--
ALTER TABLE `crafts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_crafts_artisan` (`artisan_id`),
  ADD KEY `idx_crafts_region` (`region_id`),
  ADD KEY `idx_crafts_category` (`category_id`);

--
-- Indexes for table `craft_categories`
--
ALTER TABLE `craft_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `craft_reviews`
--
ALTER TABLE `craft_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `craft_id` (`craft_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `deletes_log`
--
ALTER TABLE `deletes_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artisans`
--
ALTER TABLE `artisans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `crafts`
--
ALTER TABLE `crafts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `craft_categories`
--
ALTER TABLE `craft_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `craft_reviews`
--
ALTER TABLE `craft_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deletes_log`
--
ALTER TABLE `deletes_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artisans`
--
ALTER TABLE `artisans`
  ADD CONSTRAINT `fk_artisans_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `crafts`
--
ALTER TABLE `crafts`
  ADD CONSTRAINT `fk_crafts_artisan` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_crafts_category` FOREIGN KEY (`category_id`) REFERENCES `craft_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_crafts_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `craft_reviews`
--
ALTER TABLE `craft_reviews`
  ADD CONSTRAINT `fk_reviews_craft` FOREIGN KEY (`craft_id`) REFERENCES `crafts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
