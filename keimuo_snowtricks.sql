-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  mar. 12 nov. 2019 à 06:45
-- Version du serveur :  10.2.27-MariaDB
-- Version de PHP :  7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `keimuo_snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_date` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `trick_id`, `content`, `add_date`, `user_id`) VALUES
(110, 66, 'Amazing trick !', '2019-11-12 06:37:18', 49);

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190907162432', '2019-09-07 16:25:00'),
('20190910090646', '2019-09-10 09:07:45'),
('20190914124504', '2019-09-14 12:45:45'),
('20190920155958', '2019-09-20 16:02:47'),
('20190921222657', '2019-09-21 22:27:26'),
('20190921231012', '2019-09-21 23:10:34'),
('20190922003536', '2019-09-22 00:35:54'),
('20190924004015', '2019-09-24 00:40:34'),
('20190924011922', '2019-09-24 01:19:38'),
('20190924013956', '2019-09-24 01:40:09'),
('20190924030637', '2019-09-24 03:07:09'),
('20190929151057', '2019-09-29 15:11:25'),
('20190929213213', '2019-09-29 21:32:42'),
('20191008001907', '2019-10-08 00:20:41'),
('20191018212907', '2019-10-18 21:29:50'),
('20191105004949', '2019-11-05 00:50:16');

-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

CREATE TABLE `picture` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `picture`
--

INSERT INTO `picture` (`id`, `trick_id`, `name`, `statut`) VALUES
(75, 65, '5dca3d5fc19b5.jpg', 'main'),
(76, 66, '5dca3db7e4287.jpg', 'main'),
(77, 67, '5dca3e7416d50.jpg', 'main'),
(78, 68, '5dca3ef5dc441.jpg', 'main'),
(79, 69, '5dca3f96bacd9.jpg', 'main'),
(80, 70, '5dca40b66a7cb.jpg', 'main'),
(81, 71, '5dca415bcea08.jpg', 'main'),
(82, 72, '5dca41c1f262b.jpg', 'main'),
(83, 73, '5dca4200751cb.jpeg', 'main'),
(84, 74, '5dca423a3d320.jpg', 'main'),
(85, 66, '5dca435fc4aee.jpg', 'normal'),
(86, 66, '5dca437303d18.jpg', 'normal');

-- --------------------------------------------------------

--
-- Structure de la table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profile`
--

INSERT INTO `profile` (`id`, `image_name`, `updated_at`, `user_id`) VALUES
(25, NULL, '2019-11-12 06:33:49', 49);

-- --------------------------------------------------------

--
-- Structure de la table `trick`
--

CREATE TABLE `trick` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_post` datetime NOT NULL,
  `date_update` datetime DEFAULT NULL,
  `main_picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick`
--

