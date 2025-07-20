-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 05:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bootcamp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL,
  `dia` int(11) NOT NULL DEFAULT 1,
  `youtube_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `titulo`, `descripcion`, `archivo`, `orden`, `dia`, `youtube_id`) VALUES
(1, 'HTML: El Esqueleto de la Web', 'Aprende la estructura fundamental de la web con HTML.', 'lessons/leccion1.php', 1, 1, '21h244rMt5w'),
(2, 'CSS: Dando Estilo a tu Web', 'Aplica estilos y haz que tu página se vea increíble.', 'lessons/leccion2.php', 2, 1, '3yM5u2w_a2E'),
(3, 'Flexbox: Diseño Flexible', 'Organiza tu layout como un profesional con Flexbox.', 'lessons/leccion3.php', 3, 1, 't2sE_i4a_yQ'),
(4, 'CSS Grid: Maquetación Avanzada', 'Domina la maquetación web moderna con CSS Grid Layout.', 'lessons/leccion4.php', 4, 1, 'j-6_n4AWg04'),
(5, 'JavaScript: El Cerebro de la Web', 'Introduce la interactividad en tus páginas con JS.', 'lessons/leccion5.php', 5, 2, 'z95mZVUbsXg'),
(6, 'El DOM: Manipulando tu Página', 'Aprende a modificar el contenido y los estilos de tu web con JS.', 'lessons/leccion6.php', 6, 2, 'Q32_aVE0Mfs'),
(7, 'Eventos y Diseño Adaptable', 'Haz que tu página reaccione a las acciones del usuario y se vea bien en móviles.', 'lessons/leccion7.php', 7, 2, '3aA0aVE0Mfs'),
(8, 'Publicación y Próximos Pasos', 'Sube tu página a internet y descubre cómo seguir aprendiendo.', 'lessons/leccion8.php', 8, 2, '7iT3gK_2_mE');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
(2, 'franciscotapia1020@outlook.com', '412b585af0f47169f6f4923f8f3c6d690c72101e1421dcc73cc0724b8922cedb', '2025-07-20 05:25:26'),
(3, 'franciscotapia1020@outlook.com', '2e60ad83384ffde56ce03089a52fd8f86734eb2233e7ba58dc65b3f6ed9e2386', '2025-07-20 06:44:29'),
(4, 'franciscotapia1020@outlook.com', '66e3e66e2c9158f98d08d951aa1677f852d1e906c58e980f8159c8d8b7e7f1d9', '2025-07-20 06:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(255) DEFAULT NULL,
  `xp` int(11) NOT NULL DEFAULT 0,
  `nivel` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nombre`, `nickname`, `correo`, `password`, `creado_en`, `avatar`, `xp`, `nivel`) VALUES
(1, 'Francisco Tapia', 'xxPakoGamer10Xx', 'franciscotapia1020@outlook.com', '$2y$10$O4CbQbDubmDOW6CKWDNp/.f2/Cmy3MtUa6rKg4p6deFq/vEoujQ7C', '2025-07-20 02:23:07', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `status` enum('no_iniciado','completado') NOT NULL DEFAULT 'no_iniciado',
  `completado_en` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_lesson_unique` (`user_id`,`lesson_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
