
-- --------------------------------------------------------

--
-- Table structure for table `board_user`
--

CREATE TABLE `board_user` (
  `id` int(10) NOT NULL DEFAULT 0,
  `name` varchar(16) DEFAULT NULL,
  `date` varchar(16) DEFAULT NULL,
  `title` varchar(16) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `board_user`:
--
