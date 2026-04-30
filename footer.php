<?php
/**
 * Theme footer.
 *
 * @package cars
 */
$services_url = esc_url(cars_services_page_url());
$offer_url = esc_url(cars_get_legal_page_url("offer"));
$privacy_url = esc_url(cars_get_legal_page_url("privacy"));
$modal_status = cars_get_request_form_status("modal_contact");
$footer_service_url = static function (array $titles) use ($services_url) {
    static $services = null;

    $normalize = static function ($value) {
        $value = wp_strip_all_tags((string) $value);
        $value = str_replace("\xc2\xa0", " ", $value);
        $value = preg_replace("/\s+/u", " ", $value);

        $value = trim((string) $value);

        return function_exists("mb_strtolower")
            ? mb_strtolower($value)
            : strtolower($value);
    };

    $wanted = array_map($normalize, $titles);
    if ($services === null) {
        $services = get_posts([
            "post_type" => "service",
            "post_status" => "publish",
            "numberposts" => -1,
        ]);
    }

    foreach ($services as $service) {
        if (in_array($normalize($service->post_title), $wanted, true)) {
            return esc_url(get_permalink($service));
        }
    }

    return $services_url;
};

$footer_services = [
    "pts" => [
        "label" => "Оформление СБКТС",
        "url" => $footer_service_url(["Оформление СБКТС", "СБКТС"]),
    ],
    "epts" => [
        "label" => "ЭПТС",
        "url" => $footer_service_url(["ЭПТС", "Оформление ЭПТС"]),
    ],
    "conversion" => [
        "label" => "Переоборудование ТС",
        "url" => $footer_service_url(["Переоборудование ТС"]),
    ],
    "epsm" => [
        "label" => "Оформление ЭПСМ",
        "url" => $footer_service_url(["Оформление ЭПСМ", "ЭПСМ"]),
    ],
    "customs" => [
        "label" => "Растаможка",
        "url" => $footer_service_url(["Растаможка", "Таможенное оформление"]),
    ],
    "recycling" => [
        "label" => "Утилизационный сбор",
        "url" => $footer_service_url(["Утилизационный сбор"]),
    ],
];
?>
<footer class="site-footer" id="footer-contacts">
    <div class="site-footer__inner">
        <div class="site-footer__top">
            <section class="site-footer__contacts" aria-labelledby="footer-contacts-title">
                <h2 id="footer-contacts-title">Контакты</h2>
                <dl>
                    <dt>Телефон:</dt>
                    <dd><a href="tel:+79169496622">+7 (916) 949-66-22</a></dd>
                    <dt>Время работы:</dt>
                    <dd>9:00-21:00</dd>
                    <dt>Офис</dt>
                    <dd>Россия, г. Новочеркасск,<br>Полевая ул., д. 6 кв.12</dd>
                </dl>
            </section>

            <nav class="site-footer__nav" aria-label="Услуги ПТС-оператор">
                <h2>Услуги ПТС-оператор</h2>
                <a href="<?php echo $footer_services["pts"]["url"]; ?>"><?php echo esc_html($footer_services["pts"]["label"]); ?></a>
                <a href="<?php echo $footer_services["epts"]["url"]; ?>"><?php echo esc_html($footer_services["epts"]["label"]); ?></a>
                <a href="<?php echo $footer_services["conversion"]["url"]; ?>"><?php echo esc_html($footer_services["conversion"]["label"]); ?></a>
                <a href="<?php echo $footer_services["epsm"]["url"]; ?>"><?php echo esc_html($footer_services["epsm"]["label"]); ?></a>
            </nav>

            <nav class="site-footer__nav" aria-label="Таможенные услуги">
                <h2>Таможенные услуги:</h2>
                <a href="<?php echo $footer_services["customs"]["url"]; ?>"><?php echo esc_html($footer_services["customs"]["label"]); ?></a>
                <a href="<?php echo $footer_services["recycling"]["url"]; ?>"><?php echo esc_html($footer_services["recycling"]["label"]); ?></a>
            </nav>

            <section class="site-footer__question" aria-labelledby="footer-question-title">
                <h2 id="footer-question-title">Задать вопрос</h2>
                <form class="footer-form" action="<?php echo esc_url(
                    cars_get_request_form_action_url(),
                ); ?>" method="post">
                    <?php cars_render_request_form_notice("footer_contact"); ?>
                    <?php cars_render_request_form_hidden_fields(
                        "footer_contact",
                        cars_get_current_page_url("footer-contacts"),
                    ); ?>
                    <label class="footer-form__field">
                        <input
                          type="tel"
                          name="phone"
                          placeholder="+7 (___) ___-__-__"
                          autocomplete="tel"
                          aria-label="Телефон"
                          required
                        >
                    </label>
                    <label class="footer-form__consent">
                        <input type="checkbox" name="consent" required>
                        <span>
                            Отправляя форму, вы <strong><a href="<?php echo $privacy_url; ?>">даете согласие<br>на обработку персональных данных</a></strong>
                        </span>
                    </label>
                    <button class="footer-form__submit" type="submit">Получить консультацию</button>
                </form>
            </section>
        </div>

        <div class="site-footer__bottom">
            <div class="site-footer__company">
                <p>ИП: ФАМИЛИЯ ИМЯ ОТЧЕСТВО</p>
                <p>ОГРН: 0000000000000</p>
                <p>ИНН: 000000000000</p>
            </div>
            <a class="site-footer__legal" href="<?php echo $privacy_url; ?>">Политика конфиденциальности</a>
            <a class="site-footer__legal" href="<?php echo $offer_url; ?>">Договор оферты</a>
            <div class="site-footer__socials">
                <a class="site-footer__social site-footer__social--telegram" href="#contacts" data-open-modal="contact" aria-label="Telegram">
                    <img
                      class="hero_img1"
                      src="<?php echo esc_url(
                          get_template_directory_uri() .
                              "/assets/images/telegram.png",
                      ); ?>"
                      alt="Фон первого экрана"
                    >
                </a>
                <a class="site-footer__social site-footer__social--max" href="#contacts" data-open-modal="contact" aria-label="MAX">
                    <img
                      class="hero_img1"
                      src="<?php echo esc_url(
                          get_template_directory_uri() .
                              "/assets/images/max.png",
                      ); ?>"
                      alt="Фон первого экрана"
                    >
                </a>
            </div>
        </div>
    </div>
