<?php
/**
 * Car models post type and importer.
 *
 * @package cars
 */

function cars_read_seed_csv_rows($path)
{
    if (!is_readable($path)) {
        return [];
    }

    $handle = fopen($path, "r");

    if (!$handle) {
        return [];
    }

    $header = fgetcsv($handle);

    if (!is_array($header)) {
        fclose($handle);

        return [];
    }

    $header = array_map(static function ($column) {
        return trim((string) $column);
    }, $header);

    $rows = [];

    while (($row = fgetcsv($handle)) !== false) {
        if (!$row) {
            continue;
        }

        $rows[] = array_combine(
            $header,
            array_map(static function ($value) {
                return trim((string) $value);
            }, $row),
        );
    }

    fclose($handle);

    return array_values(array_filter($rows));
}

function cars_get_car_models_csv_path()
{
    return get_template_directory() . "/data/service-models-normalized.csv";
}

function cars_get_car_model_brand_term($brand_name)
{
    $brand_name = trim((string) $brand_name);

    if ($brand_name === "") {
        return null;
    }

    $slug = cars_build_service_term_slug($brand_name);
    $term = $slug !== "" ? get_term_by("slug", $slug, "service_brand") : null;

    if ($term) {
        return $term;
    }

    $term = get_term_by("name", $brand_name, "service_brand");

    if ($term) {
        return $term;
    }

    $result = wp_insert_term($brand_name, "service_brand", [
        "slug" => $slug,
    ]);

    if (is_wp_error($result) || empty($result["term_id"])) {
        return null;
    }

    return get_term((int) $result["term_id"], "service_brand");
}

function cars_build_car_model_slug($model_name)
{
    return sanitize_title(wp_strip_all_tags((string) $model_name));
}

function cars_build_car_model_unique_key($brand_term_id, $model_name)
{
    return (int) $brand_term_id . "|" . mb_strtolower(
        trim(wp_strip_all_tags((string) $model_name)),
        "UTF-8",
    );
}

function cars_get_car_model_variant_slug($car_model)
{
    $car_model_post = get_post($car_model);

    if (!$car_model_post || $car_model_post->post_type !== "car_model") {
        return "";
    }

    $stored_slug = (string) get_post_meta($car_model_post->ID, "model_slug", true);

    if ($stored_slug !== "") {
        return $stored_slug;
    }

    return cars_build_car_model_slug($car_model_post->post_title);
}

function cars_get_car_model_brand_term_id($car_model)
{
    $car_model_post = get_post($car_model);

    if (!$car_model_post || $car_model_post->post_type !== "car_model") {
        return 0;
    }

    return (int) get_post_meta($car_model_post->ID, "brand_term_id", true);
}

function cars_get_car_model_by_brand_and_slug($brand_term_id, $model_slug)
{
    static $cache = [];

    $brand_term_id = (int) $brand_term_id;
    $model_slug = sanitize_title((string) $model_slug);

    if (!$brand_term_id || $model_slug === "") {
        return null;
    }

    $cache_key = $brand_term_id . "|" . $model_slug;

    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }

    $posts = get_posts([
        "post_type" => "car_model",
        "post_status" => "publish",
        "numberposts" => 1,
        "meta_query" => [
            [
                "key" => "brand_term_id",
                "value" => $brand_term_id,
            ],
            [
                "key" => "model_slug",
                "value" => $model_slug,
            ],
        ],
    ]);

    $cache[$cache_key] = $posts ? $posts[0] : null;

    return $cache[$cache_key];
}

function cars_get_car_models_for_brand_term($brand_term_id)
{
    static $cache = [];

    $brand_term_id = (int) $brand_term_id;

    if (!$brand_term_id) {
        return [];
    }

    if (isset($cache[$brand_term_id])) {
        return $cache[$brand_term_id];
    }

    $posts = get_posts([
        "post_type" => "car_model",
        "post_status" => "publish",
        "numberposts" => -1,
        "orderby" => "title",
        "order" => "ASC",
        "meta_key" => "brand_term_id",
        "meta_value" => $brand_term_id,
    ]);

    $cache[$brand_term_id] = $posts ?: [];

    return $cache[$brand_term_id];
}

