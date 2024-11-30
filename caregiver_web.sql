-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 11:00 AM
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
  `ableHours` int(11) NOT NULL,
  `Rate` decimal(5,2) DEFAULT 0.00,
  `limitHours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `transactions` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `hrs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Triggers `contracts`
--
DELIMITER $$
CREATE TRIGGER `correctTransaction` BEFORE INSERT ON `contracts` FOR EACH ROW BEGIN
	DECLARE correctAmount INT;
    -- calculate the correct amount in case caregiver submits wrong hourly rate
    SET correctAmount = NEW.hrs * 30;
    
    -- check if the transaction amount is correct
    IF NEW.transactions != correctAmount THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Incorrect hourly rate';
    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `resetHoursPerWeek` BEFORE INSERT ON `contracts` FOR EACH ROW BEGIN
	-- check for a we need to reset
	IF WEEK(NEW.startDate, 1) > (
        SELECT MAX(WEEK(startdate,1))
        FROM contracts
        WHERE CID = NEW.CID
    ) OR NOT EXISTS (
		SELECT *
        FROM contracts 
        WHERE CID = NEW.CID
    ) THEN
		-- reset hours to limit hours for each week
    	UPDATE caregiver
        SET ableHours = limitHours
    	WHERE CID = CID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateHours` AFTER UPDATE ON `contracts` FOR EACH ROW BEGIN
	-- if status is approved and not already approved 
	IF NEW.status = 1 AND NOT(OLD.status = 1) THEN
    	UPDATE caregiver
        SET ableHours = ableHours - NEW.hrs
        WHERE CID = NEW.CID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateMembersBalance` AFTER UPDATE ON `contracts` FOR EACH ROW BEGIN
	-- update the CD once approved
   	-- and if its not already approved
	IF NEW.status = 1 AND OLD.status != 1 THEN
		UPDATE member
    	SET CDbalance = CDbalance - NEW.transactions
        WHERE MID = NEW.MID;
    	
        -- add CD to caregiver
        UPDATE member
        SET CDbalance = CDbalance + NEW.transactions
        WHERE MID = (
            	SELECT MID
            	FROM caregiver
            	WHERE CID = NEW.CID
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validCD` BEFORE INSERT ON `contracts` FOR EACH ROW BEGIN
	DECLARE curMembCD INT;
    -- get members current CD balance
    SELECT CDbalance INTO curMembCD
    FROM member
    WHERE MID = NEW.MID;
    
    -- check if member can afford to hire
    IF( NEW.hrs * 30) > curMembCd THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Member has insufficient Funds';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validHours` BEFORE INSERT ON `contracts` FOR EACH ROW BEGIN
	DECLARE curHrs INT;
    -- Get the current hours from caregiver set to temp
    SELECT ableHours INTO curHrs
    FROM caregiver
    WHERE CID = NEW.CID;
    
    -- check if hours are valid to work for current week
    IF NEW.hrs > curHrs THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = ' Not enough hours this week';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `MID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(30) NOT NULL,
  `address` varchar(15) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `CDbalance` int(11) DEFAULT 2000
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `updateRate` AFTER INSERT ON `review` FOR EACH ROW BEGIN
	DECLARE cgID INT;
    SELECT CID INTO cgID
    FROM contracts
    WHERE Cno = NEW.Cno;
    
    UPDATE caregiver
    SET Rate = (
    	SELECT AVG(Rate)
    	FROM review
        WHERE Cno IN (
        	SELECT Cno
            FROM contracts
            WHERE CID = cgID
        )
    )
    WHERE CID = cgID;
END
$$
DELIMITER ;
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
  MODIFY `Cno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `MID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `Rno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
