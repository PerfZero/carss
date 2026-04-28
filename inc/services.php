<?php
/**
 * Service post type and fields.
 *
 * @package cars
 */

function cars_register_service_taxonomy($taxonomy, $plural_label, $singular_label, $slug)
{
    register_taxonomy($taxonomy, ["service"], [
        "labels" => [
            "name" => $plural_label,
            "singular_name" => $singular_label,
            "search_items" => "Искать {$plural_label}",
            "all_items" => "Все {$plural_label}",
            "edit_item" => "Редактировать {$singular_label}",
            "view_item" => "Смотреть {$singular_label}",
            "update_item" => "Обновить {$singular_label}",
            "add_new_item" => "Добавить {$singular_label}",
            "new_item_name" => "Название {$singular_label}",
            "menu_name" => $plural_label,
        ],
        "public" => true,
        "show_ui" => true,
        "show_admin_column" => true,
        "show_in_rest" => true,
        "hierarchical" => false,
        "rewrite" => [
            "slug" => $slug,
            "with_front" => false,
        ],
    ]);
}

function cars_read_seed_csv_column($path)
{
    if (!is_readable($path)) {
        return [];
    }

    $handle = fopen($path, "r");

    if (!$handle) {
        return [];
    }

    $items = [];
    $is_header = true;

    while (($row = fgetcsv($handle)) !== false) {
        if (empty($row)) {
            continue;
        }

        $value = trim((string) $row[0]);

        if ($value === "") {
            continue;
        }

        if ($is_header) {
            $is_header = false;
            continue;
        }

        $items[] = $value;
    }

    fclose($handle);

    return $items;
}

function cars_build_service_term_slug($name)
{
    $normalized = mb_strtolower(
        wp_strip_all_tags((string) $name),
        "UTF-8",
    );

    $transliterated = strtr($normalized, [
        "а" => "a",
        "б" => "b",
        "в" => "v",
        "г" => "g",
        "д" => "d",
        "е" => "e",
        "ё" => "e",
        "ж" => "zh",
        "з" => "z",
        "и" => "i",
        "й" => "y",
        "к" => "k",
        "л" => "l",
        "м" => "m",
        "н" => "n",
        "о" => "o",
        "п" => "p",
        "р" => "r",
        "с" => "s",
        "т" => "t",
        "у" => "u",
        "ф" => "f",
        "х" => "kh",
        "ц" => "ts",
        "ч" => "ch",
        "ш" => "sh",
        "щ" => "shch",
        "ъ" => "",
        "ы" => "y",
        "ь" => "",
        "э" => "e",
        "ю" => "yu",
        "я" => "ya",
    ]);

    return sanitize_title($transliterated);
}

function cars_seed_service_terms($taxonomy, array $items, $option_name)
{
    if (!$items || get_option($option_name)) {
        return;
    }

    $seen = [];

    foreach ($items as $item) {
        $name = trim(wp_strip_all_tags((string) $item));

        if ($name === "") {
            continue;
        }

        $normalized = mb_strtolower($name);

        if (isset($seen[$normalized])) {
            continue;
        }

        $seen[$normalized] = true;

        $slug = cars_build_service_term_slug($name);

        if ($slug !== "" && get_term_by("slug", $slug, $taxonomy)) {
            continue;
        }

        if (term_exists($name, $taxonomy)) {
            continue;
        }

        wp_insert_term($name, $taxonomy, [
            "slug" => $slug,
        ]);
    }

    update_option($option_name, 1, false);
}

function cars_repair_service_term_slugs(array $taxonomies, $option_name)
{
    if (get_option($option_name)) {
        return;
    }

    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms([
            "taxonomy" => $taxonomy,
            "hide_empty" => false,
        ]);

        if (is_wp_error($terms) || !$terms) {
            continue;
        }

        foreach ($terms as $term) {
            $expected_slug = cars_build_service_term_slug($term->name);

            if ($expected_slug === "" || $term->slug === $expected_slug) {
                continue;
            }

            $existing = get_term_by("slug", $expected_slug, $taxonomy);

            if ($existing && (int) $existing->term_id !== (int) $term->term_id) {
                continue;
            }

            wp_update_term($term->term_id, $taxonomy, [
                "slug" => $expected_slug,
            ]);
        }
    }

    update_option($option_name, 1, false);
}

