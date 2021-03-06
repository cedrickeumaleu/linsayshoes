<?php
/**
 * eStore functions and definitions
 *
 * @package ThemeGrill
 * @subpackage eStore
 * @since eStore 1.0.1
 */

if ( ! function_exists( 'estore_entry_meta' ) ) :
	/**
	 * Display meta description of post.
	 */
	function estore_entry_meta() {
		if ( get_post_type() == 'post' && get_theme_mod( 'estore_postmeta', '' ) == '' ) { ?>
			<div class="entry-meta">
				<?php
				if ( get_theme_mod( 'estore_postmeta_author', '' ) == '' ) {
					?>
					<span class="byline author vcard"><i class="fa fa-user"></i><a
							href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
							title="<?php echo esc_attr( get_the_author() ); ?>"><?php echo esc_html( get_the_author() ); ?></a></span>
					<?php
				}

				if ( ! post_password_required() && comments_open() && get_theme_mod( 'estore_postmeta_comment', '' ) == '' ) {
					?>
					<span class="comments-link">
						<i class="fa fa-comments-o"></i>
						<?php
						comments_popup_link(
							esc_html__( '0 Comment', 'estore' ),
							esc_html__( '1 Comment', 'estore' ),
							esc_html__( ' % Comments', 'estore' )
						);
						?>
					</span>
					<?php
				}

				if ( has_category() && get_theme_mod( 'estore_postmeta_category', '' ) == '' ) {
					?>
					<span class="cat-links"><i class="fa fa-folder-open"></i><?php the_category( ', ' ); ?></span>
					<?php
				}

				$tags_list = get_the_tag_list( '<span class="tag-links">', ', ', '</span>' );
				if ( $tags_list && get_theme_mod( 'estore_postmeta_tags', '' ) == '' ) {
					echo $tags_list;
				}
				?>
			</div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'estore_layout_class' ) ) :
	/**
	 * Generate layout class for sidebar based on customizer and post meta settings.
	 */
	function estore_layout_class() {
		$layout = get_theme_mod( 'estore_global_layout', 'right_sidebar' );

		// Get Layout meta.
		$layout_meta = get_post_meta( estore_get_post_id(), 'estore_page_specific_layout', true );

		// For blog page while static front page.
		if ( is_home() && ! ( is_front_page() ) ) {
			if ( $layout_meta != 'default_layout' && $layout_meta != '' ) {
				$layout = $layout_meta;
			}
		} elseif ( is_page() ) {
			$layout = get_theme_mod( 'estore_default_page_layout', 'right_sidebar' );

			if ( $layout_meta != 'default_layout' && $layout_meta != '' ) {
				$layout = $layout_meta;
			}
		} elseif ( is_single() ) {
			$layout = get_theme_mod( 'estore_default_single_post_layout', 'right_sidebar' );

			if ( $layout_meta != 'default_layout' && $layout_meta != '' ) {
				$layout = $layout_meta;
			}
		}
		return $layout;
	}
endif;

