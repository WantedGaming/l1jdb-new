
-- --------------------------------------------------------

--
-- Table structure for table `character_monsterbooklist`
--

CREATE TABLE `character_monsterbooklist` (
  `id` int(10) UNSIGNED NOT NULL,
  `monsterlist` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `monquest` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `character_monsterbooklist`:
--
