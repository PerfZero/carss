<?php
/**
 * Theme header.
 *
 * @package cars
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo("charset"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="site-header__inner">
        <?php
        $services_url = esc_url(cars_services_page_url());
        $reviews_url = esc_url(home_url("/#reviews"));
        $contacts_url = esc_url(home_url("/#contacts"));
        ?>
        <a class="site-header__logo" href="<?php echo esc_url(home_url("/")); ?>">
            <?php echo esc_html(get_bloginfo("name") ?: "Cars"); ?>
        </a>

        <a class="site-header__phone site-header__phone--mobile" href="tel:+79169496622">
            <span class="site-header__phone-dot" aria-hidden="true"></span>
            <span>+7 (916) 949-66-22</span>
        </a>

        <button
          class="site-header__toggle"
          type="button"
          aria-expanded="false"
          aria-controls="site-header-panel"
          aria-label="Открыть меню"
          data-header-toggle
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="site-header__panel" id="site-header-panel" data-header-panel>
            <nav class="site-header__nav" aria-label="<?php esc_attr_e("Primary menu", "cars"); ?>">
                <ul class="site-header__menu">
                    <li><a href="<?php echo $services_url; ?>">Услуги</a></li>
                    <li><a href="<?php echo $reviews_url; ?>">Отзывы</a></li>
                    <li><a href="<?php echo $contacts_url; ?>">Контакты</a></li>
                </ul>
            </nav>

            <div class="site-header__actions">
                <a class="site-header__phone" href="tel:+79169496622">
                    <span class="site-header__phone-dot" aria-hidden="true"></span>
                    <span>+7 (916) 949-66-22</span>
                </a>
                <a class="site-header__messenger" href="#contacts" data-open-modal="contact">
                    Написать в мессенджер
                </a>
            </div>
        </div>
    </div>
</header>
