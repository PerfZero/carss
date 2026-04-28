<?php
/**
 * Contact forms handling.
 *
 * @package cars
 */

add_action("init", static function () {
    register_post_type("lead_request", [
        "labels" => [
            "name" => "Заявки",
            "singular_name" => "Заявка",
            "menu_name" => "Заявки",
            "add_new_item" => "Добавить заявку",
            "edit_item" => "Просмотр заявки",
            "new_item" => "Новая заявка",
            "view_item" => "Смотреть заявку",
            "search_items" => "Искать заявки",
            "not_found" => "Заявки не найдены",
            "not_found_in_trash" => "В корзине заявок нет",
        ],
        "public" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_rest" => false,
        "menu_icon" => "dashicons-email-alt",
        "supports" => ["title"],
    ]);
});

function cars_get_request_contact_types()
{
    return [
        "call" => "Позвоните мне",
        "whatsapp" => "Напишите в WhatsApp",
        "telegram" => "Напишите в Telegram",
        "max" => "Написать в MAX",
    ];
}

function cars_get_request_form_labels()
{
    return [
        "home_contact" => "Главная: форма консультации",
        "archive_contact" => "Услуги: форма консультации",
        "home_expert" => "Главная: форма эксперта",
        "service_expert" => "Услуга: форма эксперта",
        "blog_expert" => "Блог: форма эксперта",
        "footer_contact" => "Футер: задать вопрос",
        "modal_contact" => "Модальное окно: связаться",
        "expert_quiz" => "Квиз эксперта",
    ];
}

function cars_get_request_detail_labels()
{
    return [
        "quiz_origin" => "Откуда автомобиль",
        "quiz_vehicle_type" => "Тип автомобиля",
        "quiz_vehicle_age" => "Возраст автомобиля",
        "quiz_service" => "Что нужно оформить",
    ];
}

function cars_get_request_form_label($form_id)
{
    $labels = cars_get_request_form_labels();

    return $labels[$form_id] ?? "Форма сайта";
}

function cars_get_request_form_action_url()
{
    return admin_url("admin-post.php");
}

function cars_get_current_page_url($fragment = "")
{
    $request_uri = isset($_SERVER["REQUEST_URI"])
        ? wp_unslash($_SERVER["REQUEST_URI"])
        : "/";
    $url = home_url($request_uri);

    if ($fragment !== "") {
        $url = preg_replace("/#.*$/", "", $url) . "#" . ltrim($fragment, "#");
    }

    return $url;
}

function cars_render_request_form_hidden_fields($form_id, $redirect_to)
{
    wp_nonce_field("cars_submit_request", "cars_request_nonce");
    ?>
    <input type="hidden" name="action" value="cars_submit_request">
    <input type="hidden" name="form_id" value="<?php echo esc_attr($form_id); ?>">
    <input type="hidden" name="redirect_to" value="<?php echo esc_url(
        $redirect_to,
    ); ?>">
    <?php
}

function cars_get_request_form_status($form_id)
{
    $status = sanitize_key($_GET["cars_form_status"] ?? "");
    $current_form_id = sanitize_key($_GET["cars_form_id"] ?? "");

    if ($form_id !== $current_form_id) {
        return "";
    }

    if (!in_array($status, ["success", "error"], true)) {
        return "";
    }

    return $status;
}

function cars_render_request_form_notice($form_id)
{
    $status = cars_get_request_form_status($form_id);

    if ($status === "") {
        return;
    }

    $message = $status === "success"
        ? "Заявка отправлена. Мы свяжемся с вами в ближайшее время."
        : "Не удалось отправить форму. Проверьте телефон и согласие на обработку данных.";
    ?>
    <div class="cars-form-notice cars-form-notice--<?php echo esc_attr(
        $status,
    ); ?>" role="status">
        <?php echo esc_html($message); ?>
    </div>
    <?php
}

function cars_build_request_redirect_url($url, $args)
{
    $fragment = "";

    if (strpos($url, "#") !== false) {
        [$url, $fragment] = explode("#", $url, 2);
    }

    $url = remove_query_arg(["cars_form_status", "cars_form_id"], $url);
    $url = add_query_arg($args, $url);

    if ($fragment !== "") {
        $url .= "#" . $fragment;
    }

    return $url;
}

function cars_normalize_phone($phone)
{
    return preg_replace("/[^0-9+]/", "", (string) $phone);
}

function cars_store_request($data)
{
    $title_parts = [
        "Заявка",
        wp_date("d.m.Y H:i"),
    ];

    if (!empty($data["name"])) {
        $title_parts[] = $data["name"];
    } elseif (!empty($data["phone"])) {
        $title_parts[] = $data["phone"];
    }

    $request_id = wp_insert_post([
        "post_type" => "lead_request",
        "post_status" => "publish",
        "post_title" => implode(" - ", $title_parts),
    ]);

    if (is_wp_error($request_id) || !$request_id) {
        return 0;
    }

    foreach (
        [
            "name",
            "phone",
            "contact_type",
            "contact_type_label",
            "details",
            "form_id",
            "form_label",
            "page_url",
        ]
        as $key
    ) {
        update_post_meta($request_id, "_cars_" . $key, $data[$key] ?? "");
    }

    return (int) $request_id;
}

