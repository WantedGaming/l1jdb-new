
-- --------------------------------------------------------

--
-- Table structure for table `race_record`
--

CREATE TABLE `race_record` (
  `number` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `win` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lose` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `race_record`:
--
