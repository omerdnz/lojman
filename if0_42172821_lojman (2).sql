-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: sql209.infinityfree.com
-- Üretim Zamanı: 21 Haz 2026, 15:02:54
-- Sunucu sürümü: 11.4.12-MariaDB
-- PHP Sürümü: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `if0_42172821_lojman`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `employees`
--

INSERT INTO `employees` (`id`, `name`, `gender`, `department`) VALUES
(1, 'Erkan Eyinacar', 'Erkek', 'İnsan Kaynakları'),
(2, 'Ömer Deniz', 'Erkek', 'Bilgi İşlem'),
(3, 'Mesut Bozkurt', 'Erkek', 'Kalite'),
(4, 'Ayşe soyadı', 'Kadın', 'Ön Büro'),
(5, 'Mehmet soyadı', 'Erkek', 'Muhasebe'),
(6, 'Ali soyadı', 'Erkek', 'HK'),
(7, 'Erkan Eyinacar', 'Erkek', 'İnsan Kaynakları'),
(8, 'Ömer Deniz', 'Erkek', 'IT'),
(9, 'Mesut Bozkurt', 'Erkek', 'Kalite'),
(10, 'İLHAM ASLAN', 'Erkek', 'ANİMASYON'),
(11, 'BORA İNGEÇ', 'Erkek', 'ANİMASYON'),
(12, 'SERKAN TOĞO', 'Erkek', 'ANİMASYON'),
(13, 'İBRAHİM EREN ANIL', 'Erkek', 'ANİMASYON'),
(14, 'MEHMET BOĞA', 'Erkek', 'ANİMASYON'),
(15, 'DARIA ASLAN', 'Kadın', 'ANİMASYON'),
(16, 'ALPER AKBUDAK', 'Erkek', 'ANİMASYON'),
(17, 'VIKTORIIA ETMANOVA', 'Kadın', 'ANİMASYON'),
(18, 'PAVLINA ILLUS', 'Kadın', 'ANİMASYON'),
(19, 'ZHULDYZ MOLDAKHMETOVA', 'Kadın', 'ANİMASYON'),
(20, 'ANZHELIKA EVSEEVA', 'Kadın', 'ANİMASYON'),
(21, 'ELENA MALTSEVA', 'Kadın', 'ANİMASYON'),
(22, 'MAKHABBAT KULZHANOVA', 'Kadın', 'ANİMASYON'),
(23, 'TALAT CEM ÖZ', 'Erkek', 'ANİMASYON'),
(24, 'ULADZISLAVA MINICH', 'Kadın', 'ANİMASYON'),
(25, 'AZIZ CHOUAYA', 'Erkek', 'ANİMASYON'),
(26, 'EKATERINA SEROVA', 'Kadın', 'ANİMASYON'),
(27, 'DAIANA BEKBOSSYNOVA', 'Kadın', 'ANİMASYON'),
(28, 'DIANA PESTOVA', 'Kadın', 'ANİMASYON'),
(29, 'İLAYDA UĞUR', 'Kadın', 'ANİMASYON'),
(30, 'EGOR SKORIK', 'Erkek', 'ANİMASYON'),
(31, 'DERYA BEDİR', 'Kadın', 'ANİMASYON'),
(32, 'ERCAN KAVUZ', 'Erkek', 'BAHÇE'),
(33, 'EROL DEMİRTAŞ', 'Erkek', 'BAHÇE'),
(34, 'CENGİZ ASLAN', 'Erkek', 'BAHÇE'),
(35, 'VELİ DELEN', 'Erkek', 'BAHÇE'),
(36, 'HALİL ÖZDEMİR', 'Erkek', 'BAHÇE'),
(37, 'MUSA BIYIK', 'Erkek', 'BAHÇE'),
(38, 'MURAT ÖZDİLEK', 'Erkek', 'BAR'),
(39, 'HAKAN ARSLAN', 'Erkek', 'BAR'),
(40, 'BİLAL AK', 'Erkek', 'BAR'),
(41, 'MERYEM ELİF SAĞIR', 'Kadın', 'BAR'),
(42, 'MEHMET ÇELİK', 'Erkek', 'BAR'),
(43, 'HASAN İPEK', 'Erkek', 'BAR'),
(44, 'AHMET KARAAĞAÇ', 'Erkek', 'BAR'),
(45, 'HAFİZE GÜNGÖR', 'Kadın', 'BAR'),
(46, 'MUSA DURAN', 'Erkek', 'BAR'),
(47, 'FERHAT ÖZYOLCU', 'Erkek', 'BAR'),
(48, 'HİLMİ YILDIRIM', 'Erkek', 'BAR'),
(49, 'BERKANT KARAKAYA', 'Erkek', 'BAR'),
(50, 'BATUHAN TAŞ', 'Erkek', 'BAR'),
(51, 'RECEP BATUHAN ELMALI', 'Erkek', 'BAR'),
(52, 'FERHAT ACER', 'Erkek', 'BAR'),
(53, 'MİRAÇ SAĞILIR', 'Erkek', 'BAR'),
(54, 'ARZU DOĞAN', 'Kadın', 'BAR'),
(55, 'MUKHAMED CHABAKAEV', 'Erkek', 'BAR'),
(56, 'ALISHER KHAKNAZAROVICH SATIVALDIEV', 'Erkek', 'BAR'),
(57, 'TOPCHUBAI SATIVALDIEV', 'Erkek', 'BAR'),
(58, 'ZÜLÜFKAR ERTAN', 'Erkek', 'BAR'),
(59, 'ZÜLEYHA BULUT', 'Kadın', 'BAR'),
(60, 'ÇAĞRI ÇIĞA', 'Erkek', 'BAR'),
(61, 'YASİN AÇIKEL', 'Erkek', 'BAR'),
(62, 'GULNAZ MURZAKULOVA', 'Kadın', 'BAR'),
(63, 'KALIPA ZHYRGALBEK KYZY', 'Kadın', 'BAR'),
(64, 'UMUTKUL MADANBEK KYZY', 'Kadın', 'BAR'),
(65, 'KHADICHA ERNIS KYZY', 'Kadın', 'BAR'),
(66, 'BİRİCİK BURUN', 'Kadın', 'BAR'),
(67, 'VEDAT ACAR', 'Erkek', 'BAR'),
(68, 'YİĞİT MACİT ATAM', 'Erkek', 'BAR'),
(69, 'RAMAZAN DORUK', 'Erkek', 'BAR'),
(70, 'MUSTAFA SAVAŞ', 'Erkek', 'BAR'),
(71, 'ŞULE KETENLİ', 'Kadın', 'BAR'),
(72, 'ABDULVAKHID NURZHI ISLAMIDINOV', 'Erkek', 'BAR'),
(73, 'AZAY HUSEYNZADE', 'Erkek', 'BAR'),
(74, 'SÜHEYLA UYGUN', 'Kadın', 'BAR'),
(75, 'MEHMET KURT', 'Erkek', 'BAR'),
(76, 'MERVE ÖRENLİ', 'Kadın', 'BAR'),
(77, 'DAVUT BULUT', 'Erkek', 'BAR'),
(78, 'UĞUR CAN ÖZOĞAN', 'Erkek', 'BAR'),
(79, 'MEDINA KURMANBEKOVNA ERKINOVA', 'Kadın', 'BAR'),
(80, 'EMRAH ÜNEY', 'Erkek', 'BAR'),
(81, 'ERAY KEMİK', 'Erkek', 'BAR'),
(82, 'AITENIR KALDYBAEV', 'Erkek', 'BAR'),
(83, 'VASIILA ABAKIROVA', 'Kadın', 'BAR'),
(84, 'ALISHER MIRBEKOVICH SAPARBEKOV', 'Erkek', 'BAR'),
(85, 'DİLA SU DURSUNLAR', 'Kadın', 'BAR'),
(86, 'ERDAL ŞAHİN TEK', 'Erkek', 'BAR'),
(87, 'RECEP AYDINYURT', 'Erkek', 'BAR'),
(88, 'ZEYNEP KARAP', 'Kadın', 'BAR'),
(89, 'ENVER KUZÖREN', 'Erkek', 'BAR'),
(90, 'ŞENOL GÜZELYIL', 'Erkek', 'BAR'),
(91, 'YAĞMUR YILMAZ', 'Kadın', 'BAR'),
(92, 'KÜBRA TOPCU', 'Kadın', 'BAR'),
(93, 'AYŞE GÜL TOPCU', 'Kadın', 'BAR'),
(94, 'MELEK ALEYNA KAVUZ', 'Kadın', 'BAR'),
(95, 'CAHİT ÖZDEMİR', 'Erkek', 'ÇAMAŞIRHANE'),
(96, 'KADİR KÜÇÜKYAVUZ', 'Erkek', 'ÇAMAŞIRHANE'),
(97, 'ZAHİDE TEPELİ', 'Kadın', 'ÇAMAŞIRHANE'),
(98, 'AHMET KAYA', 'Erkek', 'ÇAMAŞIRHANE'),
(99, 'GULSUNAI TURGANBEKOVA', 'Kadın', 'ÇAMAŞIRHANE'),
(100, 'MEHMET VEYİS BALKAN', 'Erkek', 'ÇAMAŞIRHANE'),
(101, 'MESUT BOZKURT', 'Erkek', 'GENEL MÜDÜRLÜK'),
(102, 'MURAT ŞAHİN', 'Erkek', 'GENEL MÜDÜRLÜK'),
(103, 'ÖMER OSMAN DENİZ', 'Erkek', 'GENEL MÜDÜRLÜK'),
(104, 'ŞAHİN SÖZMEN', 'Erkek', 'GENEL MÜDÜRLÜK'),
(105, 'AHMET GÜLDAL', 'Erkek', 'GENEL MÜDÜRLÜK'),
(106, 'ÖZLEM KAYA', 'Kadın', 'GÜVENLİK'),
(107, 'İBRAHİM SEYYAR', 'Erkek', 'GÜVENLİK'),
(108, 'AHMET ÇAPAR', 'Erkek', 'GÜVENLİK'),
(109, 'EMİN KAYA', 'Erkek', 'GÜVENLİK'),
(110, 'MEHMET ORUÇ', 'Erkek', 'GÜVENLİK'),
(111, 'PINAR ÇELE', 'Kadın', 'GÜVENLİK'),
(112, 'YASİN EROL', 'Erkek', 'GÜVENLİK'),
(113, 'MELEK ÇAKIR', 'Kadın', 'GÜVENLİK'),
(114, 'AYŞE ÇELİK', 'Kadın', 'GÜVENLİK'),
(115, 'HATİCE DEMİRLİ', 'Kadın', 'GÜVENLİK'),
(116, 'MEHMET ARDIÇ', 'Erkek', 'GÜVENLİK'),
(117, 'BARIŞ AKTEPE', 'Erkek', 'GÜVENLİK'),
(118, 'ALİ SAİT BORUCU', 'Erkek', 'GÜVENLİK'),
(119, 'EMRE BAZILI', 'Erkek', 'GÜVENLİK'),
(120, 'RAMAZAN CAN NAMDAR', 'Erkek', 'GÜVENLİK'),
(121, 'TALHA EREN ÇAPAR', 'Erkek', 'GÜVENLİK'),
(122, 'BURAK ÇAKIR', 'Erkek', 'GÜVENLİK'),
(123, 'ALİ KARTAL', 'Erkek', 'GÜVENLİK'),
(124, 'YUNUS EMRE SÖNMEZ', 'Erkek', 'GÜVENLİK'),
(125, 'FURKAN DURU', 'Erkek', 'GÜVENLİK'),
(126, 'UĞUR RAMAZAN YILMAZ', 'Erkek', 'GÜVENLİK'),
(127, 'YUSUF MERT KILIÇ', 'Erkek', 'GÜVENLİK'),
(128, 'GÖKHAN ERDEM', 'Erkek', 'GÜVENLİK'),
(129, 'İBRAHİM DURAN', 'Erkek', 'GÜVENLİK'),
(130, 'ALİ DONTAŞ', 'Erkek', 'GÜVENLİK'),
(131, 'KEZİBAN YILMAZ', 'Kadın', 'HOUSEKEEPER'),
(132, 'MEHMET KAYA', 'Erkek', 'HOUSEKEEPER'),
(133, 'FATMA YILMAZ', 'Kadın', 'HOUSEKEEPER'),
(134, 'EMİNE ARAT', 'Kadın', 'HOUSEKEEPER'),
(135, 'HACER ERDOĞAN', 'Kadın', 'HOUSEKEEPER'),
(136, 'HAMİT ÇELİK', 'Erkek', 'HOUSEKEEPER'),
(137, 'FERHAT TAŞ', 'Erkek', 'HOUSEKEEPER'),
(138, 'YUSUF UMAN', 'Erkek', 'HOUSEKEEPER'),
(139, 'AYŞE ILGIN', 'Kadın', 'HOUSEKEEPER'),
(140, 'ZEYNEP BATUR', 'Kadın', 'HOUSEKEEPER'),
(141, 'SABİT ÖZTÜRK', 'Erkek', 'HOUSEKEEPER'),
(142, 'OSMAN ÇALTILI', 'Erkek', 'HOUSEKEEPER'),
(143, 'FARUK KOSBATAR', 'Erkek', 'HOUSEKEEPER'),
(144, 'RAMAZAN BAHTİYAR', 'Erkek', 'HOUSEKEEPER'),
(145, 'SELMA ABI', 'Kadın', 'HOUSEKEEPER'),
(146, 'SULTAN ABI', 'Kadın', 'HOUSEKEEPER'),
(147, 'NURAY SARI', 'Kadın', 'HOUSEKEEPER'),
(148, 'FİLİZ ARICAN', 'Kadın', 'HOUSEKEEPER'),
(149, 'REYHAN ÖZKAHRAMAN', 'Kadın', 'HOUSEKEEPER'),
(150, 'MÜSLÜM KILIÇ', 'Erkek', 'HOUSEKEEPER'),
(151, 'YUSUF ÖZCAN', 'Erkek', 'HOUSEKEEPER'),
(152, 'MERYEM ADALETLİ', 'Kadın', 'HOUSEKEEPER'),
(153, 'CANAN GÖKMEN', 'Kadın', 'HOUSEKEEPER'),
(154, 'ZEHRA ADALETLİ', 'Kadın', 'HOUSEKEEPER'),
(155, 'SEVGİ AKÇER', 'Kadın', 'HOUSEKEEPER'),
(156, 'ATİLLA DELEN', 'Erkek', 'HOUSEKEEPER'),
(157, 'İBRAHİM HALİL YÜCEEL', 'Erkek', 'HOUSEKEEPER'),
(158, 'MUSTAFA ADA', 'Erkek', 'HOUSEKEEPER'),
(159, 'ZÖHRE ŞİMŞEK', 'Kadın', 'HOUSEKEEPER'),
(160, 'ELİF ŞEN', 'Kadın', 'HOUSEKEEPER'),
(161, 'MEHMET KAYA', 'Erkek', 'HOUSEKEEPER'),
(162, 'YULIYA MUSSATAYEVA', 'Kadın', 'HOUSEKEEPER'),
(163, 'AIGUL BOTBAEVA', 'Kadın', 'HOUSEKEEPER'),
(164, 'AYŞE ÖZTAŞ', 'Kadın', 'HOUSEKEEPER'),
(165, 'SAULE BEKKHOZHINA', 'Kadın', 'HOUSEKEEPER'),
(166, 'NURIZA KENESHBEKOVNA MAMATBEKOVA', 'Kadın', 'HOUSEKEEPER'),
(167, 'MARIAM SIDIBE', 'Kadın', 'HOUSEKEEPER'),
(168, 'SELMAN İLTAŞ', 'Erkek', 'HOUSEKEEPER'),
(169, 'MEHMET NACİ İLTAŞ', 'Erkek', 'HOUSEKEEPER'),
(170, 'LEVENT KAPLAN', 'Erkek', 'HOUSEKEEPER'),
(171, 'HATİCE YILMAZ', 'Kadın', 'HOUSEKEEPER'),
(172, 'FARID ERSAEV', 'Erkek', 'HOUSEKEEPER'),
(173, 'FARUKH ERSAEV', 'Erkek', 'HOUSEKEEPER'),
(174, 'ABDUVOYIT AZIMBOEV', 'Erkek', 'HOUSEKEEPER'),
(175, 'MUZAFFER AKSOY', 'Erkek', 'HOUSEKEEPER'),
(176, 'GULBARRA ESANBOEVA', 'Kadın', 'HOUSEKEEPER'),
(177, 'HURİYE DEMİRTAŞ', 'Kadın', 'HOUSEKEEPER'),
(178, 'AINURA MUKASHOVA', 'Kadın', 'HOUSEKEEPER'),
(179, 'KARLYGACH BAETOVA', 'Kadın', 'HOUSEKEEPER'),
(180, 'AIDA TOITOEVA', 'Kadın', 'HOUSEKEEPER'),
(181, 'ZAMIRBEK BEISHENOV', 'Erkek', 'HOUSEKEEPER'),
(182, 'KASYMBEK MUKANOVIC KYDYRGYCHEV', 'Erkek', 'HOUSEKEEPER'),
(183, 'EMETULLAH ADALETLİ KARADON', 'Kadın', 'HOUSEKEEPER'),
(184, 'ASEL UZAKBAEVA', 'Kadın', 'HOUSEKEEPER'),
(185, 'YASİN ELGÜN', 'Erkek', 'HOUSEKEEPER'),
(186, 'BAKHTAY AKPANOV', 'Erkek', 'HOUSEKEEPER'),
(187, 'YERMEK KAZHYMUKAN', 'Erkek', 'HOUSEKEEPER'),
(188, 'FURKAN AKKAŞ', 'Erkek', 'HOUSEKEEPER'),
(189, 'ZIYODAKHON ATAMURADOVA', 'Kadın', 'HOUSEKEEPER'),
(190, 'CHYNARA KYSHTOBAEVA', 'Erkek', 'HOUSEKEEPER'),
(191, 'DARIKHA ASANBEKOVA', 'Erkek', 'HOUSEKEEPER'),
(192, 'ZHANYL ALYMBAEVNA SAKIEVA', 'Erkek', 'HOUSEKEEPER'),
(193, 'SANİYE SARI', 'Kadın', 'HOUSEKEEPER'),
(194, 'SHOLPAN ADIL', 'Kadın', 'HOUSEKEEPER'),
(195, 'ABDULLAH GÜNER', 'Erkek', 'HOUSEKEEPER'),
(196, 'CEMAL GÜMÜŞ', 'Erkek', 'HOUSEKEEPER'),
(197, 'NAMAT AZIZBEKOVICH ASKERKANOV', 'Erkek', 'HOUSEKEEPER'),
(198, 'KAINAZAR ANARKUL UULU', 'Erkek', 'HOUSEKEEPER'),
(199, 'MEHMET EMİN GÜNER', 'Erkek', 'HOUSEKEEPER'),
(200, 'SEYMEN ERALP', 'Erkek', 'HOUSEKEEPER'),
(201, 'MUHAMMED KAYRA TOPUZ', 'Erkek', 'HOUSEKEEPER'),
(202, 'BATIM SALİM KARABAĞ', 'Erkek', 'HOUSEKEEPER'),
(203, 'MERVE DÖKMECİ', 'Kadın', 'HALKLA İLİŞKİLER'),
(204, 'MAKHABAT ABDYBEKOV ABDYBEKOVA', 'Kadın', 'HALKLA İLİŞKİLER'),
(205, 'AINAZIK RAIMZHANOVA', 'Kadın', 'HALKLA İLİŞKİLER'),
(206, 'MELTEM ÇOBAN', 'Kadın', 'HALKLA İLİŞKİLER'),
(207, 'GİZEM KARABAĞLI', 'Kadın', 'HALKLA İLİŞKİLER'),
(208, 'ELAY ARICAN', 'Kadın', 'İNSAN KAYNAKLARI'),
(209, 'CİHAN SAĞIROĞLU', 'Erkek', 'İNSAN KAYNAKLARI'),
(210, 'ERKAN YÜZ', 'Erkek', 'İNSAN KAYNAKLARI'),
(211, 'ERKAN EYİNACAR', 'Erkek', 'İNSAN KAYNAKLARI'),
(212, 'ÖNCEL DERE', 'Erkek', 'İNSAN KAYNAKLARI'),
(213, 'HÜSEYİN GÜLEN', 'Erkek', 'İNSAN KAYNAKLARI'),
(214, 'İLAYDA DEMİR', 'Kadın', 'İNSAN KAYNAKLARI'),
(215, 'BÜLENT ÖZTÜRK', 'Erkek', 'İNSAN KAYNAKLARI'),
(216, 'ALİ KARAMAN', 'Erkek', 'İNSAN KAYNAKLARI'),
(217, 'HASAN ÇELİK', 'Erkek', 'LOJMAN'),
(218, 'TAYGUN ORHAN', 'Erkek', 'LOJMAN'),
(219, 'MAZLUM YILMAZ', 'Erkek', 'LOJMAN'),
(220, 'KENAN TEKDEMİR', 'Erkek', 'LOJMAN'),
(221, 'AHMET KOCA', 'Erkek', 'MUHASEBE'),
(222, 'FARUK SADIK', 'Erkek', 'MUHASEBE'),
(223, 'ŞEYMA ÖZTÜRK', 'Kadın', 'MUHASEBE'),
(224, 'ALEV ÇELİK', 'Kadın', 'MUHASEBE'),
(225, 'SEDAT TOPRAK', 'Erkek', 'MUHASEBE'),
(226, 'NURHAN TEREKLİ', 'Erkek', 'MUHASEBE'),
(227, 'İSMAİL COŞAR', 'Erkek', 'MUHASEBE'),
(228, 'İSRAFİL KÖYLÜ', 'Erkek', 'MUHASEBE'),
(229, 'LEVENT DEMİR', 'Erkek', 'MUHASEBE'),
(230, 'SAVAŞ TİMUÇİN DÖKMECİ', 'Erkek', 'MUTFAK'),
(231, 'ADEM BARIŞ', 'Erkek', 'MUTFAK'),
(232, 'MUSTAFA ŞİMŞEKOL', 'Erkek', 'MUTFAK'),
(233, 'HAMİT ÖZBOLAT', 'Erkek', 'MUTFAK'),
(234, 'NURİ KARACAN', 'Erkek', 'MUTFAK'),
(235, 'DÜZGÜN BOZKURT', 'Erkek', 'MUTFAK'),
(236, 'RECEP ARSLAN', 'Erkek', 'MUTFAK'),
(237, 'MUSTAFA SARI', 'Erkek', 'MUTFAK'),
(238, 'HÜSEYİN OKUR', 'Erkek', 'MUTFAK'),
(239, 'DENİZ SARICA', 'Erkek', 'MUTFAK'),
(240, 'MEVLÜT KAYA', 'Erkek', 'MUTFAK'),
(241, 'SİNAN MANULBOĞA', 'Erkek', 'MUTFAK'),
(242, 'AYŞE ERGİN', 'Kadın', 'MUTFAK'),
(243, 'İLKNUR YÜZ', 'Kadın', 'MUTFAK'),
(244, 'NURAY ÖZDEMİR', 'Kadın', 'MUTFAK'),
(245, 'ŞEMSEY AKTAN', 'Kadın', 'MUTFAK'),
(246, 'GÜLDALİ CESUR', 'Kadın', 'MUTFAK'),
(247, 'SEDAT KAPILAR', 'Erkek', 'MUTFAK'),
(248, 'ELİF DEMİR', 'Kadın', 'MUTFAK'),
(249, 'FİGEN SARI', 'Erkek', 'MUTFAK'),
(250, 'MAHSUN BATİHAN', 'Erkek', 'MUTFAK'),
(251, 'FERAH ÇELİK', 'Erkek', 'MUTFAK'),
(252, 'BAYRAM KAVUZ', 'Erkek', 'MUTFAK'),
(253, 'İCLAL KILINÇLI', 'Kadın', 'MUTFAK'),
(254, 'MUSTAFA AKGÜNDÜZ', 'Erkek', 'MUTFAK'),
(255, 'CEYLAN BULUT', 'Kadın', 'MUTFAK'),
(256, 'MUHAMMED KARATAŞ', 'Erkek', 'MUTFAK'),
(257, 'ÜMİT YAZIR', 'Erkek', 'MUTFAK'),
(258, 'ÖMER ŞEVİK', 'Erkek', 'MUTFAK'),
(259, 'MUSTAFA KESKİN', 'Erkek', 'MUTFAK'),
(260, 'METİN ÖNERYILDIZ', 'Erkek', 'MUTFAK'),
(261, 'ZAMAN ÖNERYILDIZ', 'Erkek', 'MUTFAK'),
(262, 'KADİR ÖNERYILDIZ', 'Erkek', 'MUTFAK'),
(263, 'YUSUF ÖNERYILDIZ', 'Erkek', 'MUTFAK'),
(264, 'BAYRAM BUNARBAŞI', 'Erkek', 'MUTFAK'),
(265, 'OSMAN KÜÇÜKADA', 'Erkek', 'MUTFAK'),
(266, 'HATİCE DİLEKÇİ', 'Kadın', 'MUTFAK'),
(267, 'HÜSEYİN KOCA', 'Erkek', 'MUTFAK'),
(268, 'BİRDANE BEYREK', 'Kadın', 'MUTFAK'),
(269, 'ŞADİYE ATALAY', 'Kadın', 'MUTFAK'),
(270, 'MESUT ŞAHİNBAŞ', 'Erkek', 'MUTFAK'),
(271, 'YUSUF AKYOL', 'Erkek', 'MUTFAK'),
(272, 'FİLİZ KARACAN', 'Kadın', 'MUTFAK'),
(273, 'ÜMİT AYDOĞAN', 'Erkek', 'MUTFAK'),
(274, 'AYŞE GÖRMEZ', 'Kadın', 'MUTFAK'),
(275, 'GÜLHAN ÇAKIR', 'Kadın', 'MUTFAK'),
(276, 'RAMAZAN TÜRKMEN', 'Erkek', 'MUTFAK'),
(277, 'NURETTİN ÖZDEMİR', 'Erkek', 'MUTFAK'),
(278, 'SEDAT AKYÜZ', 'Erkek', 'MUTFAK'),
(279, 'BİLAL TİLAVER', 'Erkek', 'MUTFAK'),
(280, 'YAKUP TANIR', 'Erkek', 'MUTFAK'),
(281, 'FATMA SARI', 'Kadın', 'MUTFAK'),
(282, 'ÇAĞLA YETGİN', 'Kadın', 'MUTFAK'),
(283, 'FURKAN KILIÇ', 'Erkek', 'MUTFAK'),
(284, 'BEKZAT MERGENBAYEV', 'Erkek', 'MUTFAK'),
(285, 'ERCAN DİNÇER', 'Erkek', 'MUTFAK'),
(286, 'MUNİSE BEYREK', 'Kadın', 'MUTFAK'),
(287, 'MEHMET ALİ DEMİR', 'Erkek', 'MUTFAK'),
(288, 'SERKAN KARİP', 'Erkek', 'MUTFAK'),
(289, 'EYYÜP ÇETREZ', 'Erkek', 'MUTFAK'),
(290, 'BERKAY UĞUR', 'Erkek', 'MUTFAK'),
(291, 'HAKAN ONAY', 'Erkek', 'MUTFAK'),
(292, 'ABDURRAHMAN TOPBAŞ', 'Erkek', 'MUTFAK'),
(293, 'TALHA KELEŞ', 'Erkek', 'MUTFAK'),
(294, 'HARUN CAN', 'Erkek', 'MUTFAK'),
(295, 'MEHMET YALÇIN', 'Erkek', 'MUTFAK'),
(296, 'DENİZ ASLAN', 'Erkek', 'MUTFAK'),
(297, 'EYÜP UĞURKAN', 'Erkek', 'MUTFAK'),
(298, 'SELCAN ÖZDEMİR', 'Kadın', 'MUTFAK'),
(299, 'MURATBEK ARYNALI UULU', 'Erkek', 'MUTFAK'),
(300, 'BAYRAM AKDAĞ', 'Erkek', 'MUTFAK'),
(301, 'MEDİNE BAKİ', 'Kadın', 'MUTFAK'),
(302, 'DAMLA BORUÇALAN', 'Kadın', 'MUTFAK'),
(303, 'KAAN AÇMALI', 'Erkek', 'MUTFAK'),
(304, 'YUSUF İSLAM SEZER', 'Erkek', 'MUTFAK'),
(305, 'YİĞİT ŞAHİN DEMİRCAN', 'Erkek', 'MUTFAK'),
(306, 'BUSE YÜZBAŞIOĞLU', 'Kadın', 'MUTFAK'),
(307, 'EYMEN CİNKAVUK', 'Erkek', 'MUTFAK'),
(308, 'BAHA İSMAİL AKBULUT', 'Erkek', 'MUTFAK'),
(309, 'ELA NUR TOPCU', 'Kadın', 'MUTFAK'),
(310, 'ALPEREN AKYOL', 'Erkek', 'MUTFAK'),
(311, 'BAHAR KILINÇ', 'Kadın', 'MUTFAK'),
(312, 'SERGEN YALÇIN GÜREL', 'Erkek', 'MUTFAK'),
(313, 'NURTEN KESERCİOĞLU', 'Kadın', 'ÖNBURO'),
(314, 'EBRU SÜALP KOÇAR', 'Kadın', 'ÖNBURO'),
(315, 'ABDULVAHAP ORÇAN', 'Erkek', 'ÖNBURO'),
(316, 'ALEKSANDRE ZAİTSEV', 'Erkek', 'ÖNBURO'),
(317, 'HÜSEYİN İRKEN', 'Erkek', 'ÖNBURO'),
(318, 'SALTANAT ZHAPENOVA', 'Kadın', 'ÖNBURO'),
(319, 'ONUR KAYA', 'Erkek', 'ÖNBURO'),
(320, 'BAKYT MAMYRBAEV', 'Erkek', 'ÖNBURO'),
(321, 'ALINA ZHANUZAKOVA', 'Kadın', 'ÖNBURO'),
(322, 'ELİFSU ÇAKMAK', 'Kadın', 'ÖNBURO'),
(323, 'FATİH ÖZNAMLI', 'Erkek', 'ÖNBURO'),
(324, 'ALVİDA BASİR', 'Kadın', 'ÖNBURO'),
(325, 'EREN DAYAN', 'Erkek', 'ÖNBURO'),
(326, 'BÜŞRA ERSEKMEN', 'Kadın', 'ÖNBURO'),
(327, 'TALHA AKYÜZ', 'Erkek', 'ÖNBURO'),
(328, 'ABDÜLKADİR KILIÇ', 'Erkek', 'ÖNBURO'),
(329, 'AHMET SÜLEYMAN ŞAHİN', 'Erkek', 'ÖNBURO'),
(330, 'ECE SUDE DEMİRAL', 'Kadın', 'ÖNBURO'),
(331, 'MUKARREM KULELİ', 'Erkek', 'RESTAURANT'),
(332, 'OKAY TEKGÖZ', 'Erkek', 'RESTAURANT'),
(333, 'MURAT ATEŞ', 'Erkek', 'RESTAURANT'),
(334, 'NEBİ BOZKURT', 'Erkek', 'RESTAURANT'),
(335, 'MUHAMMET BARAN DALMIŞ', 'Erkek', 'RESTAURANT'),
(336, 'GÜLÇİN DENİZCİ', 'Kadın', 'RESTAURANT'),
(337, 'ZÜLFİKAR KARTAL', 'Erkek', 'RESTAURANT'),
(338, 'RAMAZAN HANİFİ AKIN', 'Erkek', 'RESTAURANT'),
(339, 'ABDULKADİR GÜMÜŞ', 'Erkek', 'RESTAURANT'),
(340, 'EMRE KIR', 'Erkek', 'RESTAURANT'),
(341, 'CAFER OKUR', 'Erkek', 'RESTAURANT'),
(342, 'EMRAH TANER', 'Erkek', 'RESTAURANT'),
(343, 'FIRAT ÖMEROĞLU', 'Erkek', 'RESTAURANT'),
(344, 'ABDULLAH ÇETİN', 'Erkek', 'RESTAURANT'),
(345, 'HÜSEYİN MİHAİLOĞLU', 'Erkek', 'RESTAURANT'),
(346, 'MEERIM DUISHONBEK KYZY', 'Kadın', 'RESTAURANT'),
(347, 'ZHANYL KUBANYCHOVN ISAEVA', 'Kadın', 'RESTAURANT'),
(348, 'TUGOLBAI SOLTONBEK UULU', 'Erkek', 'RESTAURANT'),
(349, 'KAIRAT ALMAZBEK UULU', 'Erkek', 'RESTAURANT'),
(350, 'MEHMET ARICAN', 'Erkek', 'RESTAURANT'),
(351, 'DOĞAN BULUT', 'Erkek', 'RESTAURANT'),
(352, 'ESMA BULUT', 'Kadın', 'RESTAURANT'),
(353, 'AZAT ESEN', 'Erkek', 'RESTAURANT'),
(354, 'NURSALKYN KANALBEKOVA', 'Kadın', 'RESTAURANT'),
(355, 'NURMUKHAMED MAMAZHUSUP UULU', 'Erkek', 'RESTAURANT'),
(356, 'ADILET TURATBEK UULU', 'Erkek', 'RESTAURANT'),
(357, 'MELEK AK', 'Kadın', 'RESTAURANT'),
(358, 'MEERIM ARYKBAEVA', 'Kadın', 'RESTAURANT'),
(359, 'TURUSBEK SHARSHENOV', 'Erkek', 'RESTAURANT'),
(360, 'CHYNARKUL BEISHENBEKOVA', 'Kadın', 'RESTAURANT'),
(361, 'NAZGUL NAZAROVA', 'Kadın', 'RESTAURANT'),
(362, 'AIZADA TOKTOGULOVA', 'Kadın', 'RESTAURANT'),
(363, 'NAZIK TALGATOVNA TALGATOVA', 'Kadın', 'RESTAURANT'),
(364, 'GULBAKHIRA ZALKARBEK KYZY', 'Kadın', 'RESTAURANT'),
(365, 'YUNUS EMRE KAYA', 'Erkek', 'RESTAURANT'),
(366, 'ARSLAN ZAMIRBEK UULU', 'Erkek', 'RESTAURANT'),
(367, 'ERKEAIYM AMANBEK KYZY', 'Kadın', 'RESTAURANT'),
(368, 'BAISAL DZHANYBEKOVICH ISMAILOV', 'Erkek', 'RESTAURANT'),
(369, 'MEHMET AKİF KUŞTEPE', 'Erkek', 'RESTAURANT'),
(370, 'BURHAN ANLAR', 'Erkek', 'RESTAURANT'),
(371, 'YUSUF ÇİÇEK', 'Erkek', 'RESTAURANT'),
(372, 'MIKHRAN MARATOVICH BAZARBAEV', 'Erkek', 'RESTAURANT'),
(373, 'YUNUS TÜLAY', 'Erkek', 'RESTAURANT'),
(374, 'FURKAN ŞİMŞEK', 'Erkek', 'RESTAURANT'),
(375, 'SELİN SERRA KAYABAŞI', 'Kadın', 'RESTAURANT'),
(376, 'RUSLANA KARAGEZOVNA MURADOVA', 'Kadın', 'RESTAURANT'),
(377, 'ŞEFİKA YADİGAR TOPCU', 'Kadın', 'RESTAURANT'),
(378, 'SYIMYK NURLANOVICH TOKTOBEKOV', 'Erkek', 'RESTAURANT'),
(379, 'ARGEN BATYRBEKOV', 'Erkek', 'RESTAURANT'),
(380, 'HARUN BULUT', 'Erkek', 'RESTAURANT'),
(381, 'NURSULTAN BAKYT UULU', 'Erkek', 'RESTAURANT'),
(382, 'ALTYNAI SOLTONBEK KYZY', 'Kadın', 'RESTAURANT'),
(383, 'MUSTAFA İPEK', 'Erkek', 'RESTAURANT'),
(384, 'AHMET TURAN AKKAN', 'Erkek', 'RESTAURANT'),
(385, 'ASEMA ADAEVA', 'Kadın', 'RESTAURANT'),
(386, 'GULKAIYR AITYKULOVA', 'Kadın', 'RESTAURANT'),
(387, 'ZAMIRA AKMATALIEVA', 'Erkek', 'RESTAURANT'),
(388, 'AIDANA SHARSHENOVA', 'Erkek', 'RESTAURANT'),
(389, 'AIDANA MAKSATBEKOVNA ABDISAMIEVA', 'Kadın', 'RESTAURANT'),
(390, 'SERDAR DÜZGÜN', 'Erkek', 'RESTAURANT'),
(391, 'AIARU KERIMBEK', 'Kadın', 'RESTAURANT'),
(392, 'KAMILA SADULLAYEVA', 'Kadın', 'RESTAURANT'),
(393, 'SÜLEYMAN COMART', 'Erkek', 'RESTAURANT'),
(394, 'MEHMET YAŞLAK', 'Erkek', 'RESTAURANT'),
(395, 'NURULLAH EKER', 'Erkek', 'RESTAURANT'),
(396, 'RAMAZAN COŞKUN', 'Erkek', 'RESTAURANT'),
(397, 'HASAN BURAK OKKALI', 'Erkek', 'RESTAURANT'),
(398, 'ŞENGÜL USLU', 'Kadın', 'RESTAURANT'),
(399, 'ÇINAR CİYO BABAT', 'Erkek', 'RESTAURANT'),
(400, 'EZEL KARADAĞ', 'Kadın', 'RESTAURANT'),
(401, 'ESİLA AKBIYIK', 'Kadın', 'RESTAURANT'),
(402, 'YUSUF GÜLER', 'Erkek', 'RESTAURANT'),
(403, 'MEHMET ALİ YALÇIN', 'Erkek', 'RESTAURANT'),
(404, 'AZRA KUMCU', 'Kadın', 'RESTAURANT'),
(405, 'İLAYDA SEVGİLİ', 'Kadın', 'RESTAURANT'),
(406, 'ÇAĞAN CEYHANLI', 'Erkek', 'RESTAURANT'),
(407, 'GAMZE ÇAKAR', 'Kadın', 'SATIŞ&PAZARLAMA'),
(408, 'NAİLE ÇELİK', 'Kadın', 'SATIŞ&PAZARLAMA'),
(409, 'MUHYETTİN AYDIN', 'Erkek', 'STEWART'),
(410, 'NURETTİN GELDEÇ', 'Erkek', 'STEWART'),
(411, 'MUHAMMED GİDEN', 'Erkek', 'STEWART'),
(412, 'GÖNÜL ÇEVİK', 'Kadın', 'STEWART'),
(413, 'KÜBRA ÇEVİK', 'Kadın', 'STEWART'),
(414, 'MEHTAP GÖÇER', 'Kadın', 'STEWART'),
(415, 'ÖZCAN ÇAPACI', 'Erkek', 'STEWART'),
(416, 'CESUR TURAN', 'Erkek', 'STEWART'),
(417, 'SHUKURBEK KHOLMIRZAEV', 'Erkek', 'STEWART'),
(418, 'MAISALBEK BATYRBEK UULU', 'Erkek', 'STEWART'),
(419, 'ABDEKHELIL UMAROV', 'Erkek', 'STEWART'),
(420, 'JAVOKHIRKHUJA YUNUSKHUJAEV', 'Erkek', 'STEWART'),
(421, 'MEHMET ÖZCAN GÖKALP', 'Erkek', 'STEWART'),
(422, 'ILKHOMJON ASKAROV', 'Erkek', 'STEWART'),
(423, 'ASADBEK ALIJONOV', 'Erkek', 'STEWART'),
(424, 'MÜSLÜM GÜRBÜZ', 'Erkek', 'STEWART'),
(425, 'ABDULLA MATKASIMOV', 'Erkek', 'STEWART'),
(426, 'NEMATULLO KHOSHIMOV', 'Erkek', 'STEWART'),
(427, 'HEDİYE DÖNMEZ', 'Kadın', 'STEWART'),
(428, 'HASAN SARI', 'Erkek', 'STEWART'),
(429, 'MUKADDER ÖZTÜRK', 'Kadın', 'STEWART'),
(430, 'ZHANAR MAKHIMOVA', 'Kadın', 'STEWART'),
(431, 'ALİ YAŞAR', 'Erkek', 'STEWART'),
(432, 'HASAN EFETÜRK', 'Erkek', 'STEWART'),
(433, 'RAKHMON ABDUJALILOV', 'Erkek', 'STEWART'),
(434, 'VURAL SAFRAN', 'Erkek', 'STEWART'),
(435, 'GÜL MURADOVA', 'Kadın', 'STEWART'),
(436, 'GÜLSÜM ÖZBULUT', 'Kadın', 'TEKNİK'),
(437, 'SALİH AKYILDIZ', 'Erkek', 'TEKNİK'),
(438, 'HASAN BİLGİÇ', 'Erkek', 'TEKNİK'),
(439, 'AFŞIN AVCU', 'Erkek', 'TEKNİK'),
(440, 'DİNÇER AKÇER', 'Erkek', 'TEKNİK'),
(441, 'VEHPİ KAHRAMAN', 'Erkek', 'TEKNİK'),
(442, 'GIYASETTİN ARSLAN', 'Erkek', 'TEKNİK'),
(443, 'SALİH ALKAYA', 'Erkek', 'TEKNİK'),
(444, 'KAMİL ŞEKEROĞLU', 'Erkek', 'TEKNİK'),
(445, 'VEYSEL DÜZGÜN', 'Erkek', 'TEKNİK'),
(446, 'ERKAN DENİZ', 'Erkek', 'TEKNİK'),
(447, 'SABİT AÇIKBAŞ', 'Erkek', 'TEKNİK'),
(448, 'FIRAT GÜNGÖR KÖMÜRCÜ', 'Erkek', 'TEKNİK'),
(449, 'YUSUF TEPELİ', 'Erkek', 'TEKNİK'),
(450, 'İLHAN İŞNAS', 'Erkek', 'TEKNİK'),
(451, 'KAHRAMAN İNAL', 'Erkek', 'TEKNİK'),
(452, 'OĞUZHAN KIRBAÇ', 'Erkek', 'TEKNİK'),
(453, 'AHMET ERENLER', 'Erkek', 'TEKNİK'),
(454, 'SEDAT BİLDİRCİN', 'Erkek', 'TEKNİK'),
(455, 'TAHİR KOCA', 'Erkek', 'TEKNİK'),
(456, 'ECEVİT YILMAZ', 'Erkek', 'TEKNİK'),
(457, 'SATILMIŞ AVCI', 'Erkek', 'TEKNİK'),
(458, 'YUSUF KARAÇOBAN', 'Erkek', 'TEKNİK'),
(459, 'MUZAFFER İLKER ERKUT', 'Erkek', 'TEKNİK'),
(460, 'HASAN İNCE', 'Erkek', 'TEKNİK'),
(461, 'ÖMÜRCAN İNCE', 'Erkek', 'TEKNİK'),
(462, 'Ömer Osman DENİZ', 'Erkek', 'Bilgi İşlem');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Saved export templates';

