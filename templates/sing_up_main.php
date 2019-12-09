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
<?php var_dump($errors) ?>
<form class="form container <?= isset($errors) ? 'form--invalid' : '' ?>" method="post" autocomplete="off" enctype="multipart/form-data"> <!-- form
    --invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?> "> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" value="<?= getPostVal('email'); ?>" placeholder="Введите e-mail">
        <?php if(isset($errors)) : ?>
        <span class="form__error">Введите email</span>
        <?php endif; ?>
    </div>
    <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?> ">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" value="<?= getPostVal('password'); ?>" placeholder="Введите пароль">
        <?php if(isset($errors)) : ?>
        <span class="form__error">Введите пароль</span>
        <?php endif; ?>
    </div>
    <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" value="<?= getPostVal('name'); ?>" placeholder="Введите имя">
        <?php if(isset($errors)) : ?>
        <span class="form__error">Введите логин</span>
        <?php endif; ?>
    </div>
    <div class="form__item <?= isset($errors['contacts']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться"><?= getPostVal('contacts'); ?></textarea>
        <?php if(isset($errors)) : ?>
        <span class="form__error">Введите контактные данные</span>
        <?php endif; ?>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>
