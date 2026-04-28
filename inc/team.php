<?php
/**
 * Team members post type and helpers.
 *
 * @package cars
 */

add_action("init", static function () {
    register_post_type("team_member", [
        "labels" => [
            "name" => "Команда",
            "singular_name" => "Участник команды",
            "add_new" => "Добавить участника",
            "add_new_item" => "Добавить участника команды",
            "edit_item" => "Редактировать участника",
            "new_item" => "Новый участник",
            "view_item" => "Смотреть участника",
            "search_items" => "Искать участников",
            "not_found" => "Участники не найдены",
            "not_found_in_trash" => "В корзине участников нет",
            "menu_name" => "Команда",
        ],
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_rest" => true,
        "menu_icon" => "dashicons-groups",
        "has_archive" => false,
        "rewrite" => false,
        "supports" => [
            "title",
            "thumbnail",
            "page-attributes",
        ],
    ]);
});

add_action("acf/init", static function () {
    if (!function_exists("acf_add_local_field_group")) {
        return;
    }

    acf_add_local_field_group([
        "key" => "group_cars_team_member_fields",
        "title" => "Поля участника команды",
        "fields" => [
            [
                "key" => "field_cars_team_member_role",
                "label" => "Должность",
                "name" => "role",
                "type" => "text",
            ],
        ],
        "location" => [
            [
                [
                    "param" => "post_type",
                    "operator" => "==",
                    "value" => "team_member",
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

function cars_get_team_members()
{
    $team_posts = get_posts([
        "post_type" => "team_member",
        "post_status" => "publish",
        "posts_per_page" => -1,
        "orderby" => [
            "menu_order" => "ASC",
            "date" => "DESC",
        ],
    ]);

    if (!$team_posts) {
        return [
            [
                "name" => "Евгений",
                "role" => "Координатор растаможка Беларусь и Киргизия",
                "image_id" => 0,
            ],
            [
                "name" => "Алексей",
                "role" => "Координатор по списанию утиля",
                "image_id" => 0,
            ],
            [
                "name" => "Вадим",
                "role" => "Координатор выпуск СБКТС ЭПТС",
                "image_id" => 0,
            ],
            [
                "name" => "Мария",
                "role" => "Специалист по сопровождению клиентов",
                "image_id" => 0,
            ],
        ];
    }

    return array_map(static function ($team_post) {
        $role = "";

        if (function_exists("get_field")) {
            $role = (string) get_field("role", $team_post->ID);
        }

        return [
            "name" => get_the_title($team_post),
            "role" => $role,
            "image_id" => get_post_thumbnail_id($team_post),
        ];
    }, $team_posts);
}
