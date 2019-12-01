
INSERT INTO users (lot_id, rate_id, email, login, pass, avatar) VALUES
('6', '1', 'larisa01@mail.ru', 'Larisa', 123, 'userpic-larisa-small.jpg'),
('1', '2', 'Vladik02@mail.ru', 'Vladik', 1234, 'userpic.jpg'),
('2', '3', 'Viktor03@mail.ru', 'Viktor', 12345, 'userpic-mark.jpg');


INSERT INTO categories (cat_name, symb_code) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');


INSERT INTO lots (user_create_id, user_winner_id, cat_id, title, img, content, start_price) VALUES
('2', '1', '1', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 'Какая-то хрень 1', '10999'),
('3', '2', '1', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 'хрень 2', '159999'),
('3', '2', '2', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 'хрень 3', '8000'),
('3', '2', '3', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 'хрень 4', '10999'),
('3', '2', '4', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 'хрень 5', '7500'),
('1', '2', '6', 'Маска Oakley Canopy', 'img/lot-6.jpg', 'хрень 6', '5400');

INSERT INTO rates
set user_id = 1,
lot_id = 2,
rate_price = 20000;

INSERT INTO rates
set user_id = 2,
lot_id = 1,
rate_price = 15000;