function cars_get_service_variant_context($post_id = null)
{
    $post_id = (int) ($post_id ?: get_queried_object_id());
    $variant_slug = sanitize_title((string) get_query_var("service_variant_slug"));
    $city_slug = sanitize_title((string) get_query_var("service_city_slug"));
    $brand_slug = sanitize_title((string) get_query_var("service_brand_slug"));
    $model_slug = sanitize_title((string) get_query_var("service_model_slug"));

    static $cache = [];
    $cache_key = implode("|", [$post_id, $variant_slug, $city_slug, $brand_slug, $model_slug]);

    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }

    $context = [
        "mode" => "base",
        "is_variant" => false,
        "is_valid" => true,
        "city" => null,
        "brand" => null,
        "model" => null,
    ];

    if (!$post_id || get_post_type($post_id) !== "service") {
        $cache[$cache_key] = $context;

        return $context;
    }

    $resolve_term = static function ($slug, $taxonomy) use ($post_id) {
        if ($slug === "") {
            return null;
        }

        $term = get_term_by("slug", $slug, $taxonomy);

        if (!$term) {
            return null;
        }

        if (!cars_service_supports_term($post_id, $taxonomy, (int) $term->term_id)) {
            return null;
        }

        return $term;
    };

    if ($variant_slug !== "") {
        $context["is_variant"] = true;
        $city = $resolve_term($variant_slug, "service_city");
        $brand = $resolve_term($variant_slug, "service_brand");

        if ($model_slug !== "") {
            $brand_from_second_slug = $resolve_term($model_slug, "service_brand");

            if ($city && $brand_from_second_slug) {
                $context["mode"] = "city_brand";
                $context["city"] = $city;
                $context["brand"] = $brand_from_second_slug;
            } elseif ($brand) {
                $model = cars_get_car_model_by_brand_and_slug(
                    (int) $brand->term_id,
                    $model_slug,
                );

                if ($model) {
                    $context["mode"] = "brand_model";
                    $context["brand"] = $brand;
                    $context["model"] = $model;
                } else {
                    $context["is_valid"] = false;
                }
            } else {
                $context["is_valid"] = false;
            }
        } elseif ($city && !$brand) {
            $context["mode"] = "city";
            $context["city"] = $city;
        } elseif ($brand && !$city) {
            $context["mode"] = "brand";
            $context["brand"] = $brand;
        } else {
            $context["is_valid"] = false;
        }

        $cache[$cache_key] = $context;

        return $context;
    }

    if ($city_slug !== "" || $brand_slug !== "" || $model_slug !== "") {
        $context["is_variant"] = true;
        $context["city"] = $resolve_term($city_slug, "service_city");
        $context["brand"] = $resolve_term($brand_slug, "service_brand");

        if (
            $city_slug !== "" &&
            $brand_slug !== "" &&
            $model_slug !== "" &&
            $context["city"] &&
            $context["brand"]
        ) {
            $model = cars_get_car_model_by_brand_and_slug(
                (int) $context["brand"]->term_id,
                $model_slug,
            );

            if ($model) {
                $context["mode"] = "city_brand_model";
                $context["model"] = $model;
            } else {
                $context["is_valid"] = false;
            }
        } elseif (
            $city_slug !== "" &&
            $brand_slug !== "" &&
            $context["city"] &&
            $context["brand"]
        ) {
            $context["mode"] = "city_brand";
        } else {
            $context["is_valid"] = false;
        }
    }

    $cache[$cache_key] = $context;

    return $context;
}

function cars_service_matches_all_terms($post_id, $taxonomy)
{
    if (!$post_id) {
        return false;
    }

    $field_name = $taxonomy === "service_city"
        ? "all_cities_enabled"
        : "all_brands_enabled";

    if (function_exists("get_field")) {
        return (bool) get_field($field_name, $post_id);
    }

    return (bool) get_post_meta($post_id, $field_name, true);
}