if ( ! function_exists( 'estore_breadcrumbs' ) ) :
	/**
	 * Display Breadcrumbs
	 *
	 * This code is a modified version of Melissacabral's original menu code for dimox_breadcrumbs().
	 *
	 */
	function estore_breadcrumbs() {
		// Start of options.
		$text['home']     = esc_html__( 'Home', 'estore' ); // Text for the 'Home' link.
		$text['category'] = esc_html__( 'Archive by Category "%s"', 'estore' ); // Text for a category page.
		$text['tax']      = esc_html__( 'Archive for "%s"', 'estore' ); // Text for a taxonomy page.
		$text['search']   = esc_html__( 'Search Results for "%s" query', 'estore' ); // Text for a search results page.
		$text['tag']      = esc_html__( 'Posts Tagged "%s"', 'estore' ); // Text for a tag page.
		$text['author']   = esc_html__( 'Articles Posted by %s', 'estore' ); // Text for an author page.
		$text['404']      = esc_html__( 'Error 404', 'estore' ); // Text for the 404 page.
		$text['page']     = esc_html__( 'Page %s', 'estore' ); // Text 'Page N'.
		$text['cpage']    = esc_html__( 'Comment Page %s', 'estore' ); // Text 'Comment Page N'.

		$wrap_before = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // The opening wrapper tag.
		$wrap_after  = '</div><!-- .breadcrumbs -->'; // The closing wrapper tag.
		$sep         = '&nbsp;&frasl;&nbsp;'; // Separator between crumbs.
		$before      = '<span class="current">'; // Tag before the current crumb.
		$after       = '</span>'; // Tag after the current crumb.

		$show_on_home   = 1; // 1 - Show breadcrumbs on the homepage, 0 - don't show.
		$show_home_link = 1; // 1 - Show the 'Home' link, 0 - don't show.
		$show_current   = 1; // 1 - Show current page title, 0 - don't show.
		$show_last_sep  = 1; // 1 - Show last separator, when current page title is not displayed, 0 - don't show.
		// End of options.

		global $post;
		$home_url  = esc_url( home_url() ) . '/';
		$link      = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$link     .= '<a class="breadcrumbs-link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
		$link     .= '<meta itemprop="position" content="%3$s" />';
		$link     .= '</span>';
		$parent_id = ( $post ) ? $post->post_parent : '';
		$home_link = sprintf( $link, $home_url, $text['home'], 1 );

		if ( is_home() || is_front_page() ) {

			if ( 1 === $show_on_home ) {
				echo $wrap_before . $home_link . $wrap_after;
			}
		} else {
			$position = 0;

			echo $wrap_before;

			if ( $show_home_link ) {
				$position += 1;

				echo $home_link;
			}

			if ( is_category() ) {
				$parents = get_ancestors( get_query_var( 'cat' ), 'category' );

				foreach ( array_reverse( $parents ) as $cat ) {
					$position += 1;

					if ( $position > 1 ) {
						echo $sep;
					}

					echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}

				if ( get_query_var( 'paged' ) ) {
					$position += 1;

					$cat = get_query_var( 'cat' );

					echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );

					echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
				} else {

					if ( $show_current ) {

						if ( $position >= 1 ) {
							echo $sep;
						}

						echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
					} elseif ( $show_last_sep ) {
						echo $sep;
					}
				}
			} elseif ( is_search() ) {

				if ( get_query_var( 'paged' ) ) {
					$position += 1;

					if ( $show_home_link ) {
						echo $sep;
					}

					echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );

					echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
				} else {

					if ( $show_current ) {

						if ( $position >= 1 ) {
							echo $sep;
						}

						echo $before . sprintf( $text['search'], get_search_query() ) . $after;
					} elseif ( $show_last_sep ) {
						echo $sep;
					}
				}
			} elseif ( is_day() ) {

				if ( $show_home_link ) {
					echo $sep;
				}

				$position += 1;

				echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ), $position ) . $sep;

				$position += 1;

				echo sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ), $position );

				if ( $show_current ) {
					echo $sep . $before . get_the_time( 'd' ) . $after;
				} elseif ( $show_last_sep ) {
					echo $sep;
				}
			} elseif ( is_month() ) {

				if ( $show_home_link ) {
					echo $sep;
				}

				$position += 1;

				echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ), $position );

				if ( $show_current ) {
					echo $sep . $before . get_the_time( 'F' ) . $after;
				} elseif ( $show_last_sep ) {
					echo $sep;
				}
			} elseif ( is_year() ) {

				if ( $show_home_link && $show_current ) {
					echo $sep;
				}

				if ( $show_current ) {
					echo $before . get_the_time( 'Y' ) . $after;
				} elseif ( $show_home_link && $show_last_sep ) {
					echo $sep;
				}
			} elseif ( is_single() && ! is_attachment() ) {

				if ( get_post_type() != 'post' ) {
					$position += 1;

					$post_type = get_post_type_object( get_post_type() );

					if ( $position > 1 ) {
						echo $sep;
					}

					echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );

					if ( $show_current ) {
						echo $sep . $before . get_the_title() . $after;
					} elseif ( $show_last_sep ) {
						echo $sep;
					}
				} else {
					$cat       = get_the_category();
					$catID     = $cat[0]->cat_ID;
					$parents   = get_ancestors( $catID, 'category' );
					$parents   = array_reverse( $parents );
					$parents[] = $catID;

					foreach ( $parents as $cat ) {
						$position += 1;

						if ( $position > 1 ) {
							echo $sep;
						}

						echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
					}

					if ( get_query_var( 'cpage' ) ) {
						$position += 1;

						echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );

						echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
					} else {

						if ( $show_current ) {
							echo $sep . $before . get_the_title() . $after;
						} elseif ( $show_last_sep ) {
							echo $sep;
						}
					}
				}
			} elseif ( is_post_type_archive() ) {
				$post_type = get_post_type_object( get_post_type() );

				if ( get_query_var( 'paged' ) ) {
					$position += 1;

					if ( $position > 1 ) {
						echo $sep;
					}

					echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );

					echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
				} else {

					if ( $show_home_link && $show_current ) {
						echo $sep;
					}

					if ( $show_current ) {
						echo $before . $post_type->label . $after;
					} elseif ( $show_home_link && $show_last_sep ) {
						echo $sep;
					}
				}
			} elseif ( is_attachment() ) {
				$parent    = get_post( $parent_id );
				$cat       = get_the_category( $parent->ID );
				$catID     = $cat[0]->cat_ID;
				$parents   = get_ancestors( $catID, 'category' );
				$parents   = array_reverse( $parents );
				$parents[] = $catID;

				foreach ( $parents as $cat ) {
					$position += 1;

					if ( $position > 1 ) {
						echo $sep;
					}

					echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}

				$position += 1;

				echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );

				if ( $show_current ) {
					echo $sep . $before . get_the_title() . $after;
				} elseif ( $show_last_sep ) {
					echo $sep;
				}
			} elseif ( is_page() && ! $parent_id ) {

				if ( $show_home_link && $show_current ) {
					echo $sep;
				}

				if ( $show_current ) {
					echo $before . get_the_title() . $after;
				} elseif ( $show_home_link && $show_last_sep ) {
					echo $sep;
				}
			} elseif ( is_page() && $parent_id ) {
				$parents = get_post_ancestors( get_the_ID() );

				foreach ( array_reverse( $parents ) as $pageID ) {
					$position += 1;

					if ( $position > 1 ) {
						echo $sep;
					}

					echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
				}

				if ( $show_current ) {
					echo $sep . $before . get_the_title() . $after;
				} elseif ( $show_last_sep ) {
					echo $sep;
				}
			} elseif ( is_tag() ) {

				if ( get_query_var( 'paged' ) ) {
					$position += 1;

					$tagID = get_query_var( 'tag_id' );

					echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );

					echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
				} else {

					if ( $show_home_link && $show_current ) {
						echo $sep;
					}

					if ( $show_current ) {
						echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
					} elseif ( $show_home_link && $show_last_sep ) {
						echo $sep;
					}
				}
			} elseif ( is_author() ) {
				$author = get_userdata( get_query_var( 'author' ) );

				if ( get_query_var( 'paged' ) ) {
					$position += 1;

					echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );

					echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
				} else {

					if ( $show_home_link && $show_current ) {
						echo $sep;
					}

					if ( $show_current ) {
						echo $before . sprintf( $text['author'], $author->display_name ) . $after;
					} elseif ( $show_home_link && $show_last_sep ) {
						echo $sep;
					}
				}
			} elseif ( is_404() ) {

				if ( $show_home_link && $show_current ) {
					echo $sep;
				}

				if ( $show_current ) {
					echo $before . $text['404'] . $after;
				} elseif ( $show_last_sep ) {
					echo $sep;
				}
			} elseif ( has_post_format() && ! is_singular() ) {

				if ( $show_home_link && $show_current ) {
					echo $sep;
				}

				echo get_post_format_string( get_post_format() );
			}

			echo $wrap_after;

		}
	} // End estore_breadcrumbs().
