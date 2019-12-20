
<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= isset($user_name) ? $user_name : '' ?></p>
<p>Ваша ставка для лота <a href="<?= isset($lot_url) ? $lot_url : '#' ?>"><?= isset($lot_name) ? $lot_name : '' ?></a> победила.</p>
<p>Перейдите по ссылке <a href="<?= isset($my_rates_url) ? $my_rates_url : '#' ?>">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>