function cars_service_supports_term($post_id, $taxonomy, $term_id)
{
    if (!$post_id || !$term_id) {
        return false;
    }

    if (cars_service_matches_all_terms($post_id, $taxonomy)) {
        return true;
    }

    return has_term((int) $term_id, $taxonomy, $post_id);
}

function cars_get_service_supported_terms($post_id, $taxonomy)
{
    static $all_terms_cache = [];

    if (!$post_id) {
        return [];
    }

    if (cars_service_matches_all_terms($post_id, $taxonomy)) {
        if (!isset($all_terms_cache[$taxonomy])) {
            $all_terms_cache[$taxonomy] = get_terms([
                "taxonomy" => $taxonomy,
                "hide_empty" => false,
                "orderby" => "name",
                "order" => "ASC",
            ]);
        }

        $terms = $all_terms_cache[$taxonomy];

        return is_wp_error($terms) ? [] : $terms;
    }

    $terms = get_the_terms($post_id, $taxonomy);

    if (is_wp_error($terms) || !$terms) {
        return [];
    }

    return array_values($terms);
}

function cars_service_is_primary($service)
{
    $service_id = is_object($service) ? (int) $service->ID : (int) $service;

    if (!$service_id) {
        return false;
    }

    return !has_term("additional-services", "service_category", $service_id);
}

function cars_get_service_variant_url($service, $city_term = null, $brand_term = null)
{
    $service_post = get_post($service);

    if (!$service_post || $service_post->post_type !== "service") {
        return "";
    }

    $path = "service/" . $service_post->post_name . "/";

    if ($city_term && !empty($city_term->slug)) {
        $path .= $city_term->slug . "/";
    }

    if ($brand_term && !empty($brand_term->slug)) {
        $path .= $brand_term->slug . "/";
    }

    return home_url(user_trailingslashit($path));
}

function cars_get_service_model_variant_url($service, $brand_term, $model, $city_term = null)
{
    $model_slug = cars_get_car_model_variant_slug($model);

    if ($model_slug === "") {
        return "";
    }

    $service_post = get_post($service);

    if (!$service_post || $service_post->post_type !== "service") {
        return "";
    }

    $path = "service/" . $service_post->post_name . "/";

    if ($city_term && !empty($city_term->slug)) {
        $path .= $city_term->slug . "/";
    }

    if ($brand_term && !empty($brand_term->slug)) {
        $path .= $brand_term->slug . "/";
    }

    $path .= $model_slug . "/";

    return home_url(user_trailingslashit($path));
}

function cars_get_service_city_variant_entries()
{
    $entries = [];
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    foreach ($services as $service) {
        if (!cars_service_is_primary($service)) {
            continue;
        }

        $cities = cars_get_service_supported_terms($service->ID, "service_city");

        if (!$cities) {
            continue;
        }

        $lastmod = get_post_modified_time("c", true, $service);

        foreach ($cities as $city) {
            $url = cars_get_service_variant_url($service, $city);

            if ($url === "") {
                continue;
            }

            $entries[] = [
                "loc" => $url,
                "lastmod" => $lastmod,
            ];
        }
    }

    return $entries;
}

function cars_get_service_city_variant_total()
{
    static $total = null;

    if ($total !== null) {
        return $total;
    }

    $total = 0;
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
        "fields" => "ids",
    ]);

    foreach ($services as $service_id) {
        if (!cars_service_is_primary((int) $service_id)) {
            continue;
        }

        $total += count(
            cars_get_service_supported_terms((int) $service_id, "service_city"),
        );
    }

    return $total;
}

function cars_get_service_brand_variant_entries()
{
    $entries = [];
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    foreach ($services as $service) {
        if (!cars_service_is_primary($service)) {
            continue;
        }

        $brands = cars_get_service_supported_terms($service->ID, "service_brand");

        if (!$brands) {
            continue;
        }

        $lastmod = get_post_modified_time("c", true, $service);

        foreach ($brands as $brand) {
            $url = cars_get_service_variant_url($service, null, $brand);

            if ($url === "") {
                continue;
            }

            $entries[] = [
                "loc" => $url,
                "lastmod" => $lastmod,
            ];
        }
    }

    return $entries;
}

