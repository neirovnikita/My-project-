-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 09 2017 г., 17:41
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `rayb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `asadal_friends`
--

CREATE TABLE IF NOT EXISTS `asadal_friends` (
  `id` int(20) NOT NULL,
  `kto` int(20) NOT NULL,
  `ykogo` int(20) NOT NULL,
  `activate` int(5) NOT NULL DEFAULT '0',
  `online` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `asadal_gift`
--

CREATE TABLE IF NOT EXISTS `asadal_gift` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `gift` int(255) DEFAULT NULL,
  `komy` int(255) DEFAULT NULL,
  `otkogo` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `asadal_gifta`
--

CREATE TABLE IF NOT EXISTS `asadal_gifta` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `asadal_gifta`
--

INSERT INTO `asadal_gifta` (`id`, `file`) VALUES
(1, '11800_6986_10148.jpg'),
(2, '7214_9584_10506.jpg'),
(3, '9650_6964_11806.jpg'),
(4, '11871_8791_8441.jpg'),
(5, '12304_10978_12197.jpg'),
(6, '9192_11153_10722.jpg'),
(7, '8755_7003_9642.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `asadal_refferal`
--

CREATE TABLE IF NOT EXISTS `asadal_refferal` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `kto` int(255) DEFAULT NULL,
  `ykogo` int(255) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `time` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT '0',
  `id_admin` int(11) DEFAULT '0',
  `last` int(11) DEFAULT '0',
  `block` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `buld`
--

CREATE TABLE IF NOT EXISTS `buld` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `dohod` int(11) NOT NULL DEFAULT '3600',
  `otdeh` int(11) NOT NULL DEFAULT '43200',
  `level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `buld`
--

