
-- --------------------------------------------------------

--
-- Table structure for table `dungeon_timer_account`
--

CREATE TABLE `dungeon_timer_account` (
  `account` varchar(50) NOT NULL,
  `timerId` int(10) NOT NULL DEFAULT 0,
  `remainSecond` int(10) NOT NULL DEFAULT 0,
  `chargeCount` int(2) NOT NULL DEFAULT 0,
  `resetTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `dungeon_timer_account`:
--