function cars_get_service_brand_variant_total()
{
    static $total = null;

    if ($total !== null) {
        return $total;
    }

    $total = 0;
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
        "fields" => "ids",
    ]);

    foreach ($services as $service_id) {
        if (!cars_service_is_primary((int) $service_id)) {
            continue;
        }

        $total += count(
            cars_get_service_supported_terms((int) $service_id, "service_brand"),
        );
    }

    return $total;
}

function cars_get_service_city_brand_variant_entries()
{
    $entries = [];
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    foreach ($services as $service) {
        if (!cars_service_is_primary($service)) {
            continue;
        }

        $cities = cars_get_service_supported_terms($service->ID, "service_city");
        $brands = cars_get_service_supported_terms($service->ID, "service_brand");

        if (!$cities || !$brands) {
            continue;
        }

        $lastmod = get_post_modified_time("c", true, $service);

        foreach ($cities as $city) {
            foreach ($brands as $brand) {
                $url = cars_get_service_variant_url($service, $city, $brand);

                if ($url === "") {
                    continue;
                }

                $entries[] = [
                    "loc" => $url,
                    "lastmod" => $lastmod,
                ];
            }
        }
    }

    return $entries;
}

function cars_get_service_city_brand_variant_total()
{
    static $total = null;

    if ($total !== null) {
        return $total;
    }

    $total = 0;
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
        "fields" => "ids",
    ]);

    foreach ($services as $service_id) {
        if (!cars_service_is_primary((int) $service_id)) {
            continue;
        }

        $cities = cars_get_service_supported_terms((int) $service_id, "service_city");
        $brands = cars_get_service_supported_terms((int) $service_id, "service_brand");

        if (!$cities || !$brands) {
            continue;
        }

        $total += count($cities) * count($brands);
    }

    return $total;
}

function cars_get_service_brand_model_variant_entries()
{
    $entries = [];
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    foreach ($services as $service) {
        if (!cars_service_is_primary($service)) {
            continue;
        }

        $brands = cars_get_service_supported_terms($service->ID, "service_brand");

        if (!$brands) {
            continue;
        }

        $lastmod = get_post_modified_time("c", true, $service);

        foreach ($brands as $brand) {
            $models = cars_get_car_models_for_brand_term((int) $brand->term_id);

            if (!$models) {
                continue;
            }

            foreach ($models as $model) {
                $url = cars_get_service_model_variant_url($service, $brand, $model);

                if ($url === "") {
                    continue;
                }

                $entries[] = [
                    "loc" => $url,
                    "lastmod" => $lastmod,
                ];
            }
        }
    }

    return $entries;
}

function cars_get_service_brand_model_variant_total()
{
    static $total = null;

    if ($total !== null) {
        return $total;
    }

    $total = 0;
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
        "fields" => "ids",
    ]);

    foreach ($services as $service_id) {
        if (!cars_service_is_primary((int) $service_id)) {
            continue;
        }

        $brands = cars_get_service_supported_terms((int) $service_id, "service_brand");

        if (!$brands) {
            continue;
        }

        foreach ($brands as $brand) {
            $total += count(cars_get_car_models_for_brand_term((int) $brand->term_id));
        }
    }

    return $total;
}

function cars_get_service_latest_lastmod()
{
    static $lastmod = null;

    if ($lastmod !== null) {
        return $lastmod;
    }

    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => 1,
        "orderby" => "modified",
        "order" => "DESC",
        "fields" => "ids",
    ]);

    if (!$services) {
        $lastmod = gmdate("c");
        return $lastmod;
    }

    $lastmod = get_post_modified_time("c", true, (int) $services[0]) ?: gmdate("c");

    return $lastmod;
}

function cars_get_service_city_brand_model_variant_total()
{
    static $total = null;

    if ($total !== null) {
        return $total;
    }

    $total = 0;
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    foreach ($services as $service) {
        if (!cars_service_is_primary($service)) {
            continue;
        }

        $cities = cars_get_service_supported_terms($service->ID, "service_city");
        $brands = cars_get_service_supported_terms($service->ID, "service_brand");

        if (!$cities || !$brands) {
            continue;
        }

        foreach ($brands as $brand) {
            $models = cars_get_car_models_for_brand_term((int) $brand->term_id);

            if (!$models) {
                continue;
            }

            $total += count($cities) * count($models);
        }
    }

    return $total;
}

