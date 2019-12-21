<?php


/**
 * функция для форматирования времени, которое осталось до окончания торгов
 * @param object $time_now Объект DataTime текущего времени.
 * @param object $date_end Объект DataTime завершения торгов.
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_lost_time ($time_now, $date_end) {
    $date_now = $time_now;
    $dt_end = $date_end;
    $time = date_diff($time_now, $date_end);
    $month_con = date_interval_format($time, '%m');
    $day_con = date_interval_format($time, '%a');
    $hours_con = date_interval_format($time, '%h');
    $min_con = date_interval_format($time, '%i');
    if ($date_now > $dt_end) {
        return 'trade off';
     } else {
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
    }
};


/**
 * функция для определения класса, который нужен для подсветки таймера, если времени до конца торгов осталось меньше часа.
 * @param object $time_now Объект DataTime текущего времени.
 * @param object $date_end Объект DataTime завершения торгов.
 *
 * @return string пустая строка '' или строка c названием класса 'timer--finishing'
 */
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


/**
 * функция расчета и склонения времени ставки, относительно текущего времени
 * @param object $time_now Объект DataTime текущего времени.
 * @param object $rate_tame Объект DataTime времени ставки.
 *
 * @return string Рассчитанная форма множественнго числа
 */
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


/**
 * функция склонения ставок
 * @param int $rates число ставок
 *
 * @return string Рассчитанная форма множественнго числа либо строка 'Стартовая цена'.
 */
function get_rates_amount ($rates) {
    if ($rates == 1) {
       return $rates . ' ставка';
    } elseif ($rates > 1 and $rates <= 4) {
       return $rates . ' ставки';
    } elseif ($rates == 0) {
        return 'Стартовая цена';
    }else {
        return $rates . ' ставок';
    }
};



/**
 * функция рассчета общего количества страниц для пагинации.
 * @param array $lots массив с лотами
 *
 * @return int количество страниц
 */
function get_total_pages($lots) {
    $lots_count = count($lots);
    if($lots_count == 0) {
        return 1;
    } else {
        return ceil($lots_count/9);
    }
};


/**
 * функция расчета смещения и лимита в sql запросах, для отображения нужного количества лотов на странице.
 * @param int $page_size массив с лотами
 * @param int $total_pages массив с лотами
 *
 * @return array двумерный массив включающий в себя страницу, смещение и лимит.
 */
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

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
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



/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 *
 * @return string Итоговый HTML
 */
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


/**
 * Функция для валидации по полю емейл
 * @param string $value значение из формы регистрации, поля емеил.
 * @param string $min минимальное количество символов.
 * @param array $max максимальное количество символов.
 *
 * @return string с ошибкой валидации либо null если ошибок нету.
 */
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



/**
 * функция валидации шага ставки в форме добавления лота
 * @param string $value значение из формы добавления лота, поля шаг ставки.
 * @param string $min минимальное количество символов.
 * @param array $max максимальное количество символов.
 *
 * @return string с ошибкой валидации либо null если ошибок нету.
 */
function validate_step_rate($value, $min, $max) {
    $value = intval($value);
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        } elseif (!is_int($value)) {
            return 'Указан неверный шаг ставки';
        } elseif ($value <= 0) {
            return 'Указан неверный шаг ставки';
        }

    }

    return null;
};


/**
 * функция валидации цены лота в форме добавления лота
 * @param string $value значение из формы добавления лота, поля цена лота.
 * @param string $min минимальное количество символов.
 * @param array $max максимальное количество символов.
 *
 * @return string с ошибкой валидации либо null если ошибок нету.
 */
function validate_lot_rate($value, $min, $max) {
    $value = intval($value);
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        } elseif (!is_int($value)) {
            return 'Указана неверая цена лота';
        } elseif ($value <= 0) {
            return 'Указана неверная цена лота';
        }

    }

    return null;
};



/**
 * функция валидации длины поля
 * @param string $value значение из формы.
 * @param string $min минимальное количество символов.
 * @param array $max максимальное количество символов.
 *
 * @return string с ошибкой валидации либо null если ошибок нету.
 */
function validateLength($value, $min, $max) {
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }

    return null;
};


/**
 * функция валидация даты окончания торгов
 * @param string $value значение из формы добавления лота, поля даты завершения торгов.
 * @param string $min минимальное количество символов.
 * @param array $max максимальное количество символов.
 *
 * @return string с ошибкой валидации либо null если ошибок нету.
 */
function validate_date($value, $min, $max) {
    if ($value) {
        $date_now = date_create('now');
        $date_now->modify('+1 day');
        $date_end = date_create($value);
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Значение должно быть от $min до $max символов";
        } elseif(!strtotime($value)) {
            return "Указан неверный формат даты";
        } else {
            if ($date_now > $date_end) {
                return 'Указана неварная дата окончания торгов';
            } else {
                return null;
            }
        }
    }

    return null;
};

/**
 * функция подставновки введенного значения от пользователя в форму.
 * @param string $name значение которое ввел пользователь.
 *
 * @return string значение которое ввел пользователь.
 */
function getPostVal($name) {
    return filter_input(INPUT_POST, $name);
};


/**
 * функция для расчета минимальной ставки на лот
 * @param string $price или число показываюшиее цену.
 * @param string $step_rate или число показывающее шаг ставки.
 * @param string $rate_amount или число показывающее число ставок.
 *
 * @return string сумма минимальной ставки.
 */
function calc_min_rate ($price, $step_rate, $rate_amount) {
    if ($rate_amount == 'Стартовая цена') {
        return $price;
    } else {
        return $price + $step_rate;
    }

};


/**
 * функция валидации добавления ставки
 * @param string $value или число показываюшиее цену.
 * @param $connect mysqli Ресурс соединения.
 * @param string $get_id или число отвечающее за id лота.
 *
 * @return string ошибку валидации либо null если ошибок нету.
 */
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
                $lots_and_rates = sql_get_rates($connect, $lot['id']);
                $rates_amount = count($lots_and_rates);
                $rates_result = get_rates_amount($rates_amount);
                $min_price = calc_min_rate($cur_price, $lot['step_rate'], $rates_result);
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


/**
 * функция для фильтрации данных от тегов из формы добавления лота.
 * @param string $lot_name название лота из формы.
 * @param string $lot_step шаг ставки из формы.
 * @param string $lot_rate цена лота из формы.
 * @param string $lot_date дата окончания торгов из формы.
 * @param string $lot_description описание лота из формы.
 * @param string $lot_cat_id id категории из формы.
 *
 * @return array ассоциативный очищенный от нежелательных тегов.
 */
function xss_filter ($lot_name, $lot_step, $lot_rate, $lot_date, $lot_description, $lot_cat_id ) {
    $lot_name = strip_tags($lot_name) ?? null;
    $lot_step = strip_tags($lot_step) ?? null;
    $lot_rate = strip_tags($lot_rate) ?? null;
    $lot_date = strip_tags($lot_date) ?? null;
    $lot_description = strip_tags($lot_description) ?? null;
    $lot_cat_id = strip_tags($lot_cat_id) ?? null;
    $non_tags_arr = [
        'lot-name' => $lot_name,
        'lot-step' => $lot_step,
        'lot-rate' => $lot_rate,
        'lot-date' => $lot_date,
        'description' => $lot_description,
        'category' => $lot_cat_id];
    return $non_tags_arr;
};

