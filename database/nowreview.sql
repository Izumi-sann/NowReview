-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 08, 2026 alle 22:49
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nowreview`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `credenziali`
--

CREATE TABLE `credenziali` (
  `id_credenziali` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `credenziali`
--

INSERT INTO `credenziali` (`id_credenziali`, `UID`, `email`, `password_hash`) VALUES
(3, 3, 'anna@mail.com', 'hash_003'),
(4, 4, 'marco@mail.com', 'hash_004');

-- --------------------------------------------------------

--
-- Struttura della tabella `domanda`
--

CREATE TABLE `domanda` (
  `id_interazione` int(11) NOT NULL,
  `titolo` varchar(150) NOT NULL,
  `testo` text NOT NULL,
  `id_prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `domanda`
--

INSERT INTO `domanda` (`id_interazione`, `titolo`, `testo`, `id_prodotto`) VALUES
(4, 'La Keychron K2 è buona per programmare?', 'Cerco una tastiera meccanica per coding.', 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `interazione`
--

CREATE TABLE `interazione` (
  `id_interazione` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `interazione`
--

INSERT INTO `interazione` (`id_interazione`, `UID`, `data`) VALUES
(3, 3, '2026-05-02'),
(4, 4, '2026-05-02'),
(7, 3, '2026-05-04');

-- --------------------------------------------------------

--
-- Struttura della tabella `like`
--

CREATE TABLE `like` (
  `id_interazione` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `like`
--

INSERT INTO `like` (`id_interazione`, `UID`, `data`) VALUES
(3, 4, '2026-05-03'),
(7, 3, '2026-05-04');

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotto`
--

CREATE TABLE `prodotto` (
  `id_prodotto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prodotto`
--

INSERT INTO `prodotto` (`id_prodotto`, `nome`, `descrizione`) VALUES
(1, 'Raspberry Pi 5', 'Single board computer'),
(2, 'Logitech G Pro X', 'Gaming headset'),
(3, 'Samsung SSD 990 Pro', 'NVMe SSD ad alte prestazioni'),
(4, 'Keychron K2', 'Tastiera meccanica wireless');

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `id_interazione` int(11) NOT NULL,
  `testo` text NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `link_prodotto` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `recensione`
--

INSERT INTO `recensione` (`id_interazione`, `testo`, `id_prodotto`, `link_prodotto`) VALUES
(3, 'SSD velocissimo e ottimo per gaming.', 3, 'https://amazon.it/samsung-990pro'),
(7, 'Ottima tastiera, switch molto piacevoli.', 4, 'https://keychron.com/k2');

-- --------------------------------------------------------

--
-- Struttura della tabella `risposta`
--

CREATE TABLE `risposta` (
  `id_interazione` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `testo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `UID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`UID`, `username`, `nome`, `cognome`) VALUES
(3, 'anna99', 'Anna', 'Bianchi'),
(4, 'techguy', 'Marco', 'Verdi');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `credenziali`
--
ALTER TABLE `credenziali`
  ADD PRIMARY KEY (`id_credenziali`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `UID` (`UID`);

--
-- Indici per le tabelle `domanda`
--
ALTER TABLE `domanda`
  ADD PRIMARY KEY (`id_interazione`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `interazione`
--
ALTER TABLE `interazione`
  ADD PRIMARY KEY (`id_interazione`),
  ADD KEY `interazione_ibfk_1` (`UID`);

--
-- Indici per le tabelle `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id_interazione`,`UID`),
  ADD KEY `like_ibfk_2` (`UID`);

--
-- Indici per le tabelle `prodotto`
--
ALTER TABLE `prodotto`
  ADD PRIMARY KEY (`id_prodotto`);

--
-- Indici per le tabelle `recensione`
--
ALTER TABLE `recensione`
  ADD PRIMARY KEY (`id_interazione`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `risposta`
--
ALTER TABLE `risposta`
  ADD PRIMARY KEY (`id_interazione`),
  ADD KEY `id_domanda` (`id_domanda`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `credenziali`
--
ALTER TABLE `credenziali`
  MODIFY `id_credenziali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `interazione`
--
ALTER TABLE `interazione`
  MODIFY `id_interazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  MODIFY `id_prodotto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `credenziali`
--
ALTER TABLE `credenziali`
  ADD CONSTRAINT `credenziali_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `utente` (`UID`) ON DELETE CASCADE;

--
-- Limiti per la tabella `domanda`
--
ALTER TABLE `domanda`
  ADD CONSTRAINT `domanda_ibfk_1` FOREIGN KEY (`id_interazione`) REFERENCES `interazione` (`id_interazione`) ON DELETE CASCADE,
  ADD CONSTRAINT `domanda_ibfk_2` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id_prodotto`) ON DELETE CASCADE;

--
-- Limiti per la tabella `interazione`
--
ALTER TABLE `interazione`
  ADD CONSTRAINT `interazione_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `utente` (`UID`) ON DELETE NO ACTION;

--
-- Limiti per la tabella `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`id_interazione`) REFERENCES `interazione` (`id_interazione`) ON DELETE CASCADE,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `utente` (`UID`) ON DELETE NO ACTION;

--
-- Limiti per la tabella `recensione`
--
ALTER TABLE `recensione`
  ADD CONSTRAINT `recensione_ibfk_1` FOREIGN KEY (`id_interazione`) REFERENCES `interazione` (`id_interazione`) ON DELETE CASCADE,
  ADD CONSTRAINT `recensione_ibfk_2` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id_prodotto`) ON DELETE CASCADE;

--
-- Limiti per la tabella `risposta`
--
ALTER TABLE `risposta`
  ADD CONSTRAINT `risposta_ibfk_1` FOREIGN KEY (`id_interazione`) REFERENCES `interazione` (`id_interazione`) ON DELETE CASCADE,
  ADD CONSTRAINT `risposta_ibfk_2` FOREIGN KEY (`id_domanda`) REFERENCES `domanda` (`id_interazione`) ON DELETE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `fk1_credenziali` FOREIGN KEY (`UID`) REFERENCES `credenziali` (`UID`) ON UPDATE CASCADE;
COMMIT;

--
-- Creazione utente webapp_user
--
CREATE USER 'webapp_user'@'%'
IDENTIFIED BY 'password';

GRANT SELECT, INSERT, UPDATE, DELETE
ON nowreview.*
TO 'webapp_user'@'%';

FLUSH PRIVILEGES;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