function cars_get_service_city_brand_model_sitemap_index_entries($chunk_size = 10000)
{
    $chunk_size = max(1, (int) $chunk_size);
    $total = cars_get_service_city_brand_model_variant_total();

    if ($total < 1) {
        return [];
    }

    $chunks_count = (int) ceil($total / $chunk_size);
    $lastmod = cars_get_service_latest_lastmod();
    $entries = [];

    for ($index = 1; $index <= $chunks_count; $index++) {
        $entries[] = [
            "loc" => home_url("/service-city-brand-model-sitemap-" . $index . ".xml"),
            "lastmod" => $lastmod,
        ];
    }

    return $entries;
}

function cars_get_service_city_brand_model_variant_entries_page($page, $chunk_size = 10000)
{
    $page = (int) $page;
    $chunk_size = max(1, (int) $chunk_size);

    if ($page < 1) {
        return [];
    }

    $offset = ($page - 1) * $chunk_size;
    $limit = $chunk_size;
    $cursor = 0;
    $entries = [];
    $services = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    foreach ($services as $service) {
        if (!cars_service_is_primary($service)) {
            continue;
        }

        $cities = cars_get_service_supported_terms($service->ID, "service_city");
        $brands = cars_get_service_supported_terms($service->ID, "service_brand");

        if (!$cities || !$brands) {
            continue;
        }

        $lastmod = get_post_modified_time("c", true, $service);

        foreach ($brands as $brand) {
            $models = cars_get_car_models_for_brand_term((int) $brand->term_id);

            if (!$models) {
                continue;
            }

            foreach ($models as $model) {
                $block_size = count($cities);

                if ($cursor + $block_size <= $offset) {
                    $cursor += $block_size;
                    continue;
                }

                foreach ($cities as $city) {
                    if ($cursor < $offset) {
                        $cursor++;
                        continue;
                    }

                    if (count($entries) >= $limit) {
                        return $entries;
                    }

                    $url = cars_get_service_model_variant_url(
                        $service,
                        $brand,
                        $model,
                        $city,
                    );

                    if ($url !== "") {
                        $entries[] = [
                            "loc" => $url,
                            "lastmod" => $lastmod,
                        ];
                    }

                    $cursor++;
                }
            }
        }
    }

    return $entries;
}

function cars_get_service_city_override($post_id, $city_term_id)
{
    if (
        !$post_id ||
        !$city_term_id ||
        !function_exists("get_field")
    ) {
        return null;
    }

    $rows = get_field("city_overrides", $post_id);

    if (!is_array($rows) || !$rows) {
        return null;
    }

    foreach ($rows as $row) {
        $row_city = $row["city"] ?? null;
        $row_city_id = 0;

        if (is_object($row_city) && !empty($row_city->term_id)) {
            $row_city_id = (int) $row_city->term_id;
        } elseif (is_array($row_city) && !empty($row_city["term_id"])) {
            $row_city_id = (int) $row_city["term_id"];
        } elseif (is_numeric($row_city)) {
            $row_city_id = (int) $row_city;
        }

        if ($row_city_id !== (int) $city_term_id) {
            continue;
        }

        return [
            "title" => trim((string) ($row["title"] ?? "")),
            "description" => trim((string) ($row["description"] ?? "")),
        ];
    }

    return null;
}

