<?php
/**
 * Services section.
 *
 * @package cars
 */

$args = isset($args) && is_array($args) ? $args : [];
$variant = $args["variant"] ?? "section";
$is_page_variant = $variant === "page";
$services_url = function_exists("cars_services_page_url")
    ? cars_services_page_url()
    : home_url("/services/");
$contact_url = $is_page_variant ? home_url("/#contacts") : "#contacts";
$active_group_slug = is_tax("service_category")
    ? get_queried_object()->slug ?? ""
    : "";
$additional_group_slug = "additional-services";
$category_slugs = $active_group_slug
    ? [$active_group_slug]
    : ["pts-operator", "customs-services", $additional_group_slug];

$service_to_card = static function ($service_post) {
    $items = [];
    $lead = get_the_excerpt($service_post);
    $note = "";
    $button_text = "Оставить заявку";
    $button_link = "#contacts";

    if (function_exists("get_field")) {
        $lead = get_field("lead", $service_post->ID) ?: $lead;
        $note = get_field("note", $service_post->ID) ?: "";
        $button_text =
            get_field("button_text", $service_post->ID) ?: $button_text;
        $button_link =
            get_field("button_link", $service_post->ID) ?: $button_link;
        $raw_items = get_field("items", $service_post->ID);

        if (is_array($raw_items)) {
            foreach ($raw_items as $item) {
                if (!empty($item["text"])) {
                    $items[] = $item["text"];
                }
            }
        }
    }

    return [
        "url" => get_permalink($service_post),
        "title" => get_the_title($service_post),
        "lead" => $lead,
        "items" => $items,
        "note" => $note,
        "button" => $button_text,
        "button_link" => $button_link,
    ];
};

$service_groups = [];

foreach ($category_slugs as $category_slug) {
    $term = get_term_by("slug", $category_slug, "service_category");

    if (!$term || is_wp_error($term)) {
        continue;
    }

    $service_posts = get_posts([
        "post_type" => "service",
        "post_status" => "publish",
        "posts_per_page" => -1,
        "orderby" => [
            "menu_order" => "ASC",
            "date" => "DESC",
        ],
        "tax_query" => [
            [
                "taxonomy" => "service_category",
                "field" => "term_id",
                "terms" => $term->term_id,
            ],
        ],
    ]);

    if (!$service_posts) {
        continue;
    }

    $is_additional_group = $term->slug === $additional_group_slug;
    $service_groups[$term->slug] = [
        "title" => $term->name,
        "type" => $is_additional_group ? "chips" : "cards",
        "note" => $term->description,
        "cards" => $is_additional_group
            ? []
            : array_map($service_to_card, $service_posts),
        "items" => $is_additional_group
            ? array_map(static function ($service_post) {
                return [
                    "title" => get_the_title($service_post),
                    "url" => get_permalink($service_post),
                ];
            }, $service_posts)
            : [],
    ];
}

$card_groups = array_filter($service_groups, static function ($group) {
    return $group["type"] === "cards";
});

if (!$is_page_variant && !$active_group_slug) {
    $card_groups = array_slice($card_groups, 0, 1, true);
}

$additional_group = $service_groups[$additional_group_slug] ?? null;
$show_additional = (bool) $additional_group;
$first_card_group_key = array_key_first($card_groups);
$first_card_group = $first_card_group_key !== null
    ? $card_groups[$first_card_group_key]
    : null;
