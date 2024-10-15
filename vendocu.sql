-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 15, 2024 at 02:20 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vendocu`
--

-- --------------------------------------------------------

--
-- Table structure for table `registrar_accounts`
--

CREATE TABLE `registrar_accounts` (
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrar_accounts`
--

INSERT INTO `registrar_accounts` (`email`, `created_at`) VALUES
('bfolegario@paterostechnologicalcollege.edu.ph', '2024-10-14 11:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `document_type` enum('COG','COR') NOT NULL,
  `doc_year` int(11) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `document_link` varchar(500) DEFAULT NULL,
  `request_status` enum('pending','confirmed','completed','removed') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `order_number`, `email`, `document_type`, `doc_year`, `year_level`, `semester`, `section`, `course`, `document_link`, `request_status`, `requested_at`, `completed_at`) VALUES
(9, 'ORD-6703c13df3782', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 1, 1, 1, 'A', 'BSIT', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d51ed788db_E-CERTIFICATE%20S.A.T%20WEBINAR%2010%20%26%2011%20A.Y%202024-2025.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T171629Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=369bbc52d326b9e44a72158f02ee2718971c548fa0af98ec7670407e081e2cf6', 'removed', '2024-10-07 11:08:45', NULL),
(10, 'ORD-6703c60064e24', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 3, 1, 1, 'A', 'HRM', NULL, 'removed', '2024-10-07 11:29:04', NULL),
(11, 'ORD-6704959453d51', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 1, 1, 1, 'A', 'BSOA', NULL, 'removed', '2024-10-08 02:14:44', NULL),
(12, 'ORD-67049df553572', 'namaghamil@paterostechnologicalcollege.edu.ph', 'COG', 2, 1, 1, 'r', 'efewf', NULL, 'removed', '2024-10-08 02:50:29', NULL),
(13, 'ORD-6704a6d916156', 'namaghamil@paterostechnologicalcollege.edu.ph', 'COG', 2, 1, 1, 'r', 'efewf', NULL, 'removed', '2024-10-08 03:28:25', NULL),
(14, 'ORD-678641', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 1, 1, 1, 'A', 'sgvy', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d53938dcdd_MAYA-E-WALLET-A-CASE-STUDY-INITAIAL-PLAN.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T172331Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=f7feb15c5aa1c8e7d36c4ddc539751bf65169c8655573d4f8754af4f63321d69', 'confirmed', '2024-10-08 05:29:17', NULL),
(15, 'ORD-805453', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 4, 1, 1, 'A', 'russ', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d534d9120c_E-CERTIFICATE%20S.A.T%20WEBINAR%2010%20%26%2011%20A.Y%202024-2025.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T172222Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=a9a28f51256d2c21d34920a4cd2f295ee98b2d1672d8e06df8acd17133d62e2e', 'confirmed', '2024-10-08 05:52:43', NULL),
(16, 'ORD-692623', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 1, 2, 1, 'B', 'BSIT', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d523f42d3f_Screenshot%20from%202024-10-14%2017-47-10.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T171751Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=08ff85c121b902fe7f442aee925fdb09665c4a5b3cbfe88ac87751edefea7a05', 'confirmed', '2024-10-08 06:15:37', NULL),
(17, 'ORD-226928', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 1, 3, 1, 'A', 'BSIT', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d58083f77d_Screenshot%20from%202024-10-14%2012-35-27.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T174232Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=9ecc19c74f4b2653cc3cc0fdf7805c278b93685a1d33e272919fdc3b4d84c36f', 'confirmed', '2024-10-08 21:29:06', NULL),
(18, 'ORD-869121', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 2020, 1, 1, 'A', 'BSIT', NULL, 'removed', '2024-10-08 22:13:28', NULL),
(19, 'ORD-882927', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 2020, 2, 1, 'bs', 'BSIT', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d721d641e0_E-CERTIFICATE%20S.A.T%20WEBINAR%2010%20%26%2011%20A.Y%202024-2025.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T193349Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=72d4be1e94bf36012e7a3c9321ab459af2fc8e2354798016df1a679c1350cad1', 'confirmed', '2024-10-14 18:01:56', NULL),
(20, 'ORD-323916', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 2, 1, 1, 'bs', 'wasc', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d776fb8240_E-CERTIFICATE%20S.A.T%20WEBINAR%2010%20%26%2011%20A.Y%202024-2025.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T195632Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=a09a8df5f51794e4f1f84a175fa54b8185b720ac39fd19634d729051fe6c703f', 'confirmed', '2024-10-14 19:32:53', NULL),
(21, 'ORD-907965', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 2, 2, 2, 'sdsdc', 'rve', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d7a07e17b6_vendocu_yellow.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T200736Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=eebd5f5058a1c9ab6aa5df0779ebbf7299ae2b517099572fc504f51415de0644', 'confirmed', '2024-10-14 20:00:07', NULL),
(22, 'ORD-987108', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 2, 3, 2, 'sdsdc', 'xsdacxda', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d7d92707ec_E-CERTIFICATE%20S.A.T%20WEBINAR%2010%20%26%2011%20A.Y%202024-2025.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T202242Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=b18bc338a0f7897a08e870372f7d3577930def519c24d868540c6455e3b11ada', 'confirmed', '2024-10-14 20:17:53', NULL),
(23, 'ORD-675501', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 23, 1, 1, 'wqd', 'wearfg', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670d7e0bc0668_6709f055a27ae_TCML-202466FEC4AE776.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241014%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241014T202444Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=5dc890de0acda2f0e1f96faf6136edac39228130a2a92a0312fb90270792be1f', 'confirmed', '2024-10-14 20:24:22', NULL),
(24, 'ORD-634538', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 203, 2, 2, '21e', 'edw', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670de2751cae0_Screenshot%20from%202024-10-14%2010-16-34.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241015%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241015T033309Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=c6ca89b898ce09134806694421b63a5bafec5758fa418d78c38820d32d47369d', 'confirmed', '2024-10-14 20:27:37', NULL),
(25, 'ORD-803436', 'lsllames@paterostechnologicalcollege.edu.ph', 'COR', 543, 3, 1, 'rtghertyn', 'wtgh', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670de607bac05_Screenshot%20from%202024-10-14%2010-16-34.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241015%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241015T034824Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=3b6397fde82d9ab2039c10274e3d46451ffe228bf5a865620b28715a4d20513a', 'confirmed', '2024-10-15 03:33:51', NULL),
(26, 'ORD-376229', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 34, 3, 2, 'wqd', 'swsd', 'https://vendocu-datastore.s3.ap-southeast-1.amazonaws.com/670e2083d11c9_10-11.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAWZMWWWEWNLHDJVA4%2F20241015%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20241015T075756Z&X-Amz-SignedHeaders=host&X-Amz-Expires=518400&X-Amz-Signature=25696494ad9a81a157b45ecf646be4b9fc5726041ed044b09ea86801cc9faf22', 'confirmed', '2024-10-15 04:04:12', NULL),
(27, 'ORD-301998', 'lsllames@paterostechnologicalcollege.edu.ph', 'COG', 324, 2, 1, 'fewq', 'BSIT', NULL, 'pending', '2024-10-15 04:21:56', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registrar_accounts`
--
ALTER TABLE `registrar_accounts`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
