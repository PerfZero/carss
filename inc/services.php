<?php
/**
 * Service post type and fields.
 *
 * @package cars
 */

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
});

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
