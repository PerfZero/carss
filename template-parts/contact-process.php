<?php
/**
 * Contact form block from the main page, without process steps.
 *
 * @package cars
 */
?>
<section class="contact-process contact-process--services" id="contacts">
    <div class="contact-process__inner">
        <div class="contact-process__form-col">
            <h2 class="contact-process__title">
                После оформления СБКТС и ЭПТС
                <strong>дарим сертификат на 100 тысяч рублей</strong> на детейлинг
            </h2>

            <form class="contact-form" action="<?php echo esc_url(
                cars_get_request_form_action_url(),
            ); ?>" method="post">
                <?php cars_render_request_form_notice("archive_contact"); ?>
                <?php cars_render_request_form_hidden_fields(
                    "archive_contact",
                    cars_get_current_page_url("contacts"),
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
      alt=""
      aria-hidden="true"
    >
</section>
