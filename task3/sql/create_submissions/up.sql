CREATE SCHEMA IF NOT EXISTS public;

CREATE TABLE IF NOT EXISTS public.submissions (
  `id` varchar(500) PRIMARY KEY,
  `name` varchar(500) not null,
  `phone` varchar(500) not null,
  `email` varchar(500) not null,
  `birth_date` date not null,
  `bio` text not null,
  `language` ENUM('Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go') NOT NULL
  `sex` TINYINT(1)
  `created_at` timestamp not null default current_timestamp
);