<?php


/**
 * функция подключения к базе данных
 *
 * @param string $host хост
 * @param string $user имя пользователя
 * @param string $password  пароль
 * @param string $database_name имя базы данных
 *
 * @return mysqli Ресурс соединения
 */
function sql_get_connect ($host, $user, $password, $database_name) {
    $con = mysqli_connect($host, $user, $password, $database_name);
    if ($con == false) {
        exit('Ошибка подключения ' . mysqli_connect_error());
    };
    return $con;
};



/**
 * функция создает двумерный массив категорий из базы данных
 *
 * @param $connect mysqli Ресурс имя базы данных
 *
 * @return array двумерный массив категориий
 */
function sql_get_categories($connect) {
    mysqli_set_charset($connect, 'utf8');
    $categories = <<<SQL
    SELECT * FROM categories 
SQL;
    $sql_cut_result = mysqli_query($connect, $categories);
    if(!$sql_cut_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
       return mysqli_fetch_all($sql_cut_result, MYSQLI_ASSOC);
    }
};


/**
 * функция создает массив с названием категории из базы данных исходя из id категории
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $cat_id или число означающиее id категории
 *
 * @return array массив с именем ктегории
 */
function sql_get_categories_name($connect, $cat_id) {
    $whereCondition = ($cat_id ?  "WHERE id = $cat_id" : '');
    mysqli_set_charset($connect, 'utf8');
    $categories = <<<SQL
    SELECT cat_name FROM categories 
    $whereCondition
SQL;
    $sql_cut_result = mysqli_query($connect, $categories);
    if(!$sql_cut_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_assoc($sql_cut_result);
    }
};


/**
 * функция проверяет наличие контент id
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param int $content_id  число означающиее id категории
 *
 * @return string с ошибкой, либо $content_id если он существует
 */
function sql_isset_content_id ($connect, $content_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($content_id ?  "WHERE id = $content_id" : '');
    $lots = <<<SQL
    SELECT * FROM categories
    $whereCondition
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        $array = mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
        if (count($array) > 0) {
            return $content_id;
        }
        else {
            return 'error';
        }

    }
};


/**
 * функция проверяет существование лот ID
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param int $lot_id число означающиее id лота
 *
 * @return string с ошибкой, либо $lot_id если он существует
 */
function sql_isset_lot_id ($connect, $lot_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($lot_id ?  "WHERE id = $lot_id" : '');
    $lots = <<<SQL
    SELECT * FROM lots
    $whereCondition
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        $array = mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
        if (count($array) > 0) {
            return $lot_id;
        }
        else {
            return 'error';
        }

    }
};


/**
 * функция создает двумерный массив с лотами из базы данных
 *
 * @param $connect mysqli Ресурс имя базы данных
 *
 * @return array двумерный массив с лотами
 */
function sql_get_lots($connect) {
    mysqli_set_charset($connect, 'utf8');
    $lots = <<<SQL
    SELECT * FROM lots
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};




/**
 * функция создает двумерный массив лотов для определенной категории
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param int $get_id число означающиее id категории
 *
 * @return array двумерный массив лотов по определенной категории включая поля из таблицы категорий
 */
