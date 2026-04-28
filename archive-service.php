<?php
/**
 * Services archive.
 *
 * @package cars
 */

get_header();
?>
<main class="services-page">
    <div class="services-page__breadcrumbs" aria-label="Хлебные крошки">
        <a href="<?php echo esc_url(home_url("/")); ?>">Главная</a>
        <span>/</span>
        <span>Услуги</span>
    </div>

    <?php get_template_part("template-parts/services", null, [
        "variant" => "page",
    ]); ?>

    <?php get_template_part("template-parts/contact-process"); ?>
</main>
<?php get_footer(); ?>
