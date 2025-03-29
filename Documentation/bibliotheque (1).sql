-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 29 mars 2025 à 12:05
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `amendes`
--

DROP TABLE IF EXISTS `amendes`;
CREATE TABLE IF NOT EXISTS `amendes` (
  `id_amende` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_emprunt` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `statut` enum('impayee','payee') DEFAULT 'impayee',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_amende`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_emprunt` (`id_emprunt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(100) NOT NULL,
  PRIMARY KEY (`id_categorie`),
  UNIQUE KEY `nom_categorie` (`nom_categorie`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Science Fiction'),
(2, 'Fantasy'),
(3, 'Roman'),
(4, 'Biographie'),
(5, 'Histoire'),
(6, 'Philosophie'),
(7, 'Art et Design'),
(8, 'Technologie'),
(9, 'Économie'),
(10, 'Psychologie'),
(11, 'Cuisine'),
(12, 'Drame'),
(13, 'Aventure'),
(14, 'Mystère');

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

DROP TABLE IF EXISTS `emprunts`;
CREATE TABLE IF NOT EXISTS `emprunts` (
  `id_emprunt` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_livre` int DEFAULT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour` date DEFAULT NULL,
  `date_echeance` date NOT NULL,
  `statut` enum('actif','retourne') DEFAULT 'actif',
  PRIMARY KEY (`id_emprunt`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_livre` (`id_livre`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `emprunts`
--

INSERT INTO `emprunts` (`id_emprunt`, `id_utilisateur`, `id_livre`, `date_emprunt`, `date_retour`, `date_echeance`, `statut`) VALUES
(14, 6, 8, '2024-12-13', '2025-03-17', '2024-12-27', 'retourne'),
(13, 7, 2, '2024-12-13', '2024-12-13', '2024-12-27', 'retourne'),
(12, 7, 6, '2024-12-13', '2024-12-13', '2024-12-27', 'retourne'),
(17, 8, 2, '2024-12-13', '2024-12-24', '2024-12-22', 'retourne'),
(16, 6, 2, '2024-12-13', '2025-03-17', '2024-12-27', 'retourne'),
(11, 7, 8, '2024-12-13', '2024-12-20', '2024-12-27', 'retourne'),
(15, 7, 8, '2024-12-13', '2024-12-20', '2024-12-27', 'retourne'),
(10, 6, 2, '2024-12-13', '2025-03-17', '2024-12-27', 'retourne'),
(18, 6, 3, '2024-12-13', '2025-03-17', '2024-12-27', 'retourne'),
(19, 6, 3, '2024-12-13', '2025-03-17', '2024-12-27', 'retourne'),
(20, 7, 10, '2024-12-13', '2024-12-20', '2024-12-27', 'retourne'),
(21, 6, 7, '2024-12-13', '2024-12-24', '2024-12-27', 'retourne'),
(22, 6, 8, '2024-12-20', '2025-03-17', '2025-01-03', 'retourne'),
(23, 6, 2, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(24, 8, 6, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(25, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(26, 6, 2, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(27, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(28, 6, 8, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(29, 6, 6, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(30, 6, 3, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(31, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(32, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(33, 8, 6, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(34, 8, 3, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(35, 6, 2, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(36, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(37, 6, 8, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(38, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(39, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(40, 8, 6, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(41, 8, 3, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(42, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(43, 6, 2, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(44, 6, 8, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(45, 6, 6, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(46, 6, 3, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(47, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(48, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(49, 8, 6, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(50, 8, 3, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(51, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(52, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(53, 8, 6, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(54, 8, 3, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(55, 6, 2, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(56, 6, 8, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(57, 6, 6, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(58, 6, 3, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(59, 8, 2, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(60, 8, 8, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(61, 8, 6, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(62, 8, 3, '2024-12-24', '2024-12-24', '2025-01-07', 'retourne'),
(63, 6, 2, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(64, 6, 8, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(65, 6, 6, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(66, 6, 3, '2024-12-24', '2025-03-17', '2025-01-07', 'retourne'),
(67, 8, 2, '2025-03-17', NULL, '2025-03-31', 'actif'),
(68, 8, 3, '2025-03-17', NULL, '2025-03-31', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

DROP TABLE IF EXISTS `livres`;
CREATE TABLE IF NOT EXISTS `livres` (
  `id_livre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `isbn` varchar(13) DEFAULT NULL,
  `statut` enum('disponible','emprunte') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'disponible',
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id_livre`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id_livre`, `titre`, `auteur`, `categorie`, `isbn`, `statut`, `date_ajout`, `image`, `description`) VALUES
(1, 'L&#039;inspiration derrière tous les poèmes du monde', 'Alain Dumont', 'Philosophie', '9782370832943', 'disponible', '2024-12-06 10:20:13', 'l_039_inspiration_derri_re_tous_les_po_mes_du_monde.jpg', 'L&#039;inspiration derrière tous les poèmes du monde explore la nature même de la poésie, en dévoilant les sources profondes des rêves et des émotions humaines. Alain Dumont nous invite à un voyage introspectif où chaque poème devient une fenêtre ouverte sur l’âme, l’imaginaire et les rêves universels. Ce livre réunit réflexions poétiques et méditations philosophiques, inspirant les lecteurs à puiser dans leurs propres rêves pour créer.'),
(2, 'Secrets des fleurs', 'Élise H. Martin', 'Science Fiction', '9782368128725', 'emprunte', '2024-12-06 08:10:00', 'secrets_des_fleurs.png', 'Secrets des fleurs explore la beauté et les mystères du langage floral. À travers une narration poétique et immersive, Élise H. Martin nous plonge dans un monde où chaque pétale renferme une histoire et chaque parfum évoque un souvenir. Ce livre invite à redécouvrir la nature sous un prisme émotionnel et sensoriel unique.'),
(3, 'Euforia : À la recherche de la liberté intérieure', 'Olivia Wilson', 'Art et Design', '9782370832905', 'emprunte', '2024-12-06 11:00:00', 'euforia_la_recherche_de_la_libert_int_rieure.jpg', 'Euforia : À la recherche de la liberté intérieure propose une exploration profonde de la quête de la liberté personnelle. Olivia Wilson guide ses lecteurs à travers des réflexions sur l&#039;authenticité, la gestion des émotions et la recherche de l’équilibre intérieur. À travers des conseils pratiques et des stratégies inspirantes, ce livre invite chacun à se libérer des chaînes invisibles pour atteindre une vie plus sereine et épanouie.'),
(4, 'Sous les collines', 'J. Poulon', 'Drame', '9782370832950', 'disponible', '2024-12-05 23:09:00', 'sous_les_collines.jpg', 'Sous les collines raconte l’histoire d’un homme qui, à l’aube de ses quarante ans, se trouve confronté à des défis bien plus complexes que l&#039;âge qui avance. À travers une exploration intime et poignante, J. Poulon nous plonge dans les luttes intérieures du protagoniste, qui doit faire face à ses démons tout en cherchant un sens à sa vie. Un roman captivant sur la quête de soi et les obstacles qui jalonnent le chemin de la maturité.'),
(5, 'Le livre de recettes ultime', 'Isabelle Marnier', 'Cuisine', '9782370832912', 'disponible', '2024-12-06 12:09:00', 'le_livre_de_recettes_ultime.jpg', 'Le livre de recettes ultime est une véritable bible culinaire qui offre une collection de plats pour toutes les occasions, des plus simples aux plus sophistiquées. Isabelle Marnier partage ses recettes savoureuses et accessibles, avec des instructions claires et des astuces pour chaque étape. Ce livre est parfait pour les cuisiniers de tous niveaux qui cherchent à impressionner ou à se faire plaisir avec des mets délicieux.'),
(6, 'L’ombre derrière le voile', 'Antoine Auclair', 'Science Fiction', '9782370832899', 'disponible', '2024-12-06 12:40:00', 'l_ombre_derri_re_le_voile.jpg', 'L’ombre derrière le voile est un thriller captivant où mystère et suspense s’entrelacent. Antoine Auclair plonge le lecteur dans un monde d’illusions et de vérités cachées. Alors que les apparences se brouillent, un protagoniste tourmenté tente de lever le voile sur une ombre du passé, menaçant de révéler des secrets enfouis depuis trop longtemps.'),
(7, 'Femmes du silence', 'Béatrice Dumont', 'Romans', '9782370832936', 'disponible', '2024-12-06 16:06:00', 'femmes_du_silence.jpg', 'Femmes du silence est un roman émouvant qui raconte l’histoire de femmes prises dans le tourbillon de la société et du silence qui les enferme. Béatrice Dumont explore des thèmes de résistance, de solidarité féminine et de libération personnelle, en dressant un portrait poignant de femmes qui se battent pour faire entendre leur voix dans un monde où elles sont souvent réduites au silence.'),
(8, 'Couchers de soleil avec Annie', 'Mia Sanati', 'Histoire', '9782370832882', 'disponible', '2024-12-06 11:10:00', 'couchers_de_soleil_avec_annie.png', 'Couchers de soleil avec Annie est une ode à l’amitié et aux instants suspendus. À travers des paysages baignés de lumière dorée, Mia Sanati nous emmène dans une aventure douce et introspective où chaque coucher de soleil devient le témoin d’histoires partagées, de souvenirs inoubliables et de promesses murmurées à l’horizon.'),
(9, 'Dans nos Os', 'Alain Dumont', 'Romans', '9782370832929', 'disponible', '2024-12-06 14:02:00', 'dans_nos_os.jpg', 'Dans nos Os est un thriller captivant qui plonge le lecteur dans une enquête palpitante où chaque indice découvert révèle de sombres secrets enfouis. Alain Dumont, avec son style percutant, nous transporte dans l&#039;univers complexe des criminels et des enquêteurs, mêlant mystère, suspense et rebondissements. Ce best-seller des éditions du Nord est un incontournable pour les amateurs de romans policiers.'),
(10, 'La rivière cachée', 'Romain Stévenin', 'Aventure', '9782370832967', 'disponible', '2024-12-06 18:21:00', 'la_rivi_re_cach_e.jpg', 'La rivière cachée suit les aventures d’un ermite solitaire qui, en quête de paix et de solitude, trouve refuge dans la nature sauvage. À travers ses explorations et ses découvertes, Romain Stévenin nous invite à une immersion dans l’essence de la nature, tout en explorant les thèmes de l&#039;isolement, de la recherche intérieure et de la beauté brute du monde naturel. Un récit captivant qui célèbre l’harmonie entre l’homme et son environnement.'),
(13, 'Le feuillage du passé', 'Mia Marnier', 'Science Fiction', '9782370832875', 'disponible', '2025-03-17 16:04:47', 'le_feuillage_du_pass_.png', 'Le feuillage du passé retrace l’histoire poignante d’une famille marquée par les secrets et les non-dits. Lorsque les dernières paroles d’un vieil homme réveillent des souvenirs enfouis, commence une quête de vérité bouleversante. Mia Marnier livre un récit émouvant sur les liens familiaux, l’amour et le poids du passé.'),
(14, 'La brume des bois', 'J. Poulon', 'Science Fiction', '9782370832974', 'disponible', '2025-03-19 11:03:52', 'la_brume_des_bois.jpg', 'La brume des bois nous plonge dans l’univers inquiétant de la forêt de Condorcet, un lieu où des mystères anciens semblent hantés par un secret sombre. J. Poulon tisse un thriller haletant, où chaque pas dans la brume révèle un peu plus d’un passé lourd de secrets, d’intrigues et de terreur. Préparez-vous à une aventure où le danger rôde à chaque coin d’arbre et où seuls les plus audacieux oseront s’aventurer.'),
(15, 'Quand les étoiles s&#039;alignent', 'Mia Marnier', 'Science Fiction', '9782370832981', 'disponible', '2025-03-19 11:05:54', 'quand_les_toiles_s_039_alignent.jpg', 'Quand les étoiles s&#039;alignent est un roman captivant qui explore l&#039;intrigue autour de phénomènes célestes et de forces invisibles qui influencent le destin. Lorsque les étoiles s&#039;alignent d&#039;une manière inhabituelle, la Terre plonge dans une époque d&#039;obscurité inattendue. Mia Marnier tisse un récit palpitant où science et mysticisme se rencontrent, interrogeant la nature même de l&#039;univers et de la réalité. Une aventure fascinante pour les amateurs de mystères cosmiques et de récits énigmatiques.'),
(16, 'Voyager à New York', 'Alain Marnier', 'Aventure', '9782370832998', 'disponible', '2025-03-19 11:08:03', 'voyager_new_york.jpg', 'Voyager à New York est un guide pratique conçu pour aider les voyageurs à naviguer facilement dans la ville qui ne dort jamais. Alain Marnier offre une série de conseils utiles et d’astuces pour explorer New York, de ses incontournables monuments à ses trésors cachés. Ce livre permet de maximiser chaque instant dans la ville en fournissant des informations sur les meilleurs endroits, les astuces pour éviter les pièges à touristes et les moyens de profiter pleinement de la Grosse Pomme.'),
(17, 'Au-dessus de la cime des arbres', 'Henri Dufour', 'Histoire', '9782370833000', 'disponible', '2025-03-19 11:10:37', 'au_dessus_de_la_cime_des_arbres.jpg', 'Au-dessus de la cime des arbres est un thriller terrifiant qui plonge le lecteur dans un univers mystérieux et envoûtant, où la frontière entre le réel et l&#039;imaginaire s&#039;estompe. À travers une aventure haletante, Henri Dufour nous entraîne dans une quête de vérité où les dangers se cachent à chaque instant. Qualifié de &quot;terrifiant et addictif&quot; par la Gazette de Saint-Denis, ce roman promet de tenir les lecteurs en haleine du début à la fin.'),
(18, 'Une maison sur roues', 'Alain André', 'Roman', '9782370833011', 'disponible', '2025-03-19 11:12:23', 'une_maison_sur_roues.jpg', 'Une maison sur roues raconte l’histoire poignante d’un jeune homme qui est confronté à la réalité de grandir trop vite, avec la lourde responsabilité de devoir avancer sans avoir eu le temps de se forger. À travers des moments simples et des émotions brutes, Alain André explore les défis de la jeunesse et de l&#039;indépendance. &quot;Simple, droit au but, et absolument génial&quot; selon Paul Marnier, ce roman frappe fort par sa sincérité et son impact émotionnel.'),
(19, '24 jours à flot', 'Fatima Rashid', 'Drame', '9782370833022', 'disponible', '2025-03-19 11:13:58', '24_jours_flot.jpg', '24 jours à flot raconte l&#039;histoire déchirante de migrants qui luttent pour leur survie alors qu&#039;ils entreprennent un voyage risqué vers une vie meilleure. À travers un récit poignant et terrifiant, Fatima Rashid met en lumière les épreuves et la résilience de ceux qui fuient la guerre et la pauvreté. &quot;Un récit terrifiant et déchirant d&#039;immigrants qui luttent pour leur vie&quot;, comme l’a souligné Pierre Dufour, ce livre est une réflexion sur la quête de dignité et de sécurité.'),
(20, 'Paris, mon amour : Une Histoire d&#039;Amour', 'Pierre Dufour', 'Histoire', '9782370833033', 'disponible', '2025-03-19 11:15:36', 'paris_mon_amour_une_histoire_d_039_amour.jpg', 'Paris, mon amour est une histoire d&#039;amour envoûtante et passionnée qui se déroule dans la Ville Lumière. Pierre Dufour nous plonge dans les émotions complexes de deux âmes perdues qui, au cœur de Paris, découvrent l’amour, les défis et les rêves d’un futur commun. Avec des descriptions poignantes et une narration immersive, ce roman touche à l’essence même des relations humaines et à la magie de la capitale française.'),
(21, 'Le conte du Faux Jumeau', 'Mia Thomas', 'Roman', '9782370833044', 'disponible', '2025-03-19 11:18:18', 'le_conte_du_faux_jumeau.jpg', 'Le conte du Faux Jumeau est un roman policier palpitant qui explore les thèmes des fausses identités et des secrets enfouis. Dans cette histoire, un meurtre prend une tournure inattendue lorsqu&#039;un faux jumeau entre en jeu, brouillant les pistes et créant des mystères que même les enquêteurs peinent à résoudre. Qualifiée par Marc Allen de &quot;l&#039;une des auteures de romans policiers à suivre absolument&quot;, Mia Thomas nous captive avec une intrigue captivante, remplie de suspense et de surprises.'),
(22, 'Différentes vies, les différentes motivations', 'Léa Dumortier', 'Drame', '9782370833051', 'disponible', '2025-03-19 11:19:41', 'diff_rentes_vies_les_diff_rentes_motivations.jpg', 'Différentes vies, les différentes motivations est un roman poignant qui explore la complexité des vies humaines à travers les choix et les raisons qui poussent chaque individu à suivre un chemin particulier. Léa Dumortier nous invite à réfléchir sur nos motivations profondes et à quel point elles peuvent être influencées par des événements extérieurs, des rêves et des aspirations personnelles. Ce best-seller en France a captivé de nombreux lecteurs avec sa profondeur émotionnelle et ses personnages attachants.'),
(23, 'Space', 'Olivia Wilson', 'Science Fiction', '9782370833068', 'disponible', '2025-03-19 11:22:12', 'space.jpg', 'Space emmène les lecteurs dans un voyage palpitant au-delà des étoiles, où les mystères de l&#039;univers et les aspirations humaines se rencontrent. Olivia Wilson propose un récit captivant d&#039;exploration, de survie et de découvertes, alors que les personnages s&#039;aventurent dans l&#039;espace inexploré, confrontés à des dangers inconnus et à des défis existentiels. Ce roman de science-fiction explore le désir humain de découvrir l&#039;inconnu, offrant une histoire à la fois profonde et pleine d&#039;action.'),
(24, 'La Yogini de 80 ans : Aventures sur la découverte de soi durant l&#039;âge d&#039;or', 'Martine André', 'Aventure', '9782370833075', 'disponible', '2025-03-19 11:24:01', 'la_yogini_de_80_ans_aventures_sur_la_d_couverte_de_soi_durant_l_039_ge_d_039_or.jpg', 'La Yogini de 80 ans est un livre inspirant qui raconte le parcours de Martine André dans sa quête de soi à un âge avancé. À travers son expérience de la pratique du yoga, elle nous montre qu&#039;il n&#039;est jamais trop tard pour découvrir de nouvelles facettes de soi-même. Ce livre prouve qu’il y a toujours quelque chose à apprendre et à explorer, même dans l’âge d&#039;or. Matthieu Durant décrit l’œuvre de Martine André comme une véritable leçon de vie et un témoignage émouvant de la résilience et de la recherche du bien-être.'),
(25, 'Maison dans des centaines de lieux', 'Alexandre Andrieux', 'Aventure', '9782370833082', 'disponible', '2025-03-19 11:25:35', 'maison_dans_des_centaines_de_lieux.jpg', 'Maison dans des centaines de lieux est un récit captivant d&#039;un voyage autour du monde, où Alexandre Andrieux partage ses expériences et découvertes dans chaque endroit où il a posé ses valises. Ce livre est bien plus qu&#039;un simple carnet de voyage ; c&#039;est une réflexion sur l&#039;idée de &quot;maison&quot; et sur le fait de se sentir chez soi, peu importe où l&#039;on se trouve. Ce best-seller n° 1 explore la diversité du monde et la richesse des cultures rencontrées au fil du voyage.'),
(26, 'Tout ce que j&#039;ai écrit', 'Romain Stévenin', 'Roman', '9782370833099', 'disponible', '2025-03-19 11:27:02', 'tout_ce_que_j_039_ai_crit.jpg', 'Tout ce que j&#039;ai écrit est un roman captivant qui plonge dans les pensées et les réflexions profondes de son auteur, Romain Stévenin. Ce livre est un recueil de ses écrits, où chaque mot a une signification particulière, un message puissant à transmettre. Avec des mots qui résonnent et une plume poignante, Stévenin nous livre un &quot;pur délice&quot;, comme le souligne A. Marnier dans le Journal La Montagne. Un best-seller qui ne laisse personne indifférent.'),
(27, 'Malheureux', 'Julien Marceau', 'Drame', '9782370833105', 'disponible', '2025-03-19 11:28:57', 'malheureux.jpg', 'Malheureux raconte l’histoire poignante d’un homme pris dans la tourmente de la tristesse, dans une pièce sombre et une rue silencieuse. L’histoire débute là où le malheur s’installe, explorant les nuances de la douleur, du regret, et des choix de vie. Une réflexion sur la souffrance humaine, un voyage intérieur qui révèle la quête de sens et de rédemption. Comme le suggère la maxime, &quot;toute histoire a un début, toute histoire a une fin...&quot;'),
(28, 'Les bases de l&amp;#039;uniforme', 'Annie Dumont', 'Science Fiction', '9782370833112', 'disponible', '2025-03-19 11:30:19', 'les_bases_de_l_amp_039_uniforme.jpg', 'Les bases de l&amp;#039;uniforme guide le lecteur dans la création d&amp;#039;un style personnel, simple et élégant. Annie Dumont présente les principes d&amp;#039;un uniforme minimaliste, conçu pour durer dans le temps, offrant ainsi une approche pratique et esthétique pour optimiser sa garde-robe. Ce livre inspirant a convaincu de nombreuses personnes, comme l’actrice Julie Atani qui a partagé : &amp;quot;Il va sans dire que j&amp;#039;ai réduit ma garde-robe après avoir lu ce livre. Inspirant !&amp;quot;'),
(29, 'Une étoile est née', 'Laurie Leblanc', 'Science Fiction', '9782370833129', 'disponible', '2025-03-19 11:37:34', 'une_toile_est_n_e.jpg', 'Une étoile est née de Laurie Leblanc raconte l’histoire d’une quête de soi, d’amour et de découvertes inattendues. L’auteure, connue pour son best-seller international Crépuscule en hiver, poursuit avec ce roman captivant l&#039;exploration des émotions humaines, où le destin d’un individu se transforme en un parcours lumineux à travers les épreuves et les rencontres marquantes.'),
(30, 'Le bout de l&#039;Univers', 'Alain Dumont', 'Science Fiction', '9782370833136', 'disponible', '2025-03-19 11:38:58', 'le_bout_de_l_039_univers.jpg', 'Le bout de l&#039;Univers est la suite tant attendue de La fin de demain, un récit captivant qui plonge les lecteurs dans un futur incertain, où les enjeux de l&#039;humanité se mêlent aux mystères cosmiques. L’histoire suit des personnages audacieux à la recherche de réponses à l&#039;inconnu, et cette aventure haletante vous laissera sans voix à chaque nouvelle révélation. Alain Dumont continue de tisser son univers fascinant avec des intrigues qui défient l’imagination.');

-- --------------------------------------------------------

--
-- Structure de la table `livres_categories`
--

DROP TABLE IF EXISTS `livres_categories`;
CREATE TABLE IF NOT EXISTS `livres_categories` (
  `id_livre` int NOT NULL,
  `id_categorie` int NOT NULL,
  PRIMARY KEY (`id_livre`,`id_categorie`),
  KEY `id_categorie` (`id_categorie`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livres_categories`
--

INSERT INTO `livres_categories` (`id_livre`, `id_categorie`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 1),
(7, 2),
(8, 3),
(9, 4),
(10, 5);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_livre` int NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  `expediteur` int NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_emprunt` (`id_livre`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `id_utilisateur`, `id_livre`, `contenu`, `date_envoi`, `expediteur`) VALUES
(38, 8, 2, 'Bonjour, le livre \'1984\' que vous avez réservé est maintenant disponible. Vous pouvez procéder à l\'emprunt.', '2025-03-17 15:47:42', 0),
(39, 8, 3, 'Bonjour, le livre \'L&#039;Alchimiste\' que vous avez réservé est maintenant disponible. Vous pouvez procéder à l\'emprunt.', '2025-03-17 15:47:43', 0);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id_reservation` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_livre` int DEFAULT NULL,
  `date_reservation` datetime NOT NULL,
  `notification_envoyee` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_reservation`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_livre` (`id_livre`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_utilisateur`, `id_livre`, `date_reservation`, `notification_envoyee`) VALUES
(30, 8, 8, '2024-12-24 11:23:45', 1),
(27, 6, 2, '2024-12-20 20:31:38', 1),
(29, 8, 2, '2024-12-24 11:17:59', 1),
(26, 6, 8, '2024-12-13 21:05:05', 1),
(18, 8, 2, '2024-12-13 14:48:10', 1),
(17, 6, 2, '2024-12-13 14:47:37', 1),
(31, 8, 2, '2024-12-24 11:26:43', 1),
(32, 8, 8, '2024-12-24 11:26:48', 1),
(33, 8, 6, '2024-12-24 11:26:54', 1),
(34, 8, 3, '2024-12-24 11:26:58', 1),
(35, 6, 2, '2024-12-24 11:32:54', 1),
(36, 8, 2, '2024-12-24 11:36:37', 1),
(37, 6, 8, '2024-12-24 11:38:55', 1),
(38, 8, 8, '2024-12-24 11:41:05', 1),
(39, 8, 2, '2024-12-24 11:46:42', 1),
(40, 8, 8, '2024-12-24 11:46:51', 1),
(41, 8, 6, '2024-12-24 11:46:56', 1),
(42, 8, 3, '2024-12-24 11:47:00', 1),
(43, 6, 2, '2024-12-24 11:50:43', 1),
(44, 6, 8, '2024-12-24 11:50:56', 1),
(45, 6, 6, '2024-12-24 11:51:01', 1),
(46, 6, 3, '2024-12-24 11:51:06', 1),
(47, 8, 2, '2024-12-24 11:52:51', 1),
(48, 8, 8, '2024-12-24 11:52:56', 1),
(49, 8, 6, '2024-12-24 11:53:03', 1),
(50, 8, 3, '2024-12-24 11:53:14', 1),
(51, 6, 2, '2024-12-24 11:56:55', 1),
(52, 6, 8, '2024-12-24 11:56:59', 1),
(53, 6, 6, '2024-12-24 11:57:03', 1),
(54, 6, 3, '2024-12-24 11:57:06', 1),
(55, 8, 2, '2025-02-03 13:51:36', 1),
(56, 8, 3, '2025-02-03 13:51:46', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','bibliothecaire','utilisateur') DEFAULT 'utilisateur',
  `photo` varchar(255) DEFAULT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `photo`, `date_inscription`) VALUES
(8, 'sadikou', 'sadikou', 'sadikou@gmail.com', '$2y$10$b1J9.6OOVHpBuXN2q/C9NOGrDxQWwvFXrCAcnL9WRXxG5d0hw3tPG', 'utilisateur', 'http://localhost/bibliotheque/assets/images/sadikou_at_gmail_dot_com.png', '2024-12-13 14:30:56'),
(7, 'hamid', 'hamid', 'hamid@gmail.com', '$2y$10$kz86zhCNT8NBYbx8nCeZGeLQisRY1mkQgfA.N8ewJFJW4DefNSiTS', 'admin', 'http://localhost/bibliotheque/assets/images/hamid_at_gmail_dot_com.JPG', '2024-12-13 12:04:20');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
