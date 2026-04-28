<?php
/**
 * Blog page.
 *
 * @package cars
 */

get_header();

$paged = max(1, (int) get_query_var("paged"));

$blog_query = new WP_Query([
    "post_type" => "post",
    "post_status" => "publish",
    "posts_per_page" => 10,
    "ignore_sticky_posts" => true,
    "paged" => $paged,
]);
?>
<main class="blog-page">
    <div class="blog-page__breadcrumbs" aria-label="Хлебные крошки">
        <a href="<?php echo esc_url(home_url("/")); ?>">Главная</a>
        <span>/</span>
        <span>Блог</span>
    </div>

    <section class="blog-page__section">
        <div class="blog-page__inner">
            <h1 class="blog-page__title">Блог</h1>

            <?php if ($blog_query->have_posts()): ?>
                <div class="blog-page__grid">
                    <?php while ($blog_query->have_posts()):
                        $blog_query->the_post();
                        ?>
                        <article class="blog-card blog-card--archive">
                            <a class="blog-card__archive-link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                <?php if (has_post_thumbnail()): ?>
                                    <?php the_post_thumbnail("large", [
                                        "class" => "blog-card__thumb",
                                        "loading" => "lazy",
                                    ]); ?>
                                <?php else: ?>
                                    <span class="blog-card__image" aria-hidden="true"></span>
                                <?php endif; ?>

                                <span class="blog-card__bottom">
                                    <span class="blog-card__title"><?php the_title(); ?></span>
                                    <span class="blog-card__time">Время чтения<br><?php echo esc_html(
                                        cars_reading_time(get_the_ID()),
                                    ); ?></span>
                                </span>

                                <span class="blog-card__excerpt"><?php echo esc_html(
                                    wp_trim_words(get_the_excerpt(), 24),
                                ); ?></span>

                                <span class="blog-card__more">
                                    Подробнее
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                      <path d="M11.1798 0.74992C11.1798 0.335706 10.844 -8.05526e-05 10.4298 -8.04051e-05L3.67977 -8.08265e-05C3.26555 -8.08265e-05 2.92977 0.335705 2.92977 0.749919C2.92977 1.16413 3.26555 1.49992 3.67977 1.49992H9.67977V7.49992C9.67977 7.91413 10.0156 8.24992 10.4298 8.24992C10.844 8.24992 11.1798 7.91413 11.1798 7.49992L11.1798 0.74992ZM0.530273 10.6494L1.0606 11.1797L10.9601 1.28025L10.4298 0.749919L9.89944 0.219589L-5.66393e-05 10.1191L0.530273 10.6494Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </a>
                        </article>
                    <?php
                    endwhile; ?>
                </div>

                <?php
                $pagination = paginate_links([
                    "base" =>
                        trailingslashit(cars_blog_page_url()) . "%_%",
                    "format" => "page/%#%/",
                    "current" => $paged,
                    "total" => (int) $blog_query->max_num_pages,
                    "prev_text" => "←",
                    "next_text" => "→",
                    "type" => "list",
                ]);
                ?>
                <?php if ($pagination): ?>
                    <nav class="blog-page__pagination" aria-label="Пагинация блога">
                        <?php echo wp_kses_post($pagination); ?>
                    </nav>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
            <?php else: ?>
                <div class="blog-page__empty">
                    <p>Пока в блоге нет опубликованных статей.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
