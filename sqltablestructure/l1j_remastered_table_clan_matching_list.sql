
-- --------------------------------------------------------

--
-- Table structure for table `clan_matching_list`
--

CREATE TABLE `clan_matching_list` (
  `clanname` varchar(45) NOT NULL DEFAULT '',
  `text` varchar(500) DEFAULT NULL,
  `type` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `clan_matching_list`:
--