function cars_send_request_email($data)
{
    $to = get_option("admin_email");

    if (!$to || !is_email($to)) {
        return false;
    }

    $site_name = wp_specialchars_decode(
        get_bloginfo("name"),
        ENT_QUOTES,
    );
    $subject = sprintf("[%s] Новая заявка с сайта", $site_name);
    $message = implode(
        "\n",
        [
            "Источник: " . ($data["form_label"] ?? ""),
            "Страница: " . ($data["page_url"] ?? ""),
            "Имя: " . ($data["name"] ?: "Не указано"),
            "Телефон: " . ($data["phone"] ?? ""),
            "Способ связи: " . ($data["contact_type_label"] ?? "Не указан"),
            $data["details"] !== ""
                ? "Ответы квиза:\n" . $data["details"]
                : "",
        ],
    );

    return wp_mail($to, $subject, $message);
}

function cars_handle_request_submission()
{
    $form_id = sanitize_key($_POST["form_id"] ?? "");
    $redirect_to = wp_validate_redirect(
        wp_unslash($_POST["redirect_to"] ?? ""),
        home_url("/"),
    );

    if (
        !wp_verify_nonce(
            sanitize_text_field($_POST["cars_request_nonce"] ?? ""),
            "cars_submit_request",
        )
    ) {
        wp_safe_redirect(
            cars_build_request_redirect_url($redirect_to, [
                "cars_form_status" => "error",
                "cars_form_id" => $form_id,
            ]),
        );
        exit();
    }

    $name = sanitize_text_field(wp_unslash($_POST["name"] ?? ""));
    $phone = cars_normalize_phone(wp_unslash($_POST["phone"] ?? ""));
    $contact_types = cars_get_request_contact_types();
    $contact_type = sanitize_key($_POST["contact_type"] ?? "call");
    $consent = !empty($_POST["consent"]);

    if (!isset($contact_types[$contact_type])) {
        $contact_type = "call";
    }

    $detail_lines = [];

    foreach (cars_get_request_detail_labels() as $key => $label) {
        $value = sanitize_text_field(wp_unslash($_POST[$key] ?? ""));

        if ($value === "") {
            continue;
        }

        $detail_lines[] = $label . ": " . $value;
    }

    if (!$consent || strlen(preg_replace("/\D/", "", $phone)) < 10) {
        wp_safe_redirect(
            cars_build_request_redirect_url($redirect_to, [
                "cars_form_status" => "error",
                "cars_form_id" => $form_id,
            ]),
        );
        exit();
    }

    $data = [
        "name" => $name,
        "phone" => $phone,
        "contact_type" => $contact_type,
        "contact_type_label" => $contact_types[$contact_type],
        "details" => implode("\n", $detail_lines),
        "form_id" => $form_id,
        "form_label" => cars_get_request_form_label($form_id),
        "page_url" => esc_url_raw(wp_get_referer() ?: $redirect_to),
    ];

    $request_id = cars_store_request($data);

    if (!$request_id) {
        wp_safe_redirect(
            cars_build_request_redirect_url($redirect_to, [
                "cars_form_status" => "error",
                "cars_form_id" => $form_id,
            ]),
        );
        exit();
    }

    cars_send_request_email($data);

    wp_safe_redirect(
        cars_build_request_redirect_url($redirect_to, [
            "cars_form_status" => "success",
            "cars_form_id" => $form_id,
        ]),
    );
    exit();
}

add_action("admin_post_nopriv_cars_submit_request", "cars_handle_request_submission");
add_action("admin_post_cars_submit_request", "cars_handle_request_submission");

add_filter("manage_lead_request_posts_columns", static function ($columns) {
    return [
        "cb" => $columns["cb"] ?? "",
        "title" => "Заявка",
        "cars_phone" => "Телефон",
        "cars_contact_type" => "Способ связи",
        "cars_form_label" => "Источник",
        "date" => "Дата",
    ];
});

add_action(
    "manage_lead_request_posts_custom_column",
    static function ($column, $post_id) {
        if ($column === "cars_phone") {
            echo esc_html(get_post_meta($post_id, "_cars_phone", true));
        }

        if ($column === "cars_contact_type") {
            echo esc_html(
                get_post_meta($post_id, "_cars_contact_type_label", true),
            );
        }

        if ($column === "cars_form_label") {
            echo esc_html(get_post_meta($post_id, "_cars_form_label", true));
        }
    },
    10,
    2,
);

add_action("add_meta_boxes", static function () {
    add_meta_box(
        "cars_request_details",
        "Данные заявки",
        static function ($post) {
            $fields = [
                "name" => "Имя",
                "phone" => "Телефон",
                "contact_type_label" => "Способ связи",
                "details" => "Ответы квиза",
                "form_label" => "Источник",
                "page_url" => "Страница",
            ];
            ?>
            <table class="form-table" role="presentation">
                <tbody>
                    <?php foreach ($fields as $key => $label): ?>
                        <tr>
                            <th scope="row"><?php echo esc_html($label); ?></th>
                            <td>
                                <?php
                                $value = get_post_meta(
                                    $post->ID,
                                    "_cars_" . $key,
                                    true,
                                );

                                if ($key === "page_url" && $value) {
                                    ?>
                                    <a href="<?php echo esc_url(
                                        $value,
                                    ); ?>" target="_blank" rel="noreferrer">
                                        <?php echo esc_html($value); ?>
                                    </a>
                                    <?php
                                } elseif ($key === "details" && $value) {
                                    echo nl2br(esc_html($value));
                                } else {
                                    echo esc_html($value ?: "—");
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        },
        "lead_request",
        "normal",
        "default",
    );
});