--
-- Tablo döküm verisi `pma__export_templates`
--

INSERT INTO `pma__export_templates` (`id`, `username`, `export_type`, `template_name`, `template_data`) VALUES
(0, 'if0_42172821', 'database', 'lojman sql', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"structure_or_data_forced\":\"0\",\"table_select[]\":[\"employees\",\"pma__bookmark\",\"pma__central_columns\",\"pma__column_info\",\"pma__designer_settings\",\"pma__export_templates\",\"pma__favorite\",\"pma__history\",\"pma__navigationhiding\",\"pma__pdf_pages\",\"pma__recent\",\"pma__relation\",\"pma__savedsearches\",\"pma__table_coords\",\"pma__table_info\",\"pma__table_uiprefs\",\"pma__tracking\",\"pma__userconfig\",\"pma__usergroups\",\"pma__users\",\"rooms\",\"room_assignments\",\"users\"],\"table_structure[]\":[\"employees\",\"pma__bookmark\",\"pma__central_columns\",\"pma__column_info\",\"pma__designer_settings\",\"pma__export_templates\",\"pma__favorite\",\"pma__history\",\"pma__navigationhiding\",\"pma__pdf_pages\",\"pma__recent\",\"pma__relation\",\"pma__savedsearches\",\"pma__table_coords\",\"pma__table_info\",\"pma__table_uiprefs\",\"pma__tracking\",\"pma__userconfig\",\"pma__usergroups\",\"pma__users\",\"rooms\",\"room_assignments\",\"users\"],\"table_data[]\":[\"employees\",\"pma__bookmark\",\"pma__central_columns\",\"pma__column_info\",\"pma__designer_settings\",\"pma__export_templates\",\"pma__favorite\",\"pma__history\",\"pma__navigationhiding\",\"pma__pdf_pages\",\"pma__recent\",\"pma__relation\",\"pma__savedsearches\",\"pma__table_coords\",\"pma__table_info\",\"pma__table_uiprefs\",\"pma__tracking\",\"pma__userconfig\",\"pma__usergroups\",\"pma__users\",\"rooms\",\"room_assignments\",\"users\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@DATABASE@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"@TABLE@ tablosunun yapÄ±sÄ±\",\"latex_structure_continued_caption\":\"@TABLE@ tablosunun yapÄ±sÄ± (devam eden)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"@TABLE@ tablosunun iÃ§eriÄŸi\",\"latex_data_continued_caption\":\"@TABLE@ tablosunun iÃ§eriÄŸi (devam eden)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"structure_and_data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"structure_and_data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_procedure_function\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"xml_structure_or_data\":\"data\",\"xml_export_events\":\"something\",\"xml_export_functions\":\"something\",\"xml_export_procedures\":\"something\",\"xml_export_tables\":\"something\",\"xml_export_triggers\":\"something\",\"xml_export_views\":\"something\",\"xml_export_contents\":\"something\",\"yaml_structure_or_data\":\"data\",\"\":null,\"lock_tables\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"csv_columns\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_create_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}'),
(0, 'if0_42172821', 'database', 'lojman', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"structure_or_data_forced\":\"0\",\"table_select[]\":[\"employees\",\"pma__bookmark\",\"pma__central_columns\",\"pma__column_info\",\"pma__designer_settings\",\"pma__export_templates\",\"pma__favorite\",\"pma__history\",\"pma__navigationhiding\",\"pma__pdf_pages\",\"pma__recent\",\"pma__relation\",\"pma__savedsearches\",\"pma__table_coords\",\"pma__table_info\",\"pma__table_uiprefs\",\"pma__tracking\",\"pma__userconfig\",\"pma__usergroups\",\"pma__users\",\"rooms\",\"room_assignments\",\"users\"],\"table_structure[]\":[\"employees\",\"pma__bookmark\",\"pma__central_columns\",\"pma__column_info\",\"pma__designer_settings\",\"pma__export_templates\",\"pma__favorite\",\"pma__history\",\"pma__navigationhiding\",\"pma__pdf_pages\",\"pma__recent\",\"pma__relation\",\"pma__savedsearches\",\"pma__table_coords\",\"pma__table_info\",\"pma__table_uiprefs\",\"pma__tracking\",\"pma__userconfig\",\"pma__usergroups\",\"pma__users\",\"rooms\",\"room_assignments\",\"users\"],\"table_data[]\":[\"employees\",\"pma__bookmark\",\"pma__central_columns\",\"pma__column_info\",\"pma__designer_settings\",\"pma__export_templates\",\"pma__favorite\",\"pma__history\",\"pma__navigationhiding\",\"pma__pdf_pages\",\"pma__recent\",\"pma__relation\",\"pma__savedsearches\",\"pma__table_coords\",\"pma__table_info\",\"pma__table_uiprefs\",\"pma__tracking\",\"pma__userconfig\",\"pma__usergroups\",\"pma__users\",\"rooms\",\"room_assignments\",\"users\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@DATABASE@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"@TABLE@ tablosunun yapÄ±sÄ±\",\"latex_structure_continued_caption\":\"@TABLE@ tablosunun yapÄ±sÄ± (devam eden)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"@TABLE@ tablosunun iÃ§eriÄŸi\",\"latex_data_continued_caption\":\"@TABLE@ tablosunun iÃ§eriÄŸi (devam eden)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"structure_and_data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"structure_and_data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_procedure_function\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"xml_structure_or_data\":\"data\",\"xml_export_events\":\"something\",\"xml_export_functions\":\"something\",\"xml_export_procedures\":\"something\",\"xml_export_tables\":\"something\",\"xml_export_triggers\":\"something\",\"xml_export_views\":\"something\",\"xml_export_contents\":\"something\",\"yaml_structure_or_data\":\"data\",\"\":null,\"lock_tables\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"csv_columns\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_create_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Recently accessed tables';

--
-- Tablo döküm verisi `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"lojman\",\"table\":\"room_assignments\"},{\"db\":\"lojman\",\"table\":\"rooms\"},{\"db\":\"lojman\",\"table\":\"employees\"}]'),
('if0_42172821', '[{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__users\"}]'),
('if0_42172821', '[{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__userconfig\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__users\"}]'),
('if0_42172821', '[{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__usergroups\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__userconfig\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__users\"}]'),
('if0_42172821', '[{\"db\":\"if0_42172821_lojman\",\"table\":\"employees\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__usergroups\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__userconfig\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__users\"}]'),
('if0_42172821', '[{\"db\":\"if0_42172821_lojman\",\"table\":\"users\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"employees\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__usergroups\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__userconfig\"},{\"db\":\"if0_42172821_lojman\",\"table\":\"pma__users\"}]');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Tablo döküm verisi `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-06-13 06:04:25', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"tr\"}'),
('if0_42172821', '2026-06-21 18:58:24', '{\"lang\":\"tr\",\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin COMMENT='Users and their assignments to user groups';

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `floor_name` varchar(50) DEFAULT NULL,
  `room_no` varchar(20) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `rooms`
--

INSERT INTO `rooms` (`id`, `floor_name`, `room_no`, `capacity`, `gender`) VALUES
(1, 'Giriş', '101', 4, 'Erkek'),
(2, 'Giriş', '102', 4, 'Erkek'),
(3, '1.Kat', '201', 4, 'Erkek'),
(4, '2.Kat', '301', 4, 'Erkek'),
(5, 'Kadın', 'K101', 4, 'Kadın'),
(6, 'floor_name', 'room_no', 0, 'gender'),
(7, '1.Kat', '101', 0, ''),
(8, '1.Kat', '102', 0, ''),
(9, '1.Kat', '103', 0, ''),
(10, '1.Kat', '104', 0, ''),
(11, '1.Kat', '105', 0, ''),
(12, '1.Kat', '106', 0, ''),
(13, '1.Kat', '107', 0, ''),
(14, '1.Kat', '108', 0, ''),
(15, '1.Kat', '109', 0, ''),
(16, '1.Kat', '110', 0, ''),
(17, '1.Kat', '111', 0, ''),
(18, '1.Kat', '112', 0, ''),
(19, '1.Kat', '113', 0, ''),
(20, '1.Kat', '114', 0, ''),
(21, '1.Kat', '115', 0, ''),
(22, '1.Kat', '116', 0, ''),
(23, '1.Kat', '117', 0, ''),
(24, '1.Kat', '118', 0, ''),
(25, '1.Kat', '119', 0, ''),
(26, '1.Kat', '120', 0, ''),
(27, '1.Kat', '121', 0, ''),
(28, '1.Kat', '122', 0, ''),
(29, '1.Kat', '123', 0, ''),
(30, '1.Kat', '124', 0, ''),
(31, '1.Kat', '125', 0, ''),
(32, '1.Kat', '126', 0, ''),
(33, '1.Kat', '127', 0, ''),
(34, '1.Kat', '128', 0, ''),
(35, '2.Kat', '201', 0, ''),
(36, '2.Kat', '202', 0, ''),
(37, '2.Kat', '203', 0, ''),
(38, '2.Kat', '204', 0, ''),
(39, '2.Kat', '205', 0, ''),
(40, '2.Kat', '206', 0, ''),
(41, '2.Kat', '207', 0, ''),
(42, '2.Kat', '208', 0, ''),
(43, '2.Kat', '209', 0, ''),
(44, '2.Kat', '210', 0, ''),
(45, '2.Kat', '211', 0, ''),
(46, '2.Kat', '212', 0, ''),
(47, '2.Kat', '213', 0, ''),
(48, '2.Kat', '214', 0, ''),
(49, '2.Kat', '215', 0, ''),
(50, '2.Kat', '216', 0, ''),
(51, '2.Kat', '217', 0, ''),
(52, '2.Kat', '218', 0, ''),
(53, '2.Kat', '219', 0, ''),
(54, '2.Kat', '220', 0, ''),
(55, '2.Kat', '221', 0, ''),
(56, '2.Kat', '222', 0, ''),
(57, '2.Kat', '223', 0, ''),
(58, '2.Kat', '224', 0, ''),
(59, '2.Kat', '225', 0, ''),
(60, '2.Kat', '226', 0, ''),
(61, '2.Kat', '227', 0, ''),
(62, '2.Kat', '228', 0, ''),
(63, '2.Kat', '229', 0, ''),
(64, '2.Kat', '230', 0, ''),
(65, '2.Kat', '231', 0, ''),
(66, '2.Kat', '232', 0, ''),
(67, '2.Kat', '233', 0, ''),
(68, '2.Kat', '234', 0, ''),
(69, '2.Kat', '235', 0, ''),
(70, '2.Kat', '236', 0, ''),
(71, '2.Kat', '237', 0, ''),
(72, '2.Kat', '238', 0, ''),
(73, '3.Kat', '301', 0, ''),
(74, '3.Kat', '302', 0, ''),
(75, '3.Kat', '303', 0, ''),
(76, '3.Kat', '304', 0, ''),
(77, '3.Kat', '305', 0, ''),
(78, '3.Kat', '306', 0, ''),
(79, '3.Kat', '307', 0, ''),
(80, '3.Kat', '308', 0, ''),
(81, '3.Kat', '309', 0, ''),
(82, '3.Kat', '310', 0, ''),
(83, '3.Kat', '311', 0, ''),
(84, '3.Kat', '312', 0, ''),
(85, '3.Kat', '313', 0, ''),
(86, '3.Kat', '314', 0, ''),
(87, '3.Kat', '315', 0, ''),
(88, '3.Kat', '316', 0, ''),
(89, '3.Kat', '317', 0, ''),
(90, '3.Kat', '318', 0, ''),
(91, '3.Kat', '319', 0, ''),
(92, '3.Kat', '320', 0, ''),
(93, '3.Kat', '321', 0, ''),
(94, '3.Kat', '322', 0, ''),
(95, '3.Kat', '323', 0, ''),
(96, '3.Kat', '324', 0, ''),
(97, '3.Kat', '325', 0, ''),
(98, '3.Kat', '326', 0, ''),
(99, '3.Kat', '327', 0, ''),
(100, '3.Kat', '328', 0, ''),
(101, '3.Kat', '329', 0, ''),
(102, '3.Kat', '330', 0, ''),
(103, '3.Kat', '331', 0, ''),
(104, '3.Kat', '332', 0, ''),
(105, '3.Kat', '333', 0, ''),
(106, '3.Kat', '334', 0, ''),
(107, '3.Kat', '335', 0, ''),
(108, '3.Kat', '336', 0, ''),
(109, '3.Kat', '337', 0, ''),
(110, '3.Kat', '338', 0, ''),
(111, 'KIZ BLOK', '401', 0, ''),
(112, 'KIZ BLOK', '402', 0, ''),
(113, 'KIZ BLOK', '403', 0, ''),
(114, 'KIZ BLOK', '404', 0, ''),
(115, 'KIZ BLOK', '405', 0, ''),
(116, 'KIZ BLOK', '406', 0, ''),
(117, 'KIZ BLOK', '407', 0, ''),
(118, 'KIZ BLOK', '408', 0, ''),
(119, 'KIZ BLOK', '409', 0, ''),
(120, 'KIZ BLOK', '410', 0, ''),
(121, 'KIZ BLOK', '411', 0, ''),
(122, 'KIZ BLOK', '412', 0, ''),
(123, 'KIZ BLOK', '413', 0, ''),
(124, 'KIZ BLOK', '414', 0, ''),
(125, 'KIZ BLOK', '415', 0, ''),
(126, 'KIZ BLOK', '416', 0, ''),
(127, 'KIZ BLOK', '417', 0, ''),
(128, 'KIZ BLOK', '418', 0, ''),
(129, 'KIZ BLOK', '419', 0, ''),
(130, 'KIZ BLOK', '420', 0, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `room_assignments`
--

CREATE TABLE `room_assignments` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `room_assignments`
--

INSERT INTO `room_assignments` (`id`, `employee_id`, `room_id`) VALUES
(2, 2, 2),
(6, 4, 5),
(8, 1, 1),
(10, 5, 101),
(12, 93, 1),
(13, 6, 1),
(14, 7, 3),
(15, 8, 2),
(16, 3, 29),
(17, 9, 1),
(20, 439, 8),
(23, 108, 8),
(25, 328, 8),
(26, 344, 7),
(28, 339, 9),
(29, 425, 9),
(30, 72, 10),
(33, 315, 9),
(34, 174, 7),
(37, 356, 10),
(38, 223, 13),
(39, 103, 13);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '123456', 'admin'),
(2, 'IK', '123456', 'IK'),
(3, 'LOJMAN', '123', 'lojman');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Tablo için indeksler `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Tablo için indeksler `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `room_assignments`
--
ALTER TABLE `room_assignments`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=463;

--
-- Tablo için AUTO_INCREMENT değeri `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- Tablo için AUTO_INCREMENT değeri `room_assignments`
--
ALTER TABLE `room_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
