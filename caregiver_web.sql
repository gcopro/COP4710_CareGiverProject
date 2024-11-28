-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 02:34 AM
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
-- Database: `caregiver_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `caregiver`
--

CREATE TABLE `caregiver` (
  `CID` int(11) NOT NULL,
  `MID` int(11) NOT NULL,
  `Availble_hours_per_week` int(11) NOT NULL,
  `Rate` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `caregiver`
--

INSERT INTO `caregiver` (`CID`, `MID`, `Availble_hours_per_week`, `Rate`) VALUES
(1, 1, 40, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `Cno` int(11) NOT NULL,
  `MID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `earnedCD` int(11) NOT NULL,
  `spentCD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`Cno`, `MID`, `CID`, `PID`, `startDate`, `endDate`, `earnedCD`, `spentCD`) VALUES
(1, 1, 1, 1, '2024-11-01', '2024-11-30', 500, 30);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `MID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `PASSWORD` varchar(30) NOT NULL,
  `CDbalance` int(11) NOT NULL DEFAULT 2000
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`MID`, `NAME`, `Phone`, `Address`, `PASSWORD`, `CDbalance`) VALUES
(1, 'AMAMAMAS', '1234567890', '123 Main St', 'password123', 2000);

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `PID` int(11) NOT NULL,
  `MID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`PID`, `MID`, `NAME`, `Age`, `Address`) VALUES
(1, 1, 'Parent One', 65, '456 Target St');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Rno` int(11) NOT NULL,
  `Cno` int(11) NOT NULL,
  `Rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`Rno`, `Cno`, `Rate`) VALUES
(1, 1, 3),
(2, 1, 3);

--
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `validRate` BEFORE INSERT ON `review` FOR EACH ROW BEGIN
    IF NEW.Rate < 1 OR NEW.Rate > 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'RATE 1 - 5';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `caregiver`
--
ALTER TABLE `caregiver`
  ADD PRIMARY KEY (`CID`),
  ADD KEY `MID` (`MID`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`Cno`),
  ADD KEY `MID` (`MID`),
  ADD KEY `CID` (`CID`),
  ADD KEY `PID` (`PID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`MID`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`PID`),
  ADD KEY `MID` (`MID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Rno`),
  ADD KEY `Cno` (`Cno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caregiver`
--
ALTER TABLE `caregiver`
  MODIFY `CID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `Cno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `MID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `Rno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `caregiver`
--
ALTER TABLE `caregiver`
  ADD CONSTRAINT `caregiver_ibfk_1` FOREIGN KEY (`MID`) REFERENCES `member` (`MID`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`MID`) REFERENCES `member` (`MID`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`CID`) REFERENCES `caregiver` (`CID`),
  ADD CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`PID`) REFERENCES `parent` (`PID`);

--
-- Constraints for table `parent`
--
ALTER TABLE `parent`
  ADD CONSTRAINT `parent_ibfk_1` FOREIGN KEY (`MID`) REFERENCES `member` (`MID`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`Cno`) REFERENCES `contracts` (`Cno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
