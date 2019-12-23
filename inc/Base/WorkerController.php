<?php 
/**
 * @package  OverviewPlugin
 */
namespace Inc\Base;
use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\WorkerCallbacks;
/**
* 
*/
class WorkerController extends BaseController
{
	
	public $callbacks;
	
	public $settings;
	public function register()
	{
		if ( ! $this->activated( 'worker_manager' ) ) return;
		$this->settings = new SettingsApi();
		$this->callbacks = new WorkerCallbacks();

		add_action( 'init', array( $this, 'worker_cpt' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_action( 'manage_worker_posts_columns', array( $this, 'set_custom_columns' ) );
		add_action( 'manage_worker_posts_custom_column', array( $this, 'set_custom_columns_data' ), 10, 2 );
		add_filter( 'manage_edit-worker_sortable_columns', array( $this, 'set_custom_columns_sortable' ) );
		$this->setShortcodePage();
		add_shortcode( 'worker-display', array( $this, 'worker_display' ) );
		add_action('admin_enqueue_scripts', array($this, 'my_admin_scripts'));
		add_action('admin_enqueue_styles',array($this,'my_admin_styles'));
		add_action('my-overlay-controller', array( $this, 'my_overlay_controller' ) );
	
		
	}
    public function my_overlay_controller(){
		ob_start();
		echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/src/scss/display.scss\" type=\"text/css\" media=\"all\" />";
		//echo "<script src=\"$this->plugin_url/assets/display.min.js\"></script>";
		return ob_get_clean();
	}

	public function my_admin_scripts() {    
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', WP_PLUGIN_URL.'/overview-plugin/assets/worker.min.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
	}
	
	public function my_admin_styles() {
	
		wp_enqueue_style('thickbox');
	}
	
	
	public function worker_display()
	{
		ob_start();
		echo "<script src=\"$this->plugin_url/src/js/display.js\"></script>";
		echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/display.min.css\" type=\"text/css\" media=\"all\" />";
		require_once( "$this->plugin_path/templates/display.php" );
		
		
		return ob_get_clean();
	}

	public function setShortcodePage()
	{
		$subpage = array(
			array(
				'parent_slug' => 'edit.php?post_type=worker',
				'page_title' => 'Shortcodes',
				'menu_title' => 'Shortcodes',
				'capability' => 'manage_options',
				'menu_slug' => 'overview_worker_shortcode',
				'callback' => array( $this->callbacks, 'shortcodePage' )
			)
		);
		$this->settings->addSubPages( $subpage )->register();
	}

	public function worker_cpt ()
	{
		$labels = array(
			'name' => 'Workers',
			'singular_name' => 'Worker'
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-businessman',
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'supports' => array( 'title', 'editor' ),
			'show_in_rest' => true
		);
		register_post_type ( 'worker', $args );
	}
	public function add_meta_boxes()
	{
		add_meta_box(
			'worker_name',
			'Add Worker',
			array( $this, 'render_features_box' ),
			'worker',
			'side',
			'default'
		);
		
	
	}
	public function render_features_box($post)
	{
		wp_nonce_field( 'overview_worker', 'overview_worker_nonce' );
		$data = get_post_meta( $post->ID, '_overview_worker_key', true );
		$name = isset($data['name']) ? $data['name'] : '';
		$description = isset($data['description']) ? $data['description'] : '';
		$image = isset($data['image']) ? $data['image'] : '';
		$position = isset($data['position']) ? $data['position'] : '';
		$github = isset($data['github']) ? $data['github'] : '';
		$linkedin = isset($data['linkedin']) ? $data['linkedin'] : '';
		$xing = isset($data['xing']) ? $data['xing'] : '';
		$facebook = isset($data['facebook']) ? $data['facebook'] : '';
		$approved = isset($data['approved']) ? $data['approved'] : false;
		$featured = isset($data['featured']) ? $data['featured'] : false;
		

		?>
		<p>
			<label class="meta-label" for="overview_worker_name">Name</label>
			<input type="text" id="overview_worker_name" name="overview_worker_name" class="widefat" value="<?php echo esc_attr( $name ); ?>">
		</p>
		<p>
			<label class="meta-label" for="overview_worker_description">Short Description</label>
			<input type="text" id="overview_worker_description" name="overview_worker_description" class="widefat" value="<?php echo esc_attr( $description ); ?>">
		</p>
		<p>
			<label class="meta-label" for="overview_worker_image">Image</label>
			<input id="overview_worker_image" type="text" size="36" name="overview_worker_image" value="<?php echo esc_attr( $image ); ?>" />
            <input id="upload_image_button" type="button" class= "button" value="Upload Image" />
			
		</p>
		<p>
			<label class="meta-label" for="overview_worker_position">Position</label>
			<input type="text" id="overview_worker_position" name="overview_worker_position" class="widefat" value="<?php echo esc_attr( $position ); ?>">
		</p>
		<p>
			<label class="meta-label" for="overview_worker_github">Github</label>
			<input type="text" id="overview_worker_github" name="overview_worker_github" class="widefat" value="<?php echo esc_attr( $github ); ?>">
		</p>
		<p>
			<label class="meta-label" for="overview_worker_linkedin">Linkedin</label>
			<input type="text" id="overview_worker_linkedin" name="overview_worker_linkedin" class="widefat" value="<?php echo esc_attr( $linkedin ); ?>">
		</p>
		<p>
			<label class="meta-label" for="overview_worker_xing">Xing</label>
			<input type="text" id="overview_worker_xing" name="overview_worker_xing" class="widefat" value="<?php echo esc_attr( $xing ); ?>">
		</p>
		<p>
			<label class="meta-label" for="overview_worker_facebook">Facebook</label>
			<input type="text" id="overview_worker_facebook" name="overview_worker_facebook" class="widefat" value="<?php echo esc_attr( $facebook ); ?>">
		</p>
		<div class="meta-container">
			<label class="meta-label w-50 text-left" for="overview_worker_approved">Approved</label>
			<div class="text-right w-50 inline">
				<div class="ui-toggle inline"><input type="checkbox" id="overview_worker_approved" name="overview_worker_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
					<label for="overview_worker_approved"><div></div></label>
				</div>
			</div>
		</div>
		<div class="meta-container">
			<label class="meta-label w-50 text-left" for="overview_worker_featured">Featured</label>
			<div class="text-right w-50 inline">
				<div class="ui-toggle inline"><input type="checkbox" id="overview_worker_featured" name="overview_worker_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
					<label for="overview_worker_featured"><div></div></label>
				</div>
			</div>
		</div>
		<?php
	}

	public function save_meta_box($post_id)
	{
		if (! isset($_POST['overview_worker_nonce'])) {
			return $post_id;
		}
		$nonce = $_POST['overview_worker_nonce'];
		if (! wp_verify_nonce( $nonce, 'overview_worker' )) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if (! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		$data = array(
			'name' => sanitize_text_field( $_POST['overview_worker_name'] ),
			'description' => sanitize_text_field( $_POST['overview_worker_description'] ),
			'image' => ( $_POST['overview_worker_image'] ),
			'position' => sanitize_text_field( $_POST['overview_worker_position'] ),
			'github' => sanitize_text_field( $_POST['overview_worker_github'] ),
			'linkedin' => sanitize_text_field( $_POST['overview_worker_linkedin'] ),
			'xing' => sanitize_text_field( $_POST['overview_worker_xing'] ),
			'facebook' => sanitize_text_field( $_POST['overview_worker_facebook'] ),
			'approved' => isset($_POST['overview_worker_approved']) ? 1 : 0,
			'featured' => isset($_POST['overview_worker_featured']) ? 1 : 0,
		);
		update_post_meta( $post_id, '_overview_worker_key', $data );
	}
	public function set_custom_columns($columns)
	{
		$title = $columns['title'];
		$date = $columns['date'];
		unset( $columns['title'], $columns['date'] );
		$columns['name'] = 'Worker Name';
		$columns['description'] = 'Description';
		$columns['image'] = 'Image';
		$columns['position'] = 'Position';
		$columns['SocialLink'] = 'Social Link';
		$columns['approved'] = 'Approved';
		$columns['featured'] = 'Featured';
		$columns['date'] = $date;
		return $columns;
	}
	
	public function set_custom_columns_data($column, $post_id)
	{
		$data = get_post_meta( $post_id, '_overview_worker_key', true );
		$name = isset($data['name']) ? $data['name'] : '';
		$description = isset($data['description']) ? $data['description'] : '';
		$image = isset($data['image']) ? $data['image'] : '';
		$position = isset($data['position']) ? $data['position'] : '';
		$github = isset($data['github']) ? $data['github'] : '';
		$linkedin = isset($data['linkedin']) ? $data['linkedin'] : '';
		$xing = isset($data['xing']) ? $data['xing'] : '';
		$facebook = isset($data['facebook']) ? $data['facebook'] : '';
		$approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>YES</strong>' : 'NO';
		$featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>YES</strong>' : 'NO';
		switch($column) {
			case 'name':
				echo '<strong>' . $name . '</strong>';
				break;
			case 'description':
				echo '<strong>' . $description . '</strong>';
				break;
			case 'image':
				echo '<img height="40" src="' . $image . '" alt="worker image"/>';
				break;
			case 'position':
				echo '<strong>' . $position . '</strong>';
				break;
			case 'SocialLink':
				echo '<strong>GITHUB:</strong><a href="' . $github . '">' . $github . '</a><br/><strong>LINKEDIN:</strong><a href="' . $linkedin . '">' . $linkedin . '</a><br/><strong>XING:</strong><a href="' . $xing . '">' . $xing . '</a><br/><strong>FACEBOOK:</strong><a href="' . $facebook . '">' . $facebook . '</a>';
				break;
			case 'approved':
				echo $approved;
				break;
			case 'featured':
				echo $featured;
				break;
		}
	}

	public function set_custom_columns_sortable($columns)
	{
		$columns['name'] = 'name';
		$columns['description'] = 'description';
		$columns['image'] = 'image';
		$columns['position'] = 'position';
		$columns['SocialLink'] = 'SocialLink';
		$columns['approved'] = 'approved';
		$columns['featured'] = 'featured';
		return $columns;
	}
}