endif;

if ( ! function_exists( 'estore_sidebar_select' ) ) :
	/**
	 * Select and show sidebar based on post meta and customizer default settings
	 */
	function estore_sidebar_select() {

		$layout = estore_layout_class();

		if ( $layout != 'no_sidebar_full_width' && $layout != 'no_sidebar_content_centered' ) {

			if ( $layout == 'right_sidebar' ) {
				get_sidebar();
			} else {
				get_sidebar( 'left' );
			}
		}
	}
endif;

if ( ! function_exists( 'estore_category_color_css' ) ) :
	/**
	 * Generate color for Category and print on head
	 */
	function estore_category_color_css() {

		$categories = get_terms( 'category', array( 'hide_empty' => false ) );

		$cat_color_css = '';
		foreach ( $categories as $category ) {
			$cat_color   = get_theme_mod( 'estore_category_color_' . strtolower( $category->name ) );
			$hover_color = estore_darkcolor( $cat_color, -200 );
			$cat_id      = $category->term_id;
			if ( ! empty( $cat_color ) ) {
				$cat_color_css .= '

			/* Border Color */
			.estore-cat-color_' . $cat_id . ' .cart-wishlist-btn a i, .estore-cat-color_' . $cat_id . ' .cart-wishlist-btn a i:hover, .estore-cat-color_' . $cat_id . ' .hot-product-content-wrapper .hot-img{border-color: ' . $cat_color . '}
			/* Background Color */
			.estore-cat-color_' . $cat_id . ' .cart-wishlist-btn a i:hover, .estore-cat-color_' . $cat_id . ' .hot-content-wrapper .single_add_to_wishlist, .estore-cat-color_' . $cat_id . ' .hot-content-wrapper .single_add_to_wishlist:hover, .estore-cat-color_' . $cat_id . ' .hot-product-title, .widget-collection .estore-cat-color_' . $cat_id . ' .page-title::after, .estore-cat-color_' . $cat_id . ' .widget-featured-collection .page-title:after{background: ' . $cat_color . '}
			/* Color */
			.estore-cat-color_' . $cat_id . ' .sorting-form-wrapper a,.estore-cat-color_' . $cat_id . ' .section-title-wrapper .section-title-block .page-title a:hover, .estore-cat-color_' . $cat_id . ' .hot-content-wrapper .star-rating, .estore-cat-color_' . $cat_id . ' .hot-content-wrapper .hot-title a:hover, .estore-cat-color_' . $cat_id . ' .cart-wishlist-btn a i, .estore-cat-color_' . $cat_id . ' .product-list-wrap .product-list-block .product-list-content .price ins, .estore-cat-color_' . $cat_id . ' .product-list-wrap .product-list-block .product-list-content .product-list-title a:hover, .estore-cat-color_' . $cat_id . ' .hot-product-content-wrapper .hot-img .cart-price-wrapper .add_to_cart_button:hover{color:' . $cat_color . '}
			';
			}
		}

		if ( ! empty( $cat_color_css ) ) {
			?>
		<!-- Category Color --><style type="text/css"><?php echo $cat_color_css; ?></style>
			<?php
		}
	}
