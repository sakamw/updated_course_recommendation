-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 10:33 AM
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
-- Database: `course_recommendation`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `university` varchar(100) NOT NULL,
  `min_grade` varchar(2) NOT NULL,
  `required_subjects` text NOT NULL,
  `subject_weights` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `university`, `min_grade`, `required_subjects`, `subject_weights`) VALUES
(2, 'Bachelor of Medicine and Surgery', 'University of Nairobi', 'A-', '[\"Biology, Chemistry, Physics, English, Mathematics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Physics\": 2, \"English\": 2, \"Mathematics\": 2}'),
(3, 'Bachelor of Pharmacy', 'Kenyatta University', 'B+', '[\"Biology, Chemistry, Physics, English\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Physics\": 2, \"English\": 2}'),
(4, 'Bachelor of Nursing', 'Moi University', 'B', '[\"Biology\", \"Chemistry\", \"Mathematics\", \"English\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Mathematics\": 2, \"English\": 2}'),
(5, 'Bachelor of Law (LLB)', 'Strathmore University', 'B+', '[\"English\", \"Kiswahili\", \"Mathematics\"]', '{\"English\": 2, \"Kiswahili\": 1, \"Mathematics\": 2}'),
(6, 'Bachelor of Architecture', 'Jomo Kenyatta University of Agriculture and Technology', 'B+', '[\"Mathematics\", \"Physics\", \"Geography\", \"English\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Geography\": 1, \"English\": 2}'),
(7, 'Bachelor of Computer Science', 'Kenyatta University', 'B', '[\"Mathematics\", \"Physics\", \"English\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"English\": 2}'),
(8, 'Bachelor of Information Technology', 'Murang\'a University of Technology', 'C+', '[\"Mathematics\", \"English\", \"Physics\"]', '{\"Mathematics\": 2, \"English\": 2, \"Physics\": 2}'),
(9, 'Bachelor of Business Administration', 'University of Nairobi', 'C+', '[\"Mathematics\", \"English\", \"Business Studies\"]', '{\"Mathematics\": 2, \"English\": 2, \"Business Studies\": 1}'),
(10, 'Bachelor of Economics', 'Egerton University', 'B-', '[\"Mathematics\", \"English\", \"Business Studies\"]', '{\"Mathematics\": 2, \"English\": 2, \"Business Studies\": 1}'),
(11, 'Bachelor of Civil Engineering', 'JKUAT', 'B+', '[\"Mathematics\", \"Physics\", \"Chemistry\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Chemistry\": 2}'),
(12, 'Bachelor of Electrical Engineering', 'Technical University of Kenya', 'B+', '[\"Mathematics\", \"Physics\", \"Chemistry\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Chemistry\": 2}'),
(13, 'Bachelor of Software Engineering', 'Zetech University', 'B-', '[\"Mathematics\", \"English\", \"Physics\"]', '{\"Mathematics\": 2, \"English\": 2, \"Physics\": 2}'),
(14, 'Bachelor of Data Science', 'Strathmore University', 'B', '[\"Mathematics\", \"Physics\", \"Computer Studies\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Computer Studies\": 2}'),
(15, 'Bachelor of Hospitality Management', 'Kenyatta University', 'C+', '[\"English\", \"Mathematics\", \"Business Studies\"]', '{\"English\": 2, \"Mathematics\": 2, \"Business Studies\": 1}'),
(16, 'Bachelor of Journalism and Media Studies', 'University of Nairobi', 'C+', '[\"English\", \"Kiswahili\", \"History\"]', '{\"English\": 2, \"Kiswahili\": 1, \"History\": 1}'),
(17, 'Bachelor of Film Production', 'Multimedia University', 'C+', '[\"English\", \"Kiswahili\", \"Computer Studies\"]', '{\"English\": 2, \"Kiswahili\": 1, \"Computer Studies\": 2}'),
(18, 'Bachelor of Psychology', 'Daystar University', 'C+', '[\"English\", \"Biology\", \"Mathematics\"]', '{\"English\": 2, \"Biology\": 2, \"Mathematics\": 2}'),
(19, 'Bachelor of Tourism Management', 'Kenyatta University', 'C+', '[\"Geography\", \"Business Studies\", \"English\"]', '{\"Geography\": 1, \"Business Studies\": 1, \"English\": 2}'),
(20, 'Bachelor of Education (Science)', 'Maseno University', 'C+', '[\"Mathematics\", \"Physics\", \"Biology\", \"Chemistry\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Biology\": 2, \"Chemistry\": 2}'),
(21, 'Bachelor of Education (Arts)', 'Mount Kenya University', 'C+', '[\"English\", \"Kiswahili\", \"History\"]', '{\"English\": 2, \"Kiswahili\": 1, \"History\": 1}'),
(22, 'Bachelor of Criminology', 'Dedan Kimathi University', 'C+', '[\"History\", \"English\", \"Business Studies\"]', '{\"History\": 1, \"English\": 2, \"Business Studies\": 1}'),
(23, 'Bachelor of Cyber Security', 'Murang\'a University of Technology', 'B-', '[\"Mathematics\", \"Physics\", \"Computer Studies\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Computer Studies\": 2}'),
(24, 'Bachelor of Agricultural Engineering', 'Egerton University', 'B', '[\"Mathematics\", \"Physics\", \"Agriculture\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Agriculture\": 1}'),
(25, 'Bachelor of Veterinary Medicine', 'University of Nairobi', 'B+', '[\"Biology\", \"Chemistry\", \"Physics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Physics\": 2}'),
(26, 'Bachelor of Commerce', 'JKUAT', 'C+', '[\"Mathematics\", \"Business Studies\", \"English\"]', '{\"Mathematics\": 2, \"Business Studies\": 1, \"English\": 2}'),
(27, 'Bachelor of Banking and Finance', 'University of Nairobi', 'C+', '[\"Mathematics\", \"Business Studies\", \"Economics\"]', '{\"Mathematics\": 2, \"Business Studies\": 1, \"Economics\": 1}'),
(28, 'Bachelor of Human Resource Management', 'Mount Kenya University', 'C+', '[\"Business Studies\", \"English\", \"Mathematics\"]', '{\"Business Studies\": 1, \"English\": 2, \"Mathematics\": 2}'),
(29, 'Bachelor of Food Science and Technology', 'Egerton University', 'B-', '[\"Biology\", \"Chemistry\", \"Mathematics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Mathematics\": 2}'),
(30, 'Bachelor of Environmental Science', 'Maseno University', 'C+', '[\"Geography\", \"Biology\", \"Chemistry\"]', '{\"Geography\": 1, \"Biology\": 2, \"Chemistry\": 2}'),
(31, 'Bachelor of Biochemistry', 'University of Nairobi', 'B', '[\"Biology\", \"Chemistry\", \"Physics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Physics\": 2}'),
(32, 'Bachelor of International Relations', 'United States International University (USIU)', 'C+', '[\"History\", \"English\", \"Geography\"]', '{\"History\": 1, \"English\": 2, \"Geography\": 1}'),
(33, 'Bachelor of Actuarial Science', 'Strathmore University', 'B+', '[\"Mathematics\", \"Business Studies\", \"Physics\"]', '{\"Mathematics\": 2, \"Business Studies\": 1, \"Physics\": 2}'),
(34, 'Bachelor of Statistics', 'Technical University of Kenya', 'B-', '[\"Mathematics\", \"Physics\", \"Computer Studies\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Computer Studies\": 2}'),
(35, 'Bachelor of Real Estate Management', 'University of Nairobi', 'C+', '[\"Mathematics\", \"Geography\", \"Business Studies\"]', '{\"Mathematics\": 2, \"Geography\": 1, \"Business Studies\": 1}'),
(36, 'Bachelor of Construction Management', 'JKUAT', 'C+', '[\"Mathematics\", \"Physics\", \"Geography\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Geography\": 1}'),
(37, 'Bachelor of Renewable Energy', 'Technical University of Kenya', 'B-', '[\"Physics\", \"Mathematics\", \"Chemistry\"]', '{\"Physics\": 2, \"Mathematics\": 2, \"Chemistry\": 2}'),
(38, 'Bachelor of Aviation Management', 'East African School of Aviation', 'C+', '[\"Mathematics\", \"Physics\", \"English\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"English\": 2}'),
(39, 'Bachelor of Aeronautical Engineering', 'Technical University of Kenya', 'B+', '[\"Mathematics\", \"Physics\", \"Chemistry\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Chemistry\": 2}'),
(40, 'Bachelor of Forensic Science', 'Kenyatta University', 'B-', '[\"Biology\", \"Chemistry\", \"Physics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Physics\": 2}'),
(41, 'Bachelor of Public Health', 'Moi University', 'B-', '[\"Biology\", \"Chemistry\", \"Mathematics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Mathematics\": 2}'),
(42, 'Bachelor of Political Science', 'University of Nairobi', 'C+', '[\"History\", \"Geography\", \"English\"]', '{\"History\": 1, \"Geography\": 1, \"English\": 2}'),
(43, 'Bachelor of Procurement and Supply Chain Management', 'JKUAT', 'C+', '[\"Business Studies\", \"Mathematics\", \"English\"]', '{\"Business Studies\": 1, \"Mathematics\": 2, \"English\": 2}'),
(44, 'Bachelor of Social Work', 'Daystar University', 'C+', '[\"History\", \"English\", \"Kiswahili\"]', '{\"History\": 1, \"English\": 2, \"Kiswahili\": 1}'),
(45, 'Bachelor of Library and Information Science', 'Kenyatta University', 'C+', '[\"English\", \"Computer Studies\", \"History\"]', '{\"English\": 2, \"Computer Studies\": 2, \"History\": 1}'),
(46, 'Bachelor of Community Development', 'Mount Kenya University', 'C+', '[\"History\", \"Geography\", \"English\"]', '{\"History\": 1, \"Geography\": 1, \"English\": 2}'),
(47, 'Bachelor of Entrepreneurship', 'University of Nairobi', 'C+', '[\"Business Studies\", \"Mathematics\", \"English\"]', '{\"Business Studies\": 1, \"Mathematics\": 2, \"English\": 2}'),
(48, 'Bachelor of Marine Engineering', 'Technical University of Mombasa', 'B', '[\"Mathematics\", \"Physics\", \"Geography\"]', '{\"Mathematics\": 2, \"Physics\": 2, \"Geography\": 1}'),
(49, 'Medicine', 'UoN', 'A-', '[\"Mathematics, English, Biology, Chemistry\"]', '{\"Mathematics\": 2, \"English\": 2, \"Biology\": 2, \"Chemistry\": 2}'),
(50, 'Medicine', 'UoN', 'A-', '[\"Mathematics, English, Biology, Chemistry\"]', '{\"Mathematics\": 2, \"English\": 2, \"Biology\": 2, \"Chemistry\": 2}'),
(51, 'Bachelor of Pharmacy', 'Rongo University College', 'B+', '[\"Biology, Chemistry, Physics, English, Mathematics\"]', '{\"Biology\": 2, \"Chemistry\": 2, \"Physics\": 2, \"English\": 2, \"Mathematics\": 2}');

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `grade` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_results`
--

INSERT INTO `exam_results` (`id`, `user_id`, `subject`, `grade`) VALUES
(1, 3, 'Mathematics', 'B'),
(2, 3, 'English', 'C+'),
(3, 3, 'Kiswahili', 'B'),
(4, 3, 'Chemistry', 'A'),
(5, 3, 'Physics', 'C'),
(6, 3, 'History', 'A'),
(7, 3, 'Computer Studies', 'A'),
(8, 4, 'Mathematics', 'B'),
(9, 4, 'English', 'C+'),
(10, 4, 'Kiswahili', 'A'),
(11, 4, 'Biology', 'C+'),
(12, 4, 'Chemistry', 'C+'),
(13, 4, 'Physics', 'C+'),
(14, 4, 'Geography', 'C+'),
(15, 4, 'Computer Studies', 'A'),
(16, 2, 'Mathematics', 'B'),
(17, 2, 'English', 'C'),
(18, 2, 'Kiswahili', 'B'),
(19, 2, 'Biology', 'C'),
(20, 2, 'Chemistry', 'A'),
(21, 2, 'Physics', 'C'),
(22, 2, 'History', 'B'),
(23, 2, 'Geography', 'B-'),
(24, 2, 'Computer Studies', 'A'),
(25, 5, 'Mathematics', 'B'),
(26, 5, 'English', 'C'),
(27, 5, 'Kiswahili', 'B'),
(28, 5, 'Biology', 'C'),
(29, 5, 'Chemistry', 'A'),
(30, 5, 'Physics', 'A'),
(31, 5, 'History', 'B'),
(32, 5, 'Geography', 'B-'),
(33, 5, 'Religious Studies', 'CR'),
(34, 5, 'Mathematics', 'B'),
(35, 5, 'English', 'C'),
(36, 5, 'Kiswahili', 'B'),
(37, 5, 'Biology', 'C'),
(38, 5, 'Chemistry', 'A'),
(39, 5, 'History', 'B'),
(40, 5, 'Geography', 'B-'),
(41, 5, 'Business Studies', 'A'),
(42, 6, 'Mathematics', 'B'),
(43, 6, 'English', 'B-'),
(44, 6, 'Kiswahili', 'A'),
(45, 6, 'Biology', 'A'),
(46, 6, 'Chemistry', 'B+'),
(47, 6, 'Geography', 'B+'),
(48, 6, 'Computer Studies', 'A'),
(49, 7, 'Mathematics', '1'),
(50, 7, 'English', '2'),
(51, 7, 'Kiswahili', '3'),
(52, 7, 'Biology', 'F'),
(53, 7, 'Chemistry', 'J'),
(54, 7, 'Physics', '5'),
(55, 7, 'History', '6'),
(56, 7, 'Geography', 'K'),
(57, 7, 'Business Studies', '1'),
(58, 7, 'Agriculture', 'V'),
(59, 7, 'Computer Studies', '2'),
(60, 7, 'Other', '4'),
(61, 7, 'Mathematics', 'F'),
(62, 7, 'English', 'F'),
(63, 7, 'Kiswahili', 'F'),
(64, 7, 'History', 'G'),
(65, 7, 'Geography', 'G'),
(66, 7, 'Mathematics', 'A'),
(67, 7, 'English', 'A'),
(68, 7, 'Kiswahili', 'A'),
(69, 7, 'History', 'A'),
(70, 4, 'Mathematics', 'A'),
(71, 4, 'English', 'B-'),
(72, 4, 'Kiswahili', 'A-'),
(73, 4, 'Biology', 'B+'),
(74, 4, 'Chemistry', 'B'),
(75, 4, 'History', 'A'),
(76, 4, 'Business Studies', 'A-'),
(77, 4, 'Mathematics', 'A'),
(78, 4, 'English', 'B-'),
(79, 4, 'Kiswahili', 'A-'),
(80, 4, 'Biology', 'B+'),
(81, 4, 'Chemistry', 'B'),
(82, 4, 'History', 'A'),
(83, 4, 'Business Studies', 'A-');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `must_change_password` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `created_at`, `is_active`, `must_change_password`) VALUES
(2, 'Kevin', 'kenife@gmail.com', '$2y$10$D.svUANoPs8wR63ERgnJOOIWoWO9RlF7050iCUBfg9ryI.KLjcT.S', 'admin', '2025-03-19 09:20:19', 1, 0),
(3, 'Ithaka Mwangi', 'dennisnduto418@gmail.com', '$2y$10$ywEdWeFu7IExDobt6hayZOrZNpUPNWoLdsl03U3EQsiu/aj.c0O26', 'student', '2025-03-19 09:34:29', 0, 0),
(4, 'Havertz', 'havertz@gmail.com', '$2y$10$.wKlTbz1.D9P/0QOPzcDwe2Vh.7qHNGf9GDxBvpsxMsdHiUfD22H2', 'student', '2025-03-19 10:34:24', 1, 0),
(5, 'dennoh', 'dennoh@gmail.com', '$2y$10$FzNgilU4mJz4MDlwe0MtSe4WQ3ibJsbHGRA87FB9/7YXfQBfrunie', 'student', '2025-03-20 08:01:05', 1, 0),
(6, 'Saka', 'saka3@gmail.com', '$2y$10$w1w0RIksDTXzwG5iovoIW.9jfM/ThE0STh6XJsMgVvG3MD/wkIKpS', 'student', '2025-03-20 10:23:00', 1, 0),
(7, 'Kevin Githinji', 'kevin@gmail.com', '$2y$10$.H6c0nzL4tHFmlJghtzXTeJxDQfD37Zz9TX15jDrxfEdgSma/05hm', 'student', '2025-03-20 11:11:39', 1, 0),
(8, 'jane', 'jane@gmail.com', '$2y$10$.gQuCN7eGFUFrSPYz/CT1O9kaEw2mzdLzWaM1vA.hNY7BZ5QIz.3G', 'student', '2025-03-20 11:27:42', 1, 0),
(9, 'Konami', 'konami21@gmail.com', '$2y$10$frUm64Ow8ktgNff3y7w0ye2Mi1yqVEiIsZZ1MLML128aqVjQi0JQq', 'student', '2025-03-22 07:42:14', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD CONSTRAINT `exam_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