INSERT INTO `trick` (`id`, `name`, `content`, `category`, `date_post`, `date_update`, `main_picture`, `slug`) VALUES
(65, 'Shifty', 'An aerial trick in which a snowboarder twists their body in order to shift or rotate their board about 90° from its normal position beneath them, and then returns the board to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.', 'group 1', '2019-11-12 05:54:39', '2019-11-12 05:54:53', '5dca3d5fc19b5.jpg', 'shifty'),
(66, 'Bloody Dracula', 'A trick in which the rider grabs the tail of the board with both hands. The rear hand grabs the board as it would do it during a regular tail-grab but the front hand blindly reaches for the board behind the riders back', 'group 2', '2019-11-12 05:55:40', '2019-11-12 06:30:38', '5dca3db7e4287.jpg', 'bloody-dracula'),
(67, 'Crail', 'The rear hand grabs the toe edge in front of the front foot while the rear leg is boned. Alternatively, some consider any rear handed grab in front of the front foot on the toeside edge a crail grab, classifying a grab to the nose a \"nose crail\" or \"real crail\".', 'group 2', '2019-11-12 05:56:22', '2019-11-12 05:56:25', '5dca3e7416d50.jpg', 'crail'),
(68, 'Cross-rocket', 'Advanced variation of a Rocket Air, where the arms are crossed in order to grab opposite sides of the nose of the board, while the rear leg is boned straight and the front leg is tucked up.', 'group 2', '2019-11-12 05:56:43', '2019-11-12 05:57:04', '5dca3ef5dc441.jpg', 'cross-rocket'),
(69, 'Spins', 'Spins are typically performed in 180° increments due to the nature of the obstacles on which they are performed. Even in cases where spins are performed on unconventional obstacles, the rotation is regarded as the nearest increment of 180°, and can be identified by the direction of approach and landing (regular and switch). A spin attempted from a jump to a rail is the only time a spin can be referred to in a 90-degree increment, examples: 270 (between a 180 and 360-degree spin) or 450 (between a 360 and 540-degree spin). These spins can be frontside, backside, cab, or switch-backside just like any other spins. In April 2015 British snowboarder and Winter Olympic medallist Billy Morgan demonstrated the world\'s first quadruple cork 1800, the biggest spin ever.\r\n\r\nThe term \"Cab\" in snowboarding generally refers to any switch-frontside spin (no matter what the amount of rotation) on any feature (halfpipe, jumps, rails, boxes). For example, a \"switch-frontside 1080 double cork\" off a jump would be referred to as a \"cab 1080 double cork\". The term was originally only applied to a switch-frontside 360 in a halfpipe in which a rider would take off a wall switch, spin 360 degrees frontside, and land on their comfortable stance (regular/goofy). Therefore, the term Cab only applied to tricks in the halfpipe in which rotations were in full 360 increments, such as a \"Cab 360\" or \"Cab 720.\" For example, since a switch-frontside 540 would land a rider in the same switch position they took off from in the halfpipe, it was not referred to as a \"Cab 540\" because the rider did not take off switch, spin frontside, and land in their comfortable stance.\r\n\r\nA Half-Cab is a switch-frontside 180 spin.\r\n\r\nAn alley-oop is a spin performed in a halfpipe or quarterpipe in which the spin is rotated in the opposite direction of the air. For example, performing a frontside rotation on the backside wall of a halfpipe, or spinning clockwise while traveling right-to-left through the air on a quarterpipe would mean the spin was alley-oop.\r\n\r\nHard Way: A term used when spinning onto a feature or off a jump using your opposite edge to start the direction of your spin. Example- If a regular rider was to spin Hard Way front side 270 onto rail, they would start that spin off their toe side edge. That would make the trick a Hard Way front side 270. Opposite of the traditional front side rotation starting with your heel edge. Same applies to goofy riders.', 'group 3', '2019-11-12 05:59:33', '2019-11-12 05:59:38', '5dca3f96bacd9.jpg', 'spins'),
(70, 'Backside Misty', 'After a rider learns the basic backside 540 off the toes, the Misty Flip can be an easy next progression step. Misty Flip is quite different than the backside rodeo, because instead of corking over the heel edge with a back flip motion, the Misty corks off the toe edge specifically and has more of a Front Flip in the beginning of the trick, followed by a side flip coming out to the landing.', 'group 3', '2019-11-12 06:00:13', NULL, '5dca40b66a7cb.jpg', 'backside-misty'),
(71, 'Chicane', 'A chicane is a rarely done trick that involves doing a frontside 180 with a front flip on the X Axis. Opposite of the 90 roll, the chicane is frontside 90, tuck front flip, 90 degrees more to land switch, or vice versa.', 'group 3', '2019-11-12 06:00:35', NULL, '5dca415bcea08.jpg', 'chicane'),
(72, 'Frontside Rodeo', 'The basic frontside rodeo is all together a 540. It essentially falls into a grey area between an off axis frontside 540 and a frontside 180 with a back flip blended into it. The grab choice and different line and pop factors can make it more flipy or more of an off-axis spin. Frontside rodeo can be done off the heels or toes and with a little more spin on the Z Axis can go to 720 or 900. It is possible to do it to a 1080 but, if there is too much flip in the spin, it can be hard not to over flip when going past 720 and 900. The bigger the Z Axis spin, the later the inverted part of the rotation should be. Gaining control on big spin rodeos, may lead to a double cork or a second flip rotation in the spin, if the rider has developed a comfort level with double flips on the tramp or other gymnastic environment.;Rodeo flip; frontside rodeo: A frontward-flipping frontside spin done off the toe-edge. Most commonly performed with a 540° rotation, but also performed as a 720°, 900°, etc..', 'group 3', '2019-11-12 06:00:56', NULL, '5dca41c1f262b.jpg', 'frontside-rodeo'),
(73, 'Boardslide', 'A slide performed where the riders leading foot passes over the rail on approach, with their snowboard traveling perpendicular along the rail or other obstacle.[1] When performing a frontside boardslide, the snowboarder is facing uphill. When performing a backside boardslide, a snowboarder is facing downhill. This is often confusing to new riders learning the trick because with a frontside boardslide you are moving backward and with a backside boardslide you are moving forward.', 'group 4', '2019-11-12 06:01:38', NULL, '5dca4200751cb.jpeg', 'boardslide'),
(74, 'The Gutterball', 'The Gutterball is a one footed (front foot is strapped in and the rear foot is unstrapped ) front boardslide with a backhanded seatbelt nose grab, resembling the body position that someone would have after releasing a bowling ball down a bowling ally. This trick was invented and named by Jeremy Cameron which won him a first place in the Morrow Snowboards \"FAME WAR\" Best Trick contest in 2009.', 'group 4', '2019-11-12 06:02:02', NULL, '5dca423a3d320.jpg', 'the-gutterball');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `reset_token`, `roles`) VALUES
(49, 'Admin', 'admin@snowtricks.keimuo.com', '$argon2i$v=19$m=65536,t=4,p=1$dkhodTF0cXhXemV0T3lOMw$HYsf/cbQ2E3LiwaWmmv2WSodkp+k05xrSK2E740GCC8', NULL, '[\"ROLE_ADMIN\"]');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `trick_id`, `url`) VALUES
(5, 65, 'https://www.youtube.com/embed/ciXpKu1iaX4'),
(6, 66, 'https://www.youtube.com/embed/UU9iKINvlyU'),
(7, 67, 'https://www.youtube.com/embed/eTx2uVcbLzM'),
(8, 68, 'https://www.youtube.com/embed/tblx6cE-bkE'),
(9, 69, 'https://www.youtube.com/embed/_2TkKJ6euDc'),
(10, 70, 'https://www.youtube.com/embed/DJOuDncHnkA');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CB281BE2E` (`trick_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_16DB4F89B281BE2E` (`trick_id`);

--
-- Index pour la table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8157AA0FA76ED395` (`user_id`);

--
-- Index pour la table `trick`
--
ALTER TABLE `trick`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D8F0A91E5E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_D8F0A91E989D9B62` (`slug`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2CB281BE2E` (`trick_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT pour la table `picture`
--
ALTER TABLE `picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `trick`
--
ALTER TABLE `trick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `picture`
--
ALTER TABLE `picture`
  ADD CONSTRAINT `FK_16DB4F89B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `FK_8157AA0FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
