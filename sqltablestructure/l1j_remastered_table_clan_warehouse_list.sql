
-- --------------------------------------------------------

--
-- Table structure for table `clan_warehouse_list`
--

CREATE TABLE `clan_warehouse_list` (
  `id` int(10) NOT NULL,
  `clanid` int(11) DEFAULT 0,
  `list` varchar(200) DEFAULT '',
  `date` varchar(20) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `clan_warehouse_list`:
--
