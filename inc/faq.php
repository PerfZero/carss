<?php
/**
 * FAQ theme settings and helpers.
 *
 * @package cars
 */

add_action("acf/init", static function () {
    if (function_exists("acf_add_options_page")) {
        acf_add_options_page([
            "page_title" => "Настройки темы",
            "menu_title" => "Настройки темы",
            "menu_slug" => "cars-theme-settings",
            "capability" => "edit_posts",
            "redirect" => false,
            "position" => 61,
            "icon_url" => "dashicons-admin-generic",
        ]);
    }

    if (!function_exists("acf_add_local_field_group")) {
        return;
    }

    acf_add_local_field_group([
        "key" => "group_cars_theme_faq_settings",
        "title" => "FAQ",
        "fields" => [
            [
                "key" => "field_cars_faq_title",
                "label" => "Заголовок блока",
                "name" => "faq_title",
                "type" => "text",
                "default_value" => "Ответы на частые вопросы",
            ],
            [
                "key" => "field_cars_faq_items",
                "label" => "Вопросы и ответы",
                "name" => "faq_items",
                "type" => "repeater",
                "layout" => "block",
                "button_label" => "Добавить вопрос",
                "sub_fields" => [
                    [
                        "key" => "field_cars_faq_question",
                        "label" => "Вопрос",
                        "name" => "question",
                        "type" => "text",
                    ],
                    [
                        "key" => "field_cars_faq_answer",
                        "label" => "Ответ",
                        "name" => "answer",
                        "type" => "textarea",
                        "rows" => 4,
                        "new_lines" => "",
                    ],
                ],
            ],
        ],
        "location" => [
            [
                [
                    "param" => "options_page",
                    "operator" => "==",
                    "value" => "cars-theme-settings",
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

function cars_get_faq_section_data($columns_count = 3)
{
    $title = "Ответы на частые вопросы";
    $items = [];

    if (function_exists("get_field")) {
        $title = get_field("faq_title", "option") ?: $title;
        $raw_items = get_field("faq_items", "option");

        if (is_array($raw_items)) {
            foreach ($raw_items as $raw_item) {
                $question = trim((string) ($raw_item["question"] ?? ""));
                $answer = trim((string) ($raw_item["answer"] ?? ""));

                if ($question === "" || $answer === "") {
                    continue;
                }

                $items[] = [
                    "question" => $question,
                    "answer" => $answer,
                ];
            }
        }
    }

    if (!$items) {
        return [
            "title" => $title,
            "columns" => [],
            "has_extra" => false,
        ];
    }

    $columns_count = max(1, (int) $columns_count);
    $columns = array_fill(0, $columns_count, []);

    foreach (array_values($items) as $index => $item) {
        $columns[$index % $columns_count][] = $item;
    }

    $has_extra = false;

    foreach ($columns as $column) {
        if (count($column) > 5) {
            $has_extra = true;
            break;
        }
    }

    return [
        "title" => $title,
        "columns" => $columns,
        "has_extra" => $has_extra,
    ];
}
