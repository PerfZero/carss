<?php
/**
 * FAQ section.
 *
 * @package cars
 */

$faq_section = function_exists("cars_get_faq_section_data")
    ? cars_get_faq_section_data()
    : [];

if (empty($faq_section["columns"])) {
    return;
}
?>
<section class="faq-section">
    <div class="faq-section__inner">
        <h2 class="faq-section__title">Ответы на <strong>частые вопросы</strong></h2>
        <div class="faq-section__grid">
            <?php foreach ($faq_section["columns"] as $column): ?>
                <div class="faq-section__col">
                    <?php foreach ($column as $index => $item): ?>
                        <article class="faq-card <?php echo $index >= 5
                            ? "faq-card--extra"
                            : ""; ?>">
                            <div class="faq-card__head">
                                <h3><?php echo esc_html(
                                    cars_nbsp_short_words($item["question"]),
                                ); ?></h3>
                                <button type="button" aria-label="Открыть ответ" aria-expanded="false">
                                    <svg width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                      <path d="M14.4583 1.12435L7.79167 7.79102L1.125 1.12435" stroke="#F9F9F9" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="faq-card__answer"><?php echo wp_kses_post(
                                wpautop($item["answer"]),
                            ); ?></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($faq_section["has_extra"])): ?>
            <button class="faq-section__button" type="button" data-expanded-text="Скрыть все ответы" data-collapsed-text="Смотреть все ответы">Смотреть все ответы</button>
        <?php endif; ?>
    </div>
</section>
