-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 29 mars 2024 à 08:22
-- Version du serveur : 5.7.24
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api-bilmo`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `siret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `web_site` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `siret`, `business`, `web_site`, `legal_status`, `name`) VALUES
(131, '13574493400000', 'Arnaud S.A.S.', 'Arnaud S.A.S..fr', 'SASU', 'Lesage'),
(132, '69148825800000', 'Le Gall', 'Le Gall.org', 'SASU', 'Baudry'),
(133, '21366530500000', 'Mahe Gros SA', 'Mahe Gros SA.fr', 'SASU', 'Camus'),
(134, '75882774700000', 'Roger', 'Roger.com', 'EIRL', 'Normand'),
(135, '12315344000000', 'Merle', 'Merle.com', 'EIRL', 'Noel'),
(136, '60919600300000', 'Masson', 'Masson.net', 'EIRL', 'Gillet'),
(137, '16386815700000', 'Klein SAS', 'Klein SAS.fr', 'SARL', 'Marchand'),
(138, '34673387600000', 'Mallet SA', 'Mallet SA.net', 'SARL', 'Riviere'),
(139, '45382678800000', 'Martins Techer S.A.S.', 'Martins Techer S.A.S..com', 'EIRL', 'Regnier'),
(140, '84071524000000', 'Michel Robin S.A.R.L.', 'Michel Robin S.A.R.L..com', 'EIRL', 'Gaillard'),
(141, '60381836500000', 'free', 'free.fr', 'EI', 'Maillard'),
(142, '10521243700000', 'Petitjean', 'Petitjean.org', 'EURL', 'Ramos'),
(143, '75115145900000', 'Colas', 'Colas.fr', 'EIRL', 'Boucher'),
(144, '29270211000000', 'Thibault', 'Thibault.net', 'SARL', 'Leleu'),
(145, '61349521000000', 'Monnier', 'Monnier.fr', 'EURL', 'Hoareau'),
(146, '74696270700000', 'Becker Noel et Fils', 'Becker Noel et Fils.fr', 'EI', 'Marty'),
(147, '57969499600000', 'Jacquot', 'Jacquot.org', 'EIRL', 'Gallet'),
(148, '81841043800000', 'Hebert', 'Hebert.com', 'EIRL', 'Letellier'),
(149, '78360074700000', 'Faure', 'Faure.com', 'EI', 'Vallet'),
(150, '94901201700000', 'Rodriguez', 'Rodriguez.org', 'SAS', 'Fouquet'),
(151, '07', 'Corange', 'Corange.fr', 'SAS', 'Carapuce');

-- --------------------------------------------------------

--
-- Structure de la table `particulier`
--

CREATE TABLE `particulier` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `particulier`
--

