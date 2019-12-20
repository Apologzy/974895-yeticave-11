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

</nav>
<section class="lot-item container">
    <?php if (isset($lots_view)) : ?>
    <?php foreach ($lots_view as $lot): ?>
    <h2><?= $lot['title']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= isset($lot['img']) ? $lot['img'] : null ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= isset($lot['cat_name']) ? $lot['cat_name'] : '' ?></span></p>
            <p class="lot-item__description"><?= isset($lot['content']) ? $lot['content'] : '' ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?= isset($lot['timer']) ? $lot['timer'] : '' ?>">
                    <?= isset($lot['lost_time']) ? $lot['lost_time'] : null ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount"><?=isset($lot['rate_count']) ? $lot['rate_count'] : 'Стартовая цена' ?></span>
                        <?php if (isset($lot['price']['rate_price'])): ?>
                        <span class="lot__cost"><?= isset($lot['price']['rate_price']) ? $lot['price']['rate_price'] : null ?><b class="rub">р</b></span>
                        <?php else : ?>
                        <span class="lot__cost"><?= isset($lot['start_price']) ? $lot['start_price'] : null ?><b class="rub">р</b></span>
                        <?php endif; ?>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=isset($lot['min_price']) ? $lot['min_price'] : null ?></span>
                    </div>
                </div>
                <?php if (isset($_SESSION['user']) && $lot['lost_time']!=='trade off' && $_SESSION['user']['id']!==$lot['user_create_id']) : ?>
                <form class="lot-item__form"  method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?= isset($errors) ? 'form__item--invalid' : '' ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="rate" placeholder="<?=isset($lot['min_price']) ? $lot['min_price'] : null ?>">
                        <?php if(isset($errors)) : ?>
                        <span class="form__error">Введите корректную сумму ставки</span>
                        <?php endif; ?>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
                <?php endif; ?>
            </div>
            <div class="history">
                <h3>История ставок (<span><?=isset($lot['rate_count']) && $lot['rate_count']!=='Стартовая цена' ? $lot['rate_count'] : 'Ставок нет' ?></span>)</h3>
                <table class="history__list">
                    <?php if(isset($lot['history'])) : ?>
                    <?php foreach ($lot['history'] as $history): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=isset($history['login']) ? $history['login'] : '' ?></td>
                        <td class="history__price"><?= isset($history['rate_price']) ? $history['rate_price'] : null ?> р</td>
                        <td class="history__time"><?=isset($history['time']) ? $history['time'] : null ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</section>
