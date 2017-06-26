-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 26/06/2017 às 20:58
-- Versão do servidor: 5.6.35
-- Versão do PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gluecatc_radardobem`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_foto`
--

CREATE TABLE `tb_foto` (
  `ci_foto` int(20) UNSIGNED NOT NULL,
  `cd_local` int(20) UNSIGNED NOT NULL,
  `cd_usuario` int(20) UNSIGNED NOT NULL,
  `ds_path` varchar(250) NOT NULL,
  `ds_hash` varchar(250) NOT NULL,
  `dt_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `tb_foto`
--

INSERT INTO `tb_foto` (`ci_foto`, `cd_local`, `cd_usuario`, `ds_path`, `ds_hash`, `dt_data`) VALUES
(1, 1, 2, '../uploads/up_image_00/', 'df419a64093ba54846c57905fba9a90e', '2017-04-10 11:09:07'),
(2, 1, 2, '../uploads/up_image_00/', '206c8269fd5b4b251303eb93f60cc7b8', '2017-04-10 11:09:15'),
(4, 1, 2, '../uploads/up_image_00/', 'f60a7c29f5f679d7cc585a857aa1029e', '2017-04-10 11:11:14'),
(5, 1, 2, '../uploads/up_image_00/', '07f101a35c0080c566d9327e1f290ac4', '2017-04-10 11:11:19'),
(6, 2, 2, '../uploads/up_image_00/', 'de531e2b8d475e2cb774918395358bf9', '2017-04-10 20:40:24'),
(7, 2, 2, '../uploads/up_image_00/', 'cc07e76d1c52c05be4f8ab67b7c2b787', '2017-04-10 20:40:31'),
(8, 2, 2, '../uploads/up_image_00/', '113e703b2e98e44e41954a4b35af7f66', '2017-04-10 20:40:35'),
(9, 2, 2, '../uploads/up_image_00/', '43bca4cd5d9c95036cdd294c5e60c7bf', '2017-04-10 20:40:42'),
(10, 2, 2, '../uploads/up_image_00/', 'a774dfe4824cad6e1ee2e30e02ac5c21', '2017-04-10 20:40:46'),
(11, 2, 2, '../uploads/up_image_00/', '392b578423d17eb782713fbec66f1d06', '2017-04-10 20:41:21'),
(12, 3, 2, '../uploads/up_image_00/', '5b4152356c78740f585b82fbecb18e26', '2017-04-11 02:01:06'),
(13, 3, 2, '../uploads/up_image_00/', '095cae76fdd5655d189edbbf9c99541d', '2017-04-11 02:01:14'),
(14, 3, 2, '../uploads/up_image_00/', 'a1342067b00b3add0271b2509948495b', '2017-04-11 02:01:20'),
(15, 3, 2, '../uploads/up_image_00/', '72c1cccbf374cf907c45bfeb8708568a', '2017-04-11 02:01:25'),
(16, 3, 2, '../uploads/up_image_00/', '495c7296c50f7d4232fdd7aad2a12cb1', '2017-04-11 02:01:31'),
(17, 3, 2, '../uploads/up_image_00/', '61c6f9f4fd30a1219e0063d9fac6afeb', '2017-04-11 02:02:17'),
(18, 4, 2, '../uploads/up_image_00/', '58448785d02fe07ea7f2343125302d7b', '2017-04-11 02:12:45'),
(19, 4, 2, '../uploads/up_image_00/', '5565ca19b4c3f36eebc13957d85ab1ec', '2017-04-11 02:12:50'),
(20, 4, 2, '../uploads/up_image_00/', '8a1f6c908a4ef26ff3c1e88e1d1c9ecd', '2017-04-11 02:12:55'),
(21, 4, 2, '../uploads/up_image_00/', '5d4db67db183b5391edd7d3da0d4d149', '2017-04-11 02:13:03'),
(22, 4, 2, '../uploads/up_image_00/', 'ab314c26ae251ad81ca10913c878b86f', '2017-04-11 02:13:07'),
(23, 4, 2, '../uploads/up_image_00/', 'dc8fc002f56732466814e371f6288058', '2017-04-11 02:13:11'),
(24, 6, 2, '../uploads/up_image_00/', '5ef163a9310cbdf615ae8f83b3bd7d1a', '2017-04-11 13:26:26'),
(25, 6, 2, '../uploads/up_image_00/', '198db7b1ff230f98ea0b886b824de0ba', '2017-04-11 13:26:37'),
(26, 6, 2, '../uploads/up_image_00/', 'e40a19675be02fa09b9fef6b5e8737a1', '2017-04-11 13:26:49'),
(27, 6, 2, '../uploads/up_image_00/', '51560b59c55ccead1e39f47737424b09', '2017-04-11 13:27:05'),
(28, 6, 2, '../uploads/up_image_00/', '68e8ece8a54f51a9d330d499476179fa', '2017-04-11 13:27:13'),
(29, 6, 2, '../uploads/up_image_00/', '65632a6362054e9694e48ba51e19a1bc', '2017-04-11 13:27:18'),
(30, 6, 2, '../uploads/up_image_00/', 'f01ef4a9e9afa3993f3cd23c0f8ca21f', '2017-04-11 13:27:35'),
(31, 6, 2, '../uploads/up_image_00/', 'd8c1f097cea9994ef1ddb20542ca85cd', '2017-04-11 13:27:41'),
(32, 7, 2, '../uploads/up_image_00/', 'df0fc9cb0416e3a6540ba1f36177b8ef', '2017-04-12 11:41:28'),
(33, 7, 2, '../uploads/up_image_00/', '0eb0a6e3dc78f3f2564a361670eba661', '2017-04-12 11:41:32'),
(34, 7, 2, '../uploads/up_image_00/', '01a3e3bcebc768510ecd76efd516ec4b', '2017-04-12 11:41:37'),
(35, 7, 2, '../uploads/up_image_00/', 'b118ae7dabc564b17c96467b65c3e0b3', '2017-04-12 11:41:41'),
(36, 8, 2, '../uploads/up_image_00/', '2765eaaf6af519e269cb7853ec8d8b75', '2017-04-12 12:07:30'),
(37, 8, 2, '../uploads/up_image_00/', '4a206918340901b453346c8a916d7e3a', '2017-04-12 12:07:35'),
(38, 8, 2, '../uploads/up_image_00/', 'e07fe5fcf39512b30bccb19e33e0dd86', '2017-04-12 12:07:39'),
(39, 8, 2, '../uploads/up_image_00/', 'e2b82ac754d3c5300cc9e8fd6f3021eb', '2017-04-12 12:07:43'),
(40, 8, 2, '../uploads/up_image_00/', 'd4767a5dd128d1151c4b0cdc131f95d8', '2017-04-12 12:07:47'),
(41, 9, 2, '../uploads/up_image_00/', 'ce093910c2994990d298b7a24de65691', '2017-04-12 12:53:17'),
(42, 9, 2, '../uploads/up_image_00/', '900978e66fcd9f6047c636a158117d3f', '2017-04-12 12:53:26'),
(43, 9, 2, '../uploads/up_image_00/', '482450ed668e7a73ef3293a6b7b8b9e0', '2017-04-12 12:53:30'),
(44, 9, 2, '../uploads/up_image_00/', 'a7a7dcec624a6a3cc5cd311d5a4c31f8', '2017-04-12 12:53:38'),
(46, 10, 2, '../uploads/up_image_00/', '8dfc28fbad6bddd17506992398298b28', '2017-04-12 13:14:44'),
(47, 10, 2, '../uploads/up_image_00/', '6f3ddef91824b7e68096d72684a4115c', '2017-04-12 13:14:52'),
(48, 10, 2, '../uploads/up_image_00/', '385baac70dbbad55e22c5f50a227614e', '2017-04-12 13:15:02'),
(49, 10, 2, '../uploads/up_image_00/', 'd8de149b6c3ade75c2a61decb67837ee', '2017-04-12 13:15:06'),
(50, 10, 2, '../uploads/up_image_00/', '7f27103d302d0590b790cf0bd76d31da', '2017-04-12 13:15:09'),
(51, 11, 2, '../uploads/up_image_00/', 'a116d2803950ab926e1ef291793c8a80', '2017-04-12 13:26:44'),
(52, 11, 2, '../uploads/up_image_00/', 'decc596556303fb31485ea152dad01a8', '2017-04-12 13:26:52'),
(54, 11, 2, '../uploads/up_image_00/', '96230cce6af80f0b87a089a72300b84f', '2017-04-12 13:27:04'),
(55, 11, 2, '../uploads/up_image_00/', 'fa88e4b2e65641d011d117082f54e325', '2017-04-12 13:27:10'),
(56, 11, 2, '../uploads/up_image_00/', '2e9f3cc81ee610daf4ac5fca7d2daca5', '2017-04-12 13:27:13'),
(57, 5, 2, '../uploads/up_image_00/', '2f9933d9145843338a84e7ad2016e845', '2017-04-12 13:41:58'),
(58, 5, 2, '../uploads/up_image_00/', 'ac2debe49bcb6d3ebf379e48c10ea7ec', '2017-04-12 13:42:03'),
(59, 5, 2, '../uploads/up_image_00/', '973c2e0214fa41ce901362d9e93bbf29', '2017-04-12 13:42:07'),
(60, 12, 2, '../uploads/up_image_00/', '4b6895346bb74a52e3ed60ba473c9811', '2017-04-12 13:59:04'),
(61, 12, 2, '../uploads/up_image_00/', '1b1a6ee32ec31a4142a52b3b5182a7d9', '2017-04-12 13:59:16'),
(62, 12, 2, '../uploads/up_image_00/', 'b39481d74a37e92352bfe8d71da90d18', '2017-04-12 13:59:21'),
(63, 12, 2, '../uploads/up_image_00/', '6d5e90c2a7ae5af2b2f2f0d39e824184', '2017-04-12 13:59:24'),
(64, 12, 2, '../uploads/up_image_00/', '1ed12a4f6131840418a616893139055e', '2017-04-12 13:59:29'),
(65, 12, 2, '../uploads/up_image_00/', '90c16375094a028efe0c334d004b561a', '2017-04-12 13:59:35'),
(66, 12, 2, '../uploads/up_image_00/', '2d2f5fce3ed0c174b54aeb868d6a221b', '2017-04-12 13:59:40'),
(67, 12, 2, '../uploads/up_image_00/', 'c27e24d98a20615b98cf8d2a0061f054', '2017-04-12 13:59:45'),
(68, 12, 2, '../uploads/up_image_00/', 'f021e21e328e4432e229de0308283078', '2017-04-12 13:59:49'),
(87, 13, 2, '../uploads/up_image_00/', 'c97e828b13db2fb59b4c3dd9ec9b8131', '2017-04-15 19:12:17'),
(85, 13, 2, '../uploads/up_image_00/', 'b41ad57fa04df0795f9bc2d4e41647f9', '2017-04-15 19:12:04'),
(86, 13, 2, '../uploads/up_image_00/', 'efecab33b628ecac5257b35ca6998faf', '2017-04-15 19:12:10'),
(73, 14, 2, '../uploads/up_image_00/', '33eb25894f9fc27bebecbb7facadb70a', '2017-04-14 15:30:26'),
(74, 14, 2, '../uploads/up_image_00/', '32687559d21b0e98a4611f68215ef97f', '2017-04-14 15:30:41'),
(75, 14, 2, '../uploads/up_image_00/', '275d50b2e80ca400c35bfd4023530922', '2017-04-14 15:30:54'),
(76, 14, 2, '../uploads/up_image_00/', '83f5d25657a0ba08080debcb30447a7e', '2017-04-14 15:31:03'),
(77, 15, 2, '../uploads/up_image_00/', '57f9c86f3211ab93f3368115afb0feb5', '2017-04-14 16:22:07'),
(78, 15, 2, '../uploads/up_image_00/', 'a53173c5a80c237ba8c4bf9e58947164', '2017-04-14 16:22:21'),
(79, 15, 2, '../uploads/up_image_00/', 'de1f6c63c3f4aac76aea1051b02ec3fa', '2017-04-14 16:22:30'),
(80, 15, 2, '../uploads/up_image_00/', '9555ea6b45fd81b5e3fb628a4c77aa3c', '2017-04-14 16:22:38'),
(81, 15, 2, '../uploads/up_image_00/', '0d02e442990478e13f4fbb385448af56', '2017-04-14 16:22:50'),
(82, 15, 2, '../uploads/up_image_00/', '2c9f95c52cd714a38c49e666275136a4', '2017-04-14 16:23:02'),
(83, 15, 2, '../uploads/up_image_00/', 'b010ad2351156211b34feecb50ca660d', '2017-04-14 16:23:20'),
(84, 15, 2, '../uploads/up_image_00/', '8882f9f9c62d946bbb4086ccd2e01368', '2017-04-14 16:23:39'),
(88, 13, 2, '../uploads/up_image_00/', '7088a7eff889a084801f2b27379ef7bd', '2017-04-15 19:12:30'),
(89, 13, 2, '../uploads/up_image_00/', '5c45550fba44d26b484e8c32deef985c', '2017-04-15 19:12:49'),
(90, 13, 2, '../uploads/up_image_00/', 'd47d533b6fe05592cf8ad7bd04ac2b5e', '2017-04-15 19:12:57'),
(91, 13, 2, '../uploads/up_image_00/', 'bafb4082499132acd64a48c3b024d6f5', '2017-04-15 19:13:04'),
(92, 16, 1, '../uploads/up_image_00/', 'df06dbc4c89d8483ec7c005c3ce60b70', '2017-05-21 13:19:33'),
(93, 16, 1, '../uploads/up_image_00/', '056c45818645c26a4235a4ff54f7633a', '2017-05-21 13:19:43'),
(94, 16, 1, '../uploads/up_image_00/', 'a3958c2b3da619e33e28b9b5fbebfc28', '2017-05-21 13:19:50'),
(95, 16, 1, '../uploads/up_image_00/', '6340b0400344bac01db404e3dacf3a06', '2017-05-21 13:19:55'),
(96, 16, 1, '../uploads/up_image_00/', '2027fdf5baa3453aec8a8ded3e0ef825', '2017-05-21 13:20:01'),
(97, 16, 1, '../uploads/up_image_00/', '23a963303f4bf8eaf9b9fb9887ea1025', '2017-05-21 13:20:07'),
(98, 16, 1, '../uploads/up_image_00/', '689cfbb334f85ddcb90bc2f2524bf6c9', '2017-05-21 13:20:12'),
(99, 6, 2, '../uploads/up_image_00/', 'c7115a1aad036f97ec20005c2286c2ea', '2017-06-01 13:28:21'),
(100, 6, 2, '../uploads/up_image_00/', '58710313613292663700c50bc7593418', '2017-06-01 13:28:32'),
(101, 6, 2, '../uploads/up_image_00/', '9669eaa7101c2e96fd56de7723eb7bc1', '2017-06-01 13:29:24'),
(102, 6, 2, '../uploads/up_image_00/', '5ac09cb290f49bb044780c26329fb523', '2017-06-01 13:29:35'),
(103, 17, 2, '../uploads/up_image_00/', 'cd002dde634ad1f1c20e00d12ff4e4fc', '2017-06-21 19:22:35'),
(104, 17, 2, '../uploads/up_image_00/', '8faafcdb2cd32250d6b87bf2dc9182b7', '2017-06-21 19:22:42'),
(105, 17, 2, '../uploads/up_image_00/', '23f2537903b1e336b1c45cd07709ae4e', '2017-06-21 19:22:49'),
(106, 17, 2, '../uploads/up_image_00/', 'bc040331b42c947244137046e8078667', '2017-06-21 19:22:56'),
(107, 17, 2, '../uploads/up_image_00/', 'efdbdd4b049302e63d3c712f458b8c22', '2017-06-21 19:23:02'),
(108, 17, 2, '../uploads/up_image_00/', '4c04ec56aea6453485545f0af832f5d5', '2017-06-21 19:23:16'),
(109, 18, 2, '../uploads/up_image_00/', '6778d0e8b26765584539a01f3ac85a2e', '2017-06-25 21:56:35'),
(110, 18, 2, '../uploads/up_image_00/', '88a0d9e8934e39dea7d15e504f3ede35', '2017-06-25 21:56:43'),
(111, 18, 2, '../uploads/up_image_00/', '2a78513fd6848c56d2f0a303da47186b', '2017-06-25 21:56:47'),
(112, 18, 2, '../uploads/up_image_00/', '30e085ea6a634e6dd8fba2d9b00648b9', '2017-06-25 21:56:52'),
(113, 18, 2, '../uploads/up_image_00/', '1ac597e448b34fcf3735483135c3fe69', '2017-06-25 21:56:56'),
(114, 18, 2, '../uploads/up_image_00/', '40c64eafae4303e79898a59dd2b20077', '2017-06-25 21:57:00'),
(115, 18, 2, '../uploads/up_image_00/', 'f8ad1a98131e53af9d4522e9d6727e45', '2017-06-25 21:57:05'),
(116, 18, 2, '../uploads/up_image_00/', 'edba6bb967a982985cc027218b674b99', '2017-06-25 21:57:11'),
(117, 19, 2, '../uploads/up_image_00/', '486dd9728d461124ebe39cd37c1a1e3f', '2017-06-25 22:01:24'),
(118, 19, 2, '../uploads/up_image_00/', 'bc053b9ba8e40f289ee1e3ad85120a43', '2017-06-25 22:01:30'),
(119, 19, 2, '../uploads/up_image_00/', 'f1b50d5bdb2e5e112423cde895078dc3', '2017-06-25 22:01:34'),
(120, 19, 2, '../uploads/up_image_00/', '56279836fd6858e020217f149463d17c', '2017-06-25 22:01:38'),
(121, 19, 2, '../uploads/up_image_00/', '9cf5770a8078985f9a98968c4cda514c', '2017-06-25 22:01:42'),
(122, 19, 2, '../uploads/up_image_00/', 'e76bce39acbcdfc102537c326ca43b52', '2017-06-25 22:01:46'),
(123, 19, 2, '../uploads/up_image_00/', '829a2d7cd074267ba5fd8083f375abc4', '2017-06-25 22:02:00'),
(124, 19, 2, '../uploads/up_image_00/', '4f1ca96b5ab97853601a4bff4bff2c23', '2017-06-25 22:02:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_grupo`
--

CREATE TABLE `tb_grupo` (
  `ci_grupo` int(20) UNSIGNED NOT NULL,
  `nm_grupo` varchar(50) NOT NULL,
  `ds_descricao` varchar(100) DEFAULT NULL,
  `dt_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_grupo_transacao`
--

CREATE TABLE `tb_grupo_transacao` (
  `ci_grupo_transacao` int(20) UNSIGNED NOT NULL,
  `cd_grupo` int(20) UNSIGNED NOT NULL,
  `cd_transacao` int(20) UNSIGNED NOT NULL,
  `fl_inserir` char(1) NOT NULL DEFAULT 'N',
  `fl_alterar` char(1) NOT NULL DEFAULT 'N',
  `fl_deletar` char(1) NOT NULL DEFAULT 'N',
  `dt_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_local`
--

CREATE TABLE `tb_local` (
  `ci_local` int(20) UNSIGNED NOT NULL,
  `cd_municipio` int(20) NOT NULL,
  `cd_usuario_owner` int(20) UNSIGNED NOT NULL,
  `cd_usuario_edit` int(20) UNSIGNED DEFAULT NULL,
  `nm_local` varchar(100) NOT NULL,
  `ds_local` text NOT NULL,
  `ds_contato` varchar(200) NOT NULL,
  `nr_cep` char(8) NOT NULL,
  `nm_rua` varchar(200) NOT NULL,
  `nr_rua_numero` varchar(10) NOT NULL,
  `nm_bairro` varchar(200) DEFAULT NULL,
  `nr_lat` varchar(100) NOT NULL,
  `nr_lng` varchar(100) NOT NULL,
  `nr_view` int(30) NOT NULL DEFAULT '2',
  `fl_ativo` tinyint(1) NOT NULL DEFAULT '0',
  `dt_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dt_edit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_last_view` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `tb_local`
--

INSERT INTO `tb_local` (`ci_local`, `cd_municipio`, `cd_usuario_owner`, `cd_usuario_edit`, `nm_local`, `ds_local`, `ds_contato`, `nr_cep`, `nm_rua`, `nr_rua_numero`, `nm_bairro`, `nr_lat`, `nr_lng`, `nr_view`, `fl_ativo`, `dt_create`, `dt_edit`, `dt_last_view`) VALUES
(1, 1, 2, 2, 'Grupo EspÃ­rita Trindade de Amor - GETA', 'DoaÃ§Ã£o de alimentos nÃ£o perecÃ­veis para compor cesta bÃ¡sica.\r\nEm busca de trabalhadores para causa social e fraterna.\r\n\r\nHorÃ¡rio de atendimento:\r\nTerÃ§as e Quintas de 19:30h Ã s 21:00h.\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/grupoespiritageta/\" target=\"_blank\">www.facebook.com/grupoespiritageta/</a>', 'Nonato (85) 98821-2414', '60055360', 'Rua JoÃ£o Lobo Filho', '267', 'Bairro de FÃ¡tima', '-3.7541664547697082', '-38.52217735674594', 71, 1, '2017-06-01 10:13:17', '2017-04-12 14:09:05', '0000-00-00 00:00:00'),
(2, 1, 2, 2, 'Lar Amigos de Jesus', 'O â€œLar Amigos de Jesusâ€ disponibiliza um serviÃ§o gratuito, modelo de acolhimento, apoio e assistÃªncia Ã s CrianÃ§as e Adolescentes para tratamento, consulta e revisÃ£o mÃ©dica, onde seu funcionamento Ã© de relevÃ¢ncia para a necessidade deste pÃºblico. Conta com a participaÃ§Ã£o substancial de voluntÃ¡rios, colaboradores e da sociedade que Ã© o foco principal de apoio e colaboraÃ§Ã£o para a operacionalizaÃ§Ã£o do atendimento globalizado. \r\n\r\nSite:\r\n<a href=\"http://www.laramigosdejesus.org.br\" target=\"_blank\">www.laramigosdejesus.org.br</a>', '(85) 3226.3447 / (85) 3067.6565', '60115001', 'Rua Ildefonso Albano', '3052', 'Joaquim TÃ¡vora', '-3.7479930982941507', '-38.51805386931153', 26, 1, '2017-06-01 10:12:15', '2017-04-11 01:42:17', '0000-00-00 00:00:00'),
(3, 1, 2, 2, 'AssociaÃ§Ã£o Peter Pan', 'O dinheiro das doaÃ§Ãµes Ã©, sem dÃºvida, importante para nosso funcionamento, mas tÃ£o importante quanto Ã© a participaÃ§Ã£o dos voluntÃ¡rios no dia-a-dia dos nossas crianÃ§as e adolescentes doando amor e atenÃ§Ã£o. FaÃ§a parte vocÃª tambÃ©m !\r\n\r\nSite:\r\n<a href=\"http://app.org.br\" target=\"_blank\">http://app.org.br</a>', '(85) 4008-4109 / (85) 3257-6427 / 8892-0135', '60410770', 'Rua Alberto Montezuma', '350', 'Vila UniÃ£o', '-3.7626305225624246', '-38.532274599999994', 30, 1, '2017-06-01 10:13:24', '2017-04-11 01:57:49', '0000-00-00 00:00:00'),
(4, 1, 2, 2, 'Casa do Menino Jesus', 'Entidade religiosa e filantrÃ³pica que oferece assistÃªncia Ã  crianÃ§as e adolescentes vindas de outros estados para realizar tratamento em hospitais da nossa regiÃ£o. \r\n\r\nSite:\r\n<a href=\"https://www.casameninojesus.org.br/\" target=\"_blank\">www.casameninojesus.org.br</a>', '(85) 3253-4082 / 3253-4482', '60110261', 'Rua GonÃ§alves Ledo', '1535', 'Centro', '-3.737123903132223', '-38.518125149999946', 23, 1, '2017-06-01 10:12:00', '2017-04-11 02:14:26', '0000-00-00 00:00:00'),
(5, 1, 2, 2, 'APAE', 'A AssociaÃ§Ã£o de Pais e Amigos dos Excepcionais (APAE) Ã© uma associaÃ§Ã£o em que, alÃ©m de pais e amigos dos excepcionais, toda a comunidade se une para prevenir e tratar a deficiÃªncia e promover o bem estar e desenvolvimento da pessoa com deficiÃªncia.\r\n\r\nSite:\r\n<a href=\"http://www.fortaleza.apaebrasil.org.br/\" target=\"_blank\">www.fortaleza.apaebrasil.org.br</a>\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/apaefortaleza/\" target=\"_blank\">https://www.facebook.com/apaefortaleza/</a>\r\n', '(85) 4012-1441', '60810475', 'Avenida Rogaciano Leite', '2001', 'Luciano Cavalcante', '-3.7680024625123', '-38.497071000000005', 22, 1, '2017-06-01 10:20:20', '2017-06-01 10:20:16', '0000-00-00 00:00:00'),
(12, 1, 2, 2, 'Casa da Caridade', 'A Casa da Caridade - Dr. Adolph Fritz Ã© uma instituiÃ§Ã£o EspÃ­rita que tem atividades religiosas e trabalhos sociais para a comunidade do bairro e adjacÃªncias.\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/casadacaridade/\" target=\"_blank\">www.facebook.com/casadacaridade/</a>', 'ValdÃ©cio (85) 98630-0072, Roberto 98536-6167, AuricÃ©lia 98659-1162, Francisco (85) 98717-9733', '60870630', 'Rua IrmÃ£o OlÃ­mpio', '60', 'Jangurussu', '-3.848584252340795', '-38.5115169449997', 16, 1, '2017-06-01 10:16:28', '2017-04-19 19:06:51', '0000-00-00 00:00:00'),
(6, 1, 2, 2, 'Projeto UniÃ£o', 'Entidade sem fins lucrativos que realiza trabalhos sociais junto Ã  comunidade do Jardim Iracema em parceria com o ChildFund Brasil. Nesta instituiÃ§Ã£o tambÃ©m funciona a Biblioteca ComunitÃ¡ria Jardim LiterÃ¡rio, que promove a cultura e democratizaÃ§Ã£o do acesso ao livro na comunidade.\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/bibliotecacomunitariajardimliterario/\" target=\"_blank\">https://www.facebook.com/bibliotecacomunitariajardimliterario/</a>', 'Joice (85) 3286-4310', '60341020', 'Rua Eretides de Alencar', '302', 'Jardim Iracema', '-3.721263256301034', '-38.58246704999999', 31, 1, '2017-06-26 15:06:00', '2017-06-01 13:22:33', '0000-00-00 00:00:00'),
(7, 1, 2, 2, 'Casa de Apoio Sol Nascente', 'A Casa Sol Nascente vem a 15 anos ajudando e acolhendo adultos e crianÃ§as que convivem com HIV. As doaÃ§Ãµes sÃ£o muito importantes para nos ajudar a manter todos os nossos serviÃ§os, mas nÃ£o podemos esquecer do serviÃ§o voluntÃ¡rio, de igual importÃ¢ncia para a Sol Nascente, no que se refere ao auxÃ­lio Ã s demandas gerais das casas e apoio espiritual. Venha fazer parte!\r\n\r\nSite:\r\n<a href=\"https://casasolnascentesite.wordpress.com/\" target=\"_blank\">https://casasolnascentesite.wordpress.com/</a>\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/casasolnascenteceara/\" target=\"_blank\">https://www.facebook.com/casasolnascenteceara/</a>\r\n', '(85) 3469-4437', '60860000', 'Av. Alberto Craveiro', '2222', 'CastelÃ£o', '-3.797890352727339', '-38.51946926052017', 17, 1, '2017-06-01 10:19:10', '2017-06-01 10:19:03', '0000-00-00 00:00:00'),
(8, 1, 2, 2, 'Casa do Menor SÃ£o Miguel Arcanjo', 'Acolher crianÃ§as, adolescentes e jovens em situaÃ§Ã£o de risco pessoal, dando Ãªnfase ao trabalho com as famÃ­lias, tendo como preocupaÃ§Ã£o e objetivo a reintegraÃ§Ã£o familiar, social, e favorecer o protagonismo desses meninos.\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/pg/CasaDoMenorFortaleza/\" target=\"_blank\">www.facebook.com/pg/CasaDoMenorFortaleza/</a>', '(85) 3289-4344', '60860000', 'Av. Alberto Craveiro', '2222', 'CastelÃ£o', '-3.7991910426045985', '-38.52375006610794', 13, 1, '2017-06-01 10:17:11', '2017-04-12 12:03:03', '0000-00-00 00:00:00'),
(9, 1, 2, 2, 'Maternidade Escola Assis Chateaubriand', 'Doe Leite Materno:\r\n<a href=\"http://www.saude.ce.gov.br/index.php/rede-de-amamentacao-no-ceara\" target=\"_blank\">www.saude.ce.gov.br/index.php/rede-de-amamentacao-no-ceara</a>\r\n\r\nAtendimento: segunda a sexta-feira, 7 Ã s 19 horas.\r\nMaternidade vai buscar o leite na casa da doadora!\r\nLigue para mais informaÃ§Ãµes!', '(85) 3366-8509', '60000000', 'Rua Coronel Nunes de Melo', 's/n', 'Rodolfo TeÃ³filo', '-3.7484900214732013', '-38.55280260919267', 10, 1, '2017-06-01 10:10:13', '2017-04-12 12:53:08', '0000-00-00 00:00:00'),
(10, 1, 2, 2, 'Hemoce', 'DOE SANGUE, SALVE VIDAS - Para ser um doador de sangue Ã© simples: basta estar saudÃ¡vel, bem alimentado, pesar acima de 50kg, ter entre 16 e 69 anos e apresentar um documento oficial, com foto. \r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/HemoceOficial/\" target=\"_blank\">www.facebook.com/HemoceOficial/</a>', '(85) 3101-2296', '60431086', 'Av. JosÃ© Bastos', '3390', 'Rodolfo TeÃ³filo', '-3.7489914040152903', '-38.5498437', 12, 1, '2017-06-01 10:11:17', '2017-04-12 13:09:47', '0000-00-00 00:00:00'),
(11, 1, 2, 2, 'FUJISAN', 'Parabenizamos vocÃª pela solidariedade e pela atitude de vir a ser um doador de sangue.\r\n\r\nSite:\r\n<a href=\"http://www.fujisan.com.br/\" target=\"_blank\">http://www.fujisan.com.br/</a>', '(85) 4009-6677', '60511755', 'Av. Barao de Studart', '2626', 'Joaquim TÃ¡vora', '-3.746962502994691', '-38.51064855859829', 12, 1, '2017-06-21 19:24:10', '2017-04-12 13:22:14', '0000-00-00 00:00:00'),
(13, 1, 2, 2, 'Recanto Bom Viver - Casa do Idoso', 'InstituiÃ§Ã£o de permanÃªncia para Idosos. Tem como objetivo acolher idosos, de ambos os sexos, com faixa etÃ¡ria igual ou acima de 60 anos, em situaÃ§Ã£o de vulnerabilidade social. Aceitamos todos os tipos de doaÃ§Ãµes! Seja voluntÃ¡rio!\r\n\r\nPrÃ³ximo ao Mercantil do Rubens\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/profile.php?id=100011308538071\" target=\"_blank\">https://www.facebook.com/profile.php?id=100011308538071</a>', 'Silvia (85) 98862-2901', '60525345', 'Rua Miramar da Ponte', '909', 'Henrique Jorge', '-3.7631361601408932', '-38.58652734999998', 30, 1, '2017-06-25 22:10:01', '2017-04-15 19:13:22', '0000-00-00 00:00:00'),
(14, 1, 2, 2, 'Casa FamÃ­lia Maria MÃ£e de Ternura', 'MissionÃ¡rias dedicam vida a cuidar de crianÃ§as. O abrigo de MaracanaÃº completa dez anos. LÃ¡, crianÃ§as abandonadas recebem amor e carinho. Venha nos conhecer! Entre em contato!\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/pages/Casa-Fam%C3%ADlia-Maria-m%C3%A3e-da-Ternura/770131589669552\" target=\"_blank\">https://www.facebook.com/pages/Casa-FamÃlia-Maria-mÃ£e-da-Ternura/770131589669552</a>', '(85) 3371-1495', '61905310', 'Rua AracajÃº', '251', 'Novo MaracanaÃº', '-3.8676464541027973', '-38.627193300000044', 11, 1, '2017-06-01 10:16:44', '2017-04-14 15:34:09', '0000-00-00 00:00:00'),
(15, 1, 2, 2, 'Lar Santa MÃ´nica', 'Somos uma organizaÃ§Ã£o sem fins lucrativos, que acolhe crianÃ§as, adolescentes e jovens, de 8 a 18 anos que foram vÃ­timas de violÃªncia sexual ou que estÃ£o em risco de sofrer abusos. Venha nos conhecer! Desperte sua Solidariedade!\r\n\r\nSite:\r\n<a href=\"http://www.larsantamonica.org.br/\" target=\"_blank\">www.larsantamonica.org.br</a>\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/pg/larsantamonica/\" target=\"_blank\">www.facebook.com/pg/larsantamonica/</a>', 'EscritÃ³rio (85) 3469-9177 / 98894-1135, Comunidade Religiosa (85) 3237-8865, Equipe TÃ©cnica (85) 98685-5552 / 98685-5553', '60860000', 'Av. Alberto Craveiro', '2222', 'CastelÃ£o', '-3.7957867637159186', '-38.52501875097198', 14, 1, '2017-06-01 10:16:12', '2017-04-14 16:27:32', '0000-00-00 00:00:00'),
(16, 1, 1, 2, 'Lar TrÃªs IrmÃ£s', 'Dar um fim de vida digno para nossos vovÃ´s â™¥.\r\nO Lar TrÃªs IrmÃ£s Ã© uma ONG que busca resgatar idosos de rua, ou em situaÃ§Ã£o de abandono, garantindo-lhes assim o direito a vida. \r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/Lar-Tr%C3%AAs-Irm%C3%A3s-1655677084716383/\" target=\"_blank\">https://www.facebook.com/Lar-TrÃªs-IrmÃ£s/</a>', '(85) 3023-3343, Vanda 98705-0252, TaÃ­s 98756-9298', '60410220', 'Rua Joaquim Pimenta', '291', 'Montese', '-3.7661255074596145', '-38.544564599999944', 14, 1, '2017-06-19 19:27:25', '2017-05-22 20:01:51', '0000-00-00 00:00:00'),
(17, 1, 2, 2, 'Instituto Bia Dote', 'O Instituto Bia Dote Ã© uma organizaÃ§Ã£o civil sem fins lucrativos que trabalha com a prevenÃ§Ã£o do suicÃ­dio e a valorizaÃ§Ã£o da vida. Realiza palestras e promove encontros. Aceita voluntÃ¡rios em psicologia. Para mais informaÃ§Ãµes entrem em contato!\r\n\r\nSite:\r\n<a href=\"http://www.institutobiadote.org.br/\" target=\"_blank\">www.institutobiadote.org.br</a>\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/pg/Institutobiadote/\" target=\"_blank\">www.facebook.com/pg/Institutobiadote/</a>', '(85) 3264-2992', '6012000', 'Avenida BarÃ£o de Studart', '2360', 'DionÃ­sio Torres', '-3.7440438012873853', '-38.50995835000003', 7, 1, '2017-06-23 18:24:17', '2017-06-21 19:23:32', '0000-00-00 00:00:00'),
(18, 1, 2, 2, 'JudÃ´ CanindÃ©zinho', 'O projeto comeÃ§ou em 2008, com o objetivo de proporcionar o acesso ao esporte lazer e educaÃ§Ã£o para crianÃ§as, adolescentes e jovens, residentes em Ã¡reas de risco social. Buscamos utilizar o JudÃ´ como ferramenta de educaÃ§Ã£o e formaÃ§Ã£o para o cidadÃ£o integral, consciente de seus direitos e deveres na sociedade. Atendemos cerca de 120 alunos nos dois nÃºcleos que o projeto funciona. <b>Aberto para toda comunidade</b> e aceita doaÃ§Ãµes de patrocÃ­nios para viagens e campeonatos.\r\n\r\n<b>Locais de funcionamento:</b>\r\nEEFM Senador Osires Pontes (Rua R. Divina, 150 â€“ Canindezinho. HorÃ¡rios: Quarta e Sexta: 18h â€“ 20h30m. SÃ¡bado: 8h30m â€“ 11h30m)\r\nCentro Cultural do Bom Jardim (Rua 3 CoraÃ§Ãµes, 400 â€“ Bom Jardim. HorÃ¡rios: TerÃ§a e Quinta: 17h00m â€“ 21h).\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/judo.canindezinho.3\" target=\"_blank\">https://www.facebook.com/judo.canindezinho.3</a>\r\n', 'Sr. Kaio (85) 99939-3991', '60736120', 'Rua Divina', '150', 'Canindezinho', '-3.8079332996716584', '-38.60724725', 5, 1, '2017-06-26 15:06:07', '2017-06-25 21:57:20', '0000-00-00 00:00:00'),
(19, 1, 2, 2, 'JudÃ´ CanindÃ©zinho', 'O projeto comeÃ§ou em 2008, com o objetivo de proporcionar o acesso ao esporte lazer e educaÃ§Ã£o para crianÃ§as, adolescentes e jovens, residentes em Ã¡reas de risco social. Buscamos utilizar o JudÃ´ como ferramenta de educaÃ§Ã£o e formaÃ§Ã£o para o cidadÃ£o integral, consciente de seus direitos e deveres na sociedade. Atendemos cerca de 120 alunos nos dois nÃºcleos que o projeto funciona. <b>Aberto para toda comunidade</b> e aceita doaÃ§Ãµes de patrocÃ­nios para viagens e campeonatos.\r\n\r\n<b>Locais de funcionamento:</b>\r\nEEFM Senador Osires Pontes (Rua R. Divina, 150 â€“ Canindezinho. HorÃ¡rios: Quarta e Sexta: 18h â€“ 20h30m. SÃ¡bado: 8h30m â€“ 11h30m)\r\nCentro Cultural do Bom Jardim (Rua 3 CoraÃ§Ãµes, 400 â€“ Bom Jardim. HorÃ¡rios: TerÃ§a e Quinta: 17h00m â€“ 21h).\r\n\r\nFacebook:\r\n<a href=\"https://www.facebook.com/judo.canindezinho.3\" target=\"_blank\">https://www.facebook.com/judo.canindezinho.3</a>', 'Sr. Kaio (85) 99939-3991', '60540441', 'Rua 3 CoraÃ§Ãµes', '400', 'Bom Jardim', '-3.785864246875227', '-38.6096986', 4, 1, '2017-06-25 22:06:57', '2017-06-25 22:06:40', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_local_modalidade`
--

CREATE TABLE `tb_local_modalidade` (
  `ci_local_modalidade` int(20) UNSIGNED NOT NULL,
  `cd_local` int(20) UNSIGNED NOT NULL,
  `cd_modalidade` int(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `tb_local_modalidade`
--

INSERT INTO `tb_local_modalidade` (`ci_local_modalidade`, `cd_local`, `cd_modalidade`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 14),
(23, 2, 15),
(22, 2, 14),
(21, 2, 12),
(20, 2, 11),
(19, 2, 9),
(18, 2, 8),
(17, 2, 6),
(16, 2, 4),
(15, 2, 3),
(14, 2, 1),
(29, 4, 4),
(73, 3, 5),
(72, 3, 2),
(30, 4, 6),
(42, 6, 10),
(41, 6, 17),
(40, 6, 4),
(39, 6, 2),
(43, 6, 15),
(64, 7, 15),
(63, 7, 8),
(62, 7, 19),
(61, 7, 18),
(60, 7, 4),
(59, 7, 1),
(65, 7, 16),
(66, 8, 2),
(67, 8, 4),
(68, 8, 18),
(69, 8, 14),
(70, 8, 16),
(71, 9, 7),
(74, 3, 6),
(75, 10, 13),
(76, 11, 13),
(77, 5, 1),
(78, 5, 6),
(79, 5, 18),
(80, 5, 9),
(81, 5, 16),
(82, 12, 1),
(83, 12, 3),
(84, 12, 14),
(118, 13, 6),
(117, 13, 2),
(116, 13, 1),
(105, 14, 12),
(104, 14, 9),
(103, 14, 8),
(102, 14, 17),
(101, 14, 19),
(100, 14, 18),
(99, 14, 6),
(98, 14, 4),
(97, 14, 1),
(106, 14, 14),
(107, 14, 15),
(108, 14, 16),
(109, 15, 1),
(110, 15, 2),
(111, 15, 6),
(112, 15, 17),
(113, 15, 10),
(114, 15, 15),
(115, 15, 16),
(119, 13, 18),
(120, 13, 19),
(121, 13, 8),
(122, 13, 9),
(123, 13, 11),
(124, 13, 12),
(125, 13, 14),
(126, 13, 16),
(127, 16, 1),
(128, 16, 18),
(129, 16, 8),
(130, 16, 9),
(131, 16, 11),
(132, 16, 14),
(133, 16, 16),
(138, 17, 14),
(137, 17, 12),
(139, 18, 6),
(140, 19, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_modalidade`
--

CREATE TABLE `tb_modalidade` (
  `ci_modalidade` int(20) UNSIGNED NOT NULL,
  `nm_modalidade` varchar(150) NOT NULL,
  `ds_modalidade` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `tb_modalidade`
--

INSERT INTO `tb_modalidade` (`ci_modalidade`, `nm_modalidade`, `ds_modalidade`) VALUES
(1, 'Alimentos', NULL),
(2, 'Atividades LÃºdicas', '123'),
(3, 'Bazar', NULL),
(4, 'Brinquedos', NULL),
(5, 'Cabelo', NULL),
(6, 'Dinheiro', NULL),
(7, 'Leite Materno', NULL),
(8, 'Material de HigiÃªne', '123'),
(9, 'Material de Limpeza', NULL),
(10, 'Material Escolar', NULL),
(11, 'Material Hospitalar', NULL),
(12, 'RemÃ©dios', '123'),
(13, 'Sangue', NULL),
(14, 'ServiÃ§os', '123'),
(15, 'VestuÃ¡rio', '123'),
(16, 'VisitaÃ§Ã£o', '123'),
(17, 'Livros', '123'),
(18, 'Fraldas', '123'),
(19, 'Leite / Leite em PÃ³', '123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_municipio`
--

CREATE TABLE `tb_municipio` (
  `ci_municipio` int(20) UNSIGNED NOT NULL,
  `nm_municipio` varchar(250) NOT NULL,
  `sg_estado` char(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `tb_municipio`
--

INSERT INTO `tb_municipio` (`ci_municipio`, `nm_municipio`, `sg_estado`) VALUES
(1, 'FORTALEZA', 'CE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_test`
--

CREATE TABLE `tb_test` (
  `ci_test` int(20) UNSIGNED NOT NULL,
  `nr_lat` decimal(18,16) NOT NULL,
  `nr_lng` decimal(18,16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_transacao`
--

CREATE TABLE `tb_transacao` (
  `ci_transacao` int(20) UNSIGNED NOT NULL,
  `nm_transacao` varchar(50) NOT NULL,
  `nm_label` varchar(100) NOT NULL,
  `tp_tipo` int(11) NOT NULL DEFAULT '1',
  `ds_descricao` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `ci_usuario` int(20) UNSIGNED NOT NULL,
  `tp_nivelacesso` smallint(6) NOT NULL DEFAULT '1',
  `nm_usuario` varchar(150) NOT NULL,
  `nm_login` varchar(50) NOT NULL,
  `nm_senha` varchar(250) NOT NULL,
  `ds_email` varchar(150) NOT NULL,
  `fl_sexo` smallint(6) NOT NULL DEFAULT '1',
  `fl_atualizousenha` tinyint(1) NOT NULL DEFAULT '0',
  `fl_ativo` tinyint(1) NOT NULL DEFAULT '0',
  `dt_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dt_acesso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Fazendo dump de dados para tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`ci_usuario`, `tp_nivelacesso`, `nm_usuario`, `nm_login`, `nm_senha`, `ds_email`, `fl_sexo`, `fl_atualizousenha`, `fl_ativo`, `dt_cadastro`, `dt_acesso`) VALUES
(1, 2, 'USUÃRIO ADMINISTRADOR', 'ADMIN', 'f60be76dc125121b7fed811e9baf7841', 'admin@admin.com', 1, 1, 1, '2017-05-21 13:10:10', '2017-05-21 13:10:10'),
(2, 3, 'USUÃRIO MASTER', 'MASTER', 'c0d27b843c0101e7100a52fcfe071a46', 'master@master.com', 1, 1, 1, '2017-06-25 21:44:52', '2017-06-25 21:44:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_usuario_grupo`
--

CREATE TABLE `tb_usuario_grupo` (
  `ci_usuario_grupo` int(20) UNSIGNED NOT NULL,
  `cd_usuario` int(20) UNSIGNED NOT NULL,
  `cd_grupo` int(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `tb_foto`
--
ALTER TABLE `tb_foto`
  ADD PRIMARY KEY (`ci_foto`),
  ADD KEY `Ref_tb_foto_to_tb_usuario` (`cd_usuario`),
  ADD KEY `Ref_tb_foto_to_tb_local` (`cd_local`);

--
-- Índices de tabela `tb_grupo`
--
ALTER TABLE `tb_grupo`
  ADD PRIMARY KEY (`ci_grupo`);

--
-- Índices de tabela `tb_grupo_transacao`
--
ALTER TABLE `tb_grupo_transacao`
  ADD PRIMARY KEY (`ci_grupo_transacao`),
  ADD KEY `Ref_tb_grupo_transacao_to_tb_transacao` (`cd_transacao`),
  ADD KEY `Ref_tb_grupo_transacao_to_tb_grupo` (`cd_grupo`);

--
-- Índices de tabela `tb_local`
--
ALTER TABLE `tb_local`
  ADD PRIMARY KEY (`ci_local`),
  ADD KEY `Ref_tb_local_to_tb_usuario_owner` (`cd_usuario_owner`),
  ADD KEY `Ref_tb_local_to_tb_usuario_edit` (`cd_usuario_edit`);

--
-- Índices de tabela `tb_local_modalidade`
--
ALTER TABLE `tb_local_modalidade`
  ADD PRIMARY KEY (`ci_local_modalidade`),
  ADD KEY `Ref_tb_local_modalidade_to_tb_local` (`cd_local`),
  ADD KEY `Ref_tb_local_modalidade_to_tb_modalidade` (`cd_modalidade`);

--
-- Índices de tabela `tb_modalidade`
--
ALTER TABLE `tb_modalidade`
  ADD PRIMARY KEY (`ci_modalidade`);

--
-- Índices de tabela `tb_municipio`
--
ALTER TABLE `tb_municipio`
  ADD PRIMARY KEY (`ci_municipio`);

--
-- Índices de tabela `tb_test`
--
ALTER TABLE `tb_test`
  ADD PRIMARY KEY (`ci_test`);

--
-- Índices de tabela `tb_transacao`
--
ALTER TABLE `tb_transacao`
  ADD PRIMARY KEY (`ci_transacao`);

--
-- Índices de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`ci_usuario`),
  ADD UNIQUE KEY `unq_tb_usuario_nm_login` (`nm_login`),
  ADD UNIQUE KEY `unq_tb_usuario_ds_email` (`ds_email`);

--
-- Índices de tabela `tb_usuario_grupo`
--
ALTER TABLE `tb_usuario_grupo`
  ADD PRIMARY KEY (`ci_usuario_grupo`),
  ADD KEY `Ref_tb_usuario_grupo_to_tb_grupo` (`cd_grupo`),
  ADD KEY `Ref_tb_usuario_grupo_to_tb_usuario` (`cd_usuario`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `tb_foto`
--
ALTER TABLE `tb_foto`
  MODIFY `ci_foto` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT de tabela `tb_grupo`
--
ALTER TABLE `tb_grupo`
  MODIFY `ci_grupo` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tb_grupo_transacao`
--
ALTER TABLE `tb_grupo_transacao`
  MODIFY `ci_grupo_transacao` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tb_local`
--
ALTER TABLE `tb_local`
  MODIFY `ci_local` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de tabela `tb_local_modalidade`
--
ALTER TABLE `tb_local_modalidade`
  MODIFY `ci_local_modalidade` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;
--
-- AUTO_INCREMENT de tabela `tb_modalidade`
--
ALTER TABLE `tb_modalidade`
  MODIFY `ci_modalidade` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de tabela `tb_municipio`
--
ALTER TABLE `tb_municipio`
  MODIFY `ci_municipio` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `tb_test`
--
ALTER TABLE `tb_test`
  MODIFY `ci_test` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tb_transacao`
--
ALTER TABLE `tb_transacao`
  MODIFY `ci_transacao` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `ci_usuario` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de tabela `tb_usuario_grupo`
--
ALTER TABLE `tb_usuario_grupo`
  MODIFY `ci_usuario_grupo` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