function cars_get_service_variant_title($post_id = null)
{
    $post_id = (int) ($post_id ?: get_the_ID());
    $base_title = get_the_title($post_id);
    $context = cars_get_service_variant_context($post_id);

    if (!$context["is_variant"] || !$context["is_valid"]) {
        return $base_title;
    }

    if ($context["mode"] === "city" && $context["city"]) {
        $override = cars_get_service_city_override(
            $post_id,
            (int) $context["city"]->term_id,
        );

        if ($override && $override["title"] !== "") {
            return $override["title"];
        }

        return sprintf("%s в городе %s", $base_title, $context["city"]->name);
    }

    if ($context["mode"] === "brand" && $context["brand"]) {
        return sprintf("%s для %s", $base_title, $context["brand"]->name);
    }

    if (
        $context["mode"] === "brand_model" &&
        $context["brand"] &&
        $context["model"]
    ) {
        return sprintf(
            "%s для %s %s",
            $base_title,
            $context["brand"]->name,
            get_the_title($context["model"]),
        );
    }

    if (
        $context["mode"] === "city_brand" &&
        $context["city"] &&
        $context["brand"]
    ) {
        return sprintf(
            "%s для %s в городе %s",
            $base_title,
            $context["brand"]->name,
            $context["city"]->name,
        );
    }

    if (
        $context["mode"] === "city_brand_model" &&
        $context["city"] &&
        $context["brand"] &&
        $context["model"]
    ) {
        return sprintf(
            "%s для %s %s в городе %s",
            $base_title,
            $context["brand"]->name,
            get_the_title($context["model"]),
            $context["city"]->name,
        );
    }

    return $base_title;
}

function cars_get_service_variant_description($description, $post_id = null)
{
    $context = cars_get_service_variant_context($post_id);
    $description = trim((string) $description);

    if (!$context["is_variant"] || !$context["is_valid"]) {
        return $description;
    }

    if ($context["mode"] === "city" && $context["city"]) {
        $override = cars_get_service_city_override(
            $post_id,
            (int) $context["city"]->term_id,
        );

        if ($override && $override["description"] !== "") {
            return $override["description"];
        }

        if ($description === "") {
            return $description;
        }

        return sprintf(
            "%s Работаем по этой услуге в городе %s.",
            $description,
            $context["city"]->name,
        );
    }

    if ($context["mode"] === "brand" && $context["brand"]) {
        if ($description === "") {
            return $description;
        }

        return sprintf(
            "%s Помогаем с оформлением для автомобилей %s.",
            $description,
            $context["brand"]->name,
        );
    }

    if (
        $context["mode"] === "brand_model" &&
        $context["brand"] &&
        $context["model"]
    ) {
        if ($description === "") {
            return $description;
        }

        return sprintf(
            "%s Помогаем с оформлением для автомобилей %s %s.",
            $description,
            $context["brand"]->name,
            get_the_title($context["model"]),
        );
    }

    if (
        $context["mode"] === "city_brand" &&
        $context["city"] &&
        $context["brand"]
    ) {
        if ($description === "") {
            return $description;
        }

        return sprintf(
            "%s Помогаем с оформлением для автомобилей %s в городе %s.",
            $description,
            $context["brand"]->name,
            $context["city"]->name,
        );
    }

    if (
        $context["mode"] === "city_brand_model" &&
        $context["city"] &&
        $context["brand"] &&
        $context["model"]
    ) {
        if ($description === "") {
            return $description;
        }

        return sprintf(
            "%s Помогаем с оформлением для автомобилей %s %s в городе %s.",
            $description,
            $context["brand"]->name,
            get_the_title($context["model"]),
            $context["city"]->name,
        );
    }

    return $description;
}

function cars_seed_demo_service_variants()
{
    if (get_option("cars_seeded_service_variants_demo_v1")) {
        return;
    }

    $service = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "numberposts" => 1,
        "orderby" => "menu_order title",
        "order" => "ASC",
    ]);

    if (!$service) {
        return;
    }

    $service_id = (int) $service[0]->ID;
    $moscow = get_term_by("slug", "moskva", "service_city");
    $bmw = get_term_by("slug", "bmw", "service_brand");

    if ($moscow && !has_term((int) $moscow->term_id, "service_city", $service_id)) {
        wp_set_post_terms(
            $service_id,
            [(int) $moscow->term_id],
            "service_city",
            true,
        );
    }

    if ($bmw && !has_term((int) $bmw->term_id, "service_brand", $service_id)) {
        wp_set_post_terms(
            $service_id,
            [(int) $bmw->term_id],
            "service_brand",
            true,
        );
    }

    update_option("cars_seeded_service_variants_demo_v1", $service_id, false);
}

