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
define('CHILD_THEME_URL', get_stylesheet_directory_uri());
define('THEME_URL', get_template_directory_uri());
define('THEME_DIR', get_template_directory());

/*

ファイル読み込み

*/
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style( 'style', THEME_URL.'/style.css',array(),filemtime(THEME_DIR.'/style.css') );
	wp_enqueue_style( 'slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(),filemtime(THEME_DIR.'/style.css') );
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
	wp_enqueue_script( 'slick','//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), false, true );

	if(is_home() || is_front_page()){
		wp_enqueue_style( 'top-style', THEME_URL.'/css/top.css',array('style'),filemtime(THEME_DIR.'/css/top.css') );
	}

	if(is_single() || is_page()){
		wp_enqueue_style( 'single-page-style', THEME_URL.'/css/single.css',array('style'),filemtime(THEME_DIR.'/css/single.css') );
	}

	if(is_archive()){
		wp_enqueue_style( 'archive-style', THEME_URL.'/css/archive.css',array('style'),filemtime(THEME_DIR.'/css/archive.css') );
	}

});

/*

メニュー

*/
class MMM_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = Array() ){$output .= "";}
    function end_lvl( &$output, $depth = 0, $args = Array() ) {$output .= "";}
    function start_el( &$output, $item, $depth = 0, $args = Array(), $id = 0 ) {
        $li_class = in_array('current-menu-item', $item->classes) ? "class='current depth-".$depth."'" : "class='depth-".$depth."'" ;
        if (in_array('menu-item-has-children', $item->classes)) {
            $indent = " ";
            $output .= "\n"."<li id='mmm-menu-".$item->ID."'".$li_class.">";
            $output .= "<a href='".$item->url."' class='mmm-submenu-hover'>".$item->title."<span class='mmm-submenu-click'></span></a>";
            $output .= "\n" . $indent . '<ul class="mmm-submenu">';
        } else {
            $output .= "\n"."<li id='mmm-menu-".$item->ID."'".$li_class.">";
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

/*

記事取得関連

*/
function mmm_limit_post_content( $content, $length ){
	if( !$content ) return false;
	$content = wp_strip_all_tags( $content );
	$content = str_replace(array("\r\n","\r","\n","&nbsp;","　","	"),'',$content);
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
		   $pto = get_post_type_object( $post_type );
		   if($pto->public && $pto->has_archive){
			   $post_type_name = $pto->labels->singular_name;
			   $this->add( $post_type_name, esc_url( get_post_type_archive_link( $post_type ) ) );
		   }
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
function mmm_pagination_archive( $query = null, $prev_text = null, $next_text = null ){
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
    echo "<div class='mmm-pagination-archive'>";
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
	if( get_children( array('post_parent'=>$child_of,'post_type'=>'page','post_status' => 'publish' ) ) ){
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

//基本情報
class MMM_Info{

    function __construct(){
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    public function admin_enqueue_scripts(){
        wp_enqueue_script( 'jquery-ui-sortable' );
    }

    public function admin_menu(){

        add_menu_page(
            '基本情報',
            '基本情報',
            'manage_options',
            'mmm_info',
            array( $this, 'render_info_page' )
        );

        add_submenu_page(
            'mmm_info',
            'ダッシュボード',
            'ダッシュボード',
            'manage_options',
            'mmm_info',
            array( $this, 'render_info_page' )
        );

    }

    public function admin_init(){

        register_setting(
            'mmm_info_group',
            'mmm_info',
            array( $this, 'sanitize_info' )
        );

        add_settings_section(
            'mmm_info_section',
            '',
            array( $this, 'render_info_section' ),
            'mmm_info'
        );

        if( $info = get_option('mmm_info') ){
            foreach( (array)$info as $key => $value ){
                add_settings_field(
                    'mmm_info_field_'.$key,
                    $info[$key]['label'],
                    array( $this, 'render_info_field' ),
                    'mmm_info',
                    'mmm_info_section',
                    array( 'info' => array('key'=>$info[$key]['key'], 'label'=>$info[$key]['label'], 'value'=>$info[$key]['value']) )
                );
            }
        }else{
            add_settings_field(
                'mmm_info_field',
                '新しい情報',
                array( $this, 'render_info_field' ),
                'mmm_info',
                'mmm_info_section'
            );
        }

    }

    public function render_info_page(){
		?>
		<div class="wrap" id="mmm_info">
		    <h1 class="wp-heading-inline">基本情報</h1>
		    <a href="#" id="mmm_info_add" class="page-title-action">新しい情報を追加</a>
		    <form method="post" action="options.php">
				<?php
		        settings_fields( 'mmm_info_group' );
		        do_settings_sections( 'mmm_info' );
		        submit_button();
				?>
		    </form>
		</div>

		<style>

		    .mmm_info_section strong{
		        font-weight:bold;
		        color:#ca4a1f;
		    }

		    .mmm_info_field label{
		        display:inline-block;
		        width:80px;
		        color:gray;
		    }

		    .mmm_info_field input['type=text']{
		        height:30px;
		    }

		    .mmm_info_field textarea{
		        vertical-align:top;
		    }

		    .mmm_info_input_key{
		        color:#007cba !important;
		        font-weight:bold;
		    }

		    .mmm_info_delete{
		        text-align:right;
		    }

		</style>

		<script>
		    (function($){

		        window.onload = function(){
		            $('#mmm_info .form-table tbody').sortable();
		        };

		        //新規作成ボタン
		        $('#mmm_info_add').on('click',function(e){
		            e.preventDefault();
		            var fields = document.getElementsByClassName('mmm_info_field');
		            var key = fields.length;
		            $('#mmm_info .form-table > tbody').append( _renderField(key) );
		        });

		        //削除ボタン
		        $(document).on('click','.mmm_info_delete_btn',function(e){
		            e.preventDefault();
		            var result = confirm('本当に削除しますか？');
		            if(result) {
		                $(this).parents('tr').remove();
		            }
		        });

		        //入力イベント
		        $(document).on('keyup',function(e){

		            var target = e.target;
		            var key = $(target).val();

		            if( target.className.indexOf('mmm_info_input_key') != -1 ){
		                var field = $(target).parent().parent();
		                $(target).attr('name', 'mmm_info[' + key + '][key]');
		                $(field).find('.mmm_info_input_label').attr('name', 'mmm_info[' + key + '][label]');
		                $(field).find('.mmm_info_input_value').attr('name', 'mmm_info[' + key + '][value]');
		            }else if( target.className.indexOf('mmm_info_input_label') != -1 ){
		                $(target).parents('tr').find('th').text( key );
		            }

		        });

		        //フィールド作成
		        function _renderField(key){
		            return '<tr><th scope="row">新しい情報</th><td><div class="mmm_info_field">'
		                        + '<p><label>KEY</label><input type="text" name="mmm_info[' + key + '][key]" value="' + key + '" class="mmm_info_input_key" /></p>'
		                        + '<p><label>LABEL</label><input type="text" name="mmm_info[' + key + '][label]" value="新しい情報" class="mmm_info_input_label regular-text" /></p>'
		                        + '<p><label>VALUE</label><textarea name="mmm_info[' + key + '][value]" class="mmm_info_input_value regular-text" rows=3 /></textarea></p>'
		                        + '<p class="mmm_info_delete"><a href="#" class="mmm_info_delete_btn">この情報を削除する</a></p>'
		                    + '</div></td></tr>'
		        }

		    })(jQuery);

		</script>
		<?php
    }

    public function render_info_section(){
		?>
		<div class="mmm_info_section">
		    <p>
		    ▼同じ<strong>KEY</strong>を設定すると後の方の値で上書きされます<br/>
		    ▼<strong>get_mmm_info( 'KEYの値' )</strong> で、VALUEの値を取得できます<br>
		    ▼<strong>get_mmm_info( 'KEYの値', 'label' )</strong> で、LABELの値を取得できます
		    </p>
		</div>
		<?php
    }

    public function render_info_field( $args ){
		?>
		<?php
			if($args['info']){
				$info = $args['info'];
		?>
		<div class="mmm_info_field"">
		    <p><label>KEY</label><input type="text" name="mmm_info[<?php echo $info['key'];?>][key]" value="<?php echo $info['key'];?>" class="mmm_info_input_key" /></p>
		    <p><label>LABEL</label><input type="text" name="mmm_info[<?php echo $info['key'];?>][label]" value="<?php echo $info['label'];?>" class="mmm_info_input_label regular-text" /></p>
		    <p><label>VALUE</label><textarea name="mmm_info[<?php echo $info['key'];?>][value]" class="mmm_info_input_value regular-text" rows=3><?php echo $info['value'];?></textarea></p>
		    <p class="mmm_info_delete"><a href="#" class="mmm_info_delete_btn">この情報を削除する</a></p>
		</div>
		<?php }else{ ?>
		<div class="mmm_info_field" data-key="0">
		    <p><label>KEY</label><input type="text" name="mmm_info[0][key]" value="0" class="mmm_info_input_key" /></p>
		    <p><label>LABEL</label><input type="text" name="mmm_info[0][label]" value="新しい情報" class="mmm_info_input_label regular-text" /></p>
		    <p><label>VALUE</label><textarea name="mmm_info[0][value]" class="mmm_info_input_value regular-text" rows=3></textarea></p>
		    <p class="mmm_info_delete"><a href="#" class="mmm_info_delete_btn">この情報を削除する</a></p>
		</div>
		<?php } ?>
		<?php
    }

    public function sanitize_info( $input ){
        foreach( $input as $key => $value ){
            $input[$key]['key'] = esc_attr( $value['key'] );
            $input[$key]['label'] = esc_attr( $value['label'] );
            $input[$key]['value'] = strip_tags($value['value'], '<a><iframe>');
        }
        return $input;
    }

}

$option = new MMM_Info();


//template tag
function get_mmm_info( $key, $label = null ){
    $info = get_option( 'mmm_info' );
    if( $label==='label' ){
        $value = esc_html( $info[$key]['label'] );
    }else{
        $value = esc_html( $info[$key]['value'] );
    }
    return $value;
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


//サイトマップ
class MMM_Sitemap{

    private $settings  = array();

    public function __construct(){

        if( $settings = get_option('mmm_sitemap') ){
            $this->settings = $settings;
        }

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_shortcode( 'sitemap', array( $this, 'generate_html' ) );
    }

    public function admin_menu(){

        add_menu_page(
            'サイトマップ',
            'サイトマップ',
            'manage_options',
            'mmm_sitemap',
            array( $this, 'render_mmm_page' )
        );

        add_submenu_page(
            'mmm_sitemap',
            'ダッシュボード',
            'ダッシュボード',
            'manage_options',
            'mmm_sitemap',
            array( $this, 'render_mmm_page' )
        );

    }

    public function admin_init(){

        register_setting(
            'mmm_sitemap_group',
            'mmm_sitemap',
            array( $this, 'sanitize_info' )
        );

        add_settings_section(
            'mmm_sitemap_section',
            '',
            '',
            'mmm_sitemap'
        );

        add_settings_field(
            'mmm_sitemap_field_page',
            'HTMLサイトマップ',
            function(){
                $page_checked = isset( $this->settings['page']['post_type']['page'] ) && $this->settings['page']['post_type']['page'] ? 'checked' : '';
                $post_checked = isset( $this->settings['page']['post_type']['post'] ) && $this->settings['page']['post_type']['post'] ? 'checked' : '';
                ?>
                <div class="mmm-sitemap-field">
                    <div>
                        <p><label><input type="checkbox" name="mmm_sitemap[page][post_type][page]" value = "page" <?php echo $page_checked; ?>/> 固定ページ</label></p>
                        <div class="mmm-sitemap-post">
                            <p><label><input type="checkbox" name="mmm_sitemap[page][post_type][post]" value = "post" <?php echo $post_checked; ?>/> 投稿</label></p>
                            <?php
                            $terms = get_terms( 'category', array( 'hide_empty' => false, 'parent' => 0 ) );
                            if( $terms ){
                                ?>
                                <p class="mmm-sitemap-taxonomy">カテゴリー</p>
                                <?php
                                foreach( $terms as $term ){
                                    $term_checked = isset( $this->settings['page']['term']['post'][$term->term_id] ) && $this->settings['page']['term']['post'][$term->term_id] ? 'checked' : '';
                                    ?>
                                    <p class="mmm-sitemap-term"><label><input type="checkbox" name="mmm_sitemap[page][term][post][<?php echo $term->term_id; ?>]" value="<?php echo $term->slug; ?>" <?php echo $term_checked; ?>/> <?php echo $term->name; ?></label></p>
                                    <?php
                                }
                            }
                            $terms = get_terms( 'post_tag', array( 'hide_empty' => false ) );
                            if( $terms ){
                                ?>
                                <p class="mmm-sitemap-taxonomy">タグ</p>
                                <?php
                                foreach( $terms as $term ){
                                    $term_checked = isset( $this->settings['page']['term']['post'][$term->term_id] ) && $this->settings['page']['term']['post'][$term->term_id] ? 'checked' : '';
                                    ?>
                                    <p class="mmm-sitemap-term"><label><input type="checkbox" name="mmm_sitemap[page][term][post][<?php echo $term->term_id; ?>]" value="<?php echo $term->slug; ?>" <?php echo $term_checked; ?>/> <?php echo $term->name; ?></label></p>
                                    <?php
                                }
                            }
                            $checked = isset( $this->settings['page']['other']['post'] ) && $this->settings['page']['other']['post'] ? 'checked' : '';
                            ?>
                            <p class="mmm-sitemap-other"><label><input type="checkbox" name="mmm_sitemap[page][other][post]" value = "post" <?php echo $checked; ?>/> その他</label></p>
                        </div>
                        <?php
                            $post_types = get_post_types( array( '_builtin' => false, 'public' => true ), 'objects' );
                            foreach( $post_types as $key => $value ){
                                ?>
                                <div class="mmm-sitemap-post">
                                <?php
                                $checked = isset( $this->settings['page']['post_type'][$key] ) && $this->settings['page']['post_type'][$key] ? 'checked' : '';
                                ?>
                                    <p><label><input type="checkbox" name="mmm_sitemap[page][post_type][<?php echo $key; ?>]" value = "<?php echo $value->name; ?>" <?php echo $checked; ?> /> <?php echo $value->labels->singular_name; ?></label></p>
                                <?php
                                foreach( get_object_taxonomies( $key, 'objects' ) as $taxonomy ){
                                    $terms = get_terms( $taxonomy->name, array( 'hide_empty' => false, 'parent' => 0 ) );
                                    if( $terms ){
                                        ?>
                                        <p class="mmm-sitemap-taxonomy"><?php echo $taxonomy->label; ?></p>
                                        <?php
                                        foreach( $terms as $term ){
                                            $term_checked = isset( $this->settings['page']['term'][$key][$term->term_id] ) && $this->settings['page']['term'][$key][$term->term_id] ? 'checked' : '';
                                            ?>
                                            <p class="mmm-sitemap-term"><label><input type="checkbox" name="mmm_sitemap[page][term][<?php echo $key; ?>][<?php echo $term->term_id; ?>]" value="<?php echo $term->slug; ?>" <?php echo $term_checked; ?>/> <?php echo $term->name; ?></label></p>
                                            <?php
                                        }
                                    }
                                }
                                $checked = isset( $this->settings['page']['other'][$key] ) && $this->settings['page']['other'][$key] ? 'checked' : '';
                                ?>
                                <p class="mmm-sitemap-other"><label><input type="checkbox" name="mmm_sitemap[page][other][<?php echo $key; ?>]" value = "<?php echo $key; ?>" <?php echo $checked; ?>/> その他</label></p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div>
                        <?php
                        $exclude = esc_attr( $this->settings['page']['exclude'] );
                        ?>
                        <p class="mmm-sitemap-exclude"><input type="text" name="mmm_sitemap[page][exclude]" value="<?php echo $exclude; ?>" class="widefat" placeholder="含めない記事ID" /></p>
                    </div>
                </div>
                <?php
            },
            'mmm_sitemap',
            'mmm_sitemap_section'
        );

    }

    public function render_mmm_page(){
        ?>
        <div class="wrap" id="mmm-sitemap">
            <h1 class="wp-heading-inline">サイトマップ設定</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'mmm_sitemap_group' ); ?>
                <?php do_settings_sections( 'mmm_sitemap' ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <style>
            #mmm-sitemap .form-table th {
                padding-top:0;
            }

            .mmm-sitemap-field{
                margin-bottom:2rem;
            }

            .mmm-sitemap-field strong{
                display:block;
                margin-bottom:10px;
            }

            .mmm-sitemap-post{

            }

            .mmm-sitemap-taxonomy{
                margin-left:1.5rem;
            }

            .mmm-sitemap-term{
                margin-left:3rem;
            }

            .mmm-sitemap-other{
                margin-left:1.5rem;
            }

            .mmm-sitemap-exclude{
                padding-top:1rem;
            }
        </style>
        <?php
    }

    public function generate_html(){

        $sitemap = '<div class="mmm-sitemap">';
        $sitemap .= '<ul>';

        //page
        if( isset( $this->settings['page']['post_type']['page'] ) && $this->settings['page']['post_type']['page'] ){
            $args = array();
            $args['post_type']      = 'page';
            $args['post_status']    = 'publish';
            $args['post_parent']    = 0;
            $args['posts_per_page'] = -1;
            $args['post__not_in']   = explode( ',', $this->settings['page']['exclude'] );
            $query = new WP_Query( $args );
            if( $query->posts ){
                $sitemap .= '<li><h5 class="mmm-sitemap-title">固定ページ</h5></li>';
                foreach( $query->posts as $post ){
                    $this->get_child_pages( $post, $sitemap );
                }
            }
        }

        //single
        if( isset( $this->settings['page']['post_type'] ) ){

            foreach( $this->settings['page']['post_type'] as $post_type ){

                if( $post_type === 'page' ) continue;

                $post_type_obj = get_post_type_object( $post_type );
                if( $post_type === 'post' ){
                    $sitemap .= '<li><h5 class="mmm-sitemap-title">投稿</h5></li>';
                }else{
                    $sitemap .= '<li><h5 class="mmm-sitemap-title"><a href="'.get_post_type_archive_link( $post_type ).'">'.$post_type_obj->labels->singular_name.'</a></h5></li>';
                }

                $ignore_posts = array();
                $ignore_tax_query = array();
                $ignore_tax_query['relation'] = "AND";

                //categorized
                foreach( get_object_taxonomies( $post_type ) as $taxonomy ){

                    if( $taxonomy === 'post_format' ) continue;

                    $terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => 0 ) );

                    if( $terms ){
                        $sitemap .= '<ul>';
                        foreach( $terms as $term ){

                            $ignore_tax_query[] = array(
                                'taxonomy' => $taxonomy,
                                'field'    => 'slug',
                                'terms'    => $term->slug,
                                'operator' => 'NOT IN'
                            );

                            if( isset( $this->settings['page']['term'][$post_type][$term->term_id] ) ){
                                $args = array();
                                $args['post_type']      = $post_type;
                                $args['post_status']    = 'publish';
                                $args['posts_per_page'] = -1;
                                $args['post__not_in']   = explode( ',', $this->settings['page']['exclude'] );
                                $args['tax_query']      = array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'slug',
                                        'terms'    => $term->slug,
                                    )
                                );
                                $query = new WP_Query( $args );
                                if( $query->posts ){
                                    $sitemap .= '<li><h6 class="mmm-sitemap-title"><a href="'.get_term_link( $term->slug, $taxonomy ).'">'.$term->name.'</a></h6></li>';
                                    $sitemap .= '<ul>';
                                    foreach( $query->posts as $post ){
                                        $ignore_posts[] = $post->ID;
                                        $sitemap .= '<li><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></li>';
                                    }
                                    $sitemap .= '</ul>';
                                }
                            }
                        }
                        $sitemap .= '</ul>';
                    }
                }

                //not categorized
                if( isset( $this->settings['page']['other'][$post_type] ) ){
                    $args['post_type']      = $post_type;
                    $args['post_status']    = 'publish';
                    $args['posts_per_page'] = -1;
                    $args['post__not_in']   = $ignore_posts + explode( ',', $this->settings['page']['exclude'] );
                    $args['tax_query']      = $ignore_tax_query;
                    $query = new WP_Query( $args );
                    if( $query->posts ){
                        $sitemap .= '<ul>';
                        foreach( $query->posts as $post ){
                            $sitemap .= '<li><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></li>';
                        }
                        $sitemap .= '</ul>';
                    }
                }
            }
        }

        $sitemap .= '</ul>';
        $sitemap .= '</div>';

        return $sitemap;
    }

    private function get_child_pages( $post, &$sitemap ){
        $sitemap .= '<li><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></li>';
        $children = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'page' ) );
        if( $children ){
            foreach( $children as $child ){
                $sitemap .= '<ul>';
                $this->get_child_pages( $child, $sitemap );
                $sitemap .= '</ul>';
            }
        }
    }

}

$sitemap = new MMM_Sitemap();



/*

ショートコード

*/
add_shortcode( 'themeurl', function(){
	return get_stylesheet_directory_uri();
});

add_shortcode( 'homeurl', function(){
	return home_url();
});
