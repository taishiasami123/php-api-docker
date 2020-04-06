-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql
-- 生成日時: 2020 年 4 月 06 日 11:19
-- サーバのバージョン： 5.7.29
-- PHP のバージョン: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `sns_api`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `text`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'text1', '317', '2020-03-26 19:57:46', '2020-03-26 10:57:46'),
(2, 'text2', '318', '2020-03-27 14:52:21', '2020-03-27 05:52:21'),
(3, 'text3', '319', '2020-03-27 14:52:36', '2020-03-27 05:52:36');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `bio`, `email`, `password`, `token`, `created_at`, `updated_at`) VALUES
(317, 'test1', 'test1', 'test1', '1b4f0e9851971998e732078544c96b36c3d01cedf7caa332359d6f1d83567014', '83624b49b8d711d1a307a6cd754472f65afc5c414bf1f13c266ef18014cee504', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(318, 'test2', 'test2', 'test2', '60303ae22b998861bce3b28f33eec1be758a213c86c93c076dbe9f558c11c752', '3410740b845e4bfecd71fe5c89a8e18735a1f0845195d44e54c0e15de3f46db7', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(319, 'test3', 'test3', 'test3', 'fd61a03af4f77d870fc21e05e7e80678095c92d808cfb3b5c279ee04c74aca13', '98451817fb9542af9b2b99d4f24b98c183b6e4d8d9fb971e24addaa08b4b16f3', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(320, 'test4', 'test4', 'test4', 'a4e624d686e03ed2767c0abd85c14426b0b1157d2ce81d27bb4fe4f6f01d688a', 'fca4c52846f7f151ca9a25fd06e363a0590361d196ea00d5da2e642e1c619e34', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(321, 'test5', 'test5', 'test5', 'a140c0c1eda2def2b830363ba362aa4d7d255c262960544821f556e16661b6ff', 'a30e81d408a4ad6f743ed68c3ccff62abe6e6a3a26137987b105c0c861f13d19', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(322, 'test6', 'test6', 'test6', 'ed0cb90bdfa4f93981a7d03cff99213a86aa96a6cbcf89ec5e8889871f088727', 'b9286ef4fa6d9bab4c902140a912056d6d11d03b755ff81dcf57ba85d0c884b6', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(323, 'test7', 'test7', 'test7', 'bd7c911264aae15b66d4291b6850829aa96986b1d3ead34d1fdbfef27056c112', '5a7b2590f45d264f21b6a64a58004deef4374c5735b4dfb47b3f782af3a496fe', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(324, 'test8', 'test8', 'test8', '1f9bfeb15fee8a10c4d0711c7eb0c083962123e1918e461b6a508e7146c189b2', '433f220773a2ab390b738893f430f5c2042f243579b87ec8e976c0b1aa209c31', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(325, 'test9', 'test9', 'test9', 'b4451034d3b6590060ce9484a28b88dd332a80a22ae8e39c9c5cb7357ab26c9f', '6d3e8c1fe9dc01e0c71cdab91cbe391c9684636d98752ab748de0870b0821e2f', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(326, 'test10', 'test10', 'test10', 'ec2738feb2bbb0bc783eb4667903391416372ba6ed8b8dddbebbdb37e5102473', 'e55d4a31f3f02da275a7234921049bfdd0d08aa4aa4b50a96d85f51cccf25d68', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(327, 'test11', 'test11', 'test11', '744ea9ec6fa0a83e9764b4e323d5be6b55a5accfc7fe4c08eab6a8de1fca4855', 'e5bb88c24b189d75c293378862586f8146353b0f6aa61a6f3f5b6569c4ca19e7', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(328, 'test12', 'test12', 'test12', 'a98ec5c5044800c88e862f007b98d89815fc40ca155d6ce7909530d792e909ce', 'a17fe4a2d98dca2bf16930aec67abd51f6091e60906265ca48c56afbb0c08ebe', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(329, 'test13', 'test13', 'test13', '166fb78f0f44d271a2d9065272a67ba373c3266b59d85847c02ef695af0cbf3f', '55309bec070c3bb03bab870835b3c89a784d9a950555836632049bb314ac9058', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(330, 'test14', 'test14', 'test14', '40cca5cc13abf91c7d5a72c0aea9bcbea4108946e67f24c0c23003cbf307efa2', 'c31c6eeef76b015ab522ee68e18cab092067b2be8cba764e3994882013a61b40', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(331, 'test15', 'test15', 'test15', 'ebb39b342baead7aa52c0bcd6c0d4ba061b42f3a9dd6bafa2407a096b91b2450', 'f13da907051d7f51cde2b74a13413d7906c1dd87c27257fdf1c730cb53333de7', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(332, 'test16', 'test16', 'test16', '8ffd063b93a29f84389a635552740a9f0a7234169994158fb19692f5964dd7f5', '78813887b9ebc7c2c6dc19a91db48098438c553a3157b3622c744fcbf016c630', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(333, 'test17', 'test17', 'test17', '813e41d4092656716cb0b46a1e5002857066cdaef8decf182ae15abf0b43b8d5', 'be8fd6f6245235a3bd76caf5428db0d9e2bc52417964a8aa6dc2d904f606f230', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(334, 'test18', 'test18', 'test18', 'b3c0e5febe1ec8875cd4a06fa4a99abf270de3f131d83a65f897322edbc12aec', 'ef42cb481e460d0eec4ec8f2fafabde75edc683b85f6e176867d9c3e68b7dbae', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(335, 'test19', 'test19', 'test19', '840b1bf550a873a1dbed1381abe379cb9f1e76067b6de54bcd37367ce6ca3c0a', '348f64c73a2cbe2a23c987596d6311b80b9029753b131650a3cebb701141bb4b', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(336, 'test20', 'test20', 'test20', '946cc198869790373cd8424cd9073e9e29aaa17b6f6a6ec55b38110cae856385', '77b3d3707a804f9ee75477703357ab7e9654abf0983b5a27733b261d7de9a6f6', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(337, 'test21', 'test21', 'test21', 'aefd57c8c2afdaad0a5352b0ce87131a85a08f5c87a87f166f0ce1e213f4c0fd', 'f0f22b003be5a295ec128b9effc2c386a4ab8672b501980a527c0b3adb9e8f70', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(338, 'test22', 'test22', 'test22', '759cfde265aaddb6f728ed08d97862bbd9b56fd39de97a049c640b4c5b70aac9', '3240131b0b94dfe89360d24345a639522cfb23114752046a727f40064032b230', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(339, 'test23', 'test23', 'test23', '4e758d4760a6cffc347cdb45f0966d20f481bad806731c4c0e44f21cf9d90bb5', '4993221db24b53a0cbd1014b7c2e3518b154ad09009c98ecbc174cc8c053da3d', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(340, 'test24', 'test24', 'test24', '65b440e2e0a921e4a9adc14445374d498a95d05f299d791548d6838eb0ae65df', 'b8e0aeec43e40a759a40c102b23db2709a506240c20f2481c86529b2ab096896', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(341, 'test25', 'test25', 'test25', '0342840f6340d15691f4be1c0e0157fb0983992c4f436c18267d41dbe6bb74a2', '2903bfe66b116bd4d43f59e09f888f5793fdab0c7b7e21837222b5a65a5458a1', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(342, 'test26', 'test26', 'test26', 'ebe9f9116525a660751600a7f897df4b03d45b5dfc17ae36a9dd33b34f9849b4', 'd584fd34a657276e6e255be02849b4d54e0ef80597540fa3b721cd32c125bcf1', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(343, 'test27', 'test27', 'test27', 'a45c66cfc88915d1fc91bf998ea726b34c530b63b38984f5a0f313766b799808', '0d5e9d71b128061887519d78db9dcc4160652bb51ef25c29f1eecab007d93fb0', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(344, 'test28', 'test28', 'test28', '94384f0790043d99b7b85cb0f518eccdbfdf066b72324e3d996c4d0c738b65b5', '663089efe794b37750f1f688e38c62b6c86344555a09e904c77727fba6ed8b68', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(345, 'test29', 'test29', 'test29', '15dfe842cd5eb4a591465c8e8927e16cd2b16e3ca7b4312933d763882f38270f', '28836fe48304cefd3e974d1f0387e2ee79fc5925c1b3afd45ef17a1e5f3af7a1', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(346, 'test30', 'test30', 'test30', '98b96436416b60ef629ebe764dc75bb2d3052145829b24aa43a5168aabf0942d', '10a5ab72a0616a02db1f304fdec8592d1c154383cfe0ab30336ea4c4e8645fee', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(347, 'test31', 'test31', 'test31', '5261b37d94728f221fd8d25e9f9eff5584316d8a990372fe9282aa7e6d6710e9', 'ea120f60a4eb5769606c762682396de93da2291ade42c564e6822f8cee7afd43', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(348, 'test32', 'test32', 'test32', '91d3510c0672508f97b00f917e513e4dc2f57dd6e53cab95c28eebdc3f88a108', 'ecfd4f428f6a70710d6542904bce6bc9c0ae9a23ebcad9d63ae755579512b9f3', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(349, 'test33', 'test33', 'test33', '76226c5fd1ad06a945da03fc6ff776e7681c33661925f3d397ac8e18b4eaf564', '76d23f09e74a48fc07d31ff3900c2d4705bee0b295eda8b6db04f4c145d06328', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(350, 'test34', 'test34', 'test34', 'bd7c74fc6804bfd3bab881c04f81236c862b06606a8228dfc29174d05eb9e6fb', '808f874c93eafbb010e9f4bf2d790b4c15e6b17d379f23e0ad83015488cb0a73', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(351, 'test35', 'test35', 'test35', '43e3603bfec2885d2184d8534a9a68c65261792f5fbb6b39aab287b4b86eeaf0', '7c164dcc2658fb56fc0ab95f120bf1505ab1f5954b8636cda402031bc938a3e7', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(352, 'test36', 'test36', 'test36', '66643f8964abd994a78b8644ecdf19ddbb7fce48bcdd08dd91d3b0aa2d8476fc', 'd824d84834cf01a2b13e5ac83cd12efae625450d19be8a6a999c54b8e54bae47', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(353, 'test37', 'test37', 'test37', 'cefaef766de65a7384333fb99cc21c0e65e04e53df10d4439d70eb70b505a02d', '1e58b1b4b3f0728d793e68c380f0694f6906221c755f238858f50123c6f0e934', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(354, 'test38', 'test38', 'test38', 'e2ed63496eb5fd475c93110f8d05d3e079b69e59bd84f30b5e1b8cb8d3d22ca5', '03713a5dc443b46b2e7816ce8cea389046e6ebd1c6b4515184be2c57d2a4ea2b', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(355, 'test39', 'test39', 'test39', '62844dae9249e8443c13aee33f15743fa254498031e3949727358ce7c8168d97', '41906292f8a9619c8468f2ec94543865a95f15305ee32a3d27fd7853ef131622', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(356, 'test40', 'test40', 'test40', 'f3dc2b17fd23faab2deb6216ac9033e3c2670327abc6166794c16ce9437bc4b4', '99a10c7110b740f1f7bebddfd1858eb635ed91b733ba54a00b3a6dd8ee175b8f', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(357, 'test41', 'test41', 'test41', 'df332d413607690aeb4b8f21882ff0ecb3cf9cea9ddb2328ee6c85999de206a5', '7c0c859002cc1d27332bfce67717650e4f6e23341f3e51427a4fa3a9ba6a8d2e', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(358, 'test42', 'test42', 'test42', '338c0605bab38900480ebcc7fb0651426cc26cd1732579f04b47f779a8962d83', 'f075253c1adb7efc0b69227f33d001eca675026563e9072bee43709fa5841cba', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(359, 'test43', 'test43', 'test43', '8774f52c0f1cb920ce6470faf2dce0a5555484140fdce6e800bc482d27e0b21e', '02d8648fbf123d3ebb9c8ed97f71a05ab199964f2b883d921fc0090a6447687d', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(360, 'test44', 'test44', 'test44', 'ad59ed77bfd3757a879332e95644e322f0808bc94c6217a3490f85f176448b7d', 'fd4e17cd17205d0ee12f4fe26ef905a48cbf8c378eb57ea21da8abddb66222be', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(361, 'test45', 'test45', 'test45', '9291e045de7cfcc8dc5ac645e166d5f07b428f56872bfd1bd5c18ba707744f7e', 'dcaca2d7fd7f0206c3e982b0099ce7f5f24e1eb241c3547263d0528c6826d71b', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(362, 'test46', 'test46', 'test46', 'df4883ef2cb0b7900a8401caf33c9a57fe9526d2da728347d28f5e6589333e60', '4ee6a58a6f47432992c2e21463b30da47eef4c54127c7ed71db67e4bb4817f12', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(363, 'test47', 'test47', 'test47', 'd29c18776f9b104b63409a0b617a4f4cbcb9fe8c512bd78d4abeb3bee5d1562b', '8854dacf06a7d584c966eec198d734470a484f38388766a63478d81df6fb52a6', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(364, 'test48', 'test48', 'test48', '9640fa8ac42bbdbca83163ed2b7ee9ecff3b1dc0712514b0b1377ddc3f2fa817', 'df35fae1ab0d4a78592c96d8a7a9ce49e05f6c277a0827fa930e2ca6aa7702ad', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(365, 'test49', 'test49', 'test49', '83bc3329430af10d70f05217525b5c3970800047027e4682fabc1d69bd0fef74', '79aa48e7eb013de88e2a6cf303ac1dddd1ecf98fc57275c3be1bfc81a0cf4308', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(366, 'test50', 'test50', 'test50', '9ca6333ba92ea4aaa3e4c972c4c9301f2931154b73373a25b849fce8fd4e16c9', '02cbb35e9119a9dd0fa1b8708beb3bf986d62e9dc317a9929db5e81ee8c55184', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(367, 'test51', 'test51', 'test51', '29d75d258e03dc8758382d6bd793414670860125c848861224d1e8cc0e2899c6', 'e0c18f8d0a8b777b836fb4776a777d6458fc8f52e753526dc07b9cef8853a78b', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(368, 'test52', 'test52', 'test52', '0c117c58f1c3fbb490946d1be8dc28464cdae8814531d227bac8c34d5c48e2ca', 'f81f41db1cb3b0438a823f196a903bf4045e3ff104bbfa9927e18686e1af67b2', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(369, 'test53', 'test53', 'test53', '8b0fee57428d2465b1a5eeb83c4088cd14d3a8294e90fd9a0a4d374041bbe0e0', '21eb0e2bf41d3f8680165af82a8ef4288c27d725c65928ec53c637cf1b203c35', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(370, 'test54', 'test54', 'test54', 'c2c5d8026826fea0119924d86391e5f3fcf8e08801e4d75017ded4c212db9408', 'ce1d1d11025f1427f2d5b571a1735952a4114e108a75dcbfc92fc477cc1aee6c', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(371, 'test55', 'test55', 'test55', 'd08b55c5f8ac968e2477a0ea68308859f19a1f8adce5cc760f92f443970f90e3', '6e033c26cfb166a42bb19b8ca8b403847d2b356a76383fa612a06d15d8a7b5b3', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(372, 'test56', 'test56', 'test56', '264f4e176c3edb611154c6da9259369f8e987c23dca75ab2f2505a2754f242c3', 'b96798eb492e951327e71e1670f5babc37a5a84c4332305bdcb45893b5bc7fb5', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(373, 'test57', 'test57', 'test57', '2d5d1edafdda52004bca8c9dab84121a85056ba8ab93075477f0902816b08267', '59d68819151ed2f743c2e7c60f32c58fbf9ffb1a1ca56543f8e6f03f472a6a82', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(374, 'test58', 'test58', 'test58', '4aa32e0e703c31dbfdcc847034cdefd5aec24edd735a58a30da33f1a28684145', 'c57207a4a0dcca54c86641411c0eb08134a700e1cde70e492fffb5ee022586c0', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(375, 'test59', 'test59', 'test59', '0748b1829e7d626b21bab9e50cb6b88730a56e8546a45b806c6cbbada328cd56', '0bfcead2f9f419e8ae3df1c40d68c62d59ecba63f2c56e0d9ef24164ee1ab255', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(376, 'test60', 'test60', 'test60', 'eea12de80a09eaae4172d0f8ae0243d2d1629e3abe88ac839e790e22fea12f70', '20a706c8d3dd326e3adc79093d728380f8c1702ba25339ff450a4fc6b4fa5d2a', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(377, 'test61', 'test61', 'test61', '048f4abb501a20506550ed860a0d78f533aafdebaad3e79ae9eb002ce103c297', '54d198b018706741cdd0f59fc73bcdf7e78c4cb8040aa9dfeb07c58c78c8099f', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(378, 'test62', 'test62', 'test62', 'e3a166423764c3f6e15545c0be4d7af20fa5e16fba3533a7512f509700cc7b07', '6dae4e1867ea150e19ad4a94788420b7ebb2745a5aabb1676f9b6bc956ac8ec9', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(379, 'test63', 'test63', 'test63', '45fc3057d389c5cba1055af20f36a9422291c7f73a639bb0e8f76103bdf0b490', '50e24dd179fa6f548f99882921964f470dce605d8fdef1e76184ba9b5bad5ea5', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(380, 'test64', 'test64', 'test64', '9d11b57f078cac8dfad0ca3f980a077519a3f78efeb3f909c3c6a081bba5c2c1', '90f4ad6f8b558b432d43005af52ac31706bd924da0e6b889707fc0098f87c64b', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(381, 'test65', 'test65', 'test65', 'f242940ce161017af98b697b12de1402a38bae8c94007bbd7f81a042cb381eef', 'ff397ae98558417aa775f38df52eb61ed7b2e068dd0babe80edd171e82457bee', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(382, 'test66', 'test66', 'test66', 'c644a45ba4b14fbb202020f0e231dad5d479074c3b3384003a90d3df320f5ea7', '17bad15bf0eb5bacfac195ce41aa159e5fd8f41012773c65c51600ff8530ded7', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(383, 'test67', 'test67', 'test67', 'b85d95e12c6de414c7c7a115bac0926b81be9b27cd8628bf78352729c0df5d58', '5c8e05a0b7ad1b97288060e3a237f5d421d1f9dff850169e548543c43b2301f1', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(384, 'test68', 'test68', 'test68', '01775e9d78f43286f1b9283cfd5ac88a1b916f1f14f05be947f18ad15458f6bd', '452e55e78e56b09381676d5ffbb008202140b12fc86528d0bc2086e71202a4b2', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(385, 'test69', 'test69', 'test69', '9f2d0ea5850156f702bc2d2b422e82b82d45c0f41786dd78b26f70021ff7c90b', '74a08e6ba791b0b3d14067ec0f3ad61fd313fc2d3420309a50a46c77206c5812', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(386, 'test70', 'test70', 'test70', 'fe0e5ebf97c5941df65b903f8a7b41f9955745d62db126c5c2682df97f5b3a4f', '36e958f2425ea5d89eaea8af57d59fe37a272235af4fa2846f4c819797903872', '2020-03-26 17:30:25', '2020-03-26 08:30:25'),
(387, 'test71', 'test71', 'test71', 'fa2c5276894d911e7695b121482104a4bc180d6b65ddbb357fa48e22f03ea608', '65a21b41ee945d9d0dd158aa7459951d62d1bb914baf854b9d5379c5a5618dfb', '2020-04-06 15:24:35', '2020-04-06 06:24:35');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=388;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
