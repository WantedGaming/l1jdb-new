
-- --------------------------------------------------------

--
-- Table structure for table `dogfight_tickets`
--

CREATE TABLE `dogfight_tickets` (
  `item_id` int(10) NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `price` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- RELATIONSHIPS FOR TABLE `dogfight_tickets`:
--