endif;

add_action( 'wp_head', 'estore_category_color_css', 20 );

if ( ! function_exists( 'estore_darkcolor' ) ) :
	/**
	 * Generate darker color
	 * Source: http://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php
	 */
	function estore_darkcolor( $hex, $steps ) {
		// Steps should be between -255 and 255. Negative = darker, positive = lighter.
		$steps = max( -255, min( 255, $steps ) );

		// Normalize into a six character long hex string.
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Split into three parts: R, G and B.
		$color_parts = str_split( $hex, 2 );
		$return      = '#';

		foreach ( $color_parts as $color ) {
			$color   = hexdec( $color ); // Convert to decimal.
			$color   = max( 0, min( 255, $color + $steps ) ); // Adjust color.
			$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code.
		}

		return $return;
	}
endif;

add_action( 'wp_head', 'estore_primary_color_css', 10 );

if ( ! function_exists( 'estore_primary_color_css' ) ) :
	/**
	 * Hooks the Custom Internal CSS to head section
	 */
	function estore_primary_color_css() {

		$primary_color = get_theme_mod( 'estore_primary_color', '#00a9e0' );
		$primary_dark  = estore_darkcolor( $primary_color, -20 );

		$estore_internal_css = '';
		if ( $primary_color != '#00a9e0' ) {
			$estore_internal_css = '
		.navigation .nav-links a:hover,
		.bttn:hover,
		button,
		input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		.widget_tag_cloud a:hover,
		.right-top-header .top-header-menu-wrapper ul li a:hover,
		.right-header-block a:hover,
		#lang_sel_click a.lang_sel_sel:hover,
		.wcmenucart-contents,
		.category-menu:hover,
		.category-menu .category-toggle.active,
		.widget_shopping_cart .button:hover,
		.woocommerce .widget_shopping_cart_content .buttons a.button:hover,
		.search-user-block:hover,
		.slider-caption-wrapper .slider-btn,
		.slider-caption-wrapper .slider-btn:hover i,
		.widget-collection .page-title:after,
		.widget-featured-collection .page-title:after,
		.featured-slider li .featured-img .featured-hover-wrapper .featured-hover-block a:hover,
		.widget-featured-collection .bx-controls .bx-prev:hover,
		.widget-featured-collection .bx-controls .bx-next:hover,
		.featured-slider li .single_add_to_wishlist,
		.widget_featured_posts_block .entry-thumbnail .posted-on:hover,
		.product-collection .page-title:after,
		.men-collection-color .page-title:after,
		.hot-product-title,
		.hot-content-wrapper .single_add_to_wishlist,
		.widget-collection .cart-wishlist-btn a.added_to_cart:hover:after,
		.entry-thumbnail .posted-on:hover,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .add_to_wishlist.button.alt,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a,
		.single-product.woocommerce-page .product .cart .single_add_to_cart_button,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .add_to_wishlist.button.alt,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .single_add_to_cart_button,
		.woocommerce.widget_price_filter .price_slider_wrapper .ui-widget-content .ui-slider-range,
		.woocommerce.widget_price_filter .price_slider_wrapper .ui-widget-content .ui-slider-handle,
		.woocommerce-cart .woocommerce table.shop_table.cart tr.cart_item td.product-remove a,
		.woocommerce-cart .woocommerce table.shop_table.cart tr td.actions input[type="submit"],
		.woocommerce .cart-collaterals .cart_totals .shop_table td button,
		.woocommerce ul.products li.product .add_to_cart_button,
		.return-to-shop a.button,
		.woocommerce #content .wishlist_table tbody tr td.product-remove a.remove_from_wishlist,
		.woocommerce #content .wishlist_table tbody tr td.product-add-to-cart a,
		.woocommerce #respond input#submit,
		.woocommerce a.button,
		.woocommerce button.button,
		.woocommerce input.button,
		.woocommerce #respond input#submit.alt,
		.woocommerce a.button.alt,
		.woocommerce button.button.alt,
		.woocommerce input.button.alt,
		.sub-toggle,
		.scrollup  {
			background: ' . $primary_color . ';
		}

		a,
		.widget_archive a:hover::before,
		.widget_categories a:hover:before,
		.widget_pages a:hover:before,
		.widget_meta a:hover:before,
		.widget_recent_comments a:hover:before,
		.widget_recent_entries a:hover:before,
		.widget_rss a:hover:before,
		.widget_nav_menu a:hover:before,
		.widget_product_categories li a:hover:before,
		.widget_archive li a:hover,
		.widget_categories li a:hover,
		.widget_pages li a:hover,
		.widget_meta li a:hover,
		.widget_recent_comments li a:hover,
		.widget_recent_entries li a:hover,
		.widget_rss li a:hover,
		.widget_nav_menu li a:hover,
		.widget_tag_cloud a:hover,
		.widget_product_categories a:hover,
		.wcmenucart-contents .cart-value,
		#site-navigation ul li:hover > a,
		#site-navigation ul li.current-menu-item > a,
		#site-navigation ul li:hover > a:after,
		.slider-caption-wrapper .slider-title a:hover,
		.widget_vertical_promo .slider-title a:hover,
		.hot-content-wrapper .star-rating,
		.product-list-wrap .product-list-block .product-list-content .price ins,
		.widget-collection .cart-wishlist-btn a i,
		.widget-collection .cart-wishlist-btn a.added_to_cart:after,
		.widget-about .tg-container .about-content-wrapper .about-block .about-sub-title,
		.featured-slider li .featured-title a,
		.featured-slider li .woocommerce-product-rating .star-rating,
		.featured-slider li .price ins,
		.page-header .entry-title,
		.entry-title a:hover,
		.entry-btn .btn:hover,
		.entry-meta a:hover,
		.woocommerce-page ul.products li.product .star-rating,
		.woocommerce-page ul.products li.product .price ins,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .feedback,
		.single-product.woocommerce-page .product .summary .price,
		.single-product.woocommerce-page .product .woocommerce-product-rating .star-rating,
		.widget.woocommerce .star-rating,
		.cart-empty,
		.woocommerce .woocommerce-info:before,
		.woocommerce .woocommerce-error:before,
		.woocommerce .woocommerce-message:before,
		.toggle-wrap:hover i,
		#cancel-comment-reply-link,
		#cancel-comment-reply-link:before,
		.logged-in-as a {
			color: ' . $primary_color . ';
		}

		.widget-title span,
		#lang_sel_click ul ul,
		.wcmenucart-contents .cart-value,
		#category-navigation,
		#category-navigation ul.sub-menu,
		#masthead .widget_shopping_cart,
		.widget_shopping_cart .button:hover,
		.woocommerce .widget_shopping_cart_content .buttons a.button:hover,
		#site-navigation .sub-menu,
		.search-wrapper .header-search-box,
		.hot-product-content-wrapper .hot-img,
		.widget-collection .cart-wishlist-btn a i,
		.widget-collection .cart-wishlist-btn a.added_to_cart:after,
		.featured-slider li .featured-img .featured-hover-wrapper .featured-hover-block a:hover,
		.widget-featured-collection .bx-controls .bx-prev:hover,
		.widget-featured-collection .bx-controls .bx-next:hover,
		.single-product.woocommerce-page .product .images .thumbnails a,
		.woocommerce .woocommerce-info,
		.woocommerce .woocommerce-error,
		.woocommerce .woocommerce-message,
		.menu-primary-container,
		.comment-list .comment-body{
			border-color: ' . $primary_color . ';
		}

		.search-wrapper .header-search-box:before,
		#masthead .widget_shopping_cart::before{
			border-bottom-color:' . $primary_color . ';
		}

		.big-slider .bx-controls .bx-prev:hover,
		.category-slider .bx-controls .bx-prev:hover{
			border-left-color:' . $primary_color . ';
		}

		.big-slider .bx-controls .bx-next:hover,
		.category-slider .bx-controls .bx-next:hover{
			border-right-color:' . $primary_color . ';
		}

		#primary-menu{
			border-top-color:' . $primary_color . ';
		}

		a:hover,
		a:focus,
		a:active,
		#category-navigation ul li:hover > a,
		.section-title-wrapper .section-title-block .page-title a:hover,
		.view-all a:hover,
		.men-collection-color .section-title-wrapper .section-title-block .page-title a:hover,
		.hot-product-content-wrapper .hot-img .cart-price-wrapper .add_to_cart_button:hover,
		.hot-product-content-wrapper .hot-img .cart-price-wrapper .added_to_cart:hover,
		.hot-content-wrapper .hot-title a:hover,
		.product-list-wrap .product-list-block .product-list-content .product-list-title a:hover,
		.page-header .entry-sub-title span a:hover,
		.featured-slider li .featured-title a:hover,
		.woocommerce-page ul.products li.product .products-title a:hover,
		.woocommerce .widget_layered_nav_filters ul li a:hover,
		.woocommerce-cart .woocommerce table.shop_table.cart tr.cart_item td.product-name a:hover,
		.woocommerce .widget_layered_nav_filters ul li a:hover,
		.woocommerce-cart .woocommerce table.shop_table.cart tr.cart_item td.product-name a:hover,
		.woocommerce #content .wishlist_table tbody tr td.product-name a:hover,
		.comment-author .fn .url:hover    {
			color: ' . $primary_dark . '
		}

		.hot-content-wrapper .single_add_to_wishlist:hover,
		.widget-collection .cart-wishlist-btn a i:hover,
		.woocommerce-page ul.products li.product .products-img .products-hover-wrapper .products-hover-block a:hover,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .add_to_wishlist.button.alt:hover,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover,
		.woocommerce-page ul.products li.product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
		.single-product.woocommerce-page .product .cart .single_add_to_cart_button:hover,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .add_to_wishlist.button.alt:hover,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
		.single-product.woocommerce-page .product .yith-wcwl-add-to-wishlist .single_add_to_cart_button:hover,
		.woocommerce-cart .woocommerce table.shop_table.cart tr.cart_item td.product-remove a:hover,
		.woocommerce-cart .woocommerce table.shop_table.cart tr td.actions input[type="submit"]:hover,
		.woocommerce-cart .woocommerce table.shop_table.cart tr.cart_item td.product-remove a:hover,
		.woocommerce-cart .woocommerce table.shop_table.cart tr td.actions input[type="submit"]:hover,
		.woocommerce .cart-collaterals .cart_totals .shop_table td button:hover,
		.woocommerce-cart .woocommerce .wc-proceed-to-checkout a.checkout-button:hover,
		.woocommerce ul.products li.product .add_to_cart_button:hover,
		.return-to-shop a.button:hover,
		.woocommerce #content .wishlist_table tbody tr td.product-remove a.remove_from_wishlist:hover,
		.woocommerce #content .wishlist_table tbody tr td.product-add-to-cart a:hover,
		.woocommerce #respond input#submit:hover,
		.woocommerce a.button:hover,
		.woocommerce button.button:hover,
		.woocommerce input.button:hover,
		.woocommerce #respond input#submit.alt:hover,
		.woocommerce a.button.alt:hover,
		.woocommerce button.button.alt:hover,
		.woocommerce input.button.alt:hover,
		.featured-slider li .single_add_to_wishlist:hover,
		.slider-caption-wrapper .slider-btn i,
		.slider-caption-wrapper .slider-btn:hover,
		.sub-toggle:hover,
		.scrollup:hover,
		.scrollup:active,
		.scrollup:focus {
			background: ' . $primary_dark . '
		}

		.widget-collection .cart-wishlist-btn a i:hover,
		.woocommerce-page ul.products li.product .products-img .products-hover-wrapper .products-hover-block a:hover{
			border-color: ' . $primary_dark . '
		}


		}';
		}

		if ( ! empty( $estore_internal_css ) ) {
			?>
		<style type="text/css"><?php echo $estore_internal_css; ?></style>
			<?php
		}

	}