add_action("init", static function () {
    register_post_type("car_model", [
        "labels" => [
            "name" => "Модели авто",
            "singular_name" => "Модель авто",
            "add_new" => "Добавить модель",
            "add_new_item" => "Добавить модель авто",
            "edit_item" => "Редактировать модель",
            "new_item" => "Новая модель",
            "view_item" => "Смотреть модель",
            "search_items" => "Искать модели",
            "not_found" => "Модели не найдены",
            "not_found_in_trash" => "В корзине моделей нет",
            "menu_name" => "Модели авто",
        ],
        "public" => false,
        "show_ui" => true,
        "show_in_menu" => "edit.php?post_type=service",
        "show_in_rest" => true,
        "menu_icon" => "dashicons-car",
        "has_archive" => false,
        "rewrite" => false,
        "supports" => [
            "title",
        ],
    ]);
});

add_action("acf/init", static function () {
    if (!function_exists("acf_add_local_field_group")) {
        return;
    }

    acf_add_local_field_group([
        "key" => "group_cars_car_model_fields",
        "title" => "Поля модели авто",
        "fields" => [
            [
                "key" => "field_cars_car_model_brand",
                "label" => "Марка",
                "name" => "brand",
                "type" => "taxonomy",
                "taxonomy" => "service_brand",
                "field_type" => "select",
                "return_format" => "id",
                "allow_null" => 0,
                "add_term" => 0,
                "save_terms" => 0,
                "load_terms" => 0,
            ],
            [
                "key" => "field_cars_car_model_source_region",
                "label" => "Регион-источник",
                "name" => "source_region",
                "type" => "text",
                "readonly" => 1,
            ],
            [
                "key" => "field_cars_car_model_source_value",
                "label" => "Исходное значение",
                "name" => "source_value",
                "type" => "text",
                "readonly" => 1,
            ],
        ],
        "location" => [
            [
                [
                    "param" => "post_type",
                    "operator" => "==",
                    "value" => "car_model",
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

function cars_sync_car_models_from_csv()
{
    $csv_path = cars_get_car_models_csv_path();

    if (!is_readable($csv_path)) {
        return;
    }

    $current_hash = md5_file($csv_path);
    $option_name = "cars_car_models_import_hash_v6";

    if (!$current_hash || get_option($option_name) === $current_hash) {
        return;
    }

    $rows = cars_read_seed_csv_rows($csv_path);

    if (!$rows) {
        return;
    }

    $existing_posts = get_posts([
        "post_type" => "car_model",
        "post_status" => "any",
        "numberposts" => -1,
        "fields" => "ids",
        "orderby" => "ID",
        "order" => "ASC",
    ]);

    $existing_by_key = [];
    $existing_seeded_posts = [];
    $duplicate_post_ids = [];

    foreach ($existing_posts as $post_id) {
        $stored_key = (string) get_post_meta($post_id, "car_model_key", true);

        $brand_term_id = (int) get_post_meta($post_id, "brand_term_id", true);
        $title = get_the_title($post_id);

        if ($stored_key !== "") {
            $existing_seeded_posts[$stored_key] = (int) $post_id;

            if (isset($existing_by_key[$stored_key])) {
                $duplicate_post_ids[] = (int) $post_id;
                continue;
            }

            $existing_by_key[$stored_key] = (int) $post_id;
            continue;
        }

        if (!$brand_term_id || $title === "") {
            continue;
        }

        $unique_key = cars_build_car_model_unique_key($brand_term_id, $title);

        if (isset($existing_by_key[$unique_key])) {
            $duplicate_post_ids[] = (int) $post_id;
            continue;
        }

        $existing_by_key[$unique_key] = (int) $post_id;
    }

    $desired_keys = [];

    foreach ($rows as $row) {
        $brand_name = trim((string) ($row["brand"] ?? ""));
        $model_name = trim((string) ($row["model"] ?? ""));

        if ($brand_name === "" || $model_name === "") {
            continue;
        }

        $brand_term = cars_get_car_model_brand_term($brand_name);

        if (!$brand_term || empty($brand_term->term_id)) {
            continue;
        }

        $brand_term_id = (int) $brand_term->term_id;
        $unique_key = cars_build_car_model_unique_key($brand_term_id, $model_name);
        $model_slug = cars_build_car_model_slug($model_name);
        $desired_keys[$unique_key] = true;
        $post_id = (int) ($existing_by_key[$unique_key] ?? 0);

        $post_data = [
            "post_type" => "car_model",
            "post_status" => "publish",
            "post_title" => $model_name,
            "post_name" => sanitize_title($brand_term->slug . "-" . $model_name),
        ];

        if ($post_id) {
            $post_data["ID"] = $post_id;
            wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);

            if (is_wp_error($post_id) || !$post_id) {
                continue;
            }

            $existing_by_key[$unique_key] = (int) $post_id;
        }

        update_post_meta($post_id, "brand_term_id", $brand_term_id);
        update_post_meta($post_id, "source_region", (string) ($row["source_sheet"] ?? ""));
        update_post_meta($post_id, "source_value", (string) ($row["source_cell"] ?? ""));
        update_post_meta($post_id, "car_model_key", $unique_key);
        update_post_meta($post_id, "model_slug", $model_slug);
        update_post_meta($post_id, "cars_seeded_car_model", 1);

        if (function_exists("update_field")) {
            update_field("field_cars_car_model_brand", $brand_term_id, $post_id);
            update_field(
                "field_cars_car_model_source_region",
                (string) ($row["source_sheet"] ?? ""),
                $post_id,
            );
            update_field(
                "field_cars_car_model_source_value",
                (string) ($row["source_cell"] ?? ""),
                $post_id,
            );
        }
    }

    foreach ($existing_seeded_posts as $unique_key => $post_id) {
        if (isset($desired_keys[$unique_key])) {
            continue;
        }

        wp_delete_post($post_id, true);
    }

    foreach ($duplicate_post_ids as $post_id) {
        wp_delete_post($post_id, true);
    }

    update_option($option_name, $current_hash, false);
}

add_action("init", static function () {
    cars_sync_car_models_from_csv();
}, 30);

add_filter("manage_car_model_posts_columns", static function ($columns) {
    $title = $columns["title"] ?? "Заголовок";
    $date = $columns["date"] ?? "Дата";

    return [
        "cb" => $columns["cb"] ?? "",
        "title" => $title,
        "brand_term" => "Марка",
        "source_region" => "Регион",
        "date" => $date,
    ];
});

add_action("manage_car_model_posts_custom_column", static function ($column, $post_id) {
    if ($column === "brand_term") {
        $brand_term_id = (int) get_post_meta($post_id, "brand_term_id", true);
        $brand_term = $brand_term_id ? get_term($brand_term_id, "service_brand") : null;

        echo esc_html($brand_term && !is_wp_error($brand_term) ? $brand_term->name : "—");
        return;
    }

    if ($column === "source_region") {
        echo esc_html((string) get_post_meta($post_id, "source_region", true));
    }
}, 10, 2);

add_filter("manage_edit-car_model_sortable_columns", static function ($columns) {
    $columns["source_region"] = "source_region";

    return $columns;
});

add_action("pre_get_posts", static function ($query) {
    if (
        !is_admin() ||
        !$query->is_main_query() ||
        $query->get("post_type") !== "car_model"
    ) {
        return;
    }

    if ($query->get("orderby") === "source_region") {
        $query->set("meta_key", "source_region");
        $query->set("orderby", "meta_value");
    }
});
