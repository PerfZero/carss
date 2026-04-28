<?php
/**
 * Theme setup.
 *
 * @package cars
 */

require_once get_template_directory() . "/inc/services.php";
require_once get_template_directory() . "/inc/car-models.php";
require_once get_template_directory() . "/inc/team.php";
require_once get_template_directory() . "/inc/reviews.php";
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

function cars_nbsp_short_words($text)
{
    $text = (string) $text;

    if ($text === "") {
        return $text;
    }

    static $pattern = null;

    if ($pattern === null) {
        $words = [
            "а",
            "без",
            "в",
            "во",
            "для",
            "да",
            "до",
            "и",
            "из",
            "или",
            "к",
            "ко",
            "на",
            "над",
            "не",
            "ни",
            "но",
            "о",
            "об",
            "обо",
            "от",
            "по",
            "под",
            "при",
            "про",
            "с",
            "со",
            "у",
            "за",
        ];

        $pattern =
            "/(^|[\\s\\(\\[\\{\\\"«])(" .
            implode("|", array_map("preg_quote", $words)) .
            ")\\s+/ui";
    }

    return preg_replace_callback(
        $pattern,
        static function ($matches) {
            return $matches[1] . $matches[2] . "\u{00A0}";
        },
        $text,
    );
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

function cars_sitemap_lastmod(array $entries)
{
    $lastmod = "";

    foreach ($entries as $entry) {
        if (!empty($entry["lastmod"]) && $entry["lastmod"] > $lastmod) {
            $lastmod = $entry["lastmod"];
        }
    }

    return $lastmod ?: gmdate("c");
}

function cars_render_sitemap_urlset(array $entries)
{
    status_header(200);
    header("Content-Type: application/xml; charset=UTF-8");

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach ($entries as $entry) {
        echo "  <url>\n";
        echo "    <loc>" . esc_url($entry["loc"]) . "</loc>\n";

        if (!empty($entry["lastmod"])) {
            echo "    <lastmod>" . esc_html($entry["lastmod"]) . "</lastmod>\n";
        }

        echo "  </url>\n";
    }

    echo "</urlset>";
    exit;
}

function cars_render_sitemap_index(array $sitemaps)
{
    status_header(200);
    header("Content-Type: application/xml; charset=UTF-8");

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach ($sitemaps as $sitemap) {
        echo "  <sitemap>\n";
        echo "    <loc>" . esc_url($sitemap["loc"]) . "</loc>\n";

        if (!empty($sitemap["lastmod"])) {
            echo "    <lastmod>" .
                esc_html($sitemap["lastmod"]) .
                "</lastmod>\n";
        }

        echo "  </sitemap>\n";
    }

    echo "</sitemapindex>";
    exit;
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
    add_rewrite_rule(
        "^service-city-sitemap\\.xml$",
        "index.php?cars_service_city_sitemap=1",
        "top",
    );
    add_rewrite_rule(
        "^service-brand-sitemap\\.xml$",
        "index.php?cars_service_brand_sitemap=1",
        "top",
    );
    add_rewrite_rule(
        "^service-brand-model-sitemap\\.xml$",
        "index.php?cars_service_brand_model_sitemap=1",
        "top",
    );
    add_rewrite_rule(
        "^service-city-brand-sitemap\\.xml$",
        "index.php?cars_service_city_brand_sitemap=1",
        "top",
    );
    add_rewrite_rule(
        "^service-city-brand-model-sitemap\\.xml$",
        "index.php?cars_service_city_brand_model_sitemap=1",
        "top",
    );
    add_rewrite_rule(
        "^service-city-brand-sitemap-([0-9]+)\\.xml$",
        'index.php?cars_service_city_brand_sitemap_page=$matches[1]',
        "top",
    );
    add_rewrite_rule(
        "^service-city-brand-model-sitemap-([0-9]+)\\.xml$",
        'index.php?cars_service_city_brand_model_sitemap_page=$matches[1]',
        "top",
    );
    add_rewrite_rule(
        "^service/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$",
        'index.php?post_type=service&name=$matches[1]&service_city_slug=$matches[2]&service_brand_slug=$matches[3]&service_model_slug=$matches[4]',
        "top",
    );
    add_rewrite_rule(
        "^service/([^/]+)/([^/]+)/?$",
        'index.php?post_type=service&name=$matches[1]&service_variant_slug=$matches[2]',
        "top",
    );
    add_rewrite_rule(
        "^service/([^/]+)/([^/]+)/([^/]+)/?$",
        'index.php?post_type=service&name=$matches[1]&service_variant_slug=$matches[2]&service_model_slug=$matches[3]',
        "top",
    );
});

