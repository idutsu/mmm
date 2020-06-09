<?php

/*

各種設定

*/
add_theme_support( 'post-thumbnails' );
add_theme_support( 'menus' );
remove_action( 'wp_head', 'wp_generator' );


/*

定数

*/
define('THEME_URL', get_template_directory_uri());
define('THEME_DIR', get_template_directory());


/*

ファイル読み込み

*/
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style( 'style', THEME_URL.'/style.css',array(),filemtime(THEME_DIR.'/style.css') );
	global $wp_scripts;
	$jquery = $wp_scripts->registered['jquery-core'];
	$jq_ver = $jquery->ver;
	$jq_src = $jquery->src;
	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery', false, array('jquery-core'), $jq_ver, true );
	wp_register_script( 'jquery-core', $jq_src, array(), $jq_ver, true );
	wp_enqueue_script( 'menu',THEME_URL.'/js/menu.js', array('jquery'), filemtime(THEME_DIR.'/js/menu.js'), true );
	wp_enqueue_script( 'anchorlink',THEME_URL.'/js/anchorlink.js', array('jquery'), filemtime(THEME_DIR.'/js/anchorlink.js'), true );
	wp_enqueue_script( 'intersection-observer',THEME_URL.'/js/intersection-observer.js', array(), filemtime(THEME_DIR.'/js/intersection-observer.js'), true );
	wp_enqueue_script( 'lazyload',THEME_URL.'/js/lazyload.js', array('intersection-observer'), filemtime(THEME_DIR.'/js/lazyload.js'), true );
	wp_enqueue_script( 'objectfit',THEME_URL.'/js/ofi.min.js', array(), filemtime(THEME_DIR.'/js/ofi.min.js'), true );
});


/*

メニュー

*/
class MMM_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        $current = in_array('current-menu-item', $item->classes) ? "class='current'" : "" ;
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n"."<li id='mmm-menu-".$item->ID."'".$current.">";
            $output .= "<a href='".$item->url."' class='mmm-submenu-hover'>".$item->title."<span class='mmm-submenu-click'></span></a>";
            $output .= "\n" . $indent . '<ul class="mmm-submenu">';
        } else {
            $output .= "\n"."<li id='mmm-menu-".$item->ID."'".$current.">";
            $output .= "<a href='".$item->url."'>".$item->title."</a>";
        }
    }
    function end_el( &$output, $item, $depth = 0, $args = Array() ) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $output .= "\n".'</li></ul></li>';
        }
        else {
            $output .= "\n".'</li>';
        }
    }
}

function mmm_menu( $menu_name ){
	if( wp_get_nav_menu_items( $menu_name ) ){
		wp_nav_menu( array(
			'menu'       => $menu_name,
			'container'  => '',
			'items_wrap' => ' <ul class="mmm-menu mmm-menu--'.$menu_name.'">%3$s</ul>',
			'walker'     => new MMM_Walker_Nav_Menu
		));
	}
}

$menu_exists = wp_get_nav_menu_object( 'global' );
if( ! $menu_exists ){
    wp_create_nav_menu('global');
}

$menu_exists = wp_get_nav_menu_object( 'sidebar' );
if( ! $menu_exists ){
    wp_create_nav_menu('sidebar');
}

$menu_exists = wp_get_nav_menu_object( 'footer' );
if( ! $menu_exists ){
    wp_create_nav_menu('footer');
}

/*

記事取得関連

*/
function mmm_limit_post_content( $content, $length ){
	if( !$content ) return false;
	$content = wp_strip_all_tags( $content );
	$content = str_replace(array("\r\n","\r","\n","&nbsp;","　"),'',$content);
	if( mb_strlen( $content ) <= $length ){
		return $content;
	}else{
		return mb_substr( $content, 0, $length ).'...';
	}
}

function mmm_get_the_terms( $post_id ){

	if( !$post_id ) return false;

	$data             = array();
	$data['category'] = array();
	$data['tag']      = array();

	if( $taxs = get_post_taxonomies( $post_id ) ){
		foreach( (array)$taxs as $tax ){
			if( $tax !== "post_format" ){
				$taxonomy = get_taxonomy( $tax );
				$taxonomy_type = $taxonomy->hierarchical ? 'category' : 'tag';
				if( $terms = get_the_terms( $post_id, $tax ) ){
					foreach( (array)$terms as $term ){
						$data[$taxonomy_type][$tax][$term->term_id] = array( 'name' => $term->name, 'slug'=> $term->slug, 'link' => esc_url( get_term_link( $term->term_id ) ), 'taxonomy' => $tax );
					}
				}
			}
		}
	}

	foreach( $data['category'] as $tax => $terms ){
		foreach( $terms as $term_id => $term_data ){
			$parent_terms = get_ancestors( $term_id, $tax );
			foreach( $parent_terms as $parent_term ){
				unset( $data['category'][$tax][$parent_term] );
			}
		}
	}

	return $data;

}


