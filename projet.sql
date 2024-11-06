-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2024 at 03:59 PM
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
-- Database: `projet`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `Id_Commentaire` int(11) NOT NULL,
  `Commentaire` varchar(255) NOT NULL,
  `Id_Etudiant` int(11) NOT NULL,
  `Id_lesson` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cours`
--

CREATE TABLE `cours` (
  `Id_Cours` int(11) NOT NULL,
  `Titre_cours` varchar(255) DEFAULT NULL,
  `Id_Enseignant` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cours`
--

INSERT INTO `cours` (`Id_Cours`, `Titre_cours`, `Id_Enseignant`) VALUES
(1, 'JavaScript', 5),
(2, 'C', 6),
(3, 'Java', 1),
(4, 'Python', 3),
(5, 'SQL', 4);

-- --------------------------------------------------------

--
-- Table structure for table `enseignant`
--

CREATE TABLE `enseignant` (
  `Id_Enseignant` int(11) NOT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Mot_de_Passe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enseignant`
--

INSERT INTO `enseignant` (`Id_Enseignant`, `Nom`, `Prenom`, `Email`, `Mot_de_Passe`) VALUES
(1, 'El Bouzidi', 'Abdelilah', 'abdelilahelbouzidi@enseignant', 'P@ssw0rd1'),
(3, 'Zerouali', 'Fatima', 'fatimazerouali@enseignant', 'P@ssw0rd2'),
(4, 'Laaribi', 'Mohammed', 'mohammedlaaribi@exam', 'P@ssw0rd3'),
(5, 'Essalhi', 'Nadia', 'nadiaessalhi@example', 'P@ssw0rd4'),
(6, 'El Mansour', 'Rachid', 'rachidelmansouri@exa', 'P@ssw0rd5');

-- --------------------------------------------------------

--
-- Table structure for table `etudiant`
--

CREATE TABLE `etudiant` (
  `Id_Etudiant` int(11) NOT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Prenom` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Mot_de_Passe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `etudiant`
--

INSERT INTO `etudiant` (`Id_Etudiant`, `Nom`, `Prenom`, `Email`, `Mot_de_Passe`) VALUES
(50, 'El Aouuuuufir', 'Younes', 'youneselaoufir@examp', 'P@ssw0rd1'),
(51, 'Bouchraoui', 'Fatima', 'fatimabouchraoui@exa', 'P@ssw0rd2'),
(52, 'El Ouardig', 'Mohammed', 'mohammedelouardighi@', 'P@ssw0rd3'),
(53, 'Ben Amar', 'Nadia', 'nadiabenamar@example', 'P@ssw0rd4'),
(54, 'Chahid', 'Rachid', 'rachidchahid@example', 'P@ssw0rd5'),
(55, 'El Kharraz', 'Hafsa', 'hafsaelkharraz@examp', 'P@ssw0rd6'),
(56, 'Belhaj', 'Mehdi', 'mehdibelhaj@example.', 'P@ssw0rd7'),
(57, 'El Kettani', 'Amina', 'aminaelkettani@examp', 'P@ssw0rd8'),
(58, 'Lamrani', 'Karim', 'karimlamrani@example', 'P@ssw0rd9'),
(59, 'El Ghazi', 'Sara', 'saraelghazi@example.', 'P@ssw0rd10'),
(60, 'Benjelloun', 'Ahmed', 'ahmedbenjelloun@exam', 'P@ssw0rd11'),
(61, 'El Alaoui', 'Houda', 'houdaelalaoui@exampl', 'P@ssw0rd12'),
(62, 'Benslimane', 'Brahim', 'brahimbenslimane@exa', 'P@ssw0rd13'),
(63, 'El Moutawa', 'Loubna', 'loubnaelmoutawakil@e', 'P@ssw0rd14'),
(64, 'Benabdella', 'Abdelali', 'abdelalibenabdellah@', 'P@ssw0rd15'),
(65, 'El Fakir', 'Samira', 'samiraelefakir@examp', 'P@ssw0rd16'),
(66, 'Boukhriss', 'Omar', 'omarboukhriss@exampl', 'P@ssw0rd17'),
(67, 'El Hajoui', 'Khadija', 'khadijaelhajoui@exam', 'P@ssw0rd18'),
(68, 'Benmoussa', 'Mustapha', 'mustaphabenmoussa@ex', 'P@ssw0rd19'),
(69, 'El Mansour', 'Nawal', 'nawalelmansouri@exam', 'P@ssw0rd20');

-- --------------------------------------------------------

--
-- Table structure for table `exercice`
--

CREATE TABLE `exercice` (
  `Id_Exercice` int(11) NOT NULL,
  `Nom_Exercice` varchar(255) NOT NULL,
  `Id_lesson` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `Id_lesson` int(11) NOT NULL,
  `Titre_lesson` varchar(255) DEFAULT NULL,
  `file_lesson` longtext NOT NULL,
  `Id_Cours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson`
--

INSERT INTO `lesson` (`Id_lesson`, `Titre_lesson`, `file_lesson`, `Id_Cours`) VALUES
(1, 'JavaScript Variables', 'javascript_variables.txt', 1),
(2, 'JavaScript Functions', 'javascript_functions.txt', 1),
(3, 'JavaScript Loops', 'javascript_loops.txt', 1),
(4, 'JavaScript Events', 'javascript_events.txt', 1),
(5, 'JavaScript Arrays', 'javascript_arrays.txt', 1),
(6, 'JavaScript Objects', 'javascript_objects.txt', 1),
(7, 'C Introduction', 'c_introduction.txt', 2),
(8, 'C Data Types', 'c_data_types.txt', 2),
(9, 'C Control Structures', 'c_control_structures.txt', 2),
(10, 'C Functions', 'c_functions.txt', 2),
(11, 'C Pointers', 'c_pointers.txt', 2),
(12, 'C Arrays', 'c_arrays.txt', 2),
(13, 'Java Introduction', 'java_introduction.txt', 3),
(14, 'Java Data Types', 'java_data_types.txt', 3),
(15, 'Java Control Structures', 'java_control_structures.txt', 3),
(16, 'Java OOP Concepts', 'java_oop_concepts.txt', 3),
(17, 'Java Collections', 'java_collections.txt', 3),
(18, 'Java Exceptions', 'java_exceptions.txt', 3),
(19, 'Python Introduction', 'python_introduction.txt', 4),
(20, 'Python Data Types', 'python_data_types.txt', 4),
(21, 'Python Control Structures', 'python_control_structures.txt', 4),
(22, 'Python Functions', 'python_functions.txt', 4),
(23, 'Python Modules', 'python_modules.txt', 4),
(24, 'Python Exceptions', 'python_exceptions.txt', 4),
(25, 'SQL Introduction', 'sql_introduction.txt', 5),
(26, 'SQL Data Types', 'sql_data_types.txt', 5),
(27, 'SQL Joins', 'sql_joins.txt', 5),
(28, 'SQL Aggregation', 'sql_aggregation.txt', 5),
(29, 'SQL Subqueries', 'sql_subqueries.txt', 5),
(30, 'SQL Indexing', 'sql_indexing.txt', 5);

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `Id_Note` int(11) NOT NULL,
  `Note` tinyint(1) DEFAULT NULL,
  `Id_Etudiant` int(11) DEFAULT NULL,
  `Id_Exercice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `Id_Question` int(11) NOT NULL,
  `Question` text NOT NULL,
  `Reponse` varchar(255) DEFAULT NULL,
  `Id_Exercice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`Id_Commentaire`),
  ADD KEY `fk_etudiant_commentaire` (`Id_Etudiant`),
  ADD KEY `fk_lecon_commentaire` (`Id_lesson`);

--
-- Indexes for table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`Id_Cours`),
  ADD KEY `fk_Enseignant` (`Id_Enseignant`);

