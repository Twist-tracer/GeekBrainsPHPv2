-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 20 2016 г., 06:40
-- Версия сервера: 10.1.9-MariaDB
-- Версия PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gb_phpv2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `gb_articles`
--

CREATE TABLE `gb_articles` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(55) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_articles`
--

INSERT INTO `gb_articles` (`id`, `title`, `content`) VALUES
(1, 'Test article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
(2, 'Test article 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');

-- --------------------------------------------------------

--
-- Структура таблицы `gb_comments`
--

CREATE TABLE `gb_comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `article_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `date` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_comments`
--

INSERT INTO `gb_comments` (`id`, `article_id`, `name`, `comment`, `date`) VALUES
(1, 1, 'Test user 1', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.', 1459696259),
(2, 2, 'Test user 1', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.', 1459696253),
(3, 1, 'Simple User', 'Это комментарий обычного пользователя к первой статье', 1461126861),
(4, 1, 'Simple User', 'Это еще один комментарий обычного пользователя к первой статье', 1461126877),
(5, 1, 'Moderator', 'Это комментарий модератора к первой статье', 1461126917),
(6, 1, 'Admin', 'А это комментарий админа к первой статье', 1461126933),
(7, 2, 'Simple User', 'Это комментарий обычного пользователя ко второй статье', 1461126861),
(8, 2, 'Simple User', 'Это еще один комментарий обычного пользователя ко второй статье', 1461126877),
(9, 2, 'Moderator', 'Это комментарий модератора ко второй статье', 1461126917),
(10, 2, 'Admin', 'А это комментарий админа ко второй статье', 1461126933);

-- --------------------------------------------------------

--
-- Структура таблицы `gb_privs`
--

CREATE TABLE `gb_privs` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_privs`
--

INSERT INTO `gb_privs` (`id`, `name`, `description`) VALUES
(1, 'CONSOL_ACCESS', 'Доступ к консоли'),
(2, 'DELETE_COMMENTS', 'Удалять комментарии'),
(3, 'LEAVE_COMMENTS', 'Возможность оставлять комментарии к статьям.'),
(4, 'EDIT_USERS', 'Возможность редактирования/удаления пользователей');

-- --------------------------------------------------------

--
-- Структура таблицы `gb_privs2roles`
--

CREATE TABLE `gb_privs2roles` (
  `id_priv` int(11) UNSIGNED NOT NULL,
  `id_role` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_privs2roles`
--

INSERT INTO `gb_privs2roles` (`id_priv`, `id_role`) VALUES
(1, 1),
(2, 1),
(3, 1),
(1, 2),
(3, 2),
(3, 3),
(4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `gb_roles`
--

CREATE TABLE `gb_roles` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_roles`
--

INSERT INTO `gb_roles` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Наивысшая роль'),
(2, 'Moderator', 'Имеет доступ к консоли. Может добавлять, удалять, редактировать статьи. Имеет возможность оставлять комментарии к статьям, удалять свои комментарии.'),
(3, 'User', 'Имеет возможность оставлять комментарии к статьям, удалять свои комментарии.'),
(4, 'Guest', 'Самая низшая ступень. Может только просматривать статьи.');

-- --------------------------------------------------------

--
-- Структура таблицы `gb_sessions`
--

CREATE TABLE `gb_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `sid` varchar(10) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_last` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_sessions`
--

INSERT INTO `gb_sessions` (`id`, `id_user`, `sid`, `time_start`, `time_last`) VALUES
(1, 2, 'iF7j8GDQwU', '2016-04-20 06:33:49', '2016-04-20 06:33:49'),
(2, 3, 'ZSIoHmhucg', '2016-04-20 06:35:03', '2016-04-20 06:35:03'),
(3, 1, 'QuRbuwBPQG', '2016-04-20 06:35:21', '2016-04-20 06:35:21');

-- --------------------------------------------------------

--
-- Структура таблицы `gb_users`
--

CREATE TABLE `gb_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(55) NOT NULL,
  `password` varchar(32) NOT NULL,
  `id_role` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gb_users`
--

INSERT INTO `gb_users` (`id`, `login`, `password`, `id_role`) VALUES
(1, 'Admin', 'd8578edf8458ce06fbc5bb76a58c5ca4', 1),
(2, 'Simple User', 'd8578edf8458ce06fbc5bb76a58c5ca4', 3),
(3, 'Moderator', 'd8578edf8458ce06fbc5bb76a58c5ca4', 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `gb_articles`
--
ALTER TABLE `gb_articles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gb_comments`
--
ALTER TABLE `gb_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gb_privs`
--
ALTER TABLE `gb_privs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `gb_roles`
--
ALTER TABLE `gb_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `gb_sessions`
--
ALTER TABLE `gb_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gb_users`
--
ALTER TABLE `gb_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `gb_articles`
--
ALTER TABLE `gb_articles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `gb_comments`
--
ALTER TABLE `gb_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `gb_privs`
--
ALTER TABLE `gb_privs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `gb_roles`
--
ALTER TABLE `gb_roles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `gb_sessions`
--
ALTER TABLE `gb_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `gb_users`
--
ALTER TABLE `gb_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
