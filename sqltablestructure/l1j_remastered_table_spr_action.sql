
-- --------------------------------------------------------

--
-- Table structure for table `spr_action`
--

CREATE TABLE `spr_action` (
  `spr_id` int(10) NOT NULL,
  `act_id` int(10) NOT NULL,
  `act_name` varchar(128) DEFAULT NULL,
  `framecount` int(10) DEFAULT NULL,
  `framerate` int(10) DEFAULT NULL,
  `numOfFrame` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `spr_action`:
--
