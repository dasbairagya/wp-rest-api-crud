<?php 
/*
Plugin Name: Rest Api CRUD
*/
if ( ! defined( 'ABSPATH' ) ) {
exit; 
}

define("API_URL", plugin_dir_url( __FILE__ ));
define("API_ROOT_URI", plugins_url( __FILE__ ));
define("API_ADMIN_URI", admin_url());
define("API_PATH", __DIR__);
define('API_PLUGIN', plugin_basename( __FILE__ ));
// $auth = ['Authorization' => 'Basic ' . base64_encode( 'sc_admin:ghosh@1989') ];

// define('AUTH', $auth );

// echo API_URL.'<br>';
// echo API_ROOT_URI.'<br>';
// echo API_ADMIN_URI.'<br>';
// echo API_PATH.'<br>';
// echo API_PLUGIN.'<br>';


class RestApi{

	protected $auth;
	private $user, $pass;

	public function __construct()
	{

		$this->user = esc_attr( get_option('rest_user') )?? null;
		$this->pass = esc_attr( get_option('rest_password') )?? null;
		$this->auth = ['Authorization' => 'Basic ' . base64_encode( $this->user.':'.$this->pass) ];
		define('AUTH', $this->auth );
		add_action( 'admin_menu', array( $this, 'crete_menu_page') );
		add_action( 'wp_head', array( $this, 'upl_ajaxurl' ) );
		add_action( 'wp_ajax_update_custom_posts', array( $this,'update_my_custom_post' ) );
		add_action( 'wp_ajax_delete_custom_posts', array( $this,'delete_my_custom_post' ) );
		add_action( 'wp_ajax_add_custom_posts', array( $this,'add_my_custom_post' ) );
		// add_action( 'wp_ajax_nopriv_update_custom_posts', array($this,'update_my_custom_post' ) );
		add_action( 'admin_init', array( $this,'register_rest_setting_group' ) );
		
	}

	public function get_custom_posts(){
				//http://localhost/notebook/wp-json/wp/posts/?_embed
		$wp_request_url = site_url().'/wp-json/wp/v2/blog?_embed';

		$wp_request_headers = array('Authorization' => 'Basic ' . base64_encode( 'mynotebook:mynotebook' ));
		// print_r($wp_request_headers);
		$body = array('title' => 'Lorem Ipsum ', 'content'=>'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.');

		$wp_posts = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'GET',
		      'headers'   => AUTH
		      // 'body'      => $body
		  )
		);
		// echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
		// wp_remote_retrieve_response_message( $wp_posts );

		$posts = json_decode($wp_posts['body'], true);
		return $posts;
	}


	public function add_my_custom_post(){

		$title = $_POST['title']?? null;
		$content = $_POST['content']?? null;
		if($title==null || $content==null){
			echo "title and content required";
			die;
		}
		$wp_request_url = site_url().'/wp-json/wp/v2/blog/';
		// print_r($wp_request_headers);
		$body = array('title' => $title, 'content'=>$content, 'status'=>'publish'); //featured_media=54
		$wp_posts = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'POST',
		      'headers'   => AUTH,
		      'body'      => $body
		  )
		);
		// echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
		// wp_remote_retrieve_response_message( $wp_posts );

		// $posts = json_decode($wp_posts['body'], true);
		// return $posts;

		// echo json_encode($_POST);

		echo wp_remote_retrieve_response_message( $wp_posts );
		die;
		

	}	
	public function update_my_custom_post(){

		$id = $_POST['id'];
		$title = $_POST['title']?? null;
		$content = $_POST['content']?? null;
		if($title==null || $content==null){
			echo "title and content required";
			die;
		}

		$wp_request_url = site_url().'/wp-json/wp/v2/blog/'.$id;
		// print_r($wp_request_headers);
		$body = array('title' => $title, 'content'=>$content, 'status'=>'publish');
		$wp_posts = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'POST',
		      'headers'   => AUTH,
		      'body'      => $body
		  )
		);
		// echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
		// wp_remote_retrieve_response_message( $wp_posts );

		// $posts = json_decode($wp_posts['body'], true);
		// return $posts;

		// echo json_encode($_POST);

		echo wp_remote_retrieve_response_message( $wp_posts );
		die;

	}	

	public function delete_my_custom_post(){

		$id = $_POST['id'];

		$wp_request_url = site_url().'/wp-json/wp/v2/blog/'.$id;

		// print_r($wp_request_headers);
		//$body = array('title' => $title, 'content'=>$content, 'status'=>'publish');

		$wp_posts = wp_remote_request(
		  $wp_request_url,
		  array(
		      'method'    => 'DELETE',
		      'headers'   => AUTH
		      // 'body'      => $body
		  )
		);
		// echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
		// wp_remote_retrieve_response_message( $wp_posts );

		// $posts = json_decode($wp_posts['body'], true);
		// return $posts;

		// echo json_encode($_POST);

		echo wp_remote_retrieve_response_message( $wp_posts );
		die;

	}

	public function upl_ajaxurl(){
		?>
			<script type="text/javascript">
			  var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			</script>
		<?php
		}


	public function crete_menu_page(){
		$page_title = 'Rest';
		$menu_title = 'Rest CRUD';
		$capability = 'manage_options';
		$menu_slug = 'rest';
		$callback = array($this, 'menu_page_content');
		$icon = 'dashicons-tickets';
		$postion = 5;
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon, $postion);
		add_submenu_page($menu_slug, 'Rest Setting', 'Rest Setting', 'manage_options', 'rest-setting', array($this, 'setting_page_content'),'dashicons-admin-tools');

	}

	public function menu_page_content(){


		//if user or password is empty then redirect to the setting page

		if(empty($this->user) || empty($this->pass)){
			$url = API_ADMIN_URI.'admin.php?page=rest-setting';
			?>
			<script type="text/javascript">
				window.location.href='<?php echo $url;?>';
			</script>
			<?php 
			exit;
		}

		include 'html.php';

	}

	public function setting_page_content(){
		include_once('admin/settings.php');
	}

	public function register_rest_setting_group(){
			//register our settings
			register_setting( 'rest-settings-group', 'rest_user' );
			register_setting( 'rest-settings-group', 'rest_password' );

		}


}

$obj = new RestApi;