function sql_get_total_count_lots($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE c.id = $get_id" : '');
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
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


/**
 * функция для показа найденных лотов, с учетом запроса поиска, страницы, смещения и лимита.
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $search строка из запроса поиска.
 * @param string $page или число означающее номер страницы
 * @param array $offset_and_limits двумерный массив в котором находятся значения смещения и лимита.
 *
 * @return array двумерный массив лотов включающий поля из таблицы категорий
 */
function sql_get_found_lots_from_search ($connect, $search, $page, $offset_and_limits) {
    mysqli_set_charset($connect, 'utf8');
    $offset = $offset_and_limits[$page]['offset'] ?? null;
    $limit = $offset_and_limits[$page]['limit'] ?? null;
    $lots = <<<SQL
    SELECT l.id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    WHERE MATCH(l.title, l.content) AGAINST("$search")
    ORDER BY l.dt_create DESC
    LIMIT $limit OFFSET $offset
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        return 'error';
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};



/**
 * функция для показа ставок текущего пользователя с учетом пагинации
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $user_id или число означающиее id пользователя.
 * @param string $page или число означающее номер страницы
 * @param array $offset_and_limits двумерный массив в котором находятся значения смещения и лимита.
 *
 * @return array двумерный массив лотов включающий поля из таблицы ставок и категорий.
 */
function sql_get_lots_and_rates_for_curr_user ($connect, $user_id, $page, $offset_and_limits) {
    mysqli_set_charset($connect, 'utf8');
    $offset = $offset_and_limits[$page]['offset'];
    $limit = $offset_and_limits[$page]['limit'];
    $whereCondition = ($user_id ?  "WHERE r.user_id = $user_id" : '');
    $lots = <<<SQL
    SELECT  l.id, l.user_create_id, l.user_winner_id, l.cat_id, l.dt_create, c.cat_name, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.dt_create AS rate_dt_create, r.rate_price FROM lots l
    JOIN  rates r ON r.lot_id = l.id
    JOIN  categories c ON c.id = l.cat_id
    $whereCondition
    ORDER BY rate_dt_create DESC
    LIMIT $limit OFFSET $offset
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


/**
 * функция для показа ставок текущего пользователя с учетом пагинации
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $user_id или число означающиее id пользователя.
 * @param string $lot_id или число означающее id лота.
 *
 * @return array массив лотов включающий поля из таблицы пользователей и ставок.
 */
function sql_get__winner_info($connect, $user_id, $lot_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($user_id ?  "WHERE u.id = $user_id AND l.id = $lot_id" : '');
    $lots = <<<SQL
    SELECT  l.id, l.user_create_id, u.email, u.login, u.contacts, l.user_winner_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.dt_create AS rate_dt_create, r.rate_price FROM lots l
    JOIN users u ON u.id = l.user_winner_id
    JOIN  rates r ON r.lot_id = l.id
    $whereCondition
    ORDER BY r.rate_price DESC 
    LIMIT 1
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_assoc($sql_lots_result);
    }
};


/**
 * функция для показа контактов пользователя
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $user_id или число означающиее id пользователя.
 *
 * @return array массив в котором лежат контакты определенного пользователя.
 */
function get_user_contacts($connect, $user_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($user_id ?  "WHERE id = $user_id" : '');
    $contacts = <<<SQL
    SELECT contacts FROM users
    $whereCondition
SQL;
    $sql_contacts_result = mysqli_query($connect, $contacts);
    if(!$sql_contacts_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_assoc($sql_contacts_result);
    }
};


/**
 * функция для показа лотов определенной страницы, включающая значения страницы, смещения и лимита.
 *
 * @param $connect mysqli Ресурс имя базы данных.
 * @param string $get_id или число означающиее id категории.
 * @param string $page или число означающее номер страницы.
 * @param array $offset_and_limits двумерный массив в котором находятся значения смещения и лимита.
 *
 * @return array двумерный массив лотов включающий поля из таблицы  категорий.
 */
function sql_get_lots_for_curr_pages ($connect, $get_id, $page, $offset_and_limits) {
    mysqli_set_charset($connect, 'utf8');
    $offset = $offset_and_limits[$page]['offset'] ?? null;
    $limit = $offset_and_limits[$page]['limit'] ?? null;
    $whereCondition = ($get_id ?  "WHERE c.id = $get_id" : '');
    $lots = <<<SQL
    SELECT l.id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    $whereCondition
    ORDER BY l.dt_create DESC
    LIMIT $limit OFFSET $offset
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        return 'error';
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


/**
 * функция для проверки существования ставки на лот, конеретного пользователя.
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $lot_id или число означающее id лота.
 * @param string $user_id или число означающиее id пользователя.
 *
 * @return array двумерный массив лотов включающий поля из таблицы ставок.
 */
function sql_existence_rates ($connect, $lot_id, $user_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($user_id ?  "WHERE r.user_id = $user_id AND l.id = $lot_id" : '');
    $lots = <<<SQL
    SELECT  l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.id AS rate_id, r.user_id, r.lot_id, r.dt_create AS rate_dt_create, r.rate_price FROM lots l
    JOIN  rates r ON r.lot_id = l.id
    $whereCondition
    ORDER BY l.dt_create DESC
    LIMIT 1
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


/**
 * функция просматривает все лоты и ставки, если время торгов закончилось, выявляет победителя и записывает его в бд.
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param object $time_now Объект DataTime текущего времени.
 *
 * функция просто записывает id юзера в поле user_winner_id из таблины лотов, если были ошибки то сработает exit().
 */
function sql_lot_winner ($connect, $time_now) {
    mysqli_set_charset($connect, 'utf8');
    $all_lots = sql_get_lots($connect);
    foreach ($all_lots as $lot) {
        $date_end = date_create($lot['dt_end']);
        if ($time_now > $date_end) {
         $id = $lot['id'];
         $whereCondition = ($id ?  "WHERE l.id = $id" : '');
         $lot_and_rate = <<<SQL
         SELECT  l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.dt_create AS rate_dt_create, r.rate_price FROM lots l
         JOIN  rates r ON r.lot_id = l.id
         $whereCondition
         ORDER BY r.rate_price DESC 
         LIMIT 1
SQL;

        $sql_lots_result = mysqli_query($connect, $lot_and_rate);
        if(!$sql_lots_result) {
            $error = mysqli_error($connect);
            exit('Ошибка mySQL: ' . $error);
        }
        else {
           $lot_and_rate_arr = mysqli_fetch_assoc($sql_lots_result);
           $lot_id = $lot_and_rate_arr['id'];
           $user_id = $lot_and_rate_arr['user_id'];
           $lot_update = <<<SQL
           UPDATE lots
           SET user_winner_id = "$user_id"
           WHERE id = "$lot_id"
SQL;
           $sql_upd_res = mysqli_query($connect, $lot_update);
           if(!$sql_upd_res) {
               $error = mysqli_error($connect);
               exit('Ошибка mySQL: ' . $error);
           }
        }
      };
    };
}


/**
 * функция для показа лотов
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param int $get_id число или строка с числом означающиее id категории
 *
 * @return array двумерный массив лотов по определенной категории включая поля из таблицы категорий сортированный по дате добавления лота
 */
function sql_get_lots_view($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE c.id = $get_id" : '');
    $lots = <<<SQL
    SELECT l.id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    $whereCondition
    ORDER BY l.dt_create DESC
    LIMIT 9
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};

//функция для показа конкретного лота
function sql_get_lot ($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE l.id = $get_id" : '');
    $lots = <<<SQL
    SELECT l.id, l.user_create_id, c.id AS cat_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
    $whereCondition
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


/**
 * функция для показа всех лотов на которые были сделаны ставки конкретным пользователем
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $user_id или число означающиее id пользователя
 *
 * @return array двумерный массив ставок включающий поля из таблицы пользователя
 */
function sql_get_all_rates_of_curr_user ($connect, $user_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($user_id ?  "WHERE r.user_id = $user_id" : '');
    $rate = <<<SQL
    SELECT  l.id, l.user_winner_id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.dt_create AS rate_dt_create, r.rate_price FROM lots l
    JOIN  rates r ON r.lot_id = l.id
    $whereCondition
SQL;
    $sql_rate_result = mysqli_query($connect, $rate);
    if(!$sql_rate_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_rate_result, MYSQLI_ASSOC);
    }
};



/**
 * функия для показа истории ставок
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $get_id или число означающиее id лота
 *
 * @return array двумерный массив ставок включающий поля из таблицы пользователя
 */
function sql_get_rates_history($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE r.lot_id = $get_id" : '');
    $rate = <<<SQL
    SELECT  u.id, u.login, r.user_id, r.lot_id, r.dt_create, r.rate_price FROM users u
    JOIN  rates r ON u.id = r.user_id
    $whereCondition
SQL;
    $sql_rate_result = mysqli_query($connect, $rate);
    if(!$sql_rate_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_rate_result, MYSQLI_ASSOC);
    }
};


/**
 * функия для определения текущей цены
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $get_id или число означающиее id лота
 *
 * @return array массив ставок включающий поля из таблицы пользователя
 */
function sql_get_current_price ($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE r.lot_id = $get_id" : '');
    $rate = <<<SQL
    SELECT  u.id, u.login, r.user_id, r.lot_id, r.dt_create, r.rate_price FROM users u
    JOIN  rates r ON u.id = r.user_id
    $whereCondition
    ORDER BY r.rate_price DESC LIMIT 1
SQL;
    $sql_rate_result = mysqli_query($connect, $rate);
    if(!$sql_rate_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_assoc($sql_rate_result);
    }
};





/**
 * функция получения ставок для определенного лота
 *
 * @param $connect mysqli Ресурс имя базы данных
 * @param string $id или число означающиее id лота
 *
 * @return array двумерный массив лотов включающий поля из таблицы ставок
 */
function sql_get_rates($connect, $id) {
    mysqli_set_charset($connect, 'utf8');
    $were_con = $id;
    $lots = <<<SQL
    SELECT l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, r.user_id, r.lot_id, r.rate_price  FROM lots l 
    JOIN rates r ON l.id = r.lot_id
    WHERE r.lot_id = '$were_con'
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};



/**
 * функция для получения цены на лот.
 *
 * @param $connect mysqli Ресурс имя базы данных.
 * @param string $get_id или число означающиее id лота.
 *
 * @return array массив с ценой для конкретного лота.
 */
function sql_get_rate_price_all_lots($connect, $get_id) {
    mysqli_set_charset($connect, 'utf8');
    $whereCondition = ($get_id ?  "WHERE r.lot_id = $get_id" : '');
    $lots = <<<SQL
    SELECT r.rate_price  FROM lots l 
    JOIN rates r ON l.id = r.lot_id
    $whereCondition
    ORDER BY r.rate_price DESC LIMIT 1
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_lots_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_assoc($sql_lots_result);
    }
};

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
};
