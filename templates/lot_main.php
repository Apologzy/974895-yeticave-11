<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lists_of_cat as $category): ?>
            <li class="nav__item <?= $content_id == $category['id'] ? $active_cat : '' ?>">
                <a href="/all_lots.php?content_id=<?= $category['id']; ?>"><?= $category['cat_name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>

</nav>
<section class="lot-item container">
    <?php foreach ($lots_view as $lot): ?>
    <h2><?= $lot['title']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= $lot['img']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['cat_name']; ?></span></p>
            <p class="lot-item__description"><?= $lot['content']; ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer <?=$lot['timer'] ?>">
                    <?= $lot['lost_time']; ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount"><?=isset($lot['rate_count']) ? $lot['rate_count'] : 'Стартовая цена' ?></span>

                        <span class="lot-item__cost"><?= $lot['price']['rate_price'] ? $lot['price']['rate_price'] : $lot['start_price'] ?></span>

                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=$lot['min_price']; ?></span>
                    </div>
                </div>
                <?php if (isset($_SESSION['user']) && $lot['lost_time']!=='trade off' && $_SESSION['user']['id']!==$lot['user_create_id']) : ?>
                <form class="lot-item__form"  method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?= isset($errors) ? 'form__item--invalid' : '' ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="rate" placeholder="<?=$lot['min_price']; ?>">
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
                    <?php foreach ($lot['history'] as $history): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=$history['login'] ?></td>
                        <td class="history__price"><?=$history['rate_price'] ?> р</td>
                        <td class="history__time"><?=$history['time'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</section>
