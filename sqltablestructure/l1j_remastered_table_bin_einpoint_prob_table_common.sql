
-- --------------------------------------------------------

--
-- Table structure for table `bin_einpoint_prob_table_common`
--

CREATE TABLE `bin_einpoint_prob_table_common` (
  `isLastChance` enum('true','false') NOT NULL DEFAULT 'false',
  `bonusPoint` int(3) NOT NULL DEFAULT 0,
  `prob` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=euckr COLLATE=euckr_korean_ci;

--
-- RELATIONSHIPS FOR TABLE `bin_einpoint_prob_table_common`:
--