function cars_enable_all_cities_for_demo_service()
{
    if (get_option("cars_enabled_all_cities_for_sbkts_v1")) {
        return;
    }

    $service = get_page_by_path("oformlenie-sbkts", OBJECT, "service");

    if (!$service) {
        return;
    }

    update_post_meta($service->ID, "all_cities_enabled", 1);
    update_option("cars_enabled_all_cities_for_sbkts_v1", 1, false);
}

function cars_enable_all_brands_for_demo_service()
{
    if (get_option("cars_enabled_all_brands_for_sbkts_v1")) {
        return;
    }

    $service = get_page_by_path("oformlenie-sbkts", OBJECT, "service");

    if (!$service) {
        return;
    }

    update_post_meta($service->ID, "all_brands_enabled", 1);
    update_option("cars_enabled_all_brands_for_sbkts_v1", 1, false);
}

add_action("init", static function () {
    register_post_type("service", [
        "labels" => [
            "name" => "Услуги",
            "singular_name" => "Услуга",
            "add_new" => "Добавить услугу",
            "add_new_item" => "Добавить услугу",
            "edit_item" => "Редактировать услугу",
            "new_item" => "Новая услуга",
            "view_item" => "Смотреть услугу",
            "search_items" => "Искать услуги",
            "not_found" => "Услуги не найдены",
            "not_found_in_trash" => "В корзине услуг нет",
            "menu_name" => "Услуги",
        ],
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_rest" => true,
        "menu_icon" => "dashicons-clipboard",
        "has_archive" => "services",
        "rewrite" => [
            "slug" => "service",
            "with_front" => false,
        ],
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "thumbnail",
            "page-attributes",
        ],
    ]);

    register_taxonomy("service_category", ["service"], [
        "labels" => [
            "name" => "Категории услуг",
            "singular_name" => "Категория услуги",
            "add_new_item" => "Добавить категорию",
            "edit_item" => "Редактировать категорию",
            "menu_name" => "Категории услуг",
        ],
        "public" => true,
        "show_ui" => true,
        "show_admin_column" => true,
        "show_in_rest" => true,
        "hierarchical" => true,
        "rewrite" => [
            "slug" => "service-category",
            "with_front" => false,
        ],
    ]);

    cars_register_service_taxonomy(
        "service_city",
        "Города",
        "Город",
        "service-city",
    );

    cars_register_service_taxonomy(
        "service_brand",
        "Марки авто",
        "Марка авто",
        "service-brand",
    );
});

add_action("init", static function () {
    cars_seed_service_terms(
        "service_city",
        cars_read_seed_csv_column(
            get_template_directory() . "/data/service-cities.csv",
        ),
        "cars_seeded_service_cities_v1",
    );

    cars_seed_service_terms(
        "service_brand",
        cars_read_seed_csv_column(
            get_template_directory() . "/data/service-brands.csv",
        ),
        "cars_seeded_service_brands_v2",
    );

    cars_repair_service_term_slugs(
        ["service_city", "service_brand"],
        "cars_fixed_service_term_slugs_v2",
    );

    cars_seed_demo_service_variants();
    cars_enable_all_cities_for_demo_service();
    cars_enable_all_brands_for_demo_service();
}, 20);