?>
<section class="docs-white <?php echo $is_page_variant ? "docs-white--page" : ""; ?>" id="docs-pack">
    <div class="docs-white__inner">
        <div class="docs-white__top">
            <h2 class="docs-white__title">
                Получите <strong>полный пакет документов</strong> для постановки автомобиля на учет
            </h2>
            <p class="docs-white__intro">
                Мы помогаем оформить СБКТС на автомобиль и получить ЭПТС через наших партнеров, аккредитованные
                испытательные лаборатории. <strong>После оформления вы сможете официально зарегистрировать автомобиль.</strong>
            </p>
        </div>

        <?php if (
            $is_page_variant &&
            $first_card_group &&
            !empty($first_card_group["note"])
        ): ?>
            <div class="docs-white__page-summary">
                <a class="docs-white__head-link" href="#services-groups">Перейти в раздел услуг<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683418 14.0976 1.31658 14.0976 1.70711 13.7071L1 13L0.292893 12.2929ZM14 0.999999C14 0.447714 13.5523 -8.61581e-07 13 -1.11446e-06L4 -3.13672e-07C3.44772 -6.50847e-07 3 0.447715 3 0.999999C3 1.55228 3.44772 2 4 2L12 2L12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10L14 0.999999ZM1 13L1.70711 13.7071L13.7071 1.70711L13 0.999999L12.2929 0.292893L0.292893 12.2929L1 13Z" fill="#EF1413" />
                </svg></a>
                <p class="docs-white__head-note"><?php echo esc_html(
                    $first_card_group["note"],
                ); ?></p>
            </div>
        <?php endif; ?>

        <?php foreach ($card_groups as $group_index => $group): ?>
            <?php $is_first_group = array_key_first($card_groups) === $group_index; ?>
            <div class="docs-white__head-row <?php echo $is_page_variant
                ? "docs-white__head-row--page"
                : ""; ?> <?php echo !$is_first_group
                ? "docs-white__head-row--sub"
                : ""; ?>" <?php echo $is_first_group
    ? 'id="services-groups"'
    : ""; ?>>
                <h3 class="docs-white__head-title"><?php echo esc_html(
                    $group["title"],
                ); ?></h3>
                <?php if ($is_first_group && !$is_page_variant): ?>
                    <a class="docs-white__head-link" href="<?php echo esc_url($services_url); ?>">Перейти в раздел услуг<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683418 14.0976 1.31658 14.0976 1.70711 13.7071L1 13L0.292893 12.2929ZM14 0.999999C14 0.447714 13.5523 -8.61581e-07 13 -1.11446e-06L4 -3.13672e-07C3.44772 -6.50847e-07 3 0.447715 3 0.999999C3 1.55228 3.44772 2 4 2L12 2L12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10L14 0.999999ZM1 13L1.70711 13.7071L13.7071 1.70711L13 0.999999L12.2929 0.292893L0.292893 12.2929L1 13Z" fill="#EF1413" />
                    </svg></a>
                <?php endif; ?>
                <?php if (
                    $is_first_group &&
                    !$is_page_variant &&
                    !empty($group["note"])
                ): ?>
                    <p class="docs-white__head-note"><?php echo esc_html(
                        $group["note"],
                    ); ?></p>
                <?php endif; ?>
            </div>

            <div class="docs-white__cards">
                <?php foreach ($group["cards"] as $card): ?>
                    <?php
                    $button_link = $card["button_link"] ?? $contact_url;

                    if ($is_page_variant && $button_link === "#contacts") {
                        $button_link = $contact_url;
                    }
                    ?>
                    <a class="docs-card" href="<?php echo esc_url($card["url"]); ?>">
                        <svg
                          class="docs-card__arrow"
                          width="14"
                          height="14"
                          viewBox="0 0 14 14"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true"
                        >
                          <path d="M0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683418 14.0976 1.31658 14.0976 1.70711 13.7071L1 13L0.292893 12.2929ZM14 0.999999C14 0.447714 13.5523 -8.61581e-07 13 -1.11446e-06L4 -3.13672e-07C3.44772 -6.50847e-07 3 0.447715 3 0.999999C3 1.55228 3.44772 2 4 2L12 2L12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10L14 0.999999ZM1 13L1.70711 13.7071L13.7071 1.70711L13 0.999999L12.2929 0.292893L0.292893 12.2929L1 13Z" fill="currentColor" />
                        </svg>
                        <h4 class="docs-card__title"><?php echo esc_html(
                            $card["title"],
                        ); ?></h4>
                        <p class="docs-card__lead"><?php echo esc_html(
                            $card["lead"],
                        ); ?></p>
                        <?php if (!empty($card["items"])): ?>
                            <ul class="docs-card__list">
                                <?php foreach ($card["items"] as $item): ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if (!empty($card["note"])): ?>
                            <p class="docs-card__note"><?php echo esc_html(
                                $card["note"],
                            ); ?></p>
                        <?php endif; ?>
                        <span class="docs-card__button"><?php echo esc_html(
                            $card["button"],
                        ); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <?php if (!$active_group_slug): ?>
            <div class="docs-white__center-action">
                <a class="docs-white__outline-btn" href="<?php echo esc_url(
                    $is_page_variant ? "#docs-pack" : $services_url,
                ); ?>"><?php echo $is_page_variant
    ? "Скрыть список"
    : "Посмотреть все услуги"; ?></a>
            </div>
        <?php endif; ?>

        <?php if ($show_additional): ?>
            <h3 class="docs-white__extra-title"><?php echo esc_html(
                $additional_group["title"],
            ); ?></h3>

            <div class="docs-white__chips">
                <?php foreach ($additional_group["items"] as $index => $service): ?>
                    <a
                      class="docs-white__chip <?php echo $index === 0
                          ? "docs-white__chip--active"
                          : ""; ?>"
                      href="<?php echo esc_url($service["url"]); ?>"
                    >
                        <span><?php echo esc_html($service["title"]); ?></span>
                        <svg
                          class="docs-white__chip-arrow"
                          width="12"
                          height="12"
                          viewBox="0 0 12 12"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true"
                        >
                          <path
                            d="M11.1798 0.74992C11.1798 0.335706 10.844 -8.05526e-05 10.4298 -8.04051e-05L3.67977 -8.08265e-05C3.26555 -8.08265e-05 2.92977 0.335705 2.92977 0.749919C2.92977 1.16413 3.26555 1.49992 3.67977 1.49992H9.67977V7.49992C9.67977 7.91413 10.0156 8.24992 10.4298 8.24992C10.844 8.24992 11.1798 7.91413 11.1798 7.49992L11.1798 0.74992ZM0.530273 10.6494L1.0606 11.1797L10.9601 1.28025L10.4298 0.749919L9.89944 0.219589L-5.66393e-05 10.1191L0.530273 10.6494Z"
                            fill="currentColor"
                          />
                        </svg>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="docs-white__bottom-action">
                <a class="docs-white__main-btn" href="<?php echo esc_url($contact_url); ?>" data-open-modal="contact">Оставить заявку</a>
            </div>
        <?php endif; ?>
    </div>
</section>
