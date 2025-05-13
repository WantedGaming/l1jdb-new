
-- --------------------------------------------------------

--
-- Table structure for table `app_board_free`
--

CREATE TABLE `app_board_free` (
  `id` int(10) NOT NULL,
  `name` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'NULL',
  `date` datetime DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `readcount` int(10) DEFAULT 0,
  `chatype` int(2) DEFAULT 0,
  `chasex` int(1) DEFAULT 0,
  `likenames` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `mainImg` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `app_board_free`:
--
