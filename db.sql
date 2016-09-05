-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 05 2016 г., 17:05
-- Версия сервера: 10.1.16-MariaDB
-- Версия PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attachment`
--

CREATE TABLE `attachment` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `type` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `src` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `icon` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `lang` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `type` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `meta` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `parent`, `name`, `type`, `status`, `meta`, `position`) VALUES
(1, 0, 'Main menu', 'menu', 1, 0, 1),
(2, 0, 'User menu', 'menu', 1, 0, 2),
(3, 0, 'Acting & Extras', 'talent', 1, 0, 1),
(4, 0, 'Acting & Extras', 'job', 1, 0, 1),
(5, 0, 'Administrator', 'user', 1, 0, 1),
(6, 0, 'Emcee & Voiceover', 'talent', 1, 0, 2),
(7, 0, 'Entertaining', 'talent', 1, 0, 3),
(8, 0, 'Modeling', 'talent', 1, 0, 4),
(9, 0, 'Promotion', 'talent', 1, 0, 5),
(10, 0, 'TV Presenting', 'talent', 1, 0, 6),
(11, 0, 'Member', 'user', 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `announce` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `meta` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `attachment` int(11) NOT NULL,
  `lang` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `dictionary`
--

CREATE TABLE `dictionary` (
  `id` int(11) NOT NULL,
  `_key` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `lang` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `filter`
--

CREATE TABLE `filter` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `lang` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `router` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `attributes` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `lang` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `parent`, `category`, `name`, `router`, `status`, `position`, `attributes`, `lang`) VALUES
(1, 0, 1, 'Find audiotions & jobs', 1, 1, 1, 'data-name="router:menu"', 'en'),
(2, 0, 1, 'Talent database', 1, 1, 2, 'data-name="router:menu"', 'en'),
(3, 0, 2, 'Login', 2, 1, 1, 'data-name="router:modal"', 'en'),
(4, 0, 2, 'Sign in', 3, 1, 2, 'data-name="router:modal"', 'en');

-- --------------------------------------------------------

--
-- Структура таблицы `meta`
--

CREATE TABLE `meta` (
  `id` int(11) NOT NULL,
  `title` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `keywords` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `lang` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `router`
--

CREATE TABLE `router` (
  `id` int(11) NOT NULL,
  `url` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uri` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `access` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Дамп данных таблицы `router`
--

INSERT INTO `router` (`id`, `url`, `uri`, `access`) VALUES
(1, '/', '/', 0),
(2, '#modal={"action":"login"}', '#', 0),
(3, '#modal={"action":"register"}', '#', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `login` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `pass` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `mail` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `secret` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `category`, `login`, `pass`, `mail`, `secret`, `status`) VALUES
(17, 11, '', '741a9ea1e1ffcc5ce547c5b319f439ea', 'namirif@gmail.com', 'b0392fdcddbc6ccdc6f070f6d26b4b0f82ec83df', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_data`
--

CREATE TABLE `user_data` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `first_name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `last_name` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` int(11) NOT NULL,
  `categories` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `gender` int(11) NOT NULL,
  `nationality` int(11) NOT NULL,
  `apperance` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `country` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `language` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_data`
--

INSERT INTO `user_data` (`id`, `user`, `first_name`, `last_name`, `birthdate`, `categories`, `gender`, `nationality`, `apperance`, `country`, `city`, `language`) VALUES
(8, 17, '', '', 0, '{"4":"9"}', 0, 0, '', 0, 0, '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dictionary`
--
ALTER TABLE `dictionary`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `meta`
--
ALTER TABLE `meta`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `router`
--
ALTER TABLE `router`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `dictionary`
--
ALTER TABLE `dictionary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `filter`
--
ALTER TABLE `filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `meta`
--
ALTER TABLE `meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `router`
--
ALTER TABLE `router`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT для таблицы `user_data`
--
ALTER TABLE `user_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