</footer>
<div class="cars-modal <?php echo $modal_status ? "is-open" : ""; ?>" data-modal="contact" aria-hidden="<?php echo $modal_status
    ? "false"
    : "true"; ?>">
    <div class="cars-modal__backdrop" data-modal-close="contact"></div>
    <div class="cars-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="cars-modal-title">
        <button class="cars-modal__close" type="button" aria-label="Закрыть окно" data-modal-close="contact">×</button>
        <div class="cars-modal__content">
            <h2 class="cars-modal__title" id="cars-modal-title">
                Получите <strong>подробный разбор</strong> вашей<br>
                ситуации с экспертом <strong>бесплатно</strong>
            </h2>

            <form class="contact-form cars-modal__form" action="<?php echo esc_url(
                cars_get_request_form_action_url(),
            ); ?>" method="post">
                <?php cars_render_request_form_notice("modal_contact"); ?>
                <?php cars_render_request_form_hidden_fields(
                    "modal_contact",
                    cars_get_current_page_url(),
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

                <div class="contact-form__footer cars-modal__footer">
                    <div class="cars-modal__submit-group">
                        <button class="contact-form__submit" type="submit">Получить консультацию</button>
                        <label class="contact-form__consent">
                            <input type="checkbox" name="consent" required>
                            <span>
                                Отправляя форму, вы <strong><a href="<?php echo $privacy_url; ?>">даете согласие на обработку персональных данных</a></strong>
                            </span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>