--
-- Indexes for table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`Id_Enseignant`);

--
-- Indexes for table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`Id_Etudiant`);

--
-- Indexes for table `exercice`
--
ALTER TABLE `exercice`
  ADD PRIMARY KEY (`Id_Exercice`),
  ADD KEY `fk_lesson_exercice` (`Id_lesson`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`Id_lesson`),
  ADD KEY `fk_cours` (`Id_Cours`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`Id_Note`),
  ADD KEY `fk_etudiant_note` (`Id_Etudiant`),
  ADD KEY `fk_exercice_note` (`Id_Exercice`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`Id_Question`),
  ADD KEY `fk_exercice_question` (`Id_Exercice`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `Id_Commentaire` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cours`
--
ALTER TABLE `cours`
  MODIFY `Id_Cours` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `enseignant`
--
ALTER TABLE `enseignant`
  MODIFY `Id_Enseignant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `Id_Etudiant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `exercice`
--
ALTER TABLE `exercice`
  MODIFY `Id_Exercice` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `Id_lesson` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `Id_Note` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `Id_Question` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `fk_etudiant_commentaire` FOREIGN KEY (`Id_Etudiant`) REFERENCES `etudiant` (`Id_Etudiant`),
  ADD CONSTRAINT `fk_lecon_commentaire` FOREIGN KEY (`Id_lesson`) REFERENCES `lecon` (`Id_leçon`);

--
-- Constraints for table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `fk_Enseignant` FOREIGN KEY (`Id_Enseignant`) REFERENCES `enseignant` (`Id_Enseignant`);

--
-- Constraints for table `exercice`
--
ALTER TABLE `exercice`
  ADD CONSTRAINT `fk_lesson_exercice` FOREIGN KEY (`Id_lesson`) REFERENCES `lesson` (`Id_lesson`);

--
-- Constraints for table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `fk_cours` FOREIGN KEY (`Id_Cours`) REFERENCES `cours` (`Id_Cours`);

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `fk_etudiant_note` FOREIGN KEY (`Id_Etudiant`) REFERENCES `etudiant` (`Id_Etudiant`),
  ADD CONSTRAINT `fk_exercice_note` FOREIGN KEY (`Id_Exercice`) REFERENCES `exercice` (`Id_Exercice`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `fk_exercice_question` FOREIGN KEY (`Id_Exercice`) REFERENCES `exercice` (`Id_Exercice`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
