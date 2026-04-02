-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 06:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pawfect`
--

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `species` varchar(50) DEFAULT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `vaccinated` tinyint(1) DEFAULT NULL,
  `neutered` tinyint(1) DEFAULT NULL,
  `good_with_kids` tinyint(1) DEFAULT NULL,
  `good_with_pets` tinyint(1) DEFAULT NULL,
  `energy_level` varchar(50) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `species`, `breed`, `age`, `gender`, `size`, `description`, `vaccinated`, `neutered`, `good_with_kids`, `good_with_pets`, `energy_level`, `date_added`, `image`) VALUES
(9, 'Milo', 'Dog', 'Labrador Retriever', 3, 'Male', 'Large', 'Friendly and playful Labrador who loves long walks and playing fetch. Great with families and very affectionate.', 1, 1, 1, 1, 'High', '2026-04-02 11:29:44', 'pet_69ce5328a346b6.74236761.png'),
(10, 'Rocky', 'Dog', 'German Shepherd', 4, 'Male', 'Large', 'Loyal and intelligent dog, great for active owners. Needs regular exercise and mental stimulation.', 1, 0, 1, 1, 'High', '2026-04-02 11:30:58', 'pet_69ce5372cdf5d2.61916398.png'),
(11, 'Lucy', 'Dog', 'Cocker Spaniel', 1, 'Female', 'Medium', 'Sweet and gentle puppy who loves cuddles and short walks. Still learning basic training.', 1, 0, 1, 1, 'Medium', '2026-04-02 11:34:51', 'pet_69ce545be63518.67086893.png'),
(12, 'Jake', 'Dog', 'Jack Russell Terrier', 5, 'Male', 'Small', 'Energetic and cheeky little dog who loves to run and play. Best suited to an active home where he can burn off energy', 1, 1, 1, 0, 'High', '2026-04-02 11:37:27', 'pet_69ce54f7ae2f23.21556467.png'),
(13, 'Cara', 'Dog', 'Greyhound', 6, 'Female', 'Large', 'Calm and gentle retired racer who enjoys short walks and long naps. Very affectionate and easy-going.', 1, 1, 1, 1, 'Low', '2026-04-02 11:40:30', 'pet_69ce55ae0253b3.36833241.png'),
(15, 'Buddy', 'Dog', 'Golden Retriever', 3, 'Male', 'Medium', 'Friendly and loyal dog who loves attention and outdoor walks. Great family companion.', 1, 1, 1, 1, 'Low', '2026-04-02 11:45:49', 'pet_69ce56ede43f40.10191839.png'),
(16, 'Misty', 'Cat', 'Domestic Shorthair', 2, 'Female', 'Small', 'Gentle and affectionate cat who enjoys cuddles and quiet environments.', 1, 1, 1, 1, 'Low', '2026-04-02 11:47:35', 'pet_69ce5757251308.79879111.png'),
(17, 'Rex', 'Dog', 'Border Collie', 4, 'Male', 'Medium', 'Very intelligent and active dog who needs lots of exercise and mental stimulation.', 1, 1, 1, 0, 'High', '2026-04-02 12:10:28', 'pet_69ce5cb4b6ef30.02668029.png'),
(19, 'Oscar', 'Cat', 'Maine Coon', 4, 'Male', 'Medium', 'Big fluffy cat with a calm temperament. Loves attention and gentle play.', 1, 1, 1, 1, 'Medium', '2026-04-02 12:14:08', 'pet_69ce5d90c9be25.41430265.png'),
(20, 'Daisy', 'Dog', 'Cavalier King Charles Spaniel', 3, 'Female', 'Medium', 'Curious and energetic dog who loves exploring and sniffing everything.', 1, 0, 1, 1, 'High', '2026-04-02 12:15:46', 'pet_69ce5df25f6b78.28835105.png'),
(21, 'Benny', 'Dog', 'Beagle', 6, 'Male', 'Medium', 'Loves food and cuddles. Loves attention and playing with his toys.', 1, 1, 1, 0, 'Medium', '2026-04-02 16:02:11', 'pet_69ce93032406d4.61100494.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
