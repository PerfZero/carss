<?php
/**
 * Theme setup.
 *
 * @package cars
 */

require_once get_template_directory() . "/inc/services.php";
require_once get_template_directory() . "/inc/team.php";
require_once get_template_directory() . "/inc/faq.php";
require_once get_template_directory() . "/inc/forms.php";
require_once get_template_directory() . "/inc/legal.php";

function cars_services_page_url()
{
    $archive_url = get_post_type_archive_link("service");

    return $archive_url ?: home_url("/services/");
}

function cars_blog_page_url()
{
    return home_url("/blog/");
}

function cars_reading_time($post_id = null)
{
    $post_id = $post_id ?: get_the_ID();
    $text = wp_strip_all_tags(get_post_field("post_content", $post_id));
    $words = str_word_count(
        $text,
        0,
        "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя",
    );
    $minutes = max(1, (int) ceil($words / 180));
    $last_digit = $minutes % 10;
    $last_two_digits = $minutes % 100;
    $label = "минут";

    if ($last_digit === 1 && $last_two_digits !== 11) {
        $label = "минута";
    } elseif (
        $last_digit >= 2 &&
        $last_digit <= 4 &&
        ($last_two_digits < 12 || $last_two_digits > 14)
    ) {
        $label = "минуты";
    }

    return number_format_i18n($minutes) . " " . $label;
}

add_action("after_setup_theme", static function () {
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");

    register_nav_menus([
        "primary" => __("Primary Menu", "cars"),
    ]);
});

add_action("init", static function () {
    add_rewrite_rule("^blog/?$", "index.php?cars_blog=1", "top");
    add_rewrite_rule(
        "^blog/page/([0-9]+)/?$",
        'index.php?cars_blog=1&paged=$matches[1]',
        "top",
    );
});

add_filter("query_vars", static function ($vars) {
    $vars[] = "cars_blog";

    return $vars;
});

add_filter("template_include", static function ($template) {
    if (get_query_var("cars_blog")) {
        return get_template_directory() . "/page-blog.php";
    }

    return $template;
});

add_filter("body_class", static function ($classes) {
    if (is_post_type_archive("service") || is_tax("service_category")) {
        $classes[] = "services-page-body";
    }

    if (is_singular("service")) {
        $classes[] = "service-single-body";
    }

    if (get_query_var("cars_blog") || is_singular("post")) {
        $classes[] = "blog-page-body";
    }

    return $classes;
});

add_action("wp_enqueue_scripts", static function () {
    wp_enqueue_style(
        "cars-main-style",
        get_template_directory_uri() . "/assets/css/main.css",
        [],
        filemtime(get_template_directory() . "/assets/css/main.css"),
    );

    wp_enqueue_script(
        "cars-zoom-scale",
        get_template_directory_uri() . "/assets/js/zoom-scale.js",
        [],
        filemtime(get_template_directory() . "/assets/js/zoom-scale.js"),
        true,
    );

    wp_enqueue_script(
        "cars-expert-reviews",
        get_template_directory_uri() . "/assets/js/expert-reviews.js",
        [],
        filemtime(get_template_directory() . "/assets/js/expert-reviews.js"),
        true,
    );
});
