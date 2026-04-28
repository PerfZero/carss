<?php
/**
 * Expert consultation, reviews and quiz block.
 *
 * @package cars
 */

$form_id = isset($args["form_id"])
    ? sanitize_key($args["form_id"])
    : "blog_expert";
$redirect_to = isset($args["redirect_to"])
    ? esc_url_raw($args["redirect_to"])
    : cars_get_current_page_url("expert");
$quiz_form_id = isset($args["quiz_form_id"])
    ? sanitize_key($args["quiz_form_id"])
    : "expert_quiz";
$quiz_redirect_to = isset($args["quiz_redirect_to"])
    ? esc_url_raw($args["quiz_redirect_to"])
    : $redirect_to;
$privacy_url = esc_url(cars_get_legal_page_url("privacy"));

$expert_steps = [
    [
        "icon" => "user",
        "text" => "10-15 минут консультации с менеджером",
    ],
    [
        "icon" => "check",
        "text" => "Подробный план действий",
    ],
    [
        "icon" => "search",
        "text" => "Изучение всех нюансов процедуры",
    ],
    [
        "icon" => "login",
        "text" => "Запись в лабораторию уже сегодня",
    ],
];

$expert_reviews = cars_get_reviews();
$review_placeholder = get_template_directory_uri() .
    "/assets/images/icon_pes.svg";

$quiz_steps = [
    [
        "key" => "origin",
        "question" => "Откуда ваш автомобиль?",
        "options" => [
            "Кыргызстан",
            "Казахстан",
            "Армения",
            "Республика Беларусь",
            "Иная страна",
        ],
    ],
    [
        "key" => "vehicle_type",
        "question" => "Какой тип вашего авто?",
        "options" => ["Электромобиль", "Гибридный авто", "Бензин/Дизель"],
    ],
    [
        "key" => "vehicle_age",
        "question" => "Какого года выпуска авто?",
        "options" => ["До 3-х лет", "От 3-х до 7 лет", "Старше 7 лет"],
    ],
    [
        "key" => "service",
        "question" => "Что нужно оформить?",
        "options" => ["СБКТС", "ЭПТС", "Растаможку", "Нужна консультация"],
    ],
];