add_action("acf/init", static function () {
    if (!function_exists("acf_add_local_field_group")) {
        return;
    }

    acf_add_local_field_group([
        "key" => "group_cars_service_fields",
        "title" => "Поля услуги",
        "fields" => [
            [
                "key" => "field_cars_service_lead",
                "label" => "Краткое описание",
                "name" => "lead",
                "type" => "textarea",
                "rows" => 3,
                "new_lines" => "",
            ],
            [
                "key" => "field_cars_service_hero_points",
                "label" => "Пункты в первом экране",
                "name" => "hero_points",
                "type" => "repeater",
                "layout" => "table",
                "button_label" => "Добавить пункт hero",
                "sub_fields" => [
                    [
                        "key" => "field_cars_service_hero_point_text",
                        "label" => "Текст",
                        "name" => "text",
                        "type" => "text",
                    ],
                ],
            ],
            [
                "key" => "field_cars_service_hero_bottom_text",
                "label" => "Текст нижнего блока первого экрана",
                "name" => "hero_bottom_text",
                "type" => "textarea",
                "rows" => 2,
                "new_lines" => "",
            ],
            [
                "key" => "field_cars_service_hero_bottom_link",
                "label" => "Ссылка нижнего блока первого экрана",
                "name" => "hero_bottom_link",
                "type" => "text",
                "default_value" => "#contacts",
            ],
            [
                "key" => "field_cars_service_all_cities_enabled",
                "label" => "Доступно во всех городах",
                "name" => "all_cities_enabled",
                "type" => "true_false",
                "ui" => 1,
                "default_value" => 0,
                "instructions" =>
                    "Если включено, страницы вида /service/usluga/gorod/ будут работать для любого города без ручного назначения терминов.",
            ],
            [
                "key" => "field_cars_service_all_brands_enabled",
                "label" => "Доступно для всех марок",
                "name" => "all_brands_enabled",
                "type" => "true_false",
                "ui" => 1,
                "default_value" => 0,
                "instructions" =>
                    "Если включено, страницы вида /service/usluga/marka/ будут работать для любой марки без ручного назначения терминов.",
            ],
            [
                "key" => "field_cars_service_items",
                "label" => "Пункты услуги",
                "name" => "items",
                "type" => "repeater",
                "layout" => "table",
                "button_label" => "Добавить пункт",
                "sub_fields" => [
                    [
                        "key" => "field_cars_service_item_text",
                        "label" => "Текст",
                        "name" => "text",
                        "type" => "text",
                    ],
                ],
            ],
            [
                "key" => "field_cars_service_note",
                "label" => "Примечание в карточке",
                "name" => "note",
                "type" => "textarea",
                "rows" => 2,
                "new_lines" => "",
            ],
            [
                "key" => "field_cars_service_button_text",
                "label" => "Текст кнопки",
                "name" => "button_text",
                "type" => "text",
                "default_value" => "Оставить заявку",
            ],
            [
                "key" => "field_cars_service_button_link",
                "label" => "Ссылка кнопки",
                "name" => "button_link",
                "type" => "text",
                "default_value" => "#contacts",
            ],
            [
                "key" => "field_cars_service_image",
                "label" => "Изображение",
                "name" => "image",
                "type" => "image",
                "return_format" => "array",
                "preview_size" => "medium",
                "library" => "all",
            ],
            [
                "key" => "field_cars_service_order",
                "label" => "Порядок вывода",
                "name" => "order",
                "type" => "number",
                "default_value" => 0,
                "min" => 0,
                "step" => 1,
            ],
            [
                "key" => "field_cars_service_city_overrides",
                "label" => "Переопределения по городам",
                "name" => "city_overrides",
                "type" => "repeater",
                "layout" => "block",
                "button_label" => "Добавить переопределение города",
                "sub_fields" => [
                    [
                        "key" => "field_cars_service_city_override_city",
                        "label" => "Город",
                        "name" => "city",
                        "type" => "taxonomy",
                        "taxonomy" => "service_city",
                        "field_type" => "select",
                        "return_format" => "id",
                        "allow_null" => 0,
                        "add_term" => 0,
                        "save_terms" => 0,
                        "load_terms" => 0,
                    ],
                    [
                        "key" => "field_cars_service_city_override_title",
                        "label" => "Свой H1",
                        "name" => "title",
                        "type" => "text",
                        "instructions" =>
                            "Если заполнить, на странице этой услуги для выбранного города будет свой заголовок.",
                    ],
                    [
                        "key" => "field_cars_service_city_override_description",
                        "label" => "Свой текст первого экрана",
                        "name" => "description",
                        "type" => "textarea",
                        "rows" => 3,
                        "new_lines" => "",
                        "instructions" =>
                            "Если заполнить, заменит краткое описание на странице этой услуги для выбранного города.",
                    ],
                ],
            ],
        ],
        "location" => [
            [
                [
                    "param" => "post_type",
                    "operator" => "==",
                    "value" => "service",
                ],
            ],
        ],
        "position" => "normal",
        "style" => "default",
        "label_placement" => "top",
        "instruction_placement" => "label",
        "active" => true,
    ]);
});
