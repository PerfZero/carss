<?php
/**
 * Recommendations block.
 *
 * @package cars
 */

$trust_items = [
    [
        "title" =>
            "Каждый специалист в команде имеет 5+ лет опыта и за счет нескольких точных вопросов может сформировать конкретное решение по стоимости и срокам",
        "image" => "/assets/images/pic_1.png",
    ],
    [
        "title" =>
            "Скорость решения задачи по оформлению документов для постановки ТС на учет: от 2 часов до 48 часов",
        "image" => "/assets/images/pic_2.png",
    ],
    [
        "title" =>
            "Выгодная стоимость по рынку без переплат за счет наличия большой партнерской сети и уникальных договоренностей",
        "image" => "/assets/images/pic_3.png",
    ],
    [
        "title" =>
            "Персональный менеджер и регламент по каждому этапу до цели",
        "image" => "/assets/images/pic_4.png",
    ],
    [
        "title" =>
            "Принцип «сказал – сделал». Проверяем все документы до оформления, тем самым исключаем риск отказа",
        "image" => "/assets/images/pic_5.png",
    ],
    [
        "title" =>
            "Подбираем удобную лабораторию. Работаем с 50+ лабораториями по всей России.",
        "image" => "/assets/images/pic_6.png",
    ],
];
?>
<div class="trust-block" id="reviews">
    <h2 class="trust-block__title">
        Нас <strong>рекомендуют</strong><br>
        и вот почему
    </h2>

    <div class="trust-block__grid">
        <div class="trust-block__col">
            <?php foreach (array_slice($trust_items, 0, 4) as $item): ?>
                <article class="trust-item">
                    <span class="trust-item__thumb" aria-hidden="true">
                        <img
                          class="trust-item__image"
                          src="<?php echo esc_url(
                              get_template_directory_uri() . $item["image"],
                          ); ?>"
                          alt=""
                          aria-hidden="true"
                        >
                    </span>
                    <p><?php echo esc_html($item["title"]); ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="trust-block__col">
            <?php foreach (array_slice($trust_items, 4, 2) as $item): ?>
                <article class="trust-item">
                    <span class="trust-item__thumb" aria-hidden="true">
                        <img
                          class="trust-item__image"
                          src="<?php echo esc_url(
                              get_template_directory_uri() . $item["image"],
                          ); ?>"
                          alt=""
                          aria-hidden="true"
                        >
                    </span>
                    <p><?php echo esc_html($item["title"]); ?></p>
                </article>
            <?php endforeach; ?>

            <article class="trust-highlight">
                <div class="trust-highlight__content">
                    <div class="trust-highlight__top">
                        <p class="trust-highlight__rating">
                            <span>5.0</span>
                            <span class="trust-highlight__star">★</span>
                            <span>Рейтинг</span>
                        </p>
                        <a class="trust-highlight__reviews-link" href="#reviews">
                            Смотреть все отзывы
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683418 14.0976 1.31658 14.0976 1.70711 13.7071L1 13L0.292893 12.2929ZM14 0.999999C14 0.447714 13.5523 -8.61581e-07 13 -1.11446e-06L4 -3.13672e-07C3.44772 -6.50847e-07 3 0.447715 3 0.999999C3 1.55228 3.44772 2 4 2L12 2L12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10L14 0.999999ZM1 13L1.70711 13.7071L13.7071 1.70711L13 0.999999L12.2929 0.292893L0.292893 12.2929L1 13Z" fill="#EF1413"/>
                            </svg>
                        </a>
                    </div>
                    <p class="trust-highlight__lead"><strong>›более 130</strong> положительных отзывов</p>
                    <a class="trust-highlight__button" href="#contacts" data-open-modal="contact">Свяжитесь со мной</a>
                </div>
                <img
                  class="trust-highlight__car"
                  src="<?php echo esc_url(
                      get_template_directory_uri() .
                          "/assets/images/back_rait.png",
                  ); ?>"
                  alt=""
                  aria-hidden="true"
                >
            </article>
        </div>
    </div>
</div>