$quiz_total_steps = count($quiz_steps) + 1;
$quiz_status = cars_get_request_form_status($quiz_form_id);
$quiz_start_step = $quiz_status ? $quiz_total_steps : 1;
?>
<section class="expert-section" id="expert">
    <div class="expert-section__inner">
        <div class="expert-section__anchor" id="contacts" aria-hidden="true"></div>
        <h2 class="expert-section__title">
            Получите <strong>подробный разбор</strong> вашей<br>
            ситуации с экспертом <strong>бесплатно</strong>
        </h2>

        <div class="expert-steps">
            <?php foreach ($expert_steps as $step): ?>
                <article class="expert-step">
                    <span class="expert-step__icon" aria-hidden="true">
                        <?php if ($step["icon"] === "user"): ?>
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 9C10.933 9 12.5 7.433 12.5 5.5C12.5 3.567 10.933 2 9 2C7.067 2 5.5 3.567 5.5 5.5C5.5 7.433 7.067 9 9 9ZM3 15.25C3 12.9028 5.6863 11 9 11C12.3137 11 15 12.9028 15 15.25V16H3V15.25Z" fill="currentColor"/>
                            </svg>
                        <?php elseif ($step["icon"] === "check"): ?>
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 2.5H12V1.75C12 1.33579 11.6642 1 11.25 1C10.8358 1 10.5 1.33579 10.5 1.75V2.5H7.5V1.75C7.5 1.33579 7.16421 1 6.75 1C6.33579 1 6 1.33579 6 1.75V2.5H4.5C3.67157 2.5 3 3.17157 3 4V14.5C3 15.3284 3.67157 16 4.5 16H13.5C14.3284 16 15 15.3284 15 14.5V4C15 3.17157 14.3284 2.5 13.5 2.5ZM7.875 12.25L5.75 10.125L6.81066 9.06434L7.875 10.1287L11.1893 6.81434L12.25 7.875L7.875 12.25Z" fill="currentColor"/>
                            </svg>
                        <?php elseif ($step["icon"] === "search"): ?>
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 3C5.23858 3 3 5.23858 3 8C3 10.7614 5.23858 13 8 13C9.0193 13 9.96733 12.695 10.7578 12.1713L13.293 14.7065L14.7072 13.2923L12.1718 10.7569C12.6953 9.96655 13 9.01903 13 8C13 5.23858 10.7614 3 8 3ZM5 8C5 6.34315 6.34315 5 8 5C9.65685 5 11 6.34315 11 8C11 9.65685 9.65685 11 8 11C6.34315 11 5 9.65685 5 8Z" fill="currentColor"/>
                            </svg>
                        <?php else: ?>
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5 3H14.25C14.6642 3 15 3.33579 15 3.75V14.25C15 14.6642 14.6642 15 14.25 15H10.5V13.5H13.5V4.5H10.5V3ZM8.03033 5.46967L11.0303 8.46967C11.3232 8.76256 11.3232 9.23744 11.0303 9.53033L8.03033 12.5303L6.96967 11.4697L8.68934 9.75H3V8.25H8.68934L6.96967 6.53033L8.03033 5.46967Z" fill="currentColor"/>
                            </svg>
                        <?php endif; ?>
                    </span>
                    <p><?php echo esc_html($step["text"]); ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="expert-section__consult">
            <div class="expert-section__form-wrap">
                <p class="expert-section__intro">Оставьте свои данные, наш менеджер свяжется с вами в течение 3 минут в рабочее время</p>
                <form class="contact-form expert-form" action="<?php echo esc_url(
                    cars_get_request_form_action_url(),
                ); ?>" method="post">
                    <?php cars_render_request_form_notice($form_id); ?>
                    <?php cars_render_request_form_hidden_fields(
                        $form_id,
                        $redirect_to,
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
                            <option value="telegram">Написать в Telegram</option>
                            <option value="max">Написать в MAX</option>
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
                <div class="expert-section__messengers">
                    <a href="#contacts" data-open-modal="contact">Написать в Telegram</a>
                    <a href="#contacts" data-open-modal="contact">Написать в MAX</a>
                </div>
            </div>
        </div>

        <div class="expert-reviews" id="reviews">
            <div class="expert-reviews__head">
                <h2>Что говорят <span>о ЭПТС-оператор</span></h2>
                <div class="expert-reviews__nav">
                    <button type="button" aria-label="Предыдущий отзыв">←</button>
                    <button type="button" aria-label="Следующий отзыв">→</button>
                </div>
            </div>
            <div class="expert-reviews__viewport">
                <div class="expert-reviews__list">
                    <?php foreach ($expert_reviews as $review): ?>
                        <article class="expert-review">
                            <div class="expert-review__top">
                                <img
                                  src="<?php echo esc_url(
                                      !empty($review["image_url"])
                                          ? $review["image_url"]
                                          : $review_placeholder,
                                  ); ?>"
                                  alt="<?php echo esc_attr($review["name"]); ?>"
                                >
                                <span aria-hidden="true">★★★★★</span>
                            </div>
                            <p><?php echo esc_html($review["text"]); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="expert-quiz">
            <form
              class="expert-quiz__card"
              action="<?php echo esc_url(
                  cars_get_request_form_action_url(),
              ); ?>"
              method="post"
              data-quiz-form
              data-start-step="<?php echo esc_attr($quiz_start_step); ?>"
            >
                <?php cars_render_request_form_hidden_fields(
                    $quiz_form_id,
                    $quiz_redirect_to,
                ); ?>
                <input type="hidden" name="contact_type" value="call">

                <h2>Ответьте на 5 вопросов<br>и <strong>узнайте</strong> стоимость</h2>

                <?php foreach ($quiz_steps as $index => $step): ?>
                    <?php
                    $step_number = $index + 1;
                    $is_active = $step_number === $quiz_start_step;
                    ?>
                    <section
                      class="expert-quiz__step <?php echo $is_active
                          ? "is-active"
                          : ""; ?>"
                      data-quiz-step
                      data-step-index="<?php echo esc_attr($step_number); ?>"
                      aria-hidden="<?php echo $is_active ? "false" : "true"; ?>"
                      <?php echo $is_active ? "" : "hidden"; ?>
                    >
                        <div class="expert-quiz__meta">
                            <p><?php echo esc_html($step["question"]); ?></p>
                            <span>Шаг <?php echo esc_html(
                                (string) $step_number,
                            ); ?>/<?php echo esc_html(
    (string) $quiz_total_steps,
); ?></span>
                        </div>

                        <div class="expert-quiz__options">
                            <?php foreach ($step["options"] as $option): ?>
                                <label class="expert-quiz__option">
                                    <input
                                      type="radio"
                                      name="quiz_<?php echo esc_attr(
                                          $step["key"],
                                      ); ?>"
                                      value="<?php echo esc_attr($option); ?>"
                                    >
                                    <span><?php echo esc_html(
                                        $option,
                                    ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="expert-quiz__actions <?php echo $step_number ===
                        1
                            ? "expert-quiz__actions--single"
                            : ""; ?>">
                            <?php if ($step_number > 1): ?>
                                <button class="expert-quiz__action expert-quiz__action--back" type="button" data-quiz-prev>Назад</button>
                            <?php endif; ?>
                            <button class="expert-quiz__action expert-quiz__action--next" type="button" data-quiz-next disabled>Далее</button>
                        </div>
                    </section>
                <?php endforeach; ?>

                <?php $is_contact_step =
                    $quiz_start_step === $quiz_total_steps; ?>
                <section
                  class="expert-quiz__step expert-quiz__step--contact <?php echo $is_contact_step
                      ? "is-active"
                      : ""; ?>"
                  data-quiz-step
                  data-step-index="<?php echo esc_attr($quiz_total_steps); ?>"
                  aria-hidden="<?php echo $is_contact_step
                      ? "false"
                      : "true"; ?>"
                  <?php echo $is_contact_step ? "" : "hidden"; ?>
                >
                    <div class="expert-quiz__meta">
                        <p>Как с вами связаться?</p>
                        <span>Шаг <?php echo esc_html(
                            (string) $quiz_total_steps,
                        ); ?>/<?php echo esc_html(
    (string) $quiz_total_steps,
); ?></span>
                    </div>

                    <?php cars_render_request_form_notice($quiz_form_id); ?>

                    <div class="expert-quiz__fields">
                        <label class="expert-quiz__field">
                            <span>Ваше имя</span>
                            <input type="text" name="name" placeholder="Евгений" autocomplete="name" aria-label="Ваше имя">
                        </label>

                        <label class="expert-quiz__field">
                            <span>Ваш телефон</span>
                            <input
                              type="tel"
                              name="phone"
                              placeholder="+7 (___) ___-__-__"
                              autocomplete="tel"
                              aria-label="Ваш телефон"
                              required
                            >
                        </label>
                    </div>

                    <label class="expert-quiz__consent">
                        <input type="checkbox" name="consent" required>
                        <span>
                            Отправляя форму, вы даете согласие на обработку
                            <strong><a href="<?php echo $privacy_url; ?>">персональных данных</a></strong>
                        </span>
                    </label>

                    <div class="expert-quiz__actions">
                        <button class="expert-quiz__action expert-quiz__action--back" type="button" data-quiz-prev>Назад</button>
                        <button class="expert-quiz__action expert-quiz__action--submit" type="submit">Узнать стоимость</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
</section>