/*

パンくずリスト

*/
class MMM_Breadcrumb {

    function __construct(){
        $this->qo = get_queried_object();
        $this->breadcrumb = array();
        $this->add( 'HOME', home_url() );
    }

    public function post_type_archive(){

        if( !is_post_type_archive() ) return;

        $this->add( $this->qo->label );
        return $this->breadcrumb;
    }

    public function single(){

        if( !is_single() ) return;

        $post_id = $this->qo->ID;
        $post_type = $this->qo->post_type;
        $this->add_post_type( $post_type );

        $post_terms = mmm_get_the_terms( $post_id );
        if( count( $post_terms['category'] ) === 1 ){
            foreach( $post_terms['category'] as $taxonomy => $terms ){
                if( count( $terms ) === 1 ){
                    foreach( $terms as $term_id => $term ){
                        $parent_terms = get_ancestors( $term_id, $taxonomy );
                        foreach( $parent_terms as $parent_term ){
                            $term = get_term_by( 'id', $parent_term, $taxonomy );
                            $this->add( $term->name, get_term_link( $term->term_id ) );
                        }
                        $child_term = get_term_by( 'id', $term_id, $taxonomy );
                        $this->add( $child_term->name, esc_url( get_term_link( $child_term->term_id ) ) );
                    }
                }
            }
        }

        $this->add( $this->qo->post_title );

        return $this->breadcrumb;
    }

    public function tax(){

        if( !is_tax() && !is_category() && !is_tag() ) return;

        $taxonomy_name = $this->qo->taxonomy;
        $taxonomy = get_taxonomy( $taxonomy_name );
        $registrated_post_types = $taxonomy->object_type;
        if( count( $registrated_post_types ) === 1 ){
            $this->add_post_type( $registrated_post_types[0] );
        }
        if( $is_category = $taxonomy->hierarchical ? true : false ){
            $terms = $this->get_ancestors( $this->qo->term_id, $taxonomy_name );
            foreach( $terms as $term ){
                $term = get_term_by( 'id', $term, $taxonomy_name );
                $this->add( $term->name, esc_url( get_term_link( $term->term_id ) ) );
            }
        }
        $this->add( $this->qo->name );
        return $this->breadcrumb;
    }

    public function page(){

        if( !is_page() ) return;

        $pages = $this->get_ancestors( $this->qo->ID, 'page' );
        foreach( $pages as $page ){
            $page = get_post( $page );
            $this->add( $page->post_title, esc_url( get_the_permalink( $page ) ) );
        }
        $this->add($this->qo->post_title);
        return $this->breadcrumb;
    }

    public function search(){

        if( !is_search() ) return;

        $search_query = get_search_query();
        $this->add( "「".$search_query."」の検索結果" );
        return $this->breadcrumb;
    }

    public function notfound(){

        if( !is_404() ) return;

        $this->add( 'ページが見つかりませんでした' );
        return $this->breadcrumb;
    }

    private function add( $text = '', $link = '' ){
        array_push( $this->breadcrumb, array(
            'text' => $text,
            'link' => $link,
        ));
    }

    private function add_post_type( $post_type ){
        if( $post_type && $post_type !== 'post' ){
            $post_type_name = get_post_type_object( $post_type )->labels->singular_name;
            $this->add( $post_type_name, esc_url( get_post_type_archive_link( $post_type ) ) );
        }
    }

    private function get_ancestors( $term_id, $taxonomy_or_page ){
        $terms = get_ancestors( $term_id, $taxonomy_or_page );
        return array_reverse( $terms );
    }

}


/*

ダッシュボード

*/
class MMM_Dashboard{
    function __construct(){
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }
    public function admin_menu(){
        remove_menu_page( 'edit-comments.php' );
        //remove_menu_page( 'index.php' );
        //remove_menu_page( 'edit.php' );
        //remove_menu_page( 'upload.php' );
        //remove_menu_page( 'themes.php' );
        //remove_menu_page( 'plugins.php' );
        //remove_menu_page( 'tools.php' );
        //remove_menu_page( 'options-general.php' );
    }
}
$dashboard = new MMM_Dashboard();


