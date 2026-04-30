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
$review_placeholder =
    get_template_directory_uri() . "/assets/images/icon_pes.svg";

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
        <h2 class="expert-section__title">
            Получите <strong>подробный разбор</strong> вашей<br>
            ситуации с экспертом <strong>бесплатно</strong>
        </h2>

        <div class="expert-steps">
            <?php foreach ($expert_steps as $step): ?>
                <article class="expert-step">
                    <span class="expert-step__icon" aria-hidden="true">
                        <?php if ($step["icon"] === "user"): ?>
                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M5.18821 6.34224C5.18821 5.27158 5.61353 4.24477 6.3706 3.4877C7.12767 2.73063 8.15448 2.30531 9.22514 2.30531C10.2958 2.30531 11.3226 2.73063 12.0797 3.4877C12.8368 4.24477 13.2621 5.27158 13.2621 6.34224C13.2621 6.49519 13.3228 6.64188 13.431 6.75003C13.5391 6.85818 13.6858 6.91894 13.8388 6.91894C13.9917 6.91894 14.1384 6.85818 14.2466 6.75003C14.3547 6.64188 14.4155 6.49519 14.4155 6.34224C14.4154 5.45707 14.189 4.58663 13.7577 3.81365C13.3263 3.04067 12.7045 2.39084 11.9513 1.92592C11.198 1.461 10.3384 1.19645 9.45411 1.1574C8.56981 1.11835 7.69021 1.30611 6.89892 1.70282C6.10763 2.09954 5.43095 2.69203 4.93319 3.42399C4.43543 4.15594 4.13314 5.00303 4.05504 5.88475C3.97694 6.76646 4.12564 7.6535 4.48699 8.46155C4.84835 9.2696 5.41035 9.97181 6.11959 10.5014C6.72777 10.9557 7.4282 11.2711 8.1715 11.4253C8.27476 11.6569 8.45145 11.848 8.67425 11.9691C8.89706 12.0902 9.15355 12.1344 9.40405 12.0951C9.65454 12.0557 9.88509 11.9349 10.06 11.7513C10.2349 11.5677 10.3445 11.3316 10.3717 11.0795C10.3989 10.8274 10.3423 10.5733 10.2106 10.3566C10.0789 10.14 9.87951 9.97269 9.64319 9.88074C9.40688 9.7888 9.14685 9.77729 8.90333 9.848C8.65982 9.9187 8.44639 10.0677 8.29607 10.2719C7.41117 10.0626 6.62284 9.56074 6.05878 8.84752C5.49472 8.13431 5.18796 7.25155 5.18821 6.34224ZM5.76491 6.34224C5.76514 5.70567 5.94097 5.08149 6.27304 4.5384C6.60512 3.99531 7.08057 3.55435 7.64709 3.26403C8.21361 2.97372 8.84923 2.84532 9.48402 2.89294C10.1188 2.94057 10.7282 3.16238 11.245 3.53397C11.7619 3.90556 12.1662 4.41253 12.4135 4.99909C12.6609 5.58566 12.7416 6.22908 12.6468 6.85856C12.5521 7.48804 12.2855 8.07918 11.8764 8.56693C11.4674 9.05469 10.9317 9.42015 10.3284 9.62311C10.0181 9.36628 9.62793 9.22575 9.22514 9.22576C8.80588 9.22576 8.42179 9.37513 8.12191 9.62311C7.43491 9.39201 6.83782 8.95101 6.41492 8.36234C5.99202 7.77367 5.76466 7.06707 5.76491 6.34224ZM9.22514 12.686C9.502 12.6861 9.77486 12.6198 10.0208 12.4927C10.2667 12.3655 10.4785 12.1811 10.6384 11.9551C10.7983 11.7291 10.9017 11.468 10.9397 11.1938C10.9778 10.9196 10.9495 10.6402 10.8572 10.3792H14.1271C14.6625 10.3792 15.1759 10.5918 15.5544 10.9704C15.9329 11.3489 16.1456 11.8623 16.1456 12.3976V12.686C16.1456 14.066 15.2673 15.2333 14.0204 16.0268C12.7667 16.825 11.0694 17.2996 9.22514 17.2996C7.38084 17.2996 5.68418 16.825 4.42984 16.0268C3.18301 15.2333 2.30469 14.066 2.30469 12.686V12.3976C2.30469 11.8623 2.51735 11.3489 2.89588 10.9704C3.27442 10.5918 3.78782 10.3792 4.32315 10.3792H5.10689C5.84447 11.1323 6.77435 11.6688 7.79549 11.9305C8.10691 12.3867 8.63171 12.686 9.22514 12.686Z" fill="#F9F9F9" />
                        </svg>
                        <?php elseif ($step["icon"] === "check"): ?>
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <mask id="mask0_3617_15618" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="15" height="15">
                            <path d="M0.636353 5.5625H14V13.2993C14 13.4859 13.9259 13.6648 13.794 13.7967C13.6621 13.9286 13.4832 14.0027 13.2966 14.0027H1.3397C1.15316 14.0027 0.974262 13.9286 0.842359 13.7967C0.710455 13.6648 0.636353 13.4859 0.636353 13.2993V5.5625Z" fill="white" stroke="white" stroke-width="1.27273" stroke-linejoin="round" />
                            <path d="M0.636353 2.39671C0.636353 2.21017 0.710455 2.03127 0.842359 1.89937C0.974262 1.76746 1.15316 1.69336 1.3397 1.69336H13.2966C13.4832 1.69336 13.6621 1.76746 13.794 1.89937C13.9259 2.03127 14 2.21017 14 2.39671V5.56178H0.636353V2.39671Z" stroke="white" stroke-width="1.27273" stroke-linejoin="round" />
                            <path d="M4.50745 9.77997L6.61749 11.89L10.8376 7.66992" stroke="black" stroke-width="1.27273" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4.50745 0.636719V3.45012M10.1342 0.636719V3.45012" stroke="white" stroke-width="1.27273" stroke-linecap="round" />
                          </mask>
                          <g mask="url(#mask0_3617_15618)">
                            <path d="M-1.12146 -1.12109H15.7589V15.7593H-1.12146V-1.12109Z" fill="#F9F9F9" />
                          </g>
                        </svg>
                        <?php elseif ($step["icon"] === "search"): ?>
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M1.58243 6.33333C1.58243 5.70955 1.70529 5.09188 1.944 4.51559C2.18271 3.93929 2.53259 3.41565 2.97367 2.97458C3.41475 2.5335 3.93839 2.18362 4.51468 1.94491C5.09098 1.7062 5.70865 1.58333 6.33243 1.58333C6.95621 1.58333 7.57388 1.7062 8.15017 1.94491C8.72647 2.18362 9.25011 2.5335 9.69118 2.97458C10.1323 3.41565 10.4821 3.93929 10.7209 4.51559C10.9596 5.09188 11.0824 5.70955 11.0824 6.33333C11.0824 7.59311 10.582 8.80129 9.69118 9.69209C8.80039 10.5829 7.59221 11.0833 6.33243 11.0833C5.07265 11.0833 3.86447 10.5829 2.97367 9.69209C2.08287 8.80129 1.58243 7.59311 1.58243 6.33333ZM6.33243 3.91212e-08C5.32453 0.000144342 4.33121 0.240839 3.43504 0.702081C2.53887 1.16332 1.76572 1.83179 1.17985 2.65193C0.593985 3.47207 0.212317 4.42019 0.0665683 5.4175C-0.0791801 6.41481 0.0152007 7.4325 0.341867 8.386C0.668533 9.33949 1.21805 10.2013 1.94475 10.8997C2.67145 11.5981 3.55434 12.113 4.52005 12.4015C5.48576 12.6901 6.50639 12.744 7.49713 12.5588C8.48787 12.3736 9.4201 11.9546 10.2163 11.3367L12.8977 14.018C13.047 14.1623 13.247 14.242 13.4546 14.2402C13.6622 14.2384 13.8607 14.1552 14.0075 14.0084C14.1543 13.8616 14.2375 13.6631 14.2393 13.4555C14.2411 13.2479 14.1613 13.0479 14.0171 12.8986L11.3358 10.2173C12.0626 9.28107 12.5121 8.15973 12.6333 6.98073C12.7544 5.80174 12.5423 4.6124 12.0211 3.54797C11.4998 2.48354 10.6904 1.58673 9.68473 0.95952C8.67909 0.332309 7.51763 -0.00013167 6.33243 3.91212e-08ZM6.33243 9.5C7.17228 9.5 7.97773 9.16637 8.5716 8.5725C9.16546 7.97864 9.49909 7.17319 9.49909 6.33333C9.49909 5.49348 9.16546 4.68803 8.5716 4.09416C7.97773 3.5003 7.17228 3.16667 6.33243 3.16667C5.49258 3.16667 4.68712 3.5003 4.09326 4.09416C3.49939 4.68803 3.16576 5.49348 3.16576 6.33333C3.16576 7.17319 3.49939 7.97864 4.09326 8.5725C4.68712 9.16637 5.49258 9.5 6.33243 9.5Z" fill="#F9F9F9" />
                        </svg>
                        <?php else: ?>
                        <svg width="15" height="12" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <g clip-path="url(#clip0_3617_15736)">
                            <path d="M6.12245 11.1C6.12245 11.125 6.12564 11.1875 6.13202 11.2875C6.13839 11.3875 6.13999 11.4703 6.1368 11.5359C6.13361 11.6016 6.12404 11.675 6.1081 11.7563C6.09216 11.8375 6.06027 11.8984 6.01244 11.9391C5.9646 11.9797 5.89923 12 5.81633 12H2.7551C1.99617 12 1.34726 11.7359 0.808355 11.2078C0.269452 10.6797 0 10.0438 0 9.3V2.7C0 1.95625 0.269452 1.32031 0.808355 0.792188C1.34726 0.264063 1.99617 0 2.7551 0H5.81633C5.89923 0 5.97098 0.0296875 6.03157 0.0890625C6.09216 0.148438 6.12245 0.21875 6.12245 0.3C6.12245 0.325 6.12564 0.3875 6.13202 0.4875C6.13839 0.5875 6.13999 0.670313 6.1368 0.735938C6.13361 0.801563 6.12404 0.875 6.1081 0.95625C6.09216 1.0375 6.06027 1.09844 6.01244 1.13906C5.9646 1.17969 5.89923 1.2 5.81633 1.2H2.7551C2.33418 1.2 1.97385 1.34688 1.67411 1.64063C1.37436 1.93438 1.22449 2.2875 1.22449 2.7V9.3C1.22449 9.7125 1.37436 10.0656 1.67411 10.3594C1.97385 10.6531 2.33418 10.8 2.7551 10.8H5.7398L5.84981 10.8094L5.95982 10.8375L6.03635 10.8891L6.10332 10.9734L6.12245 11.1ZM15 6C15 6.1625 14.9394 6.30313 14.8182 6.42188L9.61416 11.5219C9.49298 11.6406 9.34949 11.7 9.18367 11.7C9.01786 11.7 8.87436 11.6406 8.75319 11.5219C8.63202 11.4031 8.57143 11.2625 8.57143 11.1V8.4H4.28571C4.1199 8.4 3.9764 8.34063 3.85523 8.22188C3.73406 8.10313 3.67347 7.9625 3.67347 7.8V4.2C3.67347 4.0375 3.73406 3.89688 3.85523 3.77813C3.9764 3.65938 4.1199 3.6 4.28571 3.6H8.57143V0.9C8.57143 0.7375 8.63202 0.596875 8.75319 0.478125C8.87436 0.359375 9.01786 0.3 9.18367 0.3C9.34949 0.3 9.49298 0.359375 9.61416 0.478125L14.8182 5.57812C14.9394 5.69688 15 5.8375 15 6Z" fill="#F9F9F9" />
                          </g>
                          <defs>
                            <clipPath id="clip0_3617_15736">
                              <rect width="15" height="12" fill="white" />
                            </clipPath>
                          </defs>
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

        <div class="expert-reviews" id="expert-reviews">
            <div class="expert-reviews__head">
                <h2>Что говорят <span><br>о ЭПТС-оператор</span></h2>
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
