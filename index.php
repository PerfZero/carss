<?php
/**
 * Basic fallback template.
 *
 * @package cars
 */

get_header(); ?>
<main>
    <section class="hero">
        <picture class="hero_img">
            <source
              media="(max-width: 767px)"
              srcset="<?php echo esc_url(
                  get_template_directory_uri() . "/assets/images/mob_back.png",
              ); ?>"
            >
            <img
              src="<?php echo esc_url(
                  get_template_directory_uri() .
                      "/assets/images/back_hero.webp",
              ); ?>"
              alt="Фон первого экрана"
            >
        </picture>
        <div class="hero__inner">
            <h1 class="hero__title">
                Получите СБКТС и ЭПТС
                <span>за 1 день в Москве и по РФ</span>
            </h1>
            <p class="hero__description">
                <?php echo wp_kses_post(
                    cars_nbsp_short_words(
                        "Поставьте автомобиль на учет без лишних действий и поездок, оформим всё “под ключ” в одном месте. <strong>Вы приезжаете в лабораторию нашей сети, а мы берём на себя весь процесс оформления от проверки до результата.</strong>",
                    ),
                ); ?>
            </p>


            <div class="hero__actions">
                <a class="hero__button hero__button--primary" href="#contacts" data-open-modal="contact">Получить консультацию</a>
                <a class="hero__button hero__button--secondary" href="<?php echo esc_url(
                    cars_services_page_url(),
                ); ?>">Найти лабораторию рядом</a>
            </div>

            <div class="hero__benefits">
                <div class="hero__benefit">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M11.6667 0C18.1102 0 23.3333 5.22317 23.3333 11.6667C23.3333 18.1102 18.1102 23.3333 11.6667 23.3333C5.22317 23.3333 0 18.1102 0 11.6667C0 5.22317 5.22317 0 11.6667 0ZM11.6667 4.66667C11.3572 4.66667 11.0605 4.78958 10.8417 5.00838C10.6229 5.22717 10.5 5.52391 10.5 5.83333V11.6667C10.5001 11.9761 10.623 12.2728 10.8418 12.4915L14.3418 15.9915C14.5619 16.204 14.8566 16.3216 15.1625 16.319C15.4684 16.3163 15.761 16.1936 15.9773 15.9773C16.1936 15.761 16.3163 15.4684 16.319 15.1625C16.3216 14.8566 16.204 14.5619 15.9915 14.3418L12.8333 11.1837V5.83333C12.8333 5.52391 12.7104 5.22717 12.4916 5.00838C12.2728 4.78958 11.9761 4.66667 11.6667 4.66667Z" fill="#F9F9F9" />
                    </svg>                    <span>Отвечаем<br> в течение <strong>3 минут</strong></span>
                </div>
                <div class="hero__benefit">
                    <svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M0 3.5C0 2.57174 0.368749 1.6815 1.02513 1.02513C1.6815 0.368749 2.57174 0 3.5 0H19.8333C20.7616 0 21.6518 0.368749 22.3082 1.02513C22.9646 1.6815 23.3333 2.57174 23.3333 3.5V12.8333C23.3333 13.7616 22.9646 14.6518 22.3082 15.3082C21.6518 15.9646 20.7616 16.3333 19.8333 16.3333H3.5C2.57174 16.3333 1.6815 15.9646 1.02513 15.3082C0.368749 14.6518 0 13.7616 0 12.8333V3.5ZM10.5 8.16667C10.5 7.85725 10.6229 7.5605 10.8417 7.34171C11.0605 7.12292 11.3572 7 11.6667 7C11.9761 7 12.2728 7.12292 12.4916 7.34171C12.7104 7.5605 12.8333 7.85725 12.8333 8.16667C12.8333 8.47609 12.7104 8.77283 12.4916 8.99162C12.2728 9.21042 11.9761 9.33333 11.6667 9.33333C11.3572 9.33333 11.0605 9.21042 10.8417 8.99162C10.6229 8.77283 10.5 8.47609 10.5 8.16667ZM11.6667 4.66667C10.7384 4.66667 9.84817 5.03542 9.19179 5.69179C8.53542 6.34817 8.16667 7.23841 8.16667 8.16667C8.16667 9.09492 8.53542 9.98516 9.19179 10.6415C9.84817 11.2979 10.7384 11.6667 11.6667 11.6667C12.5949 11.6667 13.4852 11.2979 14.1415 10.6415C14.7979 9.98516 15.1667 9.09492 15.1667 8.16667C15.1667 7.23841 14.7979 6.34817 14.1415 5.69179C13.4852 5.03542 12.5949 4.66667 11.6667 4.66667Z" fill="#F9F9F9" />
                    </svg>                    <span>Работаем <strong><br>без предоплат</strong></span>
                </div>
            </div>
        </div>
    </section>

    <?php $quick_links_items = [
        [
            "text" => "Всё в одном месте, без поиска разных подрядчиков",
            "link_text" => "Смотреть услуги",
            "link_href" => cars_services_page_url(),
        ],
        [
            "text" => "Поможем с утильсбором, документами и оформлением",
            "link_text" => "Этапы взаимодействия",
            "link_href" => "#process",
        ],
        [
            "text" => "80% клиентов получают документы за 1 день",
            "link_text" => "Читать отзывы",
            "link_href" => "#reviews",
        ],
        [
            "text" => "Проверяем документы заранее, чтобы избежать отказа",
            "link_text" => "Проверить ТС",
            "link_href" => "#check",
        ],
    ]; ?>
    <section class="quick-links" aria-label="Преимущества и быстрые переходы">
        <div class="quick-links__inner">
            <?php foreach ($quick_links_items as $item): ?>
                <article class="quick-links__card">
                    <p class="quick-links__text"><?php echo esc_html(
                        cars_nbsp_short_words($item["text"]),
                    ); ?></p>
                    <a class="quick-links__link" href="<?php echo esc_url(
                        $item["link_href"],
                    ); ?>">
                        <span><?php echo esc_html(
                            cars_nbsp_short_words($item["link_text"]),
                        ); ?></span>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M11.1798 0.74992C11.1798 0.335706 10.844 -8.05526e-05 10.4298 -8.04051e-05L3.67977 -8.08265e-05C3.26555 -8.08265e-05 2.92977 0.335705 2.92977 0.749919C2.92977 1.16413 3.26555 1.49992 3.67977 1.49992H9.67977V7.49992C9.67977 7.91413 10.0156 8.24992 10.4298 8.24992C10.844 8.24992 11.1798 7.91413 11.1798 7.49992L11.1798 0.74992ZM0.530273 10.6494L1.0606 11.1797L10.9601 1.28025L10.4298 0.749919L9.89944 0.219589L-5.66393e-05 10.1191L0.530273 10.6494Z" fill="#CFCFCF" />
                        </svg>                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
    $proof_items = [
        [
            "text" =>
                "<strong>Более 350 автомобилей</strong> оформляем ежемесячно",
            "icon" => "/assets/images/icon_1.svg",
        ],
        [
            "text" =>
                "86% клиентов <strong>получают</strong> СБКТС и ЭПТС <strong>за 1 день</strong>",
            "icon" => "/assets/images/icon_2.svg",
        ],
        [
            "text" =>
                "<strong>Работаем с дилерами:</strong> РОЛЬФ, АВИЛОН, Major Expert, АвтоГЕРМЕС, Автомир, Панавто, Москва-Тянья и другие",
            "icon" => "/assets/images/icon_3.svg",
        ],
    ];

    $service_cards = [
        [
            "title" => "Для частных владельцев",
            "lead" =>
                "Вы получите готовые документы без необходимости разбираться в процессе",
            "image" => "/assets/images/back_1.png",
            "mobile_image" => "/assets/images/mobile_offer_1.png",
            "items" => [
                "Не нужно изучать требования и документы",
                "Всё оформим в одном месте",
                "Не нужно ездить по разным инстанциям",
            ],
            "cta" => "Проверить автомобиль",
        ],
        [
            "title" => "Для автобизнеса",
            "lead" =>
                "Стабильное оформление без срывов сроков и лишних вопросов",
            "image" => "/assets/images/back_2.png",
            "mobile_image" => "/assets/images/mobile_offer_2.png",
            "items" => [
                "Быстрое оформление под поток автомобилей",
                "Работаем как надежный партнер",
                "Четкие сроки без задержек",
            ],
            "cta" => "Получить консультацию",
        ],
    ];
    ?>
    <section class="offer" id="services">
        <img
          class="offer_img"
          src="<?php echo esc_url(
              get_template_directory_uri() . "/assets/images/back_two.png",
          ); ?>"
          alt="Фон первого экрана"
        >
        <div class="offer__inner">
            <div class="offer__top">
                <h2 class="offer__title">
                    Поможем <strong>получить СБКТС</strong> <br>и оформить <strong>электронный ПТС</strong>
                </h2>
                <p class="offer__intro">
                    Если вы ввезли автомобиль из Европы, Кореи, США или стран ЕАЭС, <strong>мы поможем быстро оформить
                    СБКТС и ЭПТС для постановки автомобиля на учет</strong>
                </p>
            </div>

            <div class="offer__proofs">
                <?php foreach ($proof_items as $index => $proof): ?>
                    <article class="offer-proof">
                        <span class="offer-proof__icon-wrap">
                            <img
                              class="offer-proof__icon"
                              src="<?php echo esc_url(
                                  get_template_directory_uri() . $proof["icon"],
                              ); ?>"
                              alt=""
                              aria-hidden="true"
                            >
                        </span>
                        <p><?php echo wp_kses_post($proof["text"]); ?></p>
                    </article>
                    <?php if ($index < count($proof_items) - 1): ?>
                        <div class="offer__proof-divider" aria-hidden="true"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="offer__cards">
                <?php foreach ($service_cards as $card): ?>
                    <article class="offer-card">
                        <h3 class="offer-card__title"><?php echo esc_html(
                            cars_nbsp_short_words($card["title"]),
                        ); ?></h3>
                        <p class="offer-card__lead"><?php echo esc_html(
                            cars_nbsp_short_words($card["lead"]),
                        ); ?></p>
                        <ul class="offer-card__list">
                            <?php foreach ($card["items"] as $item): ?>
                                <li><?php echo esc_html(
                                    cars_nbsp_short_words($item),
                                ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="offer-card__image-wrap">
                            <picture class="offer-card__picture">
                                <?php if (!empty($card["mobile_image"])): ?>
                                    <source
                                      media="(max-width: 767px)"
                                      srcset="<?php echo esc_url(
                                          get_template_directory_uri() .
                                              $card["mobile_image"],
                                      ); ?>"
                                    >
                                <?php endif; ?>
                                <img
                                  class="offer-card__image"
                                  src="<?php echo esc_url(
                                      get_template_directory_uri() .
                                          $card["image"],
                                  ); ?>"
                                  alt=""
                                  aria-hidden="true"
                                >
                            </picture>
                        </div>
                        <a class="offer-card__button" href="#contacts" data-open-modal="contact"><?php echo esc_html(
                            $card["cta"],
                        ); ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php get_template_part("template-parts/services"); ?>

    <?php
    $completed_steps = [
        "Автомобиль куплен",
        "Автомобиль доставлен в РФ",
        "Исполнитель найден",
    ];

    $next_steps = [
        [
            "title" => "Проверка документов",
            "text" => "проверим и скажем, всё ли готово",
        ],
        [
            "title" => "Осмотр транспортного средства",
            "text" => "запишем в удобную лабораторию",
        ],
        [
            "title" => "Выпуск документов СБКТС и ЭПТС",
            "text" => "",
        ],
        [
            "title" => "Страховка и постановка на учет",
            "text" => "подскажем и сопроводим",
        ],
    ];
    ?>
    <section class="contact-process" id="contacts">

        <div class="contact-process__inner">
            <div class="contact-process__form-col">
                <h2 class="contact-process__title">
                    После оформления СБКТС и ЭПТС <strong>дарим сертификат<br> на 100 тысяч рублей</strong> на детейлинг
                </h2>

                <form class="contact-form" action="<?php echo esc_url(
                    cars_get_request_form_action_url(),
                ); ?>" method="post">
                    <?php cars_render_request_form_notice("home_contact"); ?>
                    <?php cars_render_request_form_hidden_fields(
                        "home_contact",
                        home_url("/#contacts"),
                    ); ?>
                    <label class="contact-form__field">
                        <input type="text" name="name" placeholder="Ваше имя" autocomplete="name" aria-label="Ваше имя">
                    </label>

                    <label class="contact-form__field">
                        <input
                          type="tel"
                          name="phone"
                          placeholder="+7 (___) ___-__-__"
                          autocomplete="tel"
                          aria-label="Телефон"
                          required
                        >
                    </label>

                    <label class="contact-form__field contact-form__field--select">
                        <select name="contact_type" aria-label="Способ связи">
                            <option value="call">Позвоните мне</option>
                            <option value="whatsapp">Напишите в WhatsApp</option>
                            <option value="telegram">Напишите в Telegram</option>
                        </select>
                    </label>

                    <div class="contact-form__footer">
                        <label class="contact-form__consent">
                            <input type="checkbox" name="consent" required>
                            <span>
                                Отправляя форму, вы даете согласие на обработку
                                <strong>персональных данных</strong>
                            </span>
                        </label>
                        <button class="contact-form__submit" type="submit">Свяжитесь со мной</button>
                    </div>
                </form>
            </div>

            <div class="contact-process__note" role="note">
                <span class="contact-process__note-icon">!</span>
                <span>Оплата сертификатом не более 10% от общей стоимости услуг</span>
            </div>
        </div>
        <img
          class="contact-process__img"
          src="<?php echo esc_url(
              get_template_directory_uri() . "/assets/images/cars_bages.png",
          ); ?>"
          alt="Фон первого экрана"
        >

        <div class="process-block" id="cases">
            <div class="contact-process__anchor" id="process" aria-hidden="true"></div>
            <h2 class="process-block__title">
                Вы уже <strong>сделали главное</strong> —
                купили автомобиль
            </h2>
            <p class="process-block__subtitle">
                Осталось всего несколько шагов,<br>
                и мы закроем их за вас
            </p>

            <div class="process-block__grid">
                <ul class="process-list process-list--done">
                    <?php foreach ($completed_steps as $step): ?>
                        <li>
                            <span class="process-list__icon process-list__icon--done" aria-hidden="true">
                                <svg width="11" height="8" viewBox="0 0 11 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.52564 5.85L9.17564 0.2C9.30897 0.0666668 9.46453 0 9.64231 0C9.82009 0 9.97564 0.0666668 10.109 0.2C10.2423 0.333333 10.309 0.491778 10.309 0.675333C10.309 0.858889 10.2423 1.01711 10.109 1.15L3.99231 7.28333C3.85897 7.41667 3.70342 7.48333 3.52564 7.48333C3.34786 7.48333 3.19231 7.41667 3.05897 7.28333L0.192308 4.41667C0.0589744 4.28333 -0.00502564 4.12511 0.000307692 3.942C0.00564103 3.75889 0.0751964 3.60044 0.208974 3.46667C0.342752 3.33289 0.501197 3.26622 0.684308 3.26667C0.867419 3.26711 1.02564 3.33378 1.15897 3.46667L3.52564 5.85Z" fill="#F5F5F5"/>
                                </svg>
                            </span>
                            <span class="process-list__text"><?php echo esc_html(
                                $step,
                            ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="process-block__main">
                    <ul class="process-list process-list--next">
                        <?php foreach ($next_steps as $step): ?>
                            <li>
                                <span class="process-list__icon process-list__icon--next" aria-hidden="true">
                                    <span class="process-list__icon-char">?</span>
                                </span>
                                <span class="process-list__text">
                                    <strong><?php echo esc_html(
                                        $step["title"],
                                    ); ?></strong>
                                    <?php if (!empty($step["text"])): ?>
                                        <span><?php echo esc_html(
                                            $step["text"],
                                        ); ?></span>
                                    <?php endif; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="process-block__button" href="#contacts" data-open-modal="contact">Начать оформление</a>
                    <p class="process-block__reply">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.8333 0C16.8166 0 21.6667 4.85008 21.6667 10.8333C21.6667 16.8166 16.8166 21.6667 10.8333 21.6667C4.85008 21.6667 0 16.8166 0 10.8333C0 4.85008 4.85008 0 10.8333 0ZM10.8333 4.33333C10.546 4.33333 10.2705 4.44747 10.0673 4.65063C9.86414 4.8538 9.75 5.12935 9.75 5.41667V10.8333C9.75006 11.1206 9.86424 11.3961 10.0674 11.5992L13.3174 14.8492C13.5217 15.0466 13.7954 15.1558 14.0794 15.1533C14.3635 15.1508 14.6352 15.0369 14.8361 14.8361C15.0369 14.6352 15.1508 14.3635 15.1533 14.0794C15.1558 13.7954 15.0466 13.5217 14.8492 13.3174L11.9167 10.3848V5.41667C11.9167 5.12935 11.8025 4.8538 11.5994 4.65063C11.3962 4.44747 11.1207 4.33333 10.8333 4.33333Z" fill="#F9F9F9" />
                        </svg>                        Отвечаем в течение 3 минут
                    </p>
                </div>

                <figure class="process-quote">
                    <blockquote>
                        “Наш принцип: Сказал - сделал! Если мы берёмся за оформление, мы доводим процесс до результата без
                        срывов и лишних вопросов. Вы получаете именно тот результат, который ожидаете”
                    </blockquote>
                    <figcaption>
                        <img
                          class="process-quote__avatar"
                          src="<?php echo esc_url(
                              get_template_directory_uri() .
                                  "/assets/images/eug.png",
                          ); ?>"
                          alt="Фон первого экрана"
                        >
                        Турков Евгений Жанович
                    </figcaption>
                </figure>
            </div>
        </div>
        <img
          class="process-block__img_1"
          src="<?php echo esc_url(
              get_template_directory_uri() . "/assets/images/cars_1.png",
          ); ?>"
          alt="Фон первого экрана"
        >
        <img
          class="process-block__img_2"
          src="<?php echo esc_url(
              get_template_directory_uri() . "/assets/images/cars_2.png",
          ); ?>"
          alt="Фон первого экрана"
        >
        <img
          class="process-block__img_mobile"
          src="<?php echo esc_url(
              get_template_directory_uri() . "/assets/images/cars_bottom.png",
          ); ?>"
          alt=""
          aria-hidden="true"
        >
    </section>

    <?php
    $support_steps = [
        [
            "title" => "Этап 0: Консультация и проверка документов",
            "items" => [
                "Персональный менеджер ответит на все вопросы",
                "Пакет документов: паспорт, договор купли-продажи, таможенные документы, свидетельство о регистрации и др.",
            ],
            "button" => "Оставить заявку",
        ],
        [
            "title" =>
                "Этап 1: Записываем на комфортную дату в удобную по локации лабораторию",
            "items" => [
                "Возможна запись в день обращения",
                "Проводится осмотр",
                "До получаса",
            ],
            "link" => "Проверить возможность пройти лабораторию сегодня",
        ],
        [
            "title" =>
                "Этап 2: Утверждение макета и регистрация электронных документов",
            "items" => [
                "Дистанционно утверждаем макет будущих документов",
                "Далее СБКТС и ЭПТС уже подгружаются в системе",
                "Оплата лаборатории только на данном этапе",
            ],
        ],
        [
            "title" => "Этап 3: Постановка автомобиля на учет",
            "items" => [
                "Наш брокер на таможне проводит всю процедуру",
                "В среднем до 1 рабочего дня",
            ],
        ],
    ];

    $support_notes = [
        "До двух дней занимает процедура полной подготовки пакета документов для постановки вашего транспортного средства на учет",
        "Ваша задача - связаться с нами и приехать в лабораторию к назначенному времени, всё остальное мы берем на себя",
    ];

    $support_pins = ["pin_1.png", "pin_2.png"];

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
    <section class="support-track" id="check">
        <div class="support-track__inner">
            <h2 class="support-track__title">
                Мы <strong>сопровождаем</strong><br>
                вас на <strong>всех этапах</strong>
            </h2>

            <div class="support-track__steps">
                <?php foreach (array_slice($support_steps, 0, 3) as $step): ?>
                    <article class="support-card">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M0.366117 12.3661C-0.122039 12.8543 -0.122039 13.6457 0.366117 14.1339C0.854272 14.622 1.64573 14.622 2.13388 14.1339L1.25 13.25L0.366117 12.3661ZM14.5 1.25C14.5 0.559644 13.9404 9.528e-08 13.25 9.528e-08L2 -5.7907e-07C1.30964 -5.7907e-07 0.750001 0.559643 0.750001 1.25C0.75 1.94036 1.30964 2.5 2 2.5H12V12.5C12 13.1904 12.5596 13.75 13.25 13.75C13.9404 13.75 14.5 13.1904 14.5 12.5L14.5 1.25ZM1.25 13.25L2.13388 14.1339L14.1339 2.13388L13.25 1.25L12.3661 0.366116L0.366117 12.3661L1.25 13.25Z" fill="#B2B2B2" />
                        </svg>
                        <h3 class="support-card__title"><?php echo esc_html(
                            cars_nbsp_short_words($step["title"]),
                        ); ?></h3>
                        <ul class="support-card__list">
                            <?php foreach ($step["items"] as $item): ?>
                                <li>
                                    <span class="support-card__bullet" aria-hidden="true"></span>
                                    <span><?php echo esc_html(
                                        cars_nbsp_short_words($item),
                                    ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (!empty($step["button"])): ?>
                            <a class="support-card__button" href="#contacts" data-open-modal="contact"><?php echo esc_html(
                                cars_nbsp_short_words($step["button"]),
                            ); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($step["link"])): ?>
                            <a class="support-card__link" href="#contacts" data-open-modal="contact"><?php echo esc_html(
                                cars_nbsp_short_words($step["link"]),
                            ); ?></a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>

                <article class="support-card support-card--compact support-card--mobile-only">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.366117 12.3661C-0.122039 12.8543 -0.122039 13.6457 0.366117 14.1339C0.854272 14.622 1.64573 14.622 2.13388 14.1339L1.25 13.25L0.366117 12.3661ZM14.5 1.25C14.5 0.559644 13.9404 9.528e-08 13.25 9.528e-08L2 -5.7907e-07C1.30964 -5.7907e-07 0.750001 0.559643 0.750001 1.25C0.75 1.94036 1.30964 2.5 2 2.5H12V12.5C12 13.1904 12.5596 13.75 13.25 13.75C13.9404 13.75 14.5 13.1904 14.5 12.5L14.5 1.25ZM1.25 13.25L2.13388 14.1339L14.1339 2.13388L13.25 1.25L12.3661 0.366116L0.366117 12.3661L1.25 13.25Z" fill="#B2B2B2" />
                    </svg>
                    <h3 class="support-card__title"><?php echo esc_html(
                        cars_nbsp_short_words($support_steps[3]["title"]),
                    ); ?></h3>
                    <ul class="support-card__list">
                        <?php foreach ($support_steps[3]["items"] as $item): ?>
                            <li>
                                <span class="support-card__bullet" aria-hidden="true"></span>
                                <span><?php echo esc_html(
                                    cars_nbsp_short_words($item),
                                ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            </div>

            <div class="support-track__lower">
                <article class="support-card support-card--compact">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.366117 12.3661C-0.122039 12.8543 -0.122039 13.6457 0.366117 14.1339C0.854272 14.622 1.64573 14.622 2.13388 14.1339L1.25 13.25L0.366117 12.3661ZM14.5 1.25C14.5 0.559644 13.9404 9.528e-08 13.25 9.528e-08L2 -5.7907e-07C1.30964 -5.7907e-07 0.750001 0.559643 0.750001 1.25C0.75 1.94036 1.30964 2.5 2 2.5H12V12.5C12 13.1904 12.5596 13.75 13.25 13.75C13.9404 13.75 14.5 13.1904 14.5 12.5L14.5 1.25ZM1.25 13.25L2.13388 14.1339L14.1339 2.13388L13.25 1.25L12.3661 0.366116L0.366117 12.3661L1.25 13.25Z" fill="#B2B2B2" />
                    </svg>
                    <h3 class="support-card__title"><?php echo esc_html(
                        cars_nbsp_short_words($support_steps[3]["title"]),
                    ); ?></h3>
                    <ul class="support-card__list">
                        <?php foreach ($support_steps[3]["items"] as $item): ?>
                            <li>
                                <span class="support-card__bullet" aria-hidden="true"></span>
                                <span><?php echo esc_html(
                                    cars_nbsp_short_words($item),
                                ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>

                <div class="support-track__notes">
                    <?php foreach ($support_notes as $index => $note): ?>
                        <?php $pin =
                            $support_pins[$index % count($support_pins)]; ?>
                        <article class="support-note">
                            <img
                              class="support-note__pin"
                              src="<?php echo esc_url(
                                  get_template_directory_uri() .
                                      "/assets/images/" .
                                      $pin,
                              ); ?>"
                              alt=""
                              aria-hidden="true"
                            >
                            <p><?php echo esc_html(
                                cars_nbsp_short_words($note),
                            ); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="trust-block" >
                <h2 class="trust-block__title">
                    Нас <strong>рекомендуют</strong><br>
                    и вот почему
                </h2>

                <div class="trust-block__grid">
                    <div class="trust-block__col">
                        <?php foreach (
                            array_slice($trust_items, 0, 4)
                            as $item
                        ): ?>
                            <article class="trust-item">
                                <span class="trust-item__thumb" aria-hidden="true">
                                    <?php if (!empty($item["image"])): ?>
                                        <img
                                          class="trust-item__image"
                                          src="<?php echo esc_url(
                                              get_template_directory_uri() .
                                                  $item["image"],
                                          ); ?>"
                                          alt=""
                                          aria-hidden="true"
                                        >
                                    <?php else: ?>
                                        <?php echo esc_html($item["badge"]); ?>
                                    <?php endif; ?>
                                </span>
                                <p><?php echo esc_html(
                                    cars_nbsp_short_words($item["title"]),
                                ); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="trust-block__col">
                        <?php foreach (
                            array_slice($trust_items, 4, 2)
                            as $item
                        ): ?>
                            <article class="trust-item">
                                <span class="trust-item__thumb" aria-hidden="true">
                                    <?php if (!empty($item["image"])): ?>
                                        <img
                                          class="trust-item__image"
                                          src="<?php echo esc_url(
                                              get_template_directory_uri() .
                                                  $item["image"],
                                          ); ?>"
                                          alt=""
                                          aria-hidden="true"
                                        >
                                    <?php else: ?>
                                        <?php echo esc_html($item["badge"]); ?>
                                    <?php endif; ?>
                                </span>
                                <p><?php echo esc_html(
                                    cars_nbsp_short_words($item["title"]),
                                ); ?></p>
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
        </div>
    </section>

    <?php get_template_part("template-parts/expert-quiz-section", null, [
        "form_id" => "home_expert",
        "redirect_to" => home_url("/#expert"),
        "quiz_form_id" => "expert_quiz",
        "quiz_redirect_to" => home_url("/#expert"),
    ]); ?>

    <?php $reliable_cards = [
        [
            "title" => "350+ автомобилей проходят через нас ежемесячно",
            "text" =>
                "Мы даем честную оценку вашей ситуации и всегда сообщаем, если в вашем случае процедура не подходит или имеет маленький шанс на реализацию",
            "link" => "Проверить возможность оформления",
            "image" => "/assets/images/Дублер/reliable_1.png",
        ],
        [
            "title" => "50% клиентов обращаются по рекомендации",
            "text" =>
                "Мы каждый день работаем над тем, чтобы оказывать вам качественный сервис",
            "link" => "Смотреть более 100 отзывов",
            "image" => "/assets/images/Дублер/reliable_2.png",
        ],
        [
            "title" => "90+ экспертов в команде",
            "text" => "Работают над вашими запросами ежедневно",
            "link" => "К команде",
            "image" => "/assets/images/Дублер/reliable_3.png",
        ],
    ]; ?>
    <section class="reliable-section">
        <div class="reliable-section__inner">
            <div class="reliable-section__top">
                <h2 class="reliable-section__title">
                    Если вы ищете <br class="pk"><strong>надежное <br class="mb">решение</strong>,<br> вы в правильном месте
                </h2>
                <p class="reliable-section__note">
                    Основатель компании Евгений Жанович Турков
                    <strong>лично отвечает за каждый процесс</strong>
                </p>
            </div>

            <div class="reliable-section__cards">
                <?php foreach ($reliable_cards as $card): ?>
                    <article class="reliable-card">
                        <div class="reliable-card__content">
                            <h3><?php echo esc_html($card["title"]); ?></h3>
                            <p><?php echo esc_html($card["text"]); ?></p>
                            <a href="#contacts" data-open-modal="contact">
                                <?php echo esc_html($card["link"]); ?>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683418 14.0976 1.31658 14.0976 1.70711 13.7071L1 13L0.292893 12.2929ZM14 0.999999C14 0.447714 13.5523 -8.61581e-07 13 -1.11446e-06L4 -3.13672e-07C3.44772 -6.50847e-07 3 0.447715 3 0.999999C3 1.55228 3.44772 2 4 2L12 2L12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10L14 0.999999ZM1 13L1.70711 13.7071L13.7071 1.70711L13 0.999999L12.2929 0.292893L0.292893 12.2929L1 13Z" fill="#EF1413" />
                                </svg>                            </a>
                        </div>
                        <div class="reliable-card__image-slot">
                            <img
                              src="<?php echo esc_url(
                                  get_template_directory_uri() . $card["image"],
                              ); ?>"
                              alt=""
                              aria-hidden="true"
                            >
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php get_template_part("template-parts/team-blog-section"); ?>

    <?php get_template_part("template-parts/faq-section"); ?>


</main>
<?php get_footer();