add_filter("query_vars", static function ($vars) {
    $vars[] = "cars_blog";
    $vars[] = "cars_service_city_sitemap";
    $vars[] = "cars_service_brand_sitemap";
    $vars[] = "cars_service_brand_model_sitemap";
    $vars[] = "cars_service_city_brand_sitemap";
    $vars[] = "cars_service_city_brand_model_sitemap";
    $vars[] = "cars_service_city_brand_sitemap_page";
    $vars[] = "cars_service_city_brand_model_sitemap_page";
    $vars[] = "service_variant_slug";
    $vars[] = "service_city_slug";
    $vars[] = "service_brand_slug";
    $vars[] = "service_model_slug";

    return $vars;
});

add_action("init", static function () {
    $version = "cars_rewrite_rules_v9";

    if (get_option($version)) {
        return;
    }

    flush_rewrite_rules(false);
    update_option($version, 1, false);
}, 99);

add_filter("template_include", static function ($template) {
    if (get_query_var("cars_blog")) {
        return get_template_directory() . "/page-blog.php";
    }

    if (is_singular("service")) {
        $context = cars_get_service_variant_context(get_queried_object_id());

        if ($context["is_variant"] && !$context["is_valid"]) {
            $not_found_template = get_404_template();

            return $not_found_template ?: $template;
        }
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

add_filter("redirect_canonical", static function ($redirect_url) {
    $request_path = parse_url($_SERVER["REQUEST_URI"] ?? "", PHP_URL_PATH);

    if (!is_string($request_path)) {
        return $redirect_url;
    }

    if (
        preg_match("#^/service-(city|brand|brand-model)-sitemap\\.xml/?$#", $request_path) ||
        preg_match("#^/service-city-brand-sitemap-[0-9]+\\.xml/?$#", $request_path) ||
        preg_match("#^/service-city-brand-model-sitemap-[0-9]+\\.xml/?$#", $request_path)
    ) {
        return false;
    }

    return $redirect_url;
});

add_action("template_redirect", static function () {
    $request_path = parse_url(
        $_SERVER["REQUEST_URI"] ?? "",
        PHP_URL_PATH,
    );

    if (
        get_query_var("cars_service_city_sitemap") ||
        $request_path === "/service-city-sitemap.xml"
    ) {
        cars_render_sitemap_urlset(cars_get_service_city_variant_entries());
    }

    if (
        get_query_var("cars_service_brand_sitemap") ||
        $request_path === "/service-brand-sitemap.xml"
    ) {
        cars_render_sitemap_urlset(cars_get_service_brand_variant_entries());
    }

    if (
        get_query_var("cars_service_brand_model_sitemap") ||
        $request_path === "/service-brand-model-sitemap.xml"
    ) {
        cars_render_sitemap_urlset(cars_get_service_brand_model_variant_entries());
    }

    $combo_page = (int) get_query_var("cars_service_city_brand_sitemap_page");

    if (
        $combo_page < 1 &&
        is_string($request_path) &&
        preg_match(
            "#^/service-city-brand-sitemap-([0-9]+)\\.xml/?$#",
            $request_path,
            $matches,
        )
    ) {
        $combo_page = (int) $matches[1];
    }

    if (
        get_query_var("cars_service_city_brand_sitemap") ||
        $combo_page > 0 ||
        $request_path === "/service-city-brand-sitemap.xml"
    ) {
        $entries = cars_get_service_city_brand_variant_entries();
        $chunk_size = 10000;

        if ($combo_page > 0) {
            $chunks = array_chunk($entries, $chunk_size);
            $chunk_index = $combo_page - 1;

            if (!isset($chunks[$chunk_index])) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                nocache_headers();
                return;
            }

            cars_render_sitemap_urlset($chunks[$chunk_index]);
        }

        $chunks = array_chunk($entries, $chunk_size);
        $sitemaps = [];

        foreach ($chunks as $index => $chunk_entries) {
            $sitemaps[] = [
                "loc" => home_url(
                    "/service-city-brand-sitemap-" . ($index + 1) . ".xml",
                ),
                "lastmod" => cars_sitemap_lastmod($chunk_entries),
            ];
        }

        cars_render_sitemap_index($sitemaps);
    }

    $city_brand_model_page = (int) get_query_var(
        "cars_service_city_brand_model_sitemap_page",
    );

    if (
        $city_brand_model_page < 1 &&
        is_string($request_path) &&
        preg_match(
            "#^/service-city-brand-model-sitemap-([0-9]+)\\.xml/?$#",
            $request_path,
            $matches,
        )
    ) {
        $city_brand_model_page = (int) $matches[1];
    }

    if (
        get_query_var("cars_service_city_brand_model_sitemap") ||
        $city_brand_model_page > 0 ||
        $request_path === "/service-city-brand-model-sitemap.xml"
    ) {
        $chunk_size = 10000;

        if ($city_brand_model_page > 0) {
            $entries = cars_get_service_city_brand_model_variant_entries_page(
                $city_brand_model_page,
                $chunk_size,
            );

            if (!$entries) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                nocache_headers();
                return;
            }

            cars_render_sitemap_urlset($entries);
        }

        cars_render_sitemap_index(
            cars_get_service_city_brand_model_sitemap_index_entries($chunk_size),
        );
    }

    if (!is_singular("service")) {
        return;
    }

    $context = cars_get_service_variant_context(get_queried_object_id());

    if (!$context["is_variant"] || $context["is_valid"]) {
        return;
    }

    global $wp_query;

    $wp_query->set_404();
    status_header(404);
    nocache_headers();
});

add_filter("pre_get_document_title", static function ($title) {
    if (!is_singular("service")) {
        return $title;
    }

    return cars_get_service_variant_title(get_queried_object_id());
});

add_filter("wpseo_title", static function ($title) {
    if (!is_singular("service")) {
        return $title;
    }

    $context = cars_get_service_variant_context(get_queried_object_id());

    if (!$context["is_variant"] || !$context["is_valid"]) {
        return $title;
    }

    return cars_get_service_variant_title(get_queried_object_id());
});

add_filter("wpseo_metadesc", static function ($description) {
    if (!is_singular("service")) {
        return $description;
    }

    $post_id = get_queried_object_id();
    $context = cars_get_service_variant_context($post_id);

    if (!$context["is_variant"] || !$context["is_valid"]) {
        return $description;
    }

    $base_description = "";

    if (function_exists("get_field")) {
        $base_description = (string) (get_field("lead", $post_id) ?: "");
    }

    if ($base_description === "") {
        $base_description = (string) get_the_excerpt($post_id);
    }

    if ($base_description === "") {
        $base_description = wp_strip_all_tags(
            (string) get_post_field("post_content", $post_id),
        );
    }

    return cars_get_service_variant_description($base_description, $post_id);
});

add_filter("wpseo_canonical", static function ($canonical) {
    if (!is_singular("service")) {
        return $canonical;
    }

    $post_id = get_queried_object_id();
    $context = cars_get_service_variant_context($post_id);

    if (!$context["is_variant"] || !$context["is_valid"]) {
        return $canonical;
    }

    if ($context["mode"] === "city" && $context["city"]) {
        return cars_get_service_variant_url(get_post($post_id), $context["city"]);
    }

    if ($context["mode"] === "brand" && $context["brand"]) {
        return cars_get_service_variant_url(get_post($post_id), null, $context["brand"]);
    }

    if (
        $context["mode"] === "brand_model" &&
        $context["brand"] &&
        $context["model"]
    ) {
        return cars_get_service_model_variant_url(
            get_post($post_id),
            $context["brand"],
            $context["model"],
        );
    }

    if (
        $context["mode"] === "city_brand" &&
        $context["city"] &&
        $context["brand"]
    ) {
        return cars_get_service_variant_url(
            get_post($post_id),
            $context["city"],
            $context["brand"],
        );
    }

    if (
        $context["mode"] === "city_brand_model" &&
        $context["city"] &&
        $context["brand"] &&
        $context["model"]
    ) {
        return cars_get_service_model_variant_url(
            get_post($post_id),
            $context["brand"],
            $context["model"],
            $context["city"],
        );
    }

    return $canonical;
});

add_filter("wpseo_opengraph_url", static function ($url) {
    if (!is_singular("service")) {
        return $url;
    }

    $post_id = get_queried_object_id();
    $context = cars_get_service_variant_context($post_id);

    if (!$context["is_variant"] || !$context["is_valid"]) {
        return $url;
    }

    if ($context["mode"] === "city" && $context["city"]) {
        return cars_get_service_variant_url(get_post($post_id), $context["city"]);
    }

    if ($context["mode"] === "brand" && $context["brand"]) {
        return cars_get_service_variant_url(get_post($post_id), null, $context["brand"]);
    }

    if (
        $context["mode"] === "brand_model" &&
        $context["brand"] &&
        $context["model"]
    ) {
        return cars_get_service_model_variant_url(
            get_post($post_id),
            $context["brand"],
            $context["model"],
        );
    }

    if (
        $context["mode"] === "city_brand" &&
        $context["city"] &&
        $context["brand"]
    ) {
        return cars_get_service_variant_url(
            get_post($post_id),
            $context["city"],
            $context["brand"],
        );
    }

    if (
        $context["mode"] === "city_brand_model" &&
        $context["city"] &&
        $context["brand"] &&
        $context["model"]
    ) {
        return cars_get_service_model_variant_url(
            get_post($post_id),
            $context["brand"],
            $context["model"],
            $context["city"],
        );
    }

    return $url;
});

add_filter("wpseo_sitemap_index", static function ($sitemap_index) {
    $append_variant_sitemap = static function ($xml, $has_entries, $path, $lastmod = "") {
        if (!$has_entries) {
            return $xml;
        }

        if ($lastmod === "") {
            $lastmod = cars_get_service_latest_lastmod();
        }

        return $xml .
            sprintf(
                "<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>\n",
                esc_url(home_url($path)),
                esc_html($lastmod),
            );
    };

    $sitemap_index = $append_variant_sitemap(
        $sitemap_index,
        cars_get_service_city_variant_total() > 0,
        "/service-city-sitemap.xml",
    );

    $sitemap_index = $append_variant_sitemap(
        $sitemap_index,
        cars_get_service_brand_variant_total() > 0,
        "/service-brand-sitemap.xml",
    );

    $sitemap_index = $append_variant_sitemap(
        $sitemap_index,
        cars_get_service_brand_model_variant_total() > 0,
        "/service-brand-model-sitemap.xml",
    );

    if (cars_get_service_city_brand_model_variant_total() > 0) {
        $sitemap_index .= sprintf(
            "<sitemap><loc>%s</loc><lastmod>%s</lastmod></sitemap>\n",
            esc_url(home_url("/service-city-brand-model-sitemap.xml")),
            esc_html(cars_get_service_latest_lastmod()),
        );
    }

    return $append_variant_sitemap(
        $sitemap_index,
        cars_get_service_city_brand_variant_total() > 0,
        "/service-city-brand-sitemap.xml",
    );
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
