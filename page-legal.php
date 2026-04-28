<?php
/**
 * Legal pages template.
 *
 * @package cars
 */

get_header();

$legal_pages = cars_get_legal_pages();
?>
<main class="legal-page">
    <?php while (have_posts()):
        the_post(); ?>
        <div class="legal-page__breadcrumbs" aria-label="Хлебные крошки">
            <a href="<?php echo esc_url(home_url("/")); ?>">Главная</a>
            <span>/</span>
            <span><?php the_title(); ?></span>
        </div>

        <section class="legal-page__section">
            <div class="legal-page__inner">
                <div class="legal-page__layout">
                    <article class="legal-page__article">
                        <header class="legal-page__header">
                            <p class="legal-page__eyebrow">Документы</p>
                            <h1 class="legal-page__title"><?php the_title(); ?></h1>
                        </header>

                        <div class="legal-page__content">
                            <?php the_content(); ?>
                        </div>
                    </article>

                    <aside class="legal-page__aside" aria-label="Другие документы">
                        <h2>Другие документы</h2>
                        <nav class="legal-page__nav">
                            <?php foreach ($legal_pages as $key => $page): ?>
                                <?php
                                $is_current = get_post_field("post_name", get_the_ID()) ===
                                    $page["slug"];
                                ?>
                                <a
                                  class="legal-page__nav-link <?php echo $is_current
                                      ? "legal-page__nav-link--current"
                                      : ""; ?>"
                                  href="<?php echo esc_url(
                                      cars_get_legal_page_url($key),
                                  ); ?>"
                                  <?php echo $is_current
                                      ? 'aria-current="page"'
                                      : ""; ?>
                                >
                                    <?php echo esc_html($page["menu_title"]); ?>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </aside>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