INSERT INTO `buld` (`id`, `id_user`, `dohod`, `otdeh`, `level`) VALUES
(1, 0, 3600, 43200, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `cforum_comments`
--

CREATE TABLE IF NOT EXISTS `cforum_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_topic` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_clan` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cforum_sub`
--

CREATE TABLE IF NOT EXISTS `cforum_sub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clan` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `clan_rang` set('1','4','5','') NOT NULL,
  `gb` set('0','1','') NOT NULL DEFAULT '0',
  `onlick` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cforum_topic`
--

CREATE TABLE IF NOT EXISTS `cforum_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_forum` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_clan` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `top` set('0','1','','') NOT NULL DEFAULT '0',
  `close` set('0','1','','') NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `onlick` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clanchat`
--

CREATE TABLE IF NOT EXISTS `clanchat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_clan` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clans`
--

CREATE TABLE IF NOT EXISTS `clans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL DEFAULT '1',
  `exp` int(11) NOT NULL DEFAULT '0',
  `ruby` varchar(1000) NOT NULL DEFAULT '0',
  `rate_angels` varchar(1000) NOT NULL DEFAULT '0',
  `rate_time` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `date` int(11) NOT NULL,
  `gerb` varchar(50) NOT NULL DEFAULT '0.png',
  `side` varchar(100) NOT NULL,
  `stat` int(11) NOT NULL DEFAULT '0',
  `stat_level` int(11) NOT NULL DEFAULT '0',
  `max` int(11) NOT NULL DEFAULT '10',
  `count` int(11) NOT NULL,
  `ruby_mesto` varchar(1000) NOT NULL DEFAULT '1000',
  `sclad_ruby` int(11) NOT NULL DEFAULT '0',
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clan_histor`
--

CREATE TABLE IF NOT EXISTS `clan_histor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` int(11) NOT NULL,
  `text` text NOT NULL,
  `id_clan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clan_history`
--

CREATE TABLE IF NOT EXISTS `clan_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `ruby` int(11) NOT NULL,
  `login` varchar(255) CHARACTER SET utf8 NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clan_memb`
--

CREATE TABLE IF NOT EXISTS `clan_memb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user2` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum`
--

CREATE TABLE IF NOT EXISTS `forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `access` set('0','1','2','') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `forum`
--

INSERT INTO `forum` (`id`, `name`, `access`) VALUES
(1, 'Новости игры', '1'),
(2, 'Помощь по игре', '0'),
(3, 'Предложения', '0'),
(4, 'Развлечения', '0'),
(5, 'Разное', '0'),
(6, 'Конкурсы', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `gr`
--

CREATE TABLE IF NOT EXISTS `gr` (
  `id` int(11) NOT NULL,
  `id_up` int(11) NOT NULL,
  `g_name` varchar(1000) NOT NULL,
  `g_cena` int(11) NOT NULL,
  `g_x2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gr`
--

INSERT INTO `gr` (`id`, `id_up`, `g_name`, `g_cena`, `g_x2`) VALUES
(1, 1, 'Прописку', 2000, 2),
(2, 2, 'Вид на жительство', 5000, 4),
(3, 3, 'Гражданство', 10000, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `guard`
--

CREATE TABLE IF NOT EXISTS `guard` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `antidos` varchar(5) NOT NULL,
  `antoflood` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `guard`
--

INSERT INTO `guard` (`id`, `antidos`, `antoflood`) VALUES
(1, '0.5', '4');

-- --------------------------------------------------------

--
-- Структура таблицы `guests`
--

CREATE TABLE IF NOT EXISTS `guests` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `browser` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `guests`
--

INSERT INTO `guests` (`id`, `browser`, `ip`, `time`) VALUES
(1, 'Mozilla/5.0 Windows NT 10.0; Win64; x64 AppleWebKit/537.36 KHTML, like Gecko Chrome/57.0.2987.133 Safari/537.36', '127.0.0.1', '1491748839');

-- --------------------------------------------------------

--
-- Структура таблицы `hp_mp`
--

CREATE TABLE IF NOT EXISTS `hp_mp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `last` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `kont`
--

CREATE TABLE IF NOT EXISTS `kont` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_kont` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `leveling`
--

CREATE TABLE IF NOT EXISTS `leveling` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user` int(255) DEFAULT NULL,
  `time` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `in` int(11) NOT NULL,
  `out` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `online` int(11) NOT NULL DEFAULT '1',
  `unlink` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `number_format`
--

CREATE TABLE IF NOT EXISTS `number_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `col1` varchar(255) NOT NULL,
  `col2` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `number_format`
--

INSERT INTO `number_format` (`id`, `col1`, `col2`, `text`) VALUES
(1, '1', '1000', 'k'),
(2, '1000', '1000000', 'm'),
(3, '1000000', '1000000000', 'b');

-- --------------------------------------------------------

--
-- Структура таблицы `room_users`
--

CREATE TABLE IF NOT EXISTS `room_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_up` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_user` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `money_sek` varchar(1000) NOT NULL DEFAULT '1',
  `item` varchar(1000) NOT NULL,
  `pay` varchar(1000) NOT NULL,
  `pay_update` varchar(1000) NOT NULL,
  `pay_money_sek` varchar(1000) NOT NULL,
  `pay_money_sek_up` varchar(1000) NOT NULL,
  `limits` varchar(1000) NOT NULL,
  `id_room` bigint(20) NOT NULL,
  `give_money_sek` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sahta`
--

CREATE TABLE IF NOT EXISTS `sahta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL DEFAULT '0',
  `rand1` int(11) NOT NULL DEFAULT '25',
  `rand2` int(11) NOT NULL DEFAULT '50',
  `time` int(11) NOT NULL DEFAULT '0',
  `otdih` int(11) NOT NULL DEFAULT '0',
  `on` int(11) NOT NULL DEFAULT '1',
  `level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `sahta`
--

INSERT INTO `sahta` (`id`, `id_user`, `rand1`, `rand2`, `time`, `otdih`, `on`, `level`) VALUES
(1, 0, 25, 50, 0, 0, 1, 1),
(2, 1, 25, 50, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `topic`
--

CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_forum` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `top` set('0','1','','') NOT NULL DEFAULT '0',
  `close` set('0','1','','') NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `onlick` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `topic_msg`
--

CREATE TABLE IF NOT EXISTS `topic_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_topic` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` set('w','m') NOT NULL DEFAULT 'm',
  `registr` int(11) NOT NULL,
  `access` set('0','1','2','3') NOT NULL DEFAULT '0',
  `ruby` varchar(1000) NOT NULL DEFAULT '1',
  `gold` double NOT NULL DEFAULT '2',
  `online` int(11) NOT NULL,
  `news_read` int(11) NOT NULL DEFAULT '0',
  `id_partner` int(11) NOT NULL DEFAULT '0',
  `fix_mesto` varchar(100) NOT NULL,
  `angels` varchar(255) DEFAULT '0.000000',
  `id_clan` int(11) NOT NULL DEFAULT '0',
  `clan_angels` varchar(1000) NOT NULL DEFAULT '0',
  `clan_rang` int(11) NOT NULL DEFAULT '1',
  `clan_gold` int(11) NOT NULL DEFAULT '0',
  `limit_clan_gold` int(11) NOT NULL DEFAULT '3',
  `room` int(11) NOT NULL DEFAULT '1',
  `gold_room` varchar(1000) NOT NULL DEFAULT '1',
  `angels_bonus` varchar(1000) NOT NULL DEFAULT '1',
  `grazh_cena` int(11) NOT NULL DEFAULT '1000',
  `grazh_up` int(11) NOT NULL DEFAULT '1000',
  `tangels` varchar(1000) NOT NULL,
  `bonus_time` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `gold_dubl` varchar(1000) NOT NULL DEFAULT '1000000000',
  `dubl` varchar(1000) NOT NULL DEFAULT '1',
  `x2` varchar(1000) NOT NULL DEFAULT '1',
  `g_cena` int(11) NOT NULL DEFAULT '2500',
  `g_id` int(11) NOT NULL DEFAULT '1',
  `g_x2` int(11) NOT NULL DEFAULT '1',
  `ava` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `auto_load` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `pass` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `vip_time` int(11) NOT NULL,
  `vip` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `ip`, `login`, `password`, `email`, `sex`, `registr`, `access`, `ruby`, `gold`, `online`, `news_read`, `id_partner`, `fix_mesto`, `angels`, `id_clan`, `clan_angels`, `clan_rang`, `clan_gold`, `limit_clan_gold`, `room`, `gold_room`, `angels_bonus`, `grazh_cena`, `grazh_up`, `tangels`, `bonus_time`, `bonus`, `gold_dubl`, `dubl`, `x2`, `g_cena`, `g_id`, `g_x2`, `ava`, `status`, `auto_load`, `level`, `pass`, `secret`, `vip_time`, `vip`) VALUES
(1, '127.0.0.1', 'Админ', '', 'admin@site.ru', 'm', 1491748767, '2', '17', 1, 1491748838, 0, 0, '', '0.000000', 0, '0', 1, 0, 3, 1, '1', '1', 1000, 1000, '', 1491834771, 0, '1000000000', '1', '1', 2500, 1, 1, '', '', 0, 1, '123456', '', 0, 0),
(2, '127.0.0.1', 'Система', '', 'system@site.ru', 'm', 1491748767, '3', '17', 1, 1491748786, 0, 0, '', '0.000000', 0, '0', 1, 0, 3, 1, '1', '1', 1000, 1000, '', 1491834771, 0, '1000000000', '1', '1', 2500, 1, 1, '', '', 0, 1, '123456', '', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users_count`
--

CREATE TABLE IF NOT EXISTS `users_count` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `chat` int(1) DEFAULT '0',
  `forum` int(1) DEFAULT '0',
  `cchat` int(1) NOT NULL DEFAULT '0',
  `cforum` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users_count`
--

INSERT INTO `users_count` (`id`, `id_user`, `chat`, `forum`, `cchat`, `cforum`) VALUES
(1, 0, 0, 0, 0, 0),
(2, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `text` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `words`
--

INSERT INTO `words` (`id`, `text`) VALUES
(1, 'mmars'),
(2, 'www'),
(3, 'keo'),
(4, 'ru');

-- --------------------------------------------------------

--
-- Структура таблицы `worldkassa`
--

CREATE TABLE IF NOT EXISTS `worldkassa` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID платежа (Внутренний ID)',
  `id_user` int(11) unsigned NOT NULL COMMENT 'ID пользователя',
  `id_bill` int(11) unsigned NOT NULL COMMENT 'ID платежа в Worldkassa',
  `time` int(11) unsigned NOT NULL COMMENT 'Время инициализации платежа',
  `time_oplata` int(11) unsigned DEFAULT '0' COMMENT 'Время оплаты',
  `summa` decimal(5,0) NOT NULL DEFAULT '0' COMMENT 'Сумма',
  `alm` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Статистика платежей через WorldKassa' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `xsolla_payment`
--

CREATE TABLE IF NOT EXISTS `xsolla_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) DEFAULT '0',
  `payment_date` varchar(64) DEFAULT NULL,
  `payment_currency` varchar(5) DEFAULT NULL,
  `payment_amount` decimal(11,2) DEFAULT '0.00',
  `id_user` int(11) DEFAULT '0',
  `currency_name` varchar(32) DEFAULT NULL,
  `currency_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
