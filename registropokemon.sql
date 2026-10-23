-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2026 at 12:07 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desarrolloweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `registropokemon`
--

CREATE TABLE `registropokemon` (
  `id_registro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `numero_pokemon` int(11) NOT NULL,
  `nombre_pokemon` varchar(100) NOT NULL,
  `id_region` int(11) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registropokemon`
--

INSERT INTO `registropokemon` (`id_registro`, `id_usuario`, `numero_pokemon`, `nombre_pokemon`, `id_region`, `fecha_registro`) VALUES
(1, 3, 25, 'pikachu', 1, '2026-10-23 15:41:38'),
(2, 1, 146, 'moltres', 1, '2026-10-23 15:45:12'),
(3, 1, 133, 'eevee', 1, '2026-10-23 15:45:22'),
(4, 1, 120, 'staryu', 1, '2026-10-23 15:47:31'),
(5, 1, 54, 'psyduck', 1, '2026-10-23 15:47:41'),
(6, 3, 152, 'chikorita', 2, '2026-10-23 15:48:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registropokemon`
--
ALTER TABLE `registropokemon`
  ADD PRIMARY KEY (`id_registro`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`numero_pokemon`),
  ADD KEY `id_region` (`id_region`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registropokemon`
--
ALTER TABLE `registropokemon`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registropokemon`
--
ALTER TABLE `registropokemon`
  ADD CONSTRAINT `registropokemon_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `registropokemon_ibfk_2` FOREIGN KEY (`id_region`) REFERENCES `regiones` (`id_region`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
