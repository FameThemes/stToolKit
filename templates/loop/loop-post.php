<?php
 global $post;
?>
<article <?php post_class(); ?>  id="post-<?php the_ID(); ?>">
        
        <header class="entry-header">
			<h2 class="entry-title">
				<a  title="<?php printf( esc_attr__( 'Permalink to %s', 'smooththemes' ), the_title_attribute( 'echo=0' ) ); ?>"  rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
		</header>

    <?php $thumb = st_post_thumbnail($post->ID,false, false); ?>
    <?php if($thumb!=''){ ?>
    <div class="post-thumbnail">
        <?php echo $thumb; ?>
    </div>
    <?php } ?>
        
        <div class="entry-excerpt">
			<?php the_excerpt(); ?>
		</div>
        
        <footer class="entry-meta">
			<div class="entry-meta-inner">
				<span class="post-author"><?php _e('Post by','smooththemes'); ?> <?php the_author_posts_link(); ?></span>
				<span class="post-charactor"> / </span>
				<span class="post-date"><?php the_time($date_format); ?></span>
				<span class="post-charactor"> / </span>
  				<span class="post-category"><?php the_category(', '); ?></span>
				<span class="post-charactor cha-category"> / </span>
				<span class="post-comments"><a title="<?php printf( esc_attr__( 'Permalink to %s', 'smooththemes' ), the_title_attribute( 'echo=0' ) ); ?>"  rel="bookmark" href="<?php echo get_comments_link(); ?>"><?php comments_number(__('0 Comment','smooththemes'),__('1 Comment','smooththemes'),__('% Comments','smooththemes') ); ?></a></span>
				<a title="<?php printf( esc_attr__( 'Permalink to %s', 'smooththemes' ), the_title_attribute( 'echo=0' ) ); ?>"  rel="bookmark" href="<?php echo $link; ?>" class="readmore"><?php _e('Read more','smooththemes'); ?></a>
			</div>
		</footer>
    <div class="clear"></div>   
</article>