<?php
/**
 * Team showcase section.
 *
 * @package cars
 */

$team_members = function_exists("cars_get_team_members")
    ? cars_get_team_members()
    : [];

if (!$team_members) {
    return;
}
?>
<div class="team-showcase">
    <div class="team-showcase__head">
        <h2>Команда <strong>профессионалов</strong></h2>
        <div class="team-showcase__nav">
            <button type="button" aria-label="Предыдущий сотрудник">←</button>
            <button type="button" aria-label="Следующий сотрудник">→</button>
        </div>
    </div>
    <div class="team-showcase__viewport">
        <div class="team-showcase__list">
            <?php foreach ($team_members as $member): ?>
                <article class="team-card">
                    <div class="team-card__photo" aria-hidden="true">
                        <?php if (!empty($member["image_id"])): ?>
                            <?php echo wp_get_attachment_image(
                                $member["image_id"],
                                "large",
                                false,
                                [
                                    "class" => "team-card__image",
                                    "alt" => "",
                                    "loading" => "lazy",
                                ],
                            ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="team-card__body">
                        <h3><?php echo esc_html($member["name"]); ?></h3>
                        <?php if (!empty($member["role"])): ?>
                            <p><?php echo esc_html($member["role"]); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</div>
