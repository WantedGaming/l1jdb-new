
-- --------------------------------------------------------

--
-- Table structure for table `log_adena_monster`
--

CREATE TABLE `log_adena_monster` (
  `startTime` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `accounts` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `count` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `log_adena_monster`:
--
