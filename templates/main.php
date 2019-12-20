<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php if (isset($lists_of_cat)) : ?>
        <?php foreach ($lists_of_cat as $category): ?>
        <li class="promo__item promo__item--<?=isset($category['symb_code']) ? $category['symb_code'] : '' ?>">
            <a class="promo__link" href="/all_lots.php?content_id=<?= isset($category['id']) ? $category['id'] : null ?>"><?= isset($category['cat_name']) ? $category['cat_name'] : ''?></a>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php if (isset($lots_view)) : ?>
        <?php foreach ($lots_view as $lot): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=isset($lot['img']) ? $lot['img'] : null ?>" width="350" height="260" alt="">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= isset($lot['cat_name']) ? $lot['cat_name'] : null ?></span>
                <h3 class="lot__title"><a class="text-link" href="/lot.php?lot_id=<?= isset($lot['id']) ? $lot['id'] : null ?>"><?=isset($lot['title']) ? $lot['title'] : '' ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount"><?=isset($lot['rate_count']) ? $lot['rate_count'] : 'Стартовая цена' ?></span>
                        <?php if (isset($lot['price']['rate_price'])): ?>
                        <span class="lot__cost"><?= isset($lot['price']['rate_price']) ? $lot['price']['rate_price'] : null ?><b class="rub">р</b></span>
                        <?php else : ?>
                        <span class="lot__cost"><?= isset($lot['start_price']) ? $lot['start_price'] : null ?><b class="rub">р</b></span>
                        <?php endif; ?>
                    </div>
                    <div class="lot__timer timer <?= isset($lot['timer']) ? $lot['timer'] : '' ?>">
                        <?= isset($lot['lost_time']) ? $lot['lost_time'] : null  ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>
