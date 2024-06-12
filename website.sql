-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 12, 2024 lúc 09:33 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `id` int(10) NOT NULL,
  `idPost` int(10) NOT NULL,
  `idUser` int(10) NOT NULL,
  `creationDate` timestamp NULL DEFAULT NULL,
  `content` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `friendship`
--

CREATE TABLE `friendship` (
  `id` int(10) NOT NULL,
  `userID1` int(10) NOT NULL,
  `userID2` int(10) NOT NULL,
  `requestDate` timestamp NULL DEFAULT NULL,
  `acceptDate` timestamp NULL DEFAULT NULL,
  `terminateDate` datetime NOT NULL,
  `state` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `like`
--

CREATE TABLE `like` (
  `id` int(10) NOT NULL,
  `idPost` int(10) NOT NULL,
  `idUser` int(10) NOT NULL,
  `creationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likestory`
--

CREATE TABLE `likestory` (
  `id` int(10) NOT NULL,
  `idStory` int(10) NOT NULL,
  `idUser` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification`
--

CREATE TABLE `notification` (
  `id` int(10) NOT NULL,
  `content` varchar(100) NOT NULL,
  `idUser` int(10) NOT NULL,
  `idPost` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', 'f1bbc231486f840368ccc1c6ef80ec3784003290dd6f2426fceba119d0080c57', '[\"*\"]', NULL, NULL, '2024-06-11 12:27:19', '2024-06-11 12:27:19'),
(2, 'App\\Models\\User', 2, 'auth_token', 'eb4b4f9700f9cd43fc1bac945733b9484fc9f3340f52277aba1a07900ae6ed62', '[\"*\"]', NULL, NULL, '2024-06-11 12:28:41', '2024-06-11 12:28:41'),
(3, 'App\\Models\\User', 1111111111, 'auth_token', '657b8e47389dd368441f1b7ae0c4c6afa696be9b66029e89313aeaaee80a8647', '[\"*\"]', NULL, NULL, '2024-06-11 12:30:18', '2024-06-11 12:30:18'),
(4, 'App\\Models\\User', 1111111111, 'auth_token', '947c9b7f2ec18ce34dbd73cdd04513b4796eba36af6ae3000e1a2a8a9c7dfef2', '[\"*\"]', NULL, NULL, '2024-06-11 12:41:09', '2024-06-11 12:41:09'),
(5, 'App\\Models\\User', 1111111111, 'auth_token', '348b9a23629237b4cc6f5fef8e02b70a54a9e4f21f415ec0b9e01a2aa03c26dc', '[\"*\"]', NULL, NULL, '2024-06-11 13:06:36', '2024-06-11 13:06:36'),
(6, 'App\\Models\\User', 1111111111, 'auth_token', '080b4e458747d732536e3657cd182cc87381b437315026b39d2eb63959c5a7c9', '[\"*\"]', NULL, NULL, '2024-06-11 13:09:41', '2024-06-11 13:09:41'),
(7, 'App\\Models\\User', 1111111111, 'auth_token', '1716dcdfabe59769c10f75d0088b79407a02584bafa378d2ef1041910c9810e1', '[\"*\"]', NULL, NULL, '2024-06-11 13:55:26', '2024-06-11 13:55:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `photo`
--

CREATE TABLE `photo` (
  `id` int(10) NOT NULL,
  `idPost` int(10) DEFAULT NULL,
  `idStory` int(10) DEFAULT NULL,
  `photoUrl` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post`
--

CREATE TABLE `post` (
  `id` int(10) NOT NULL,
  `content` varchar(100) NOT NULL,
  `privacy` tinyint(1) NOT NULL,
  `idUser` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `story`
--

CREATE TABLE `story` (
  `id` int(10) NOT NULL,
  `privacy` int(1) NOT NULL,
  `idUser` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phoneNumber` int(10) NOT NULL,
  `birth` date NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `coverimage` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phoneNumber`, `birth`, `gender`, `avatar`, `coverimage`, `created_at`, `updated_at`) VALUES
(1, 'AAACC', 'A79@gmail.com', '$2y$12$QC0D4QPqG78HgqvqtfQ8a.rCUJuXbPrKQT6zy2kBQQkjLUfRR4Dee', 979877988, '2024-06-12', 1, 'fqfq21', 'rqr123', '2024-06-05 18:38:12', '2024-06-13 18:38:16'),
(2, 'AAACC', 'A779@gmail.com', '$2y$12$PBfzCKrvWCKC4mDm2NQ/m.14gqbuGNrkVY/LqaizyhkN3lhQD0ram', 979877988, '2024-06-12', 1, 'fqfq21', 'rqr123', '2024-06-05 18:38:12', '2024-06-13 18:38:16'),
(1111111111, 'AAACC', 'AAC779@gmail.com', '$2y$12$yE8GleNqmv5eKbhX.Id5IOtH2Ps96o/HlTVt4uBNS5vla2NVXPBPG', 979877988, '2024-06-12', 1, 'fqfq21', 'rqr123', '2024-06-05 18:38:12', '2024-06-13 18:38:16'),
(1111111112, 'ABC', 'AAC7779@gmail.com', '$2y$12$.3Fchx/RGGYsDbumAfmFf.bWhhOiB4WY6Lgi0PHa68e3nTF2iDRZy', 979779779, '2023-12-31', 0, NULL, NULL, '2024-06-11 14:11:40', '2024-06-11 14:11:40'),
(1111111113, 'ABC1', 'ABC7779@gmail.com', '$2y$12$3APpeQONNU9KHgpekdwRz.tp9NrSex0xVJOCp3CWymtevVaB653kG', 979779779, '2023-12-31', 0, NULL, NULL, '2024-06-11 14:15:19', '2024-06-11 14:15:19'),
(1111111114, 'ABC2', 'ABC7799@gmail.com', '$2y$12$0Bu7ecWh5e.sHLj80ufyEuM7SA.JTIFaB7h3K/lsRpXTEYW6AlvFS', 979779779, '2023-12-31', 0, NULL, NULL, '2024-06-11 14:24:56', '2024-06-11 14:24:56'),
(1111111115, 'AB2', 'AB7799@gmail.com', '$2y$12$AY17db6PjBYk8Tp/MJnw6OOFtYHHwnihJSmU/qDoUom1UMyxm3PVG', 979779779, '2023-12-31', 1, NULL, NULL, '2024-06-11 14:38:56', '2024-06-11 14:38:56'),
(1111111116, 'AB2', 'AB77799@gmail.com', '$2y$12$C10LtZITbznDo2xcojR9HeqPDK0.T/xC8RRTFy9cLG54QUYv91OKK', 979779779, '2023-12-31', 1, NULL, NULL, '2024-06-11 14:40:55', '2024-06-11 14:40:55'),
(1111111117, 'AB2', 'AB777799@gmail.com', '$2y$12$ZUN5tm8yFKuVHG65jISwCOPTOWI7khTf5OPrszuVldj6K0JpzP4xe', 979779779, '2023-12-31', 1, NULL, NULL, '2024-06-12 00:16:41', '2024-06-12 00:16:41'),
(1111111118, 'AB2', 'AB7777999@gmail.com', '$2y$12$y1dishd.C6gGFz2CSg6lJOJrfH0.MbWuCE73Md7wOrj9Et5nKO5gq', 979779779, '2023-12-31', 1, NULL, NULL, '2024-06-12 00:16:56', '2024-06-12 00:16:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `video`
--

CREATE TABLE `video` (
  `id` int(10) NOT NULL,
  `idPost` int(10) DEFAULT NULL,
  `idStory` int(10) DEFAULT NULL,
  `videoUrl` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser_comment` (`idUser`),
  ADD KEY `idpost_comment` (`idPost`);

--
-- Chỉ mục cho bảng `friendship`
--
ALTER TABLE `friendship`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduserd1` (`userID1`),
  ADD KEY `iduserd2` (`userID2`);

--
-- Chỉ mục cho bảng `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduserLike` (`idUser`),
  ADD KEY `idPostLike` (`idPost`);

--
-- Chỉ mục cho bảng `likestory`
--
ALTER TABLE `likestory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser_Like_story` (`idUser`),
  ADD KEY `idStory_Like_story` (`idStory`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUser1` (`idUser`),
  ADD KEY `idPost` (`idPost`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idphoto_Post` (`idPost`),
  ADD KEY `idphoto_Story` (`idStory`);

--
-- Chỉ mục cho bảng `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser` (`idUser`);

--
-- Chỉ mục cho bảng `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`id`),
  ADD KEY `iduser_story` (`idUser`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idVideo_Post` (`idPost`),
  ADD KEY `idVideo_Story` (`idStory`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `friendship`
--
ALTER TABLE `friendship`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `like`
--
ALTER TABLE `like`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `likestory`
--
ALTER TABLE `likestory`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `post`
--
ALTER TABLE `post`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `story`
--
ALTER TABLE `story`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1111111119;

--
-- AUTO_INCREMENT cho bảng `video`
--
ALTER TABLE `video`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `idpost_comment` FOREIGN KEY (`idPost`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `iduser_comment` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `iduserd1` FOREIGN KEY (`userID1`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `iduserd2` FOREIGN KEY (`userID2`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `idPostLike` FOREIGN KEY (`idPost`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `iduserLike` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `likestory`
--
ALTER TABLE `likestory`
  ADD CONSTRAINT `idStory_Like_story` FOREIGN KEY (`idStory`) REFERENCES `story` (`id`),
  ADD CONSTRAINT `iduser_Like_story` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `idUser1` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `idphoto_Post` FOREIGN KEY (`idPost`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `idphoto_Story` FOREIGN KEY (`idStory`) REFERENCES `story` (`id`);

--
-- Các ràng buộc cho bảng `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `iduser` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `story`
--
ALTER TABLE `story`
  ADD CONSTRAINT `iduser_story` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `idVideo_Post` FOREIGN KEY (`idPost`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `idVideo_Story` FOREIGN KEY (`idStory`) REFERENCES `story` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
