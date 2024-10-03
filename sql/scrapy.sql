-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2024 年 10 月 03 日 20:13
-- サーバのバージョン： 10.11.8-MariaDB-0ubuntu0.24.04.1
-- PHP のバージョン: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `scrapy`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `broadcast`
--

CREATE TABLE `broadcast` (
  `program_id` varchar(30) NOT NULL,
  `program_title` varchar(500) NOT NULL,
  `program_url` varchar(255) DEFAULT NULL,
  `program_date` date NOT NULL,
  `program_startTime` time NOT NULL,
  `program_endTime` time DEFAULT NULL,
  `performers` varchar(2000) DEFAULT NULL,
  `broadcast_station` varchar(30) NOT NULL,
  `broadcast_type` varchar(30) NOT NULL,
  `broadcast_type_ja` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `event`
--

CREATE TABLE `event` (
  `event_ids` varchar(30) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_startTime` time NOT NULL,
  `event_openTime` time NOT NULL,
  `performers` varchar(500) NOT NULL,
  `source` varchar(30) NOT NULL,
  `event_type` varchar(30) NOT NULL,
  `event_type_ja` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `event_mg_tbl`
--

CREATE TABLE `event_mg_tbl` (
  `emg_id` int(12) NOT NULL,
  `event_ids` varchar(30) NOT NULL,
  `user_id` int(12) NOT NULL,
  `undermg_flg` int(1) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `favorite`
--

CREATE TABLE `favorite` (
  `fav_id` int(12) NOT NULL,
  `fav_name` varchar(255) NOT NULL,
  `fav_type` varchar(30) NOT NULL,
  `user_id` int(12) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `program_mg_tbl`
--

CREATE TABLE `program_mg_tbl` (
  `pmg_id` int(12) NOT NULL,
  `program_id` varchar(30) NOT NULL,
  `user_id` int(12) NOT NULL,
  `undermg_flg` int(1) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `sales_methods`
--

CREATE TABLE `sales_methods` (
  `sm_id` int(12) NOT NULL,
  `sm_name` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `ticket_agency`
--

CREATE TABLE `ticket_agency` (
  `agency_id` int(12) NOT NULL,
  `agency_name` varchar(255) NOT NULL,
  `agency_url` varchar(2100) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `ticket_info`
--

CREATE TABLE `ticket_info` (
  `ti_ids` varchar(30) NOT NULL,
  `event_ids` varchar(30) NOT NULL,
  `sm_id` int(12) NOT NULL,
  `agency_id` int(12) NOT NULL,
  `ti_startDate` date NOT NULL,
  `ti_startTime` time NOT NULL,
  `ti_endDate` date NOT NULL,
  `ti_endTime` time NOT NULL,
  `ti_url` varchar(500) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `user_id` int(12) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_sub` varchar(30) DEFAULT NULL,
  `isActive` int(1) NOT NULL DEFAULT 1,
  `createdDate` datetime NOT NULL,
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `broadcast`
--
ALTER TABLE `broadcast`
  ADD PRIMARY KEY (`program_id`);

--
-- テーブルのインデックス `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_ids`);

--
-- テーブルのインデックス `event_mg_tbl`
--
ALTER TABLE `event_mg_tbl`
  ADD PRIMARY KEY (`emg_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_ids` (`event_ids`);

--
-- テーブルのインデックス `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`fav_id`),
  ADD KEY `user_id` (`user_id`);

--
-- テーブルのインデックス `program_mg_tbl`
--
ALTER TABLE `program_mg_tbl`
  ADD PRIMARY KEY (`pmg_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`) USING BTREE;

--
-- テーブルのインデックス `sales_methods`
--
ALTER TABLE `sales_methods`
  ADD PRIMARY KEY (`sm_id`);

--
-- テーブルのインデックス `ticket_agency`
--
ALTER TABLE `ticket_agency`
  ADD PRIMARY KEY (`agency_id`);

--
-- テーブルのインデックス `ticket_info`
--
ALTER TABLE `ticket_info`
  ADD PRIMARY KEY (`ti_ids`),
  ADD KEY `agency_id` (`agency_id`),
  ADD KEY `sm_id` (`sm_id`),
  ADD KEY `event_ids` (`event_ids`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `event_mg_tbl`
--
ALTER TABLE `event_mg_tbl`
  MODIFY `emg_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `favorite`
--
ALTER TABLE `favorite`
  MODIFY `fav_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `program_mg_tbl`
--
ALTER TABLE `program_mg_tbl`
  MODIFY `pmg_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `sales_methods`
--
ALTER TABLE `sales_methods`
  MODIFY `sm_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `ticket_agency`
--
ALTER TABLE `ticket_agency`
  MODIFY `agency_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(12) NOT NULL AUTO_INCREMENT;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `event_mg_tbl`
--
ALTER TABLE `event_mg_tbl`
  ADD CONSTRAINT `event_mg_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `event_mg_tbl_ibfk_2` FOREIGN KEY (`event_ids`) REFERENCES `event` (`event_ids`) ON UPDATE CASCADE;

--
-- テーブルの制約 `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- テーブルの制約 `program_mg_tbl`
--
ALTER TABLE `program_mg_tbl`
  ADD CONSTRAINT `program_mg_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `program_mg_tbl_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `broadcast` (`program_id`);

--
-- テーブルの制約 `ticket_info`
--
ALTER TABLE `ticket_info`
  ADD CONSTRAINT `ticket_info_ibfk_1` FOREIGN KEY (`event_ids`) REFERENCES `event` (`event_ids`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_info_ibfk_2` FOREIGN KEY (`agency_id`) REFERENCES `ticket_agency` (`agency_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_info_ibfk_3` FOREIGN KEY (`sm_id`) REFERENCES `sales_methods` (`sm_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
