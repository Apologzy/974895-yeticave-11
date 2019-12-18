<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lists_of_cat as $category): ?>
            <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
                <a href="/all_lots.php?content_id=<?= $category['id']; ?>"><?= $category['cat_name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    </ul>
</nav>

<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($lots_view as $lot): ?>
        <tr class="rates__item <?= ($lot['lost_time']=='trade off' && $_SESSION['user']['id']!==$lot['user_winner_id']) ? 'rates__item--end' : '' ?> <?= $_SESSION['user']['id']==$lot['user_winner_id'] ? 'rates__item--win' : '' ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="../<?=$lot['img']; ?>" width="54" height="40" alt="<?=$lot['title']; ?>">
                </div>
                <div>
                <h3 class="rates__title"><a href="lot.php?lot_id=<?= $lot['id']; ?>"><?=$lot['title']; ?></a></h3>
                <?php if($_SESSION['user']['id']==$lot['user_winner_id']): ?>
                <p><?=$lot['user_contact']['contacts'] ?></p>
                </div>
                <?php endif; ?>
            </td>
            <td class="rates__category">
                <?= $lot['cat_name']; ?>
            </td>
            <td class="rates__timer">
                <?php if($_SESSION['user']['id']==$lot['user_winner_id']): ?>
                <div class="timer timer--win">Ставка выиграла</div>
                <?php else: ?>
                <div class="timer <?= $lot['timer']; ?>"><?= $lot['lost_time']; ?></div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= $lot['rate_price']?>
            </td>
            <td class="rates__time">
                    <?=$lot['time'] ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>
