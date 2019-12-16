INSERT INTO users (email, login, pass) VALUES
('larisa01@mail.ru', 'Larisa', 123),
('Vladik02@mail.ru', 'Vladik', 1234),
('Viktor03@mail.ru', 'Viktor', 12345);


INSERT INTO categories (cat_name, symb_code) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');


INSERT INTO lots (user_create_id, cat_id, title, img, content, start_price, dt_end, step_rate) VALUES
('2', '1', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 'Какая-то хрень 1', '10999', '2020-02-05', '100'),
('3', '1', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 'хрень 2', '15000', '2020-01-07', '100'),
('3', '2', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 'хрень 3', '8000', '2020-01-09', '300'),
('3', '3', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 'хрень 4', '10999', '2020-01-01', '200'),
('3', '4', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 'хрень 5', '7500', '2019-12-22', '500'),
('1', '6', 'Маска Oakley Canopy', 'img/lot-6.jpg', 'хрень 6', '5400', '2019-12-20', '1000'),
('3', '5', 'Крепления Union Contact', 'img/lot-3.jpg', 'хрень 7', '5000', '2019-12-24', '150');

INSERT INTO rates
set user_id = 1,
lot_id = 2,
rate_price = 20000;

INSERT INTO rates
set user_id = 2,
lot_id = 1,
rate_price = 15000;