endif;

add_action( 'wp_enqueue_scripts', 'estore_prettyphoto' );

if ( ! function_exists( 'estore_prettyphoto' ) ) {

	/**
	 * Enqueue prettyphoto on pages
	 */
	function estore_prettyphoto() {
		global $woocommerce;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( ( class_exists( 'woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ) || is_front_page() ) {
			wp_enqueue_script( 'prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'prettyPhoto-init', get_template_directory_uri() . '/js/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), null, true );
			wp_enqueue_style( 'woocommerce_prettyPhoto_css', get_template_directory_uri() . '/css/prettyPhoto.css' ); // Prettyphoto css prefixed with woocommerce to make sure it won't load twice
		}
	}
}

if ( ! function_exists( 'estore_the_custom_logo' ) ) {
	/**
	 * Displays the optional custom logo.
	 *   *
	 */
	function estore_the_custom_logo() {
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}
	}
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function estore_body_classes( $classes ) {
	// Adds a class for grid/list layout.
	$layout_class = esc_attr( get_theme_mod( 'estore_archive_page_style', 'archive-list' ) );
	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = $layout_class;
	}
	if ( is_home() ) {
		$classes[] = 'page-header-disable';
	}

	return $classes;
}
add_filter( 'body_class', 'estore_body_classes' );
