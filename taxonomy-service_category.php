<?php
/**
 * Service category archive.
 *
 * @package cars
 */

get_header();
$term = get_queried_object();
?>
<main class="services-page">
    <div class="services-page__breadcrumbs" aria-label="Хлебные крошки">
        <a href="<?php echo esc_url(home_url("/")); ?>">Главная</a>
        <span>/</span>
        <a href="<?php echo esc_url(cars_services_page_url()); ?>">Услуги</a>
        <span>/</span>
        <span><?php echo esc_html($term->name ?? "Категория"); ?></span>
    </div>

    <?php get_template_part("template-parts/services", null, [
        "variant" => "page",
    ]); ?>
</main>
<?php get_footer(); ?>
