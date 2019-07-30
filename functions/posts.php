<?php
/*
============================================
  [[[ Post Support & Pagination Settings ]]]
============================================
*/

// Author & date/time meta
function ex_post_meta($timePre = 'Posted', $authorPre = 'by') {
 printf(__($timePre, 'exonym').' %1$s %2$s',
   /* the time the post was published */
   '<time class="entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
   /* the author of the post */
   '<span class="entry-byline">' . __($authorPre, 'exonym') . '</span> <span class="entry-author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
 );
}

// List out categories
function ex_categories($catPre = 'Filed under') {
 printf(__($catPre, 'exonym' ) . ': %1$s' , get_the_category_list(', '));
}

// List out tags
function ex_tags($tagsPre = 'Tags:') {
 the_tags(__($tagsPre, 'exonym' ) . '</span> ', ', ', '');
}

// List comment counts
function ex_comment_count(
  $noComms = '<span>No</span> Comments',
  $oneComm = '<span>One</span> Comment',
  $manyComms = '<span>%</span> Comments'
) {
	comments_number(__($noComms, 'exonym'), __($oneComm, 'exonym'), __($manyComms, 'exonym'));
}

// Post pagination
function ex_post_pagination(
  $pagesPre = 'Pages:',
  $pageNext = 'Next page',
  $pagePrev = 'Previous page'
) {
	$post_pagination_options = array(
		'before'           => '<nav class="nav-post">' . ($pagesPre),
		'after'            => '</nav>',
		'link_before'      => '',
		'link_after'       => '',
		'next_or_number'   => 'number',
		'separator'        => ' ',
		'nextpagelink'     => ($pageNext),
		'previouspagelink' => ($pagePrev),
		'pagelink'         => '%',
		'echo'             => 1
	);
	wp_link_pages($post_pagination_options);
}

// Integrate wp_page_navi
function ex_page_navi() {
 global $wp_query;
 $bignum = 999999999;
 if ($wp_query->max_num_pages <= 1)
   return;
 echo '<nav class="nav-pagination">';
 echo paginate_links(array(
   'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
   'format'       => '',
   'current'      => max( 1, get_query_var('paged') ),
   'total'        => $wp_query->max_num_pages,
   'prev_text'    => '&lt;',
   'next_text'    => '&gt;',
   'type'         => 'list',
   'end_size'     => 3,
   'mid_size'     => 3
 ) );
 echo '</nav>';
}

// Format for the Blog Navigation
function ex_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
	$next     = get_adjacent_post(false, '', false);

	if (!$next && ! $previous) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links">
			<?php
				previous_post_link('<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'exonym' ) );
				next_post_link('<div class="nav-next">%link</div>', _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link', 'exonym' ) );
			?>
		</div>
	</nav>
	<?php
}
