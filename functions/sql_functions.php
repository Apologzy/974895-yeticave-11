<?php

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

function sql_get_lots($connect) {
    mysqli_set_charset($connect, 'utf8');
    $lots = <<<SQL
    SELECT * FROM lots
SQL;
    $sql_lots_result = mysqli_query($connect, $lots);
    if(!$sql_cut_result) {
        $error = mysqli_error($connect);
        exit('Ошибка mySQL: ' . $error);
    }
    else {
        return mysqli_fetch_all($sql_lots_result, MYSQLI_ASSOC);
    }
};


function sql_get_lots_view($connect) {
    mysqli_set_charset($connect, 'utf8');
    $lots = <<<SQL
    SELECT l.id, l.cat_id, l.dt_create, l.title, l.img, l.content, l.start_price, l.dt_end, l.step_rate, c.cat_name, c.symb_code FROM lots l 
    JOIN categories c ON c.id = l.cat_id
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

?>
