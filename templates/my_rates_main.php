<nav class="nav">
    <ul class="nav__list container">
        <?php if (isset($lists_of_cat)): ?>
        <?php foreach ($lists_of_cat as $category): ?>
        <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
            <a href="/all_lots.php?content_id=<?= isset($category['id']) ? $category['id'] : null  ?>"><?= isset($category['cat_name']) ? $category['cat_name'] : '' ?></a>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    </ul>
</nav>

<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php if (isset($lots_view)) : ?>
        <?php foreach ($lots_view as $lot): ?>
        <tr class="rates__item <?= ($lot['lost_time']=='trade off' && $_SESSION['user']['id']!==$lot['user_winner_id']) ? 'rates__item--end' : '' ?> <?= $_SESSION['user']['id']==$lot['user_winner_id'] ? 'rates__item--win' : '' ?>">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="../<?=isset($lot['img']) ? $lot['img'] : null ?>" width="54" height="40" alt="<?=isset($lot['title']) ? $lot['title'] : '' ?>">
                </div>
                <div>
                <h3 class="rates__title"><a href="lot.php?lot_id=<?= isset($lot['id']) ? $lot['id'] : null ?>"><?=isset($lot['title']) ? $lot['title'] : '' ?></a></h3>
                <?php if($_SESSION['user']['id']==$lot['user_winner_id']): ?>
                <p><?= isset($lot['user_contact']['contacts']) ? $lot['user_contact']['contacts'] : '' ?></p>
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
                <div class="timer <?= isset($lot['timer']) ? $lot['timer'] : '' ?>"><?= isset($lot['lost_time']) ? $lot['lost_time'] : null ?></div>
                <?php endif; ?>
            </td>
            <td class="rates__price">
                <?= isset($lot['rate_price']) ? $lot['rate_price'] : null ?>
            </td>
            <td class="rates__time">
                    <?=isset($lot['time']) ? $lot['time'] : null ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>
</section>