/*

ページ送り

*/
function mmm_pagination( $query = null, $prev_text = null, $next_text = null ){
    global $wp_query;
    $current_query = $query ? $query : $wp_query ;
    $big = 999999999;
    $args =	array(
        'type' => 'array',
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'current' => max( 1, get_query_var('paged') ),
        'total' => $current_query->max_num_pages,
        'prev_text' => $prev_text ? $prev_text : "前のページ",
        'next_text' => $next_text ? $next_text : "次のページ",
    );
    $pager = paginate_links( $args );
    if( !$pager ) return;
    echo "<div class='mmm-pagination'>";
    echo "<ul>";
    foreach( $pager as $page ){
        if ( strpos( $page, 'next' ) != false ){
            echo "<li class='next'>" . $page . "</li>";
        } elseif ( strpos( $page, 'prev' ) != false ){
            echo "<li class='prev'>" . $page . "</li>";
        } elseif ( strpos( $page, 'current' ) != false ){
            echo "<li class='current'>" . $page . "</li>";
        } elseif ( strpos( $page, 'dots' ) != false ){
            echo "<li class='dots'>" . $page . "</li>";
        } else {
            echo "<li>" . $page . "</li>";
        }
    }
    echo "</ul>";
    echo "</div>";
}

add_filter( 'previous_post_link', function($output){
	return str_replace('<a href=', '<a class="prev" href=', $output);
});

add_filter( 'next_post_link', function($output){
	return str_replace('<a href=', '<a class="next" href=', $output);
});

function mmm_pagination_single(){
	echo "<div class='mmm-pagination-single'>";
	previous_post_link('%link','前のページ');
	next_post_link('%link','次のページ');
	echo "</div>";
}


/*

テンプレート読み込み

*/
class MMM_Template{
    private $template_dir;
    public function __construct( $template_dir ){
        $this->template_dir = THEME_DIR."/".$template_dir."/";
    }
    public function render( $name, $data = array() ){
        include $this->template_dir.$name.".php";
    }
}


/*

関連ページ

*/
class MMM_Walker_Page extends Walker_Page{
    function start_lvl( &$output, $depth=0, $args=array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul>\n";
    }
    function start_el( &$output, $page, $depth=0, $args=array(), $current_page=0 ){
		$indent = $depth ? str_repeat("\t", $depth) : '';
		$link_before = '';
		$link_after  = '';
        if ( !empty($current_page) && $current_page == $page->ID ) {
            $link_before = '<strong>';
            $link_after  = '</strong>';
        }
        $output .= $indent . '<li><a href="' . get_page_link($page->ID) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';
    }
}

function mmm_related_pages( $post_id ){

	if( !$post_id ) return false;

	if( $parents = get_ancestors( $post_id, 'page') ){
		$parents = array_reverse( $parents );
		$child_of = $parents[0];
		$child_of_post = get_post( $child_of );
		$title_li = esc_html( $child_of_post->post_title );
	}else{
		$child_of = $post_id;
		$title_li = esc_html( get_the_title( $child_of ) );
	}

	if( get_children( array('post_parent'=>$child_of,'post_type'=>'page') ) ){
		echo "<div class='mmm-related-posts'>";
		echo "<ul>";
		echo "<p class='mmm-related-posts-title'>「<a href='".esc_url(get_the_permalink($child_of))."'>".$title_li."</a>」の関連記事</li>";
		$walker = new MMM_Walker_Page();
		wp_list_pages( array('title_li' => '', 'child_of' => $child_of, 'walker' => $walker ) );
		echo "</ul>";
		echo "</div>";
	}

}


function mmm_related_posts( $post_id ){

	if( !$post_id ) return false;

	$terms = mmm_get_the_terms( $post_id );

	if( $terms_all = $terms['category'] + $terms['tag'] ){

		foreach( $terms_all as $terms ){

			foreach( $terms as $term ){

				$args = array(
					'post_type'   => get_post_type( $post_id ),
					'post_status' => 'publish',
					'exclude'     => $post_id,
					'numberposts' => -1,
					'tax_query'   => array(
						array(
							'taxonomy' => $term['taxonomy'],
							'field'    => 'slug',
							'terms'    => $term['slug'],
						)
					),
				);

				if( $posts = get_posts( $args ) ){
					echo '<div class="mmm-related-posts">';
					echo '<p class="mmm-related-posts-title">「'.$term['name'].'」の関連記事</p>';
					echo '<ul>';
					foreach( $posts as $post ){
						echo '<li><a href="'.esc_url( get_the_permalink( $post->ID ) ).'">'.esc_html( get_the_title( $post->ID ) ).'</a></li>';
					}
					echo '</ul>';
					echo '</div>';
				}

			}

		}

	}

}

/*

検索フォーム

*/

add_filter( 'get_search_form', function($form){
	$form = '<form role="search" method="get" id="mmm-searchform" class="mmm-searchform" action="' . home_url( '/' ) . '" >
	<input type="text" value="' . get_search_query() . '" name="s" id="s" />
	<input type="submit" value="検索" />
	</form>';
	return $form;
});

/*

ショートコード

*/

add_shortcode( 'themeurl', function(){
	return get_template_directory_uri();
});

add_shortcode( 'homeurl', function(){
	return home_url();
});
