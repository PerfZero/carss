<?php
/**
 * Reviews post type and helpers.
 *
 * @package cars
 */

add_action("init", static function () {
    register_post_type("review", [
        "labels" => [
            "name" => "Отзывы",
            "singular_name" => "Отзыв",
            "add_new" => "Добавить отзыв",
            "add_new_item" => "Добавить отзыв",
            "edit_item" => "Редактировать отзыв",
            "new_item" => "Новый отзыв",
            "view_item" => "Смотреть отзыв",
            "search_items" => "Искать отзывы",
            "not_found" => "Отзывы не найдены",
            "not_found_in_trash" => "В корзине отзывов нет",
            "menu_name" => "Отзывы",
        ],
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_rest" => true,
        "menu_icon" => "dashicons-format-quote",
        "has_archive" => false,
        "rewrite" => false,
        "supports" => [
            "title",
            "editor",
            "thumbnail",
            "page-attributes",
        ],
    ]);
});

function cars_get_review_seed_data()
{
    return [
        [
            "title" => "Отзыв клиента 1",
            "content" => trim('
Хочу поблагодарить Евгения за отличную работу
по списанию утиля!!! Я обратился к нему после неудачной работы с другим таможенным брокером, который проморозил меня неделю. Хоть Евгения
я лично и не знаю, и обратился к нему наобум
по Авито, он ровно за сутки всё сделал в лучшем виде!!! В 18:20 я перекинул ему сканы документов,
а в 18:10 следующего дня утиль уже был списан. Оплатил его услуги после списания утиля,
без всяких предоплат!
'),
        ],
        [
            "title" => "Отзыв клиента 2",
            "content" => trim('
Недавно воспользовался услугами по утилизационному сбору автомобиля и остался очень доволен. Процесс прошёл быстро
и без лишних хлопот. Специалисты компании
всё подробно объяснили и помогли с оформлением документов. Утилизация была выполнена качественно, и я получил все необходимые подтверждения. Рекомендую эту компанию всем, кто ищет надежное решение для утилизации авто!
'),
        ],
        [
            "title" => "Отзыв клиента 3",
            "content" => trim('
Недавно воспользовался услугами по утилизационному сбору автомобиля и остался очень доволен. Процесс прошёл быстро и без лишних хлопот. Специалисты компании всё подробно объяснили и помогли с оформлением документов. Утилизация была выполнена качественно, и я получил все необходимые подтверждения. Рекомендую эту компанию всем, кто ищет надежное решение для утилизации авто!
'),
        ],
        [
            "title" => "Отзыв клиента 4",
            "content" => trim('
Хочу поблагодарить Евгения за отличную работу
по списанию утиля!!! Я обратился к нему после неудачной работы с другим таможенным брокером, который проморозил меня неделю. Хоть Евгения
я лично и не знаю, и обратился к нему наобум
по Авито, он ровно за сутки всё сделал в лучшем виде!!! В 18:20 я перекинул ему сканы документов,
а в 18:10 следующего дня утиль уже был списан. Оплатил его услуги после списания утиля,
без всяких предоплат!
'),
        ],
    ];
}

add_action("init", static function () {
    $seed_option = "cars_reviews_seeded_v1";

    if (get_option($seed_option)) {
        return;
    }

    $existing_reviews = get_posts([
        "post_type" => "review",
        "post_status" => "any",
        "posts_per_page" => 1,
        "fields" => "ids",
    ]);

    if (!empty($existing_reviews)) {
        update_option($seed_option, 1, false);
        return;
    }

    foreach (cars_get_review_seed_data() as $index => $review_data) {
        wp_insert_post([
            "post_type" => "review",
            "post_status" => "publish",
            "post_title" => $review_data["title"],
            "post_content" => $review_data["content"],
            "menu_order" => $index,
        ]);
    }

    update_option($seed_option, 1, false);
});

function cars_get_reviews()
{
    $review_posts = get_posts([
        "post_type" => "review",
        "post_status" => "publish",
        "posts_per_page" => -1,
        "orderby" => [
            "menu_order" => "ASC",
            "date" => "DESC",
        ],
    ]);

    if (!$review_posts) {
        return array_map(static function ($review_data) {
            return [
                "name" => $review_data["title"],
                "text" => trim(
                    preg_replace("/\s+/u", " ", $review_data["content"]),
                ),
                "image_url" => "",
            ];
        }, cars_get_review_seed_data());
    }

    return array_map(static function ($review_post) {
        $image_url = "";
        $image_id = get_post_thumbnail_id($review_post);

        if ($image_id) {
            $image_src = wp_get_attachment_image_url($image_id, "medium");

            if ($image_src) {
                $image_url = $image_src;
            }
        }

        return [
            "name" => get_the_title($review_post),
            "text" => trim(
                preg_replace(
                    "/\s+/u",
                    " ",
                    wp_strip_all_tags($review_post->post_content),
                ),
            ),
            "image_url" => $image_url,
        ];
    }, $review_posts);
}
