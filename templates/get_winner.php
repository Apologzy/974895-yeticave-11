<?php
require ('vendor/autoload.php');

$transport = new Swift_SmtpTransport($config['smtp']['host'], $config['smtp']['port']);
$transport->setUsername($config['smtp']['user']);
$transport->setPassword($config['smtp']['password']);

$mailer = new Swift_Mailer($transport);
$winner = sql_lot_winner($con, $dt_now);
$all_lots = sql_get_lots($con);
$users_winner_info_arr = [];
foreach ($all_lots as $lot) {
    if (isset($lot['user_winner_id'])) {
        $user_winner_info = sql_get__winner_info($con, $lot['user_winner_id'], $lot['id'] );
        $users_winner_info_arr[] = $user_winner_info;
    }
};

foreach ($users_winner_info_arr as $user) {
    $recipients = [];
    $recipients[$user['email']] = $user['login'];
    $message = new Swift_Message();
    $message->setSubject("Ваша ставка выиграла");
    $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
    $message->setBcc($recipients);
    $user_name = $user['login'];
    $lot_url = 'http://yeticave/lot.php?lot_id=' . $user['id'];
    $lot_name = $user['title'];
    $my_rates_url = 'http://yeticave/my_rates.php';
    $email_page = include_template ('email.php', ['user_name' => $user_name, 'lot_url' => $lot_url, 'lot_name' => $lot_name, 'my_rates_url' => $my_rates_url]);
    $message->setBody($email_page, 'text/html');
    $result = $mailer->send($message);
};

