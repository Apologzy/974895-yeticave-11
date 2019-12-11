<?php


function get_lost_time ($time_now, $date_end) {
    $time = date_diff($time_now, $date_end);
    $month_con = date_interval_format($time, '%m');
    $day_con = date_interval_format($time, '%a');
    $hours_con = date_interval_format($time, '%h');
    $min_con = date_interval_format($time, '%i');
    if ($month_con >= 1) {
        return get_noun_plural_form($month_con, 'месяц', 'месяца', 'месяцев');
    } else if ($day_con >= 7) {
        $week_con = floor($day_con / 7);
        return get_noun_plural_form($week_con, 'неделя', 'недели', 'недель');
    } else if ($day_con > 0 and $day_con < 7 ) {
        return get_noun_plural_form($day_con, 'день', 'дня', 'дней');
    } else if ($hours_con >= 1) {
        return get_noun_plural_form($hours_con, 'час', 'часа', 'часов');
    } else {
        return get_noun_plural_form($min_con, 'минута', 'минуты', 'минут');
    }
};

function timer_finisher ($time_now, $date_end) {
    $time = date_diff($time_now, $date_end);
    $month_con = date_interval_format($time, '%m');
    $day_con = date_interval_format($time, '%a');
    $hours_con = date_interval_format($time, '%h');
    $min_con = date_interval_format($time, '%i');
    if ($month_con >= 1) {
        return '';
    } else if ($day_con >= 7) {
        $week_con = floor($day_con / 7);
        return '';
    } else if ($day_con > 0 and $day_con < 7 ) {
        return '';
    } else if ($hours_con >= 1) {
        return '';
    } else {
        return 'timer--finishing';
    }
};


function get_history_time_ago ($time_now, $rate_tame) {
    $time = date_diff($time_now, $rate_tame);
    $month_con = date_interval_format($time, '%m');
    $day_con = date_interval_format($time, '%a');
    $hours_con = date_interval_format($time, '%h');
    $min_con = date_interval_format($time, '%i');
    if ($month_con >= 1) {
        return get_noun_plural_form($month_con, 'месяц', 'месяца', 'месяцев') . ' назад';
    } else if ($day_con >= 7) {
        $week_con = floor($day_con / 7);
        return get_noun_plural_form($week_con, 'неделя', 'недели', 'недель') . ' назад';
    } else if ($day_con > 0 and $day_con < 7 ) {
        return get_noun_plural_form($day_con, 'день', 'дня', 'дней') . ' назад';
    } else if ($hours_con >= 1) {
        return get_noun_plural_form($hours_con, 'час', 'часа', 'часов'). ' назад';
    } else {
        return get_noun_plural_form($min_con, 'минута', 'минуты', 'минут') . ' назад';
    }
};


function get_rates_amount ($rates) {
    if ($rates == 1) {
       return $rates . ' ставка';
    } else {
       return $rates . ' ставки';
    };
};

function get_total_pages($lots) {
    $lots_count = count($lots);
    if($lots_count == 0) {
        return 1;
    } else {
        return ceil($lots_count/9);
    }
};

function get_offset_and_limits ($page_size, $total_pages) {
   $limit = $page_size;
   $offset = 0;
   $offset_and_limits = [];
   for ($i = 1; $i <= $total_pages; $i++) {
       $offset_and_limits[$i] = ['page' => $i, 'offset'=> $offset, 'limit'=> $limit];
       $offset += 9;
   };
   return $offset_and_limits;
};


function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;
    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $number . ' ' . $many;

        case ($mod10 > 5):
            return $number . ' ' . $many;

        case ($mod10 === 1):
            return $number . ' '  . $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $number . ' '  . $two;

        default:
            return $number . ' '  . $many;
    }

};


function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
};


function getContent($text, $max_length) {

    $str_length = mb_strlen($text);
    if ($str_length > $max_length) {
        $words = explode(' ', $text);
        $new_words = [];
        $divide = '...';

        foreach ($words as $word) {
            $new_words[] = $word;
            $new_str_lng = mb_strlen(implode(' ', $new_words));
            if ($new_str_lng > $max_length) {
                array_pop($new_words);
                return implode(' ', $new_words).$divide;
            } elseif ($new_str_lng == $max_length) {
                return implode(' ', $new_words).$divide;
            };
        };
    }
    else {
        return $text;
    }
};


function posts_filtered ($content) {
    foreach ($content as $key => & $x_cont) {
        $x_cont['title'] = strip_tags($x_cont['title']);
        $x_cont['login'] = strip_tags($x_cont['login']);
        $x_cont['content'] = strip_tags($x_cont['content']);
        $x_cont['login'] = strip_tags($x_cont['login']);
        $x_cont['avatar'] = strip_tags($x_cont['avatar']);
        $x_cont['autor'] = strip_tags($x_cont['autor']);
        $x_cont['img'] = strip_tags($x_cont['img']);
        $x_cont['link'] = strip_tags($x_cont['link']);
        $x_cont['video'] = strip_tags($x_cont['video']);
        $x_cont['avatar'] = strip_tags($x_cont['avatar']);
    };
    return $content;
};


function include_template ($name, $data) {
    $name = 'templates/' . $name;
    $result = '';
    if (!file_exists($name)) {
        return $result;
    }
    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();
    return $result;
};

// валидация по полю емейл
function validateEmail($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "Введите корректный email";
        };
    }

    return null;
};


// проверка заполненности поля
function validateFilled($name) {
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
};

// проверка длины поля
function validateLength($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
};

//валидация даты
function validate_date($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        } elseif(!strtotime($value)) {
            return "Указан неверный формат даты";
        }
    }

    return null;
};

//возвращает введенное пользователем значение в форму
function getPostVal($name) {
    return filter_input(INPUT_POST, $name);
};

function calc_min_rate ($price, $step_rate) {
    return $price + $step_rate;
};

function validate_rate ($value, $connect, $get_id) {
    if (strlen($value) > 0) {
        mysqli_set_charset($connect, 'utf8');
        $whereCondition = ($get_id ?  "WHERE l.id = $get_id" : '');
        $lots = <<<SQL
    SELECT l.id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    $whereCondition
SQL;
        $sql_lots_result = mysqli_query($connect, $lots);
        if(!$sql_lots_result) {
            $error = mysqli_error($connect);
            exit('Ошибка mySQL: ' . $error);
        }
        else {
            $lots_arr = mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
            foreach ($lots_arr as &$lot) {
                $current_price = sql_get_current_price($connect, $lot['id']);
                $lot['price'] = $current_price;
                $cur_price = (isset($lot['price']['rate_price']) ? $lot['price']['rate_price'] : $lot['start_price']);
                $min_price = calc_min_rate($cur_price, $lot['step_rate']);
                $lot['min_price'] = $min_price;
                if ($lot['min_price'] > $value) {
                    return 'Ставка слишком мала';
                } else {
                    return null;
                }
            };
        };
    }
    return null;
};

function show_error(&$content, $error) {
    $content = include_template('error.php', ['error' => $error]);
};
