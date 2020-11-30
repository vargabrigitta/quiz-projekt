CREATE TABLE `eredmenyek` (
  `id` int(11) NOT NULL,
  `nev` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `pontszam` int(11) NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `eredmenyek`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `eredmenyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;
