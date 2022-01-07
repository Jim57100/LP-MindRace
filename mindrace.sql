-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 07 jan. 2022 à 12:28
-- Version du serveur : 8.0.21
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mindrace`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_question` int NOT NULL,
  `valid` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question` (`id_question`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`id`, `label`, `id_question`, `valid`) VALUES
(1, 'Le Nil', 1, 1),
(2, 'Continent d’Asie', 2, 1),
(3, 'Londres', 3, 1),
(4, 'Paris', 4, 1),
(5, 'Neuf pays', 5, 1),
(6, 'Pierre de diamant.', 6, 1),
(7, 'Amérique du Sud', 7, 1),
(8, 'Espagne', 8, 1),
(9, 'Le Mont Kilimanjaro', 9, 1),
(10, 'le mont Everest', 10, 1),
(11, '32', 11, 1),
(12, 'Paris', 12, 1),
(13, 'Lithium ', 14, 1),
(14, 'Gaz carbonique.', 15, 1),
(15, 'Radium', 13, 1),
(16, 'La baleine bleue', 16, 1),
(17, 'Poulpe', 17, 1),
(18, 'Thon', 18, 1),
(19, 'Une étoile de mer', 19, 1),
(20, 'Singe', 20, 1),
(21, 'Chameau', 21, 1),
(22, 'Desm', 22, 1),
(23, 'Chameau', 23, 1),
(24, 'Porc', 24, 1),
(25, '22 mois', 25, 1),
(26, 'La girafe', 26, 1),
(27, 'Bleu', 27, 1),
(28, 'Colibri', 28, 1),
(29, 'Autruche', 29, 1),
(30, 'Hyène', 30, 1),
(31, '5', 31, 1),
(32, 'Les Comores', 32, 1),
(33, 'A l\'est', 33, 1),
(34, 'Soudan', 34, 1),
(35, 'Chine', 35, 1),
(36, 'Syrie', 36, 1),
(37, 'Libellule', 37, 1),
(38, 'L\'os lacrymal', 38, 1),
(39, 'Le foie', 39, 1),
(40, 'L\'hypophyse', 40, 1),
(41, ' Glande pituitaire', 40, 0),
(42, '33', 41, 1),
(43, '10 millions de couleurs', 42, 1),
(44, 'La langue', 43, 1),
(45, 'Os de coccyx', 44, 1),
(46, 'Thyroïde', 45, 1),
(47, 'Le pied', 46, 1),
(48, 'La main', 46, 0),
(49, '66%', 47, 1),
(50, 'Télescope Hubble', 48, 1),
(51, 'Non, c\'est pareil que sur Terre', 49, 0),
(52, 'Le son ne voyage dans l’espace', 49, 1),
(53, '5x plus rapide que sur Terre', 49, 0),
(54, 'Blanc', 50, 1),
(55, 'Brésil', 51, 1),
(56, 'Blanc', 52, 1),
(57, ' En 1952 à NY', 53, 1),
(58, 'Dans les reins', 54, 1),
(59, 'Oui', 55, 1),
(60, 'De 9 à 12 secondes', 56, 1),
(61, 'Le mont Kilimandjaro', 57, 1),
(62, 'Amérique du Nord', 58, 1),
(63, '620 muscles', 59, 1),
(64, 'la mer Caspienne', 60, 1),
(65, 'Canada', 61, 1),
(66, 'Vénus', 62, 1),
(67, 'Wong Kar-Wai', 63, 1),
(68, 'Chan Feng Zhao', 63, 0),
(69, 'Zhang Yimou', 63, 0),
(70, 'Montaigne', 64, 1),
(71, 'Voltaire', 64, 0),
(72, 'Diderot', 64, 0),
(73, 'Socrate ', 65, 0),
(74, 'Hegel', 65, 0),
(75, 'Descartes', 65, 1),
(76, 'Un chien', 66, 1),
(77, 'Salzbourg', 67, 1),
(78, 'Turin', 67, 0),
(79, 'Vienne', 67, 0),
(80, 'Londres', 68, 1),
(81, 'Paris', 68, 0),
(82, 'New York', 68, 0),
(83, 'Il sourd', 69, 1),
(84, 'Un poisson', 70, 1),
(85, 'Arizona', 71, 1),
(86, 'Colorado', 71, 0),
(87, 'Minnesota', 71, 0),
(88, 'Célibat', 72, 0),
(89, 'Débauche', 72, 1),
(90, 'Prudence', 72, 0),
(91, 'Un skippeur', 73, 0),
(92, 'Un catamaran', 73, 0),
(93, 'Un trimaran', 73, 1),
(94, 'Le renard', 74, 1),
(95, 'Un lion', 75, 1),
(96, 'L\'Arabie Saoudite', 76, 0),
(97, 'L\'Egypte', 76, 0),
(98, 'L\'Indonésie', 76, 1),
(99, 'Sydney', 77, 0),
(100, 'Perth', 77, 0),
(101, 'Canberra', 77, 1),
(102, 'Un canard', 78, 1),
(103, 'Les naines brunes', 79, 1),
(104, 'Le mont Elbrus', 80, 1),
(105, 'Zimbabwe', 81, 1),
(106, 'Zimbabwe', 82, 0),
(107, 'Lula', 82, 0),
(108, 'Nicolas Maduro', 82, 1),
(109, '375 ap. JC', 83, 0),
(110, '476 ap. JC', 83, 1),
(111, '496 ap. JC', 83, 0),
(112, 'Remettre quelque-chose à plus tard', 84, 1),
(113, 'Ingrid Bergman', 85, 1),
(114, 'Greta Garbo', 85, 0),
(115, 'Claudia Cardinale', 85, 0),
(116, 'La décision de lancer une bombe nucléaire sur Hiroshima', 86, 0),
(117, 'La débarquement allié en Normandie', 86, 1),
(118, 'Le débarquement américain en Afrique du Nord', 86, 0),
(119, 'Montesquieu', 87, 1),
(120, 'Molière', 87, 0),
(121, 'Voltaire', 87, 0),
(122, 'Francis Ford Coppola', 88, 1),
(123, 'Michael Cimino', 88, 0),
(124, 'Steven Spielberg', 88, 0),
(125, 'l\'Autriche-Hongrie', 89, 1),
(126, 'Athènes', 90, 1),
(127, 'Corinthe', 90, 0),
(128, 'Thèbes', 90, 0),
(129, 'La Crimée', 91, 1),
(130, 'La Tchétchénie', 91, 0),
(131, 'L\'Ossetie', 91, 0),
(132, 'Portugal', 92, 1),
(133, 'Gênes', 92, 0),
(134, 'Venise', 92, 0),
(135, 'Dire Straits', 93, 1),
(136, 'Genesis', 93, 0),
(137, 'Police', 93, 0),
(138, 'Environ 4808m', 94, 1),
(139, 'Environ 3808m', 94, 0),
(140, 'Environ 2808m', 94, 0),
(141, 'Qui se montre dans toute sa superbe', 95, 0),
(142, 'Qui a une importance particulière', 95, 0),
(143, 'Qui s\'ajoute inutilement', 95, 1),
(144, 'Le Tigre', 96, 0),
(145, 'L\'Oronte', 96, 1),
(146, 'Le Jourdain', 96, 0),
(147, 'Kazakhstan', 97, 1),
(148, '27/02/1594', 98, 1),
(149, 'néoplastisme', 99, 1),
(150, 'aristotélisme', 99, 0),
(151, 'scepticisme', 99, 0),
(152, 'La théorie de la gravitation universelle', 100, 1),
(153, 'Chèvre', 101, 1),
(154, 'Istanbul', 102, 1),
(155, 'Rhodes', 102, 0),
(156, 'Moscou', 102, 0),
(157, 'Pays-bas', 103, 1),
(158, 'Belgique', 103, 0),
(159, 'Angleterre', 103, 0),
(160, 'The Final Cute', 104, 0),
(161, 'The Wall', 104, 1),
(162, 'Wish You Were Here', 104, 0),
(163, 'Wagner', 105, 1),
(164, 'Richard Strausse', 105, 0),
(165, 'Verdi', 105, 0),
(166, 'Kingdom of Heaven', 106, 0),
(167, 'Libération', 106, 0),
(168, 'Braveheart', 106, 1),
(169, 'Le saxophone', 107, 0),
(170, 'Le violon', 107, 1),
(171, 'Le piano', 107, 0),
(172, 'Le Misanthrope', 108, 1),
(173, 'Dom Juan', 108, 0),
(174, 'Le malade imaginaire', 108, 0),
(175, 'Reservoir Dogs', 109, 0),
(176, 'Usual Suspects', 109, 1),
(177, 'Seven', 109, 0),
(178, '756 ap JC', 110, 0),
(179, '800 ap JC', 110, 1),
(180, '843 ap JC', 110, 0),
(181, 'Rousseau', 111, 1),
(182, 'Mirabeau', 111, 0),
(183, 'Chateaubriand', 111, 0),
(184, 'Egypte', 112, 0),
(185, 'Iran', 112, 1),
(186, 'Jordanie', 112, 0),
(187, 'Yammassoukro', 113, 1),
(188, 'Le Meilleur des Mondes', 114, 0),
(189, '1984', 114, 1),
(190, 'Si c\'est un homme', 114, 0),
(191, 'Churchill/Roosevelt/Staline', 115, 1),
(192, 'Truman/Molotov/Attle', 115, 0),
(193, 'De Gaulle/Churchill/Truman', 115, 0),
(194, 'The Big Lebowski', 116, 1),
(195, 'Barton Fink', 116, 0),
(196, 'Fargo', 116, 0),
(197, 'De 220 millions à 230 millions d’années terrestres', 117, 1),
(198, 'Géorgie', 118, 1),
(199, '193', 119, 1),
(200, 'Ganymède, l’une des lunes de Jupiter.', 120, 1),
(202, 'Brennos', 122, 1),
(203, 'Place Maïdan', 123, 1),
(204, 'Alexandre le Grand', 124, 1),
(205, 'Sangaris', 125, 1),
(206, ' William Booth', 126, 1),
(207, '1975', 127, 1),
(208, '149 597 870 kilomètres', 128, 1),
(209, 'Prince saoudien: Sultan bin Salman bin Abdulaziz Al Saud.', 129, 1);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `level` int NOT NULL,
  `answers` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `answers` (`answers`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `label`, `level`, `answers`) VALUES
(1, 'Quel est le plus long fleuve du monde ?', 1, 1),
(2, 'Quel est le continent sur lequel se trouve l’état de Palestine ?', 1, 1),
(3, 'Quelle est la plus grande ville du continent européen ?  ', 1, 1),
(4, 'Quelle est la ville de l’amour et de la beauté?', 1, 1),
(5, 'Combien de pays arabes y a-t-il sur le continent africain?', 1, 1),
(6, 'Quels sont les types de pierres précieuses les plus solides ?', 1, 1),
(7, 'Sur quel continent se trouve le fleuve Amazone?', 1, 1),
(8, 'Quel pays s’appelle le pays des lapins?', 1, 1),
(9, 'Quelle est la plus haute montagne du continent africain?', 1, 1),
(10, 'Quelle est la plus haute montagne du monde ?', 1, 1),
(11, 'Combien de dents un humain adulte a-t-il ?', 1, 1),
(12, 'Où se trouve la tour Eiffel?', 1, 1),
(13, 'Quel est le métal le plus cher?', 1, 1),
(14, 'Quel est le métal le plus léger?', 1, 1),
(15, 'Quel gaz est utilisé pour éteindre le feu?', 1, 1),
(16, 'Quelle est la plus grande créature du monde?', 1, 1),
(17, 'Quel est l’animal le plus intelligent au monde?', 1, 1),
(18, 'Quelle est la créature marine la plus rapide du monde ?', 1, 1),
(19, 'Qui est l’animal dont les bras repoussent s’ils sont coupés?', 1, 1),
(20, 'Quel est l’animal qui infecte la rougeole comme une personne?', 1, 1),
(21, 'Quel est l’animal à mémoire le plus puissant au monde?', 1, 1),
(22, 'Comment s’appelle le cœur d\'un bébé ours?', 1, 1),
(23, 'Qui est l’animal qui a été nommé Umm Hail?', 1, 1),
(24, 'Quel est l’animal qui n’a pas la capacité de flotter dans l’eau?', 1, 1),
(25, 'Quelle est la durée de la grossesse de l’éléphant?', 1, 1),
(26, 'Quel est le plus grand animal sur Terre?', 1, 1),
(27, 'De quelle couleur est le sang d’une pieuvre?', 1, 1),
(28, 'Quel est le nom du plus petit oiseau du globe?', 1, 1),
(29, 'Quel est le plus gros oiseau du monde?', 1, 1),
(30, 'Qui est l’ennemi du premier lion?', 1, 1),
(31, 'Quel est le nombre d’yeux d’une abeille?', 1, 1),
(32, 'Dans quel océan se trouvent les Comores ?', 1, 1),
(33, 'Où se trouve la Jordanie par rapport à l’État de Palestine?', 1, 1),
(34, 'Quel pays occupe la plus grande superficie du continent africain?', 1, 1),
(35, 'Dans quel pays la poudre à canon a-t-elle été inventée?', 1, 1),
(36, 'Dans quel pays est né Abu Alaa Al-Maari?', 1, 1),
(37, 'Quel est l’insecte le plus rapide du monde?', 1, 1),
(38, ' Quel est l’os le plus vulnérable du corps humain à la fracture?', 1, 1),
(39, 'Quelle est la plus grande glande du corps humain?', 1, 1),
(40, 'Quelle est la glande principale du corps humain?', 1, 2),
(41, 'Combien de vertèbres dans la colonne vertébrale?', 1, 1),
(42, 'Combien de couleurs l’œil humain distingue-t-il?', 1, 1),
(43, 'Quel est le muscle le plus flexible du corps humain?', 1, 1),
(44, 'Quel est le plus petit os de la colonne vertébrale humaine?', 1, 1),
(45, 'Quelle est la glande responsable de la régulation du métabolisme dans le corps humain?', 1, 1),
(46, 'Dans quelle partie du corps humain se trouve l’os scaphoïde?', 1, 2),
(47, 'Combien d’eau y a-t-il dans le corps humain?', 1, 1),
(48, 'Quel est le nom du télescope spatial le plus célèbre?', 1, 1),
(49, 'Le son voyage-t-il plus vite dans l’espace?', 1, 3),
(50, 'De quelle couleur est le soleil?', 1, 1),
(51, 'Quel est le plus grand pays d’Amérique du Sud par superficie?', 1, 1),
(52, 'Quelle est la couleur du cheval blanc d\'Henry IV ?', 1, 1),
(53, 'Quand la première greffe de cheveux a-t-elle été réalisée?', 2, 1),
(54, 'Dans quel organe du corps humain le flux sanguin le plus fort se produit-il?', 2, 1),
(55, 'Les étoiles peuvent-elles se transformer en planètes?', 2, 1),
(56, 'Combien de temps une personne peut-elle survivre dans l’espace sans porter de combinaison spatiale?', 2, 1),
(57, 'Quelle est la plus haute montagne autoportante du monde?', 2, 1),
(58, ' Quel est le troisième plus grand continent de la Terre en termes de taille de la surface de la Terre?', 2, 1),
(59, ' Combien de muscles dans le corps humain?', 2, 1),
(60, 'Qu’est-ce qui est généralement considéré comme le plus grand lac de la planète ?', 2, 1),
(61, 'Quel pays a le plus long littoral du monde?', 2, 1),
(62, 'Quelle est la planète la plus chaude?', 2, 1),
(63, 'Qui a réalisé le film \"In the mood for love\" ?', 2, 3),
(64, 'De l\'oeuvre de quel écrivain est tirée la célèbre question \"Que sais-je?\" ?', 2, 3),
(65, 'Quel philosophe conclut \"Je pense, donc je suis. \" ?', 2, 3),
(66, 'Quelle race d\'animal est un \"briard\" ?', 2, 1),
(67, 'Où est né Mozart?', 2, 3),
(68, 'Quelle est la première ville du monde à s\'être dôté d\'un métro?', 2, 3),
(69, 'Quelle est la 3ème personne du singulier du présent de l\'indicatif de sourdre?', 2, 1),
(70, 'Quel genre d\'animal est un silure?', 2, 1),
(71, 'Dans quel état se trouve le Grand Canyon?', 2, 3),
(72, 'Parmis les mots suivant, lequel est un antonyme de chasteté?', 2, 3),
(73, 'Comment se nomme un bateau à trois coques?', 2, 3),
(74, 'Quel animal était appelé au Moyen Age le goupil?', 2, 1),
(75, 'De quel animal le sphynx de Gizeh a-t-il le corps?', 2, 1),
(76, 'Quel est le plus grand pays musulman du monde en population?', 2, 3),
(77, 'Quel est la capitale de l\'Australie', 2, 3),
(78, 'Quel animal est le colvert?', 2, 1),
(79, 'Quel type d\'étoile permet la transformation en planète', 3, 1),
(80, 'Quelle est la plus haute montagne d’Europe ?', 3, 1),
(81, 'Victoria Falls est située à la frontière entre la Zambie et quel autre pays?', 3, 1),
(82, 'Parmi les hommes politiques suivants, lequel a succedé a Hugo Chavez en tant que Président du Venezuela?', 3, 3),
(83, 'Quelle année retient-on habituellement comme l\'année de la chute de l\'Empire romain d\'Occident?', 3, 3),
(84, 'Que signifie \"procrastiner\" ?', 3, 1),
(85, 'Quelle célèbre actrice peut-on admirer dans le film de 1942 \"Casablanca\"?', 3, 3),
(86, 'Qu\'était-ce que l\'opération Overlord?', 3, 3),
(87, 'Quel célèbre philosophe et écrivain publia en 1721 les \"Lettres persanes\" pour y critiquer la société française ?', 3, 3),
(88, 'A quel réalisateur doit-on \"Apocalypse Now\"?', 3, 3),
(89, 'L\'archeduc François Ferdinand assassiné le 28 janvier 1914 était l\'héritier de ...', 3, 1),
(90, 'De quelle cité venait Thucydide ?', 3, 3),
(91, 'Quelle région d\'Ukraine la Russie a t-elle annexée en 2014?', 3, 3),
(92, 'D\'où vient Vasco de Gama?', 3, 3),
(93, 'Quel groupe anglais avait pour leader Malk Knopfler dès 1978?', 3, 3),
(94, 'Quel est la hauteur du Mont Blanc?', 3, 3),
(95, 'Que signifie \"superfetatoire\" ?', 3, 3),
(96, 'Quel est le fleuve qui traverse le Liban, la Syrie et la Turquie?', 3, 1),
(97, 'Quel est le plus grand pays enclavé du monde ?', 4, 1),
(98, 'En quelle date Henry IV a t\'il été couronné ?', 4, 1),
(99, 'De quel courant philosophique Plotin est-il le grand représentant ?', 4, 3),
(100, 'Quelle théroie doit-on à Isaac Newton ?', 4, 1),
(101, 'Avec la laine de quel animal fait-on du cachemire?', 4, 1),
(102, 'Parmi les villes suivantes, laquelle est à la fois en Europe et en Asie?', 4, 3),
(103, 'Quel pays a pour devise \"Je maintiendrai\" ?', 4, 3),
(104, 'Quel est l\'album le plus vendu des Pink Floyd?', 4, 3),
(105, 'A quel compositeur doit-on \"La Chevauchée des Walkyries\" ?', 4, 3),
(106, 'Quel film raconte l\'histoire d\'un chef guerrier rebelle à une domination étrangère ?', 4, 3),
(107, 'Quel était l\'instrument de prédilection de Yehudi Menuhin?', 4, 3),
(108, 'De quel pièce de Molière Alcest et Célimène sont-ils les protagonistes?', 4, 3),
(109, 'Dans quel film retrouve t-on le personnage de Keyser Söze?', 4, 3),
(110, 'En quelle année Charlemagne s\'est-il fait sacrer empereur ? ', 5, 3),
(111, 'A quel célèbre écrivain et philosophe doit-on le \"Contrat social\" et \"La nouvelle Héloïse\" ?', 5, 3),
(112, 'A la tête de quel pays Hassan Rohani a t\'il été élu en 2013?', 5, 3),
(113, 'Quel est la capitale de la Côte d\'Ivoire?', 5, 1),
(114, 'Dans quel roman de Georges Orwell trouve t-on la figure totalitaire de Big Brother ?', 5, 3),
(115, 'Quel célèbre dirigeants se sont rénis à Yalta en févier 1945?', 5, 3),
(116, 'Quel film met en scène le personnage d\'un fainéant sans emploi passioné de Bowling?', 5, 3),
(117, 'Quelle est la durée de l’année galactique?', 5, 1),
(118, 'Dans quel pays cette manifestation a-t-elle eu lieu? : Révolution des roses en 2003', 5, 1),
(119, 'Quel est le nombre d’États membres de l’ONU dans le monde en 2014?', 5, 1),
(120, 'Quelle est la plus grande lune du système solaire?', 5, 1),
(122, 'Qui aurait dit \"Malheur aux vaincus\" après avoir mis Rome à sac?', 6, 1),
(123, 'Sur quelle place les manifestants en 2013 favorables à un rapprochement de l\'Ukraine avec l\'UE se sont-ils réunis ?', 6, 1),
(124, '\"Ôte-toi de mon Soleil\". A qui Diogène s\'adresse t-il?', 6, 1),
(125, 'Quel est la nom de l\'opération française en Cenrafique lancée en 2013?', 6, 1),
(126, 'Quelle figure historique a vécu entre ces années? 1829-1912', 6, 1),
(127, 'Quand ce pays a-t-il accédé à l’indépendance? Papouasie-Nouvelle-Guinée ,Australie', 6, 1),
(128, 'À quelle distance est le soleil de la terre?', 6, 1),
(129, 'Qui est le premier astronaute arabe?', 6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `mail` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `roles` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `firstName` varchar(128) DEFAULT NULL,
  `lastName` varchar(128) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `uniquId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `mail`, `roles`, `firstName`, `lastName`, `createdAt`, `uniquId`) VALUES
(32, 'Ines', '$2y$10$oz9QqXt2yLZm1ogR26xnZuBNd1frg4/gZRKIDqn/PCEvg.eQUt.mO', 'ines.perria@gmail.com', 'ROLE_ADMIN', 'Ines', 'Perria', '2021-12-27 09:40:31', 4759),



--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`id_question`) REFERENCES `questions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
