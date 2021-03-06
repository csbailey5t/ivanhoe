<?php get_header(); ?>

<header>
    <h1><?php _e( 'Games', 'ivanhoe' ); ?></h1>
    <?php if ( is_user_logged_in() ) : ?>
    	<?php $url = add_query_arg(array('ivanhoe' => 'ivanhoe_game'), home_url()); ?>
            <?php echo ivanhoe_a($url, 'Make a Game', 'class="btn" id="make-a-game"'); ?>
    <?php endif; ?>
</header>

<?php
if ( have_posts()) : ?>
    <?php echo ivanhoe_paginate_links($wp_query);?>
    <?php while(have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
        <h2 class="game-title"><a class="game-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>
    </article>

<?php endwhile; ?>
<?php echo ivanhoe_paginate_links();?>
<?php else : ?>
<p><?php _e( 'Apologies, but no results were found.', 'ivanhoe' ); ?></p>
<?php endif; ?>

<?php get_footer();