INSERT INTO `particulier` (`id`, `client_id`, `first_name`, `last_name`, `birthday`, `gender`, `job`) VALUES
(132, 132, 'Thomas', 'Fernandez', '2008-10-02', 'Mx', 'Potier'),
(134, 134, 'Auguste', 'Chevallier', '1986-03-14', 'Mx', 'Assurance'),
(135, 134, 'Lucas', 'Marin', '2005-02-28', 'Masculin', 'Scripte télévision'),
(136, 150, 'Raymond', 'Marie', '1981-01-21', 'Masculin', 'Stratifieur'),
(137, 149, 'Audrey', 'Boucher', '1996-07-07', 'Féminin', 'Primeuriste'),
(138, 149, 'Paulette', 'Mahe', '1994-12-23', 'Féminin', 'Employé d\'accueil'),
(139, 136, 'Antoine', 'Neveu', '2008-07-18', 'Masculin', 'Pédologue'),
(140, 137, 'Pénélope', 'Guillon', '1990-10-17', 'Féminin', 'Pizzaïolo'),
(141, 138, 'Émilie', 'Guillet', '1972-02-04', 'Féminin', 'Logistique'),
(142, 139, 'Céline', 'Lucas', '1978-02-26', 'Féminin', 'Piqueur en ganterie'),
(143, 140, 'Adèle', 'Olivier', '1991-08-04', 'Mx', 'Mannequin détail'),
(146, 143, 'Honoré', 'De Oliveira', '1990-01-02', 'Masculin', 'Danseur'),
(147, 142, 'Sébastien', 'Weiss', '1973-11-29', 'Masculin', 'Sapeur-pompier'),
(148, 143, 'Christophe', 'Boyer', '2021-02-20', 'Mx', 'Chromiste'),
(149, 144, 'Pierre', 'Cousin', '1998-09-03', 'Mx', 'Régleur funéraire'),
(150, 150, 'Hélène', 'Dubois', '2014-12-17', 'Mx', 'Scripte télévision'),
(151, NULL, 'Gaetan', 'Solis', '1994-05-06', 'Masculin', 'dev fullstack'),
(154, NULL, 'Li', 'Yi', '1993-04-10', 'Féminin', 'DAF'),
(155, NULL, 'Li', 'Yi', '1993-04-10', 'Féminin', 'DAF'),
(157, NULL, 'Li', 'Yi', '1993-04-10', 'Féminin', 'DAF'),
(158, NULL, 'Li', 'Yi', '1993-04-10', 'Féminin', 'DAF'),
(159, NULL, 'TotofName', 'TotolName', '2024-03-10', 'Masculin', 'Totor'),
(160, NULL, 'Souris', 'Grise', '2024-03-10', 'Féminin', 'Fromagère'),
(161, NULL, 'Souris', 'Grise', '2024-03-10', 'Féminin', 'Fromagère'),
(162, NULL, 'Souris', 'Grise', '2024-03-10', 'Féminin', 'Fromagère'),
(164, 141, 'Sourisette', 'Grise', '2024-03-10', 'Féminin', 'Fromagère'),
(165, 141, 'Draco', 'Feux', '2024-03-10', 'Masculin', 'Chauffagiste'),
(166, 141, 'Bulbi', 'Zard', '2024-03-17', 'Masculin', 'Fleuriste'),
(169, 141, 'Pika', 'Chu', '2024-03-17', 'Masculin', 'Electricien'),
(170, 151, 'Bu', 'Bu', '2024-03-17', 'Masculin', 'CoffeeCat'),
(171, 141, 'Du', 'Du', '2024-03-22', 'Féminin', 'CoffeeCat'),
(172, 141, 'Lou', 'Lou', '2024-03-22', 'Masculin', 'Chasseur'),
(174, 141, 'Cast', 'Or', '2024-03-22', 'Masculin', 'Bucherons'),
(175, 141, 'Lap', 'In', '2024-03-22', 'Masculin', 'Jardinier'),
(176, 141, 'Cro', 'co', '2024-03-22', 'Masculin', 'Pecheur'),
(178, 141, 'Dro', 'Dro', '2024-03-22', 'Masculin', 'Marcheur'),
(180, 141, 'Chauve', 'Souris', '2024-03-22', 'Masculin', 'Batman'),
(181, 141, 'Mar', 'Motte', '2024-03-22', 'Féminin', 'Chocolatier'),
(183, 141, 'Aig', 'Le', '2024-03-22', 'Masculin', 'Pilote d avion'),
(184, 141, 'Fau', 'Con', '2024-03-22', 'Masculin', 'Chasseur des prairies'),
(185, 141, 'Serp', 'ent', '2024-03-22', 'Masculin', 'Chasseur des prairies'),
(187, 141, 'Pi', 'Vert', '2024-03-22', 'Masculin', 'Chasseur d argent'),
(188, 141, 'Souris', 'Vert', '2024-03-22', 'Masculin', 'Ninja des prairies'),
(190, 141, 'Sourisette', 'Vert', '2024-03-22', 'Masculin', 'Ninja des prairies'),
(192, 141, 'Tau', 'Py', '2024-03-22', 'Masculin', 'Creuseur de trou'),
(194, 141, 'Py', 'Thon', '2024-03-22', 'Masculin', 'codeur des champs'),
(195, 141, 'Pap', 'Illon', '2024-03-23', 'Masculin', 'Butineur des champs'),
(196, 141, 'Cac', 'Tus', '2024-03-23', 'Masculin', 'Le cowboy du farwest'),
(197, 141, 'Ab', 'Eille', '2024-03-23', 'Masculin', 'Butineuse des champs'),
(198, 141, 'Moust', 'Tique', '2024-03-23', 'Masculin', 'Butineur de sang'),
(199, 141, 'Ou', 'Rs', '2024-03-23', 'Masculin', 'Mangeur de miel'),
(200, 141, 'Cy', 'Gogne', '2024-03-23', 'Masculin', 'Nourisse'),
(201, 141, 'Ax', 'Olote', '2024-03-23', 'Masculin', 'Demineur');

-- --------------------------------------------------------

--
-- Structure de la table `phone`
--

CREATE TABLE `phone` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `phone`
--

