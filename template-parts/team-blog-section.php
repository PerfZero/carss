<?php
/**
 * Team and blog preview section.
 *
 * @package cars
 */

$blog_query = new WP_Query([
    "post_type" => "post",
    "post_status" => "publish",
    "posts_per_page" => 3,
    "ignore_sticky_posts" => true,
]);
?>
<section class="team-blog-section">
    <div class="team-blog-section__inner">
        <?php get_template_part("template-parts/team-showcase"); ?>

        <div class="blog-preview">
            <div class="blog-preview__head">
                <h2>Блог</h2>
                <div class="blog-preview__actions">
                    <a href="<?php echo esc_url(cars_blog_page_url()); ?>">
                        Перейти в раздел блог
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683418 14.0976 1.31658 14.0976 1.70711 13.7071L1 13L0.292893 12.2929ZM14 0.999999C14 0.447714 13.5523 -8.61581e-07 13 -1.11446e-06L4 -3.13672e-07C3.44772 -6.50847e-07 3 0.447715 3 0.999999C3 1.55228 3.44772 2 4 2L12 2L12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10L14 0.999999ZM1 13L1.70711 13.7071L13.7071 1.70711L13 0.999999L12.2929 0.292893L0.292893 12.2929L1 13Z" fill="#EF1413" />
                        </svg>
                    </a>
                    <div class="blog-preview__nav">
                        <button type="button" aria-label="Предыдущая статья">←</button>
                        <button type="button" aria-label="Следующая статья">→</button>
                    </div>
                </div>
            </div>
            <div class="blog-preview__viewport">
                <div class="blog-preview__grid">
                <?php if ($blog_query->have_posts()): ?>
                    <?php while ($blog_query->have_posts()):
                        $blog_query->the_post();
                        ?>
                        <article class="blog-card">
                            <a class="blog-card__preview-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail("large", [
                                        "class" => "blog-card__thumb",
                                        "loading" => "lazy",
                                    ]); ?>
                                <?php else: ?>
                                    <div class="blog-card__image" aria-hidden="true"></div>
                                <?php endif; ?>
                                <div class="blog-card__bottom">
                                    <h3><?php the_title(); ?></h3>
                                    <p>Время чтения<br><?php echo esc_html(
                                        cars_reading_time(get_the_ID()),
                                    ); ?></p>
                                </div>
                            </a>
                        </article>
                    <?php
                    endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <div class="blog-preview__empty">
                        <p>Первые статьи появятся здесь после публикации в блоге.</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
