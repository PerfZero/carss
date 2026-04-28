<?php
/**
 * Single blog post.
 *
 * @package cars
 */

get_header();
?>
<main class="blog-page blog-single">
    <?php while (have_posts()):
        the_post();
        $related_posts = new WP_Query([
            "post_type" => "post",
            "post_status" => "publish",
            "posts_per_page" => 3,
            "post__not_in" => [get_the_ID()],
            "ignore_sticky_posts" => true,
        ]);
        ?>
        <div class="blog-page__breadcrumbs" aria-label="Хлебные крошки">
            <a href="<?php echo esc_url(home_url("/")); ?>">Главная</a>
            <span>/</span>
            <a href="<?php echo esc_url(cars_blog_page_url()); ?>">Блог</a>
            <span>/</span>
            <span><?php the_title(); ?></span>
        </div>

        <article class="blog-single__article">
            <?php if (has_post_thumbnail()): ?>
                <figure class="blog-single__image">
                    <?php the_post_thumbnail("large"); ?>
                </figure>
            <?php endif; ?>

            <header class="blog-single__header">
                <h1><?php the_title(); ?></h1>
                <p>Время чтения<br><?php echo esc_html(cars_reading_time()); ?></p>
            </header>

            <div class="blog-single__content">
                <?php the_content(); ?>
            </div>
        </article>

        <?php if ($related_posts->have_posts()): ?>
            <section class="blog-single__related">
                <h2>Другие статьи:</h2>
                <div class="blog-single__related-grid">
                    <?php while ($related_posts->have_posts()):
                        $related_posts->the_post();
                        ?>
                        <article class="blog-card blog-card--related">
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
                            </a>
                        </article>
                    <?php
                    endwhile; ?>
                </div>
            </section>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <section class="support-track support-track--blog-recommendations">
            <div class="support-track__inner">
                <?php get_template_part("template-parts/recommendations"); ?>
            </div>
        </section>

        <?php get_template_part("template-parts/expert-quiz-section"); ?>
    <?php
    endwhile; ?>
</main>
<?php get_footer(); ?>