INSERT INTO `phone` (`id`, `type`, `brand`, `model`, `price`, `description`, `stock`) VALUES
(181, 'phablette', 'One+', 'OnePlus 9', 695, 'Voluptate eos voluptatem et quia odit. Occaecati tenetur nobis omnis omnis et iure occaecati alias. Commodi alias et quia et. Quisquam consequatur ut et maiores consectetur eum.', 66),
(182, 'phone', 'Apple', 'iPhone 13', 732, 'Quo veritatis ullam tenetur minima at. Est sed occaecati in nihil. Recusandae facere reiciendis quae provident mollitia omnis non. Fugiat repellat in voluptas dolor et. Quia quibusdam dolor repudiandae molestiae illo est quo.', 503),
(183, 'phone', 'Apple', 'iPhone 13 Pro Max', 639, 'Numquam sunt cum aut soluta inventore earum odio. Dolor dolores et quas et exercitationem. Sit repellendus quis inventore sed rem odit illum. Quis et maiores maxime sed facere dolor. Tenetur veritatis ea in harum quo.', 91),
(184, 'phablette', 'Oppo', 'Reno 5 Pro', 879, 'Quos ipsa eos qui veniam. Ut quia facilis veniam nam vero repudiandae non quidem. Sint tenetur distinctio omnis cupiditate.', 501),
(185, 'tablette', 'Huawei', 'P40 Pro', 823, 'Possimus facere officiis distinctio in est dolores incidunt. Sequi rem nostrum assumenda placeat. Quibusdam a nesciunt hic iusto ullam omnis quia.', 56),
(186, 'phablette', 'Samsung', 'Galaxy Note 20', 953, 'Sint provident voluptates quis velit quo non. Aut inventore fugiat et omnis.', 512),
(187, 'phone', 'Leica', 'Leica Q2', 947, 'Labore modi sunt in aut deleniti perspiciatis. Quas et reiciendis cumque et. Quo consectetur et odit. Quia amet reprehenderit neque aut iusto vero eligendi.', 412),
(188, 'phone', 'Oppo', 'Reno 5 Pro', 970, 'Eaque sapiente qui sit atque quis doloremque dolorum. Aliquid magnam qui enim quia. Enim aliquam est rerum rerum nobis. In et dolores quia aut nobis.', 161),
(189, 'tablette', 'Leica', 'Leica M10', 837, 'Officia ab cum omnis. Explicabo reiciendis optio aut. Est dolorem quis voluptatem fugiat. Consequatur voluptatem voluptas temporibus.', 184),
(190, 'tablette', 'Samsung', 'Galaxy Note 20', 870, 'Cumque voluptatibus consectetur molestiae incidunt rerum veniam. Aliquid aperiam quia iusto saepe quia. Vel quis iste qui animi quibusdam praesentium vel. Illum ea saepe consequatur ducimus voluptatem minus impedit.', 583),
(191, 'tablette', 'Leica', 'Leica M10', 839, 'Est nobis sunt omnis consequatur. Fuga autem minima nulla. Explicabo est voluptatem fuga tenetur qui.', 95),
(192, 'phablette', 'Apple', 'iPhone 13', 998, 'Similique iusto deserunt reiciendis sint eos non dolorum. Adipisci eum et dolores minus minima voluptates similique totam. Sit asperiores facere non alias et ipsa.', 365),
(193, 'phablette', 'Apple', 'iPhone 13', 678, 'Assumenda laboriosam qui aut ut aperiam. Ut occaecati beatae voluptas sed et et. Et deserunt nesciunt et officiis laboriosam neque dicta. Recusandae ab nemo est.', 566),
(194, 'tablette', 'Huawei', 'Mate 40 Pro', 862, 'Quasi accusantium nobis voluptas. Consequatur et deserunt inventore officiis provident consequatur ut. Doloribus quia et perspiciatis ex.', 690),
(195, 'phablette', 'Leica', 'Leica M10', 873, 'Doloremque sunt molestias accusantium facere excepturi aut quod. Nostrum mollitia nostrum eaque quaerat et dolor perferendis sunt. Laborum incidunt qui dolore minus animi sunt.', 368),
(196, 'phablette', 'Oppo', 'Reno 5 Pro', 721, 'Reiciendis quam sunt numquam dolores perferendis doloribus qui. Dignissimos inventore voluptatum officiis quidem consequuntur. Nam adipisci sapiente animi.', 715),
(197, 'phablette', 'Apple', 'iPhone 13', 821, 'Distinctio et vitae adipisci expedita voluptatem voluptatum. Quia ullam nulla reprehenderit et quas beatae nesciunt dolores.', 61),
(198, 'tablette', 'Apple', 'iPhone 13', 732, 'Nisi laudantium hic inventore quod veniam in est. Aut consectetur ea molestias ea soluta. Inventore ea perferendis maxime maxime ipsum blanditiis.', 207),
(199, 'phone', 'Huawei', 'Mate 40 Pro', 856, 'Ullam et quo sint incidunt. Tenetur est fugit et praesentium similique eaque omnis. Nam quis doloremque rerum laboriosam sit veniam officiis dolorum.', 907),
(200, 'tablette', 'Huawei', 'Mate 40 Pro', 651, 'Magni quo non minima assumenda. Et eaque ut veniam laborum aperiam molestias aut. Rem suscipit dolore quos rerum nemo. Nihil autem accusamus magni fugit placeat.', 148);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `particulier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `roles`, `address`, `phone`, `avatar`, `is_verified`, `client_id`, `particulier_id`) VALUES
(243, 'ruiz.william@live.com', '$2y$13$xncBLz1oGPtOlsgGKREH6uw1hstAoAEcA5P6iHLKCMoVNeidJ3OSO', '[\"ROLE_USER\"]', '14, chemin Pons\n69998 Delorme-sur-Rousseau', '06 39 15 52 14', 'https://via.placeholder.com/360x360.png/000055?text=Avatar+necessitatibus', 1, NULL, 132),
(245, 'ibesson@hotmail.fr', '$2y$13$rQkIKSNhtiDcxipMR1QnvuCBogXH.clPmiXrbQlqQbQywRJnghiBm', '[\"ROLE_USER\"]', '57, rue Françoise Fernandez\n14656 Cohen', '+33 (0)1 54 81 26 91', 'https://via.placeholder.com/360x360.png/00eedd?text=Avatar+aspernatur', 1, NULL, 134),
(246, 'gerard92@laposte.net', '$2y$13$Om2o1I6PXxpMdy.sytp4WOEPqvBYq4445JVjnznk7juQ2LT1LgU.i', '[\"ROLE_USER\"]', '8, avenue Laetitia Loiseau\n20135 Labbe', '+33 8 16 62 86 63', 'https://via.placeholder.com/360x360.png/00aaff?text=Avatar+temporibus', 1, NULL, 135),
(247, 'blin.amelie@sfr.fr', '$2y$13$IW3861ok5nl.4sWF0IR52OA4llhF9K2zDrE3V3rUZA6exV8nwo1hu', '[\"ROLE_USER\"]', '2, boulevard de Toussaint\n15799 Teixeira', '+33 2 69 96 57 65', 'https://via.placeholder.com/360x360.png/003311?text=Avatar+cum', 1, NULL, 136),
(248, 'marie.therese@laposte.net', '$2y$13$SNOtkPraZ5uJsXUh8gTdn.cvqPVWKiWoaoIntdV.idh6XJ.E6c5da', '[\"ROLE_USER\"]', '44, impasse Jean Herve\n87207 Gomez-sur-Mer', '+33 5 36 65 19 88', 'https://via.placeholder.com/360x360.png/000033?text=Avatar+aperiam', 1, NULL, 137),
(249, 'andre26@sfr.fr', '$2y$13$pQ2mU5noQw5ofMRNdBG1BO4sletTFtEQKyId.Bqw.5B3TzGaR8Tmq', '[\"ROLE_USER\"]', 'rue Courtois\n74228 Traore-sur-Hoareau', '0571916464', 'https://via.placeholder.com/360x360.png/0077ee?text=Avatar+dolorem', 1, NULL, 138),
(250, 'paul72@noos.fr', '$2y$13$qR8VH/5iWuw5XaIYyNPmO.fwisRVXSJCudL2jYh3QDD6BQ0a0K9PS', '[\"ROLE_USER\"]', '10, boulevard de Gaillard\n34275 Lebreton', '01 62 08 95 22', 'https://via.placeholder.com/360x360.png/00ccee?text=Avatar+dolores', 1, NULL, 139),
(251, 'jhoarau@sfr.fr', '$2y$13$uWb6Zuh.DROr9zl67pXUCuIgddruRiyyRNdiRicRdToJ8ukqY5yuS', '[\"ROLE_USER\"]', '33, chemin de Marchand\n59264 Valentin', '09 24 08 40 66', 'https://via.placeholder.com/360x360.png/003333?text=Avatar+voluptatem', 1, NULL, 140),
(252, 'fcordier@tele2.fr', '$2y$13$DGoVSxPBp5fAHOOvml3yvOCiKTQBTZIH/Smuk.eUeAFMGFzXKd4Ze', '[\"ROLE_USER\"]', 'rue de Georges\n19360 Lenoir', '+33 7 74 48 61 15', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+vel', 1, NULL, 141),
(253, 'ggillet@sfr.fr', '$2y$13$pBXoINPL2apmu6CGjgP8QuKQHbJhKUOKcKmOkCgKiuYJ2YT47gyDq', '[\"ROLE_USER\"]', 'rue Samson\n96559 Le Roux', '09 83 12 60 25', 'https://via.placeholder.com/360x360.png/00cc00?text=Avatar+unde', 1, NULL, 142),
(254, 'ollivier.capucine@tele2.fr', '$2y$13$0D5nJ/7/8wW70nPzsT7jfuKIbqIXowuOx8fKYWjjopzq7Qw6E2zw2', '[\"ROLE_USER\"]', '68, avenue de Blot\n27251 Faivre', '01 20 29 54 10', 'https://via.placeholder.com/360x360.png/0022cc?text=Avatar+quae', 1, NULL, 143),
(257, 'thomas70@hotmail.fr', '$2y$13$QuqF2I9iTq4LZYSNoI7SPuv/GUjxhlLkisQ4GLDFfx1EYjLnNrO8W', '[\"ROLE_USER\"]', '94, rue Michèle Roger\n41148 Mathieu', '0193526857', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+aliquam', 1, NULL, 146),
(258, 'claudine.blin@club-internet.fr', '$2y$13$2eB4ctctjrjZn2Ml8komBORBbS7JQUMHtpG7b2dpPbKKisEUNIaUG', '[\"ROLE_USER\"]', 'rue de Diallo\n39544 Chauvet', '06 59 53 23 07', 'https://via.placeholder.com/360x360.png/00ee66?text=Avatar+non', 1, NULL, 147),
(259, 'faure.gregoire@wanadoo.fr', '$2y$13$20lfSwBr/nRq4VNvqEHc7uBPoaAfjL3qRN2dUzyPxzZR89ENamCuG', '[\"ROLE_USER\"]', '81, impasse Legendre\n99521 Imbert', '0984964311', 'https://via.placeholder.com/360x360.png/0022ee?text=Avatar+quo', 1, NULL, 148),
(260, 'nicole.weiss@yahoo.fr', '$2y$13$QNdaz/JpDxg4OmdpO7zwqOsydtlf3/vQeHPy8v8/9tNntcsPeLDqS', '[\"ROLE_USER\"]', '45, rue Madeleine Gregoire\n03210 Barre', '+33 (0)5 76 16 79 60', 'https://via.placeholder.com/360x360.png/0000ee?text=Avatar+vitae', 1, NULL, 149),
(261, 'julien.duval@wanadoo.fr', '$2y$13$7Dr6gwKM8xKhxBUUWWbtw.aylDm2sOe2O./G/OvJ.cCEAGxVphE5u', '[\"ROLE_USER\"]', '13, avenue Perrot\n46656 Lagarde', '09 47 87 61 43', 'https://via.placeholder.com/360x360.png/0011aa?text=Avatar+repellendus', 1, NULL, 150),
(262, 'free@free.fr', '$2y$13$pnJVmCWvbRVEt6L6Go0exetpypCVbTDmxtp2IeG95Yeh82TgKUEqC', '[\"ROLE_ADMIN\"]', '5, rue de Marion\n66041 Toussaint-sur-Berthelot', '02 94 77 88 93', 'https://via.placeholder.com/360x360.png/00aa00?text=Avatar+beatae', 1, 141, NULL),
(263, 'cecile51@tele2.fr', '$2y$13$6bCjf0X.aWZQC6cMOaudAeLJbQENZB1Ai9srDdsPZ/I68nNvLkPPW', '[\"ROLE_ADMIN\"]', '69, place Rémy Caron\n00415 Mailletnec', '07 47 57 41 70', 'https://via.placeholder.com/360x360.png/004499?text=Avatar+modi', 1, 145, NULL),
(264, 'matthieu.richard@dbmail.com', '$2y$13$aqMaVTE9zCq23uOqpqpTbOhHVeAlNbU5czHnI6DF4J2BBVomhYKtS', '[\"ROLE_ADMIN\"]', '16, rue Éléonore De Sousa\n49337 Maury', '+33 (0)5 05 85 70 37', 'https://via.placeholder.com/360x360.png/00dd66?text=Avatar+assumenda', 1, 139, NULL),
(265, 'ogoncalves@wanadoo.fr', '$2y$13$ajR4WrJfoDbpwlOhghskvO6cjGXh9e2l8siI.kvCu44MqD7aZ6cc2', '[\"ROLE_ADMIN\"]', '91, rue Amélie Perrier\n49146 Lebretonboeuf', '0159562557', 'https://via.placeholder.com/360x360.png/003300?text=Avatar+nisi', 1, 132, NULL),
(266, 'wgillet@noos.fr', '$2y$13$tVtFAVBFDkGRa3pG5/62QuAJRyxi6vC8cizwqyY5oPBKY6MKek0Bm', '[\"ROLE_ADMIN\"]', '546, chemin Virginie Wagner\n83378 Gosselin', '+33 (0)8 93 62 86 22', 'https://via.placeholder.com/360x360.png/0033ff?text=Avatar+voluptatibus', 1, 146, NULL),
(267, 'joseph.caron@wanadoo.fr', '$2y$13$XXLcgL61WAiJkuOVKhTa2OlkERe31CVm79E29bBuzEEve5FWUGDbu', '[\"ROLE_ADMIN\"]', 'place Roland Descamps\n17173 Paul-sur-Weiss', '+33 5 63 45 48 00', 'https://via.placeholder.com/360x360.png/00ffbb?text=Avatar+veritatis', 1, 134, NULL),
(268, 'petitjean.denise@live.com', '$2y$13$Qpfou8oDT7zKtFRM1bTGI.exTZhfwaR7EP.xzMmJUjEz8ji3d5ry.', '[\"ROLE_ADMIN\"]', '350, avenue de Potier\n20295 Charrier', '06 88 94 44 21', 'https://via.placeholder.com/360x360.png/001166?text=Avatar+facilis', 1, 140, NULL),
(269, 'gaillard.colette@live.com', '$2y$13$aQtCK.ffwMqk6vs8QGz.J.J2R6zJ3YV5clj4d.KZ5lPpVq1UoU3LK', '[\"ROLE_ADMIN\"]', 'chemin de Moreno\n58461 Jourdannec', '07 88 07 31 03', 'https://via.placeholder.com/360x360.png/00bb44?text=Avatar+quam', 1, 147, NULL),
(270, 'maggie.chauveau@noos.fr', '$2y$13$fUlUsq6dWf5W1VM0VFsebu8pYpMwNJ.3bpRih1AiwVl0aESS2j4ri', '[\"ROLE_ADMIN\"]', 'place Riou\n96268 Lombarddan', '+33 (0)5 27 74 16 66', 'https://via.placeholder.com/360x360.png/00ff88?text=Avatar+excepturi', 1, 131, NULL),
(271, 'adrien.weiss@live.com', '$2y$13$EfmC8Ina3EN3bAP/5yJDAuAm/YcAZ6oC/sXnclqIVy.9EKmnaF/Ja', '[\"ROLE_ADMIN\"]', '34, impasse Auguste Mace\n24250 Baron-sur-Marty', '0117993802', 'https://via.placeholder.com/360x360.png/0011cc?text=Avatar+consequatur', 1, 133, NULL),
(272, 'alexandre.henriette@tele2.fr', '$2y$13$4VtoTw1f7pcmKvMWtNHUReB6XZu5CFfgqci05lSyNOyk1.c1fzDji', '[\"ROLE_ADMIN\"]', '73, impasse Thibault\n75023 Gaillard-sur-Mer', '0891596488', 'https://via.placeholder.com/360x360.png/0055aa?text=Avatar+repudiandae', 1, 148, NULL),
(273, 'hortense24@noos.fr', '$2y$13$Z.u8VuR7XXQalpP6xwREo.h.Jh2QCW1OqX8ii.E8jzEONCOsT9TPi', '[\"ROLE_ADMIN\"]', '3, place Guillaume Rodrigues\n99028 Louis', '0926484612', 'https://via.placeholder.com/360x360.png/003322?text=Avatar+corporis', 1, 138, NULL),
(274, 'yriviere@noos.fr', '$2y$13$u5liMqltcc1fpgHQPgt4nORp.v/iZHy.wF8chU.Pwv4q5G2wlsSCu', '[\"ROLE_ADMIN\"]', '934, rue de Legendre\n52359 Ollivier-sur-Legendre', '+33 (0)7 82 10 40 42', 'https://via.placeholder.com/360x360.png/006655?text=Avatar+neque', 1, 149, NULL),
(275, 'lemoine.frederique@laposte.net', '$2y$13$f6dALwD55Nk.pduNvUhurehS00AK6OyxKFRqo2IaBX6p8LLxxT2FG', '[\"ROLE_ADMIN\"]', '51, impasse de Jacob\n88730 Riouboeuf', '0265705589', 'https://via.placeholder.com/360x360.png/00aaff?text=Avatar+rerum', 1, 136, NULL),
(276, 'lorraine.joly@yahoo.fr', '$2y$13$nXRv2nji3M0cwkTDX4Ji4O0S/ua9QijnjZZ90RtrUHM4Qc5.bSWh2', '[\"ROLE_ADMIN\"]', '26, rue Alfred Boutin\n86880 Leger-sur-Coste', '+33 (0)8 29 62 69 58', 'https://via.placeholder.com/360x360.png/0066bb?text=Avatar+incidunt', 1, 143, NULL),
(277, 'xmaillard@club-internet.fr', '$2y$13$FW9hMxjt9lVq9sJoDnEJMuXVVMF9VKmARvvQfUY03ZS59OmK4BvJS', '[\"ROLE_ADMIN\"]', '24, impasse Deschamps\n92528 Potier', '0940315953', 'https://via.placeholder.com/360x360.png/008899?text=Avatar+libero', 1, 150, NULL),
(278, 'agarnier@sfr.fr', '$2y$13$zwieLpFMNoEizSnHeJVFbumXsw377.i7uh9gFcDtPWnhhVgNuH.3C', '[\"ROLE_ADMIN\"]', 'avenue Théophile Jourdan\n80059 Adam-sur-Lemaitre', '0357456549', 'https://via.placeholder.com/360x360.png/006655?text=Avatar+voluptatem', 1, 135, NULL),
(279, 'munoz.luc@orange.fr', '$2y$13$4w6Tk19w1.rwRfp0pQL9kurGN1xTDVFMFJFyaHwFFe2PEkYiVziO2', '[\"ROLE_ADMIN\"]', 'rue Laurent Colin\n78395 Martel-sur-Perez', '0397402354', 'https://via.placeholder.com/360x360.png/005533?text=Avatar+natus', 1, 142, NULL),
(280, 'ifischer@sfr.fr', '$2y$13$EeIh0mI6QF8XuI.S4ZazgOKpXWFEYNr6xQsQxNylGrowf9QBywEiu', '[\"ROLE_ADMIN\"]', '806, rue de Bodin\n77120 Leconte', '0420227585', 'https://via.placeholder.com/360x360.png/002211?text=Avatar+corporis', 1, 137, NULL),
(281, 'lecoq.theophile@wanadoo.fr', '$2y$13$94hEKq2vsXBvHJuY8dZ9beMB6..1i.Kc5b6M43yOL7gSYt/MxL7pG', '[\"ROLE_ADMIN\"]', '41, impasse de Martineau\n28644 Cohen-sur-Mer', '01 17 72 14 88', 'https://via.placeholder.com/360x360.png/009900?text=Avatar+accusantium', 1, 144, NULL),
(282, 'getssone@mailo.com', 'password', '[\"ROLE_USER\"]', '17 impasse chevreul', '0678842460', 'http://loveferrari.l.o.pic.centerblog.net/o/da4f9f20.jpg', 1, NULL, 151),
(286, 'yili@noos.fr', '$2y$13$qWnukEKQNdRMxom36OcLOuLfROfRpeP.WXf3Rcn5isDam7yUUMFKG', '[\"ROLE_USER\"]', '17 impasse chevreul', '0705134920', 'https://thumbs.dreamstime.com/b/hibou-de-grange-53950216.jpg', 1, NULL, 155),
(289, 'yilili@noos.fr', '$2y$13$OIBijiJzc99O2Wce8zeC/eLaO4gJ18KA20Bm5iAzuG4.YRfc88Oty', '[\"ROLE_USER\"]', '17 impasse chevreul', '0705134920', 'https://thumbs.dreamstime.com/b/hibou-de-grange-53950216.jpg', 1, NULL, 158),
(290, 'toto@toto.fr', '$2y$13$pELIiDhfFW9L4e0fY46O4.89ZBVbt04iP/9ElGs79VBt16JFPSNsa', '[\"ROLE_USER\"]', '10 rue du toto', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+toto', 1, NULL, 159),
(291, 'souris@grise.fr', '$2y$13$UwK.FRLwNIpJ/kwQMIqUxO1R.KQC4pGh5rWHJxXp/59beXuVXxc/i', '[\"ROLE_USER\"]', '11 rue des souris', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+souris', 1, NULL, 160),
(295, 'sourisette@grise.fr', '$2y$13$vVedx/7fSBdI6z65J1CWX.0AyjPdk9QjzwyBmOH0d8ir91Z7O6ny6', '[\"ROLE_USER\"]', '11 rue des souris', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+souris', 1, NULL, 164),
(296, 'draco@feux.fr', '$2y$13$K5DbRRMss4/rWTfOssosKewxILoTy5YseyeXilI9Q3QvAoV.JSYqy', '[\"ROLE_USER\"]', '11 rue des enfers', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+draco', 1, NULL, 165),
(297, 'Bulbi@zard.fr', '$2y$13$RYsd/FYmu2y6yeQQe0paUeSYq4CQmivMhGH1t/hXzNgA9w3FjTd/a', '[\"ROLE_USER\"]', '11 rue des fleurs', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+bulbi', 1, NULL, 166),
(300, 'pika@chu.fr', '$2y$13$oYgOiI7ekXRibuYFVJem4OJQwLdH9krCx5CByIZqqz/9J7WZN.5n.', '[\"ROLE_USER\"]', '11 rue des éclaires', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+pika', 1, NULL, 169),
(301, 'cara@puce.fr', '$2y$13$PTAprhbcqYepOs2Va/lZBOaMC9Kkep38bOg.umfh3/Ctyl4CKFUJS', '[\"ROLE_ADMIN\"]', '11 rue des fontaines', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+cara', 1, 151, NULL),
(302, 'bu@bu.fr', '$2y$13$w03FSLUlhjgxgpmRMFYf.Oe83oU3Utns.fLCKa1C7a6CAodhIurJ2', '[\"ROLE_USER\"]', '11 rue des Bubu', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+bubu', 1, NULL, 170),
(303, 'du@du.fr', '$2y$13$VXFkLYHXT7WibrO7hXaqMuwwRaN0p0WQjfPjav2OzzVaI4xLq20rq', '[\"ROLE_USER\"]', '11 rue des dudu', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+dudu', 1, NULL, 171),
(304, 'lou@lou.fr', '$2y$13$sgVbd4PHOeCLS8Kw/lJfLeeMM3CuDoxGrpKXZddWV5rBTQ.p2.mbe', '[\"ROLE_USER\"]', '11 rue des loups', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+loulou', 1, NULL, 172),
(306, 'cast@or.fr', '$2y$13$AS9Af.zB.VaofmuoMWpWV.1np8eKhlFsje92U6TAIyMe3fH343BVq', '[\"ROLE_USER\"]', '11 rue des rivieres', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+castor', 1, NULL, 174),
(307, 'lap@in.fr', '$2y$13$fv5lrKSBL1j2Lb4YVy3GUei//I2/CUqUTEmKgL1y0rQyE36DqPP8O', '[\"ROLE_USER\"]', '11 rue des jardins', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+lapin', 1, NULL, 175),
(308, 'cro@co.fr', '$2y$13$tIxWiCBWFWJkLVcD0dhDAOOv1.1D0rF3vuvLrFUT/LTZzbJDB39Ky', '[\"ROLE_USER\"]', '11 rue des marecage', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+croco', 1, NULL, 176),
(310, 'dro@dro.fr', '$2y$13$nrys2YjorDqzIdbLR615begZahu909/6wnFliYe0gvaBCVoIli8J2', '[\"ROLE_USER\"]', '11 rue des marecage', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+dromadaire', 1, NULL, 178),
(312, 'chauve@souris.fr', '$2y$13$wYArdUz/ZL3pFcH/TuvryO7KHlwX64scRbzNw92ATL.SbNCk6iFqe', '[\"ROLE_USER\"]', '11 rue des Grottes', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+ChauveSouris', 1, NULL, 180),
(313, 'mar@motte.fr', '$2y$13$wJgZ3NWIZw/ngmLsRXn9r.3p1GrNnz8xxfmJoU/UokqjZ4CH/CP/y', '[\"ROLE_USER\"]', '11 rue des Colinnes', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Marmotte', 1, NULL, 181),
(315, 'aig@le.fr', '$2y$13$0IT40TfJSBsD2DDI75Ga7eSSOtSv/N3eL07iscCmshmoJ/C.77lTu', '[\"ROLE_USER\"]', '11 rue des Sommets', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Aigle', 1, NULL, 183),
(316, 'fau@con.fr', '$2y$13$pP92QwLknuPzP1OePnlKAeIEcwes/Nj4OLyPtW9qWbADVbgZ6TXmy', '[\"ROLE_USER\"]', '11 rue des prairies', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Faucon', 1, NULL, 184),
(317, 'serp@ent.fr', '$2y$13$UgWSSkg3HTR8re8H9/wpEOTUqxm9wA3ZFUBi2AFRdggFQeOxm.PSe', '[\"ROLE_USER\"]', '11 rue des sonnettes', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Serpent', 1, NULL, 185),
(319, 'pi@vert.fr', '$2y$13$8k5w9T9UW5DIXeNqtfQluuXahFv9.IyO3f2HJC4DihgwAfBFOjlSe', '[\"ROLE_USER\"]', '11 rue des moineaux', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Pivert', 1, NULL, 187),
(320, 'souris@vert.fr', '$2y$13$vRF1kcPcwk78RzIizhZueunrU4md08kf1fQiFhvG9V1ODh4Quk2Uq', '[\"ROLE_USER\"]', '11 rue des verdure', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+SourisVerte', 1, NULL, 188),
(322, 'sourisette@verte.fr', '$2y$13$ebMIjzgv9A8.A8Ke/BIMveNdG.fDaV8eoYFlnTISTKR5ctt/1ldM2', '[\"ROLE_USER\"]', '11 rue des verdure', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+SourisetteVerte', 1, NULL, 190),
(324, 'tau@py.fr', '$2y$13$Vxpcq/RKLvMduQwPXjWpX.pQD6VjPnMSChRAC0/pKdMCGwkDYSZgG', '[\"ROLE_USER\"]', '11 rue des terreaux', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Taupy', 1, NULL, 192),
(326, 'py@thon.fr', '$2y$13$ujXFJ0kH3w641Qq111me.OKt2saiZvqqjYFNsGyepeB.L7l/jzGMW', '[\"ROLE_USER\"]', '11 rue des codes', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Python', 1, NULL, 194),
(327, 'pap@illon.fr', '$2y$13$B6XlcBadXeNSJqHfG2TtR.CvRvPk3Lv879kRuNs5xUScTsX/aTRIC', '[\"ROLE_USER\"]', '11 rue des codes', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Papillon', 1, NULL, 195),
(328, 'cac@tus.fr', '$2y$13$WxPeALFB8AxNtna8EjhJ5Or.QUXh/cVwMraqC4eN0dTastYJPcpoC', '[\"ROLE_USER\"]', '11 rue du dessert', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Cactus', 1, NULL, 196),
(329, 'ab@eille.fr', '$2y$13$moT1hgcBMnD3kjEFSl2tuOzoUbi8Hc5vf6if6P8kPPr9nqe1fLbo.', '[\"ROLE_USER\"]', '11 rue des champs', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Abeille', 1, NULL, 197),
(330, 'mous@tique.fr', '$2y$13$tfHPfb7oi3LlJyUI1mUB5uIoYMc7bIoShmxXkJzLZ7aLaSqizmLcO', '[\"ROLE_USER\"]', '11 rue de la nuit', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Moustique', 1, NULL, 198),
(331, 'ou@rs.fr', '$2y$13$RqiDz52K9aAcyQooSuHQd.RJSO.V6h9yVrVCGivwAgDElDzpUXc8q', '[\"ROLE_USER\"]', '11 rue du miel', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Ours', 1, NULL, 199),
(332, 'cy@gogne.fr', '$2y$13$LsQ/vgCs13zZrtfJGVBIz.Qc7Kty8XmdiqX236SqbGzxAv8c2PN1y', '[\"ROLE_USER\"]', '11 rue des baluchons', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Cygogne', 1, NULL, 200),
(333, 'ax@olote.fr', '$2y$13$mzqF29ipdTR8Lm3/j1aFxeWJcdFJQW/JEZvbhk/cgyKSy2x3C.Pza', '[\"ROLE_USER\"]', '11 rue des grottes', '0708090102', 'https://via.placeholder.com/360x360.png/004455?text=Avatar+Axolote', 1, NULL, 201);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `particulier`
--
ALTER TABLE `particulier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6CC4D4F319EB6921` (`client_id`);

--
-- Index pour la table `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D64919EB6921` (`client_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649A89E0E67` (`particulier_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT pour la table `particulier`
--
ALTER TABLE `particulier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT pour la table `phone`
--
ALTER TABLE `phone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `particulier`
--
ALTER TABLE `particulier`
  ADD CONSTRAINT `FK_6CC4D4F319EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64919EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_8D93D649A89E0E67` FOREIGN KEY (`particulier_id`) REFERENCES `particulier` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
