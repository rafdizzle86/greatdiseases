<?php
class GD_Settings_Page
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_gd_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_notices', array( $this, 'show_settings_errors') );
        add_filter( 'pre_update_option_gd_progress_pts', array( $this, 'save_gd_progress_pts'), 10, 2 );
        add_action( 'wp_ajax_gd_save_choice_settings', array( $this, 'gd_save_choice_settings') );
        add_action( 'wp_ajax_gd_set_visibility', array( $this, 'gd_set_visibility') );
	    add_action( 'wp_ajax_gd_set_milestone', array( $this, 'gd_set_milestone') );
        add_action( 'wp_ajax_gd_add_new_choice', array( $this, 'gd_add_new_choice') );
        add_action( 'wp_ajax_gd_delete_step_choice', array( $this, 'gd_delete_step_choice') );
        add_action( 'wp_ajax_gd_set_step_order', array( $this, 'gd_set_step_order') );
        add_action( 'wp_ajax_gd_save_metadata', array( $this, 'gd_save_metadata') );
        add_action( 'wp_ajax_gd_save_post_title', array( $this, 'gd_save_post_title') );
        add_action( 'wp_ajax_gd_set_progress_tracker_steps', array( $this, 'gd_set_progress_tracker_steps') );
        add_action( 'wp_ajax_gd_set_progress_step_order', array( $this, 'gd_set_progress_step_order') );

        add_action( 'admin_head', array( &$this, 'admin_header' ) );

        if( is_admin() ){
            wp_enqueue_script( 'jquery-form' );
            wp_enqueue_script( 'gd_admin_js', get_template_directory_uri() . '/js/gd_admin.js' );
            wp_enqueue_style( 'jquery_editable', get_template_directory_uri() . '/js/jquery.jeditable.mini.js' );
            wp_enqueue_style( 'gd_admin_css', get_template_directory_uri() . '/css/gd_admin.css' );
        }
    }

    function admin_header(){
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'gd-setting-admin' != $page )
            return;
    }

    /**
    * Add options page
    */
    public function add_gd_plugin_page()
    {
        add_menu_page(
            'Great Diseases Settings',
            'Great Diseases Settings',
            'manage_options',
            'gd-setting-admin',
            array( $this, 'gd_create_admin_page' )
        );
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'gd_option_group', // Option group
            'gd_progress_pts', // Option name
            array( $this, 'sanitize_gd_options' ) // Sanitize
        );

        add_settings_section(
            'gd_progress_pt_section', // ID
            'Generate new progress points for teams', // Title
            array( $this, 'gd_progress_pts_section_info' ), // Callback
            'gd-setting-admin' // Page
        );

        add_settings_field(
            'gd_step_title', // ID
            '*Step title:', // Title
            array( $this, 'gd_step_title_callback' ), // Callback
            'gd-setting-admin', // Page
            'gd_progress_pt_section' // Section
        );

        add_settings_field(
            'gd_is_milestone', // ID
            'Is this step a milestone? <small>(determines if step requires an answer before proceeding and display choices to the user):</small>', // Title
            array( $this, 'gd_is_milestone_callback' ), // Callback
            'gd-setting-admin', // Page
            'gd_progress_pt_section' // Section
        );

        add_settings_field(
            'step_metadata', // ID
            'Step meta data: <small>(this can be used to as a way to identify different steps)</small>', // Title
            array( $this, 'gd_step_metadata' ), // Callback
            'gd-setting-admin', // Page
            'gd_progress_pt_section' // Section
        );
    }

    /**
     * Options page callback
     */
    public function gd_create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'gd_progress_pts' );

        $tab = 'gd_steps';
        if ( isset ( $_GET['tab'] ) ) {
            $tab = $_GET['tab'];
        }
        ?>
        <div class="wrap">
            <h2>Great Diseases Site Settings</h2>

            <h2 class="nav-tab-wrapper">
                <a class='nav-tab<?php echo ( $tab == 'gd_steps' ) ? ' nav-tab-active' : ''; ?>'  href='?page=gd-setting-admin&tab=gd_steps'>Decision Tree Steps</a>
                <a class='nav-tab<?php echo ( $tab == 'gd_progress_tracker' ) ? ' nav-tab-active' : ''; ?>' href='?page=gd-setting-admin&tab=gd_progress_tracker'>Progress Tracker Settings</a>
            </h2>

            <?php
            if( $tab == 'gd_steps' ) {
                ?>
                <form method="post" action="options.php">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('gd_option_group');
                    do_settings_sections('gd-setting-admin');
                    submit_button('Create new step');
                    ?>
                </form>

                <div id="gd-steps">
                    <h3>Current progress/steps:</h3>
                    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
                    <form id="gd_list_progress_pts-filter" method="get">
                        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                        <?php
                        $wp_list_table = new GD_Progress_Pts_Table();
                        $wp_list_table->prepare_items();
                        $wp_list_table->display();
                        ?>
                    </form>
                </div>
            <?php
            }else if( $tab == 'gd_progress_tracker') {
                self::render_progress_tracker_settings();
            }
            wp_nonce_field('gd_add_new_choice', 'gd_admin_nonce', false);
            ?>
        </div>
    <?php
    }

    /**
     * Render the progress tracker settings tabs
     */
    function render_progress_tracker_settings()
    {
        ?>
        <h2>Progress Tracker Settings</h2>
        <p>Settings for the progress tracker that displayed on the team page.</p>
        <p>Add a new progress step:</p>
        <table>
            <tr style="vertical-align: text-top;">
                <td>Step:</td>
                <td><input type="text" id="progress-tracker-step-name" name="progress-tracker-step-name"
                           placeholder="Enter step name"></td>
                <td>is displayed as "completed" if the following step(s) is/are completed:</td>
                <td>
                    <?php echo self::render_progress_pts_dropdown('gd_steps_dropdown'); ?>
                    <button id="gd_progress_tracker_new_step" class="button">Add</button>
                </td>
            </tr>
        </table>
        <table id="required-completed-steps" style="width: 100%; display: none;">
            <thead>
            <tr>
                <td class="column-step_title"><b>Step Title</b></td>
                <td class="column-step_logic"><b>Logic</b></td>
                <td class="column-step_delete"><b>Remove</b></td>
            </tr>
            </thead>
        </table>
        <br/>
        <button id="gd-progress-tracker-settings-submit" class="button button-primary">Submit</button>
        <?php
        $gd_progress_tracker_steps = get_option('gd_progress_tracker_steps');
        if ( !empty( $gd_progress_tracker_steps ) ) {
            ?>
            <table class="wp-list-table widefat fixed progress-tracker-steps">
                <thead>
                <tr>
                    <th class="column-step_title"><b>Step Text</b></td>
                    <th class="column-required_steps"><b>Required Steps</b></td>
                    <th class="column-reorder_step"><b>Re-order</b></td>
                </tr>
                </thead>
                <tbody id="the-list">
                <?php
                $c = true;
                foreach ($gd_progress_tracker_steps as $step_id => $step_data ){
                    ?>
                    <tr id="<?php echo $step_id ?>" <?php echo (($c = !$c)?' class="alternate"':'') ?>>
                        <td>
                            <span class="step-text"><?php echo $step_data['step_text'] ?></span>
                            <br />
                            <span class="delete-progress-step">Delete</span>
                        </td>
                        <td>
                            <?php
                                foreach( $step_data['required_steps'] as $required_step_id => $logic ){
                                    if( $logic == 'false' ){
                                        echo get_the_title( $required_step_id ) . ' <br />';
                                    }else{
                                        echo get_the_title( $required_step_id ) . ', ' . $logic . '<br />';
                                    }
                                }
                            ?>
                        </td>
                        <td>
                            <span class="step-sorting-handle"></span>
                            <div style="clear: both;"></div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        }
    }

    /**
     * Renders a drop-down of all the progress points with an optional select_id
     * that selects one by default
     * @param string $drop_down_id
     * @param string $before_txt
     * @param string $select_id
     * @param string $after_txt
     * @return string
     */
    public static function render_progress_pts_dropdown( $drop_down_id, $before_txt = '', $select_id = '', $after_txt = '' ){

        $gd_progress_pts = get_option( 'gd_progress_pts' );

        $drop_down_html = '';

        if( !empty( $gd_progress_pts ) && is_array( $gd_progress_pts ) ){
            $drop_down_html = $before_txt;
            $drop_down_html .= '<select id="' . $drop_down_id . '">';

            foreach( $gd_progress_pts as $progress_pt_id ){
                $progress_pt_post = get_post( $progress_pt_id );
                $meta_data = get_post_meta( $progress_pt_id, '_gd_step_metadata', true );
                $meta_data = !empty( $meta_data ) ? '(' . $meta_data . ')' : '';
                $selected = $progress_pt_id == $select_id ? 'selected' : '';
                $drop_down_html .= '<option id="progress-pt-' . $progress_pt_id .'" value="' . $progress_pt_id . '" ' . $selected . '>' . $progress_pt_post->post_title . ' ' . $meta_data . '</option>';
            }

            $drop_down_html .= '</select>';
            $drop_down_html .= $after_txt;
        }

        return $drop_down_html;
    }

    /**
     * Provides user with error notices
     */
    function show_settings_errors() {
        $errors = get_settings_errors();
        if( !empty( $errors ) ){
            foreach( $errors as $error ){
        ?>
            <div class="<?php echo $error['type'] ?>">
                <p><?php echo $error['message']; ?></p>
            </div>
        <?php
            }
        }
    }

    /**
     * Formats / sanitizes the option
     * @param $input
     * @return mixed
     */
    public function sanitize_gd_options( $input ){

        if( empty( $input['gd_step_title'] ) ){
            add_settings_error( 'gd_step_title', 'gd_step_title_empty', 'Please give this step a name' );
            return;
        }

        return $input;
    }

    /**
     * Save gd progress points
     * @param $new_value
     * @param $old_value
     * @return array
     */
    public function save_gd_progress_pts( $new_value, $old_value ){

        $gd_progress_pts = get_option( 'gd_progress_pts' );

        if( empty( $gd_progress_pts ) || !is_array( $gd_progress_pts ) ){
            $gd_progress_pts = array();
        }

        // create a new page
        $new_step_args = array(
            'post_title'    => $new_value['gd_step_title'],
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'page_template' => 'page-step.php',
        );
        $step_id = wp_insert_post( $new_step_args );

        add_post_meta( $step_id, '_gd_is_milestone', $new_value['gd_is_milestone'] );
        add_post_meta( $step_id, '_gd_step_metadata', $new_value['gd_step_metadata'], true );
        add_post_meta( $step_id, '_gd_step_order', count( $gd_progress_pts ), true );

        array_push( $gd_progress_pts, $step_id );

        return $gd_progress_pts;
    }

    /**
     * Print the Section text
     */
    public function gd_progress_pts_section_info()
    {
        print 'Create new steps below:';
    }

    public function gd_step_metadata(){
        echo '<input type="text" id="gd_step_metadata" name="gd_progress_pts[gd_step_metadata]" value="" />';
    }

    /**
     * Milestone checkbox
     */
    public function gd_is_visible_callback(){
        echo '<input type="checkbox" id="gd_is_visible" name="gd_progress_pts[gd_is_visible]" value="true" />';
    }

    /**
     * Milestone checkbox
     */
    public function gd_is_milestone_callback(){
        echo '<input type="checkbox" id="gd_is_milestone" name="gd_progress_pts[gd_is_milestone]" value="true" />';
    }

    /**
     * Step title input box
     */
    public function gd_step_title_callback()
    {
        echo '<input type="text" id="gd_step_title" name="gd_progress_pts[gd_step_title]" value="" />';
    }

    /**
     * Removes a choice from a decision tree step
     */
    public function gd_delete_step_choice(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['postID'] ) ){
            header("HTTP/1.0 409 Could not locate post ID.");
            exit;
        }

        if( !isset( $_POST['choiceID'] ) ){
            header("HTTP/1.0 409 Could not locate choice ID.");
            exit;
        }

        $post_id = (int) $_POST['postID'];
        $choice_id = (int) $_POST['choiceID'];

        $gd_step_choices = get_post_meta( $post_id, '_gd_progress_pt_choices', true );

        $success = false;
        if( !empty( $gd_step_choices ) && is_array( $gd_step_choices ) ){
            if( isset( $gd_step_choices[$choice_id] ) ){
                unset( $gd_step_choices[$choice_id] );
                $success = update_post_meta( $post_id, '_gd_progress_pt_choices', array_values($gd_step_choices) );
            }
        }

        echo json_encode( array( 'success' => $success, 'post_id' => $post_id, 'choice_id_removed' => $choice_id ) );
        exit;
    }

    /**
     * Sets the order of the steps
     */
    function gd_set_step_order(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['step_order'] ) ){
            header("HTTP/1.0 409 Could not locate step order.");
            exit;
        }

        $step_order = $_POST['step_order'];

        if( !empty( $step_order ) && is_array( $step_order ) ){
            foreach( $step_order as $step_key => $step_id ){
                update_post_meta( $step_id, '_gd_step_order', $step_key );
            }
        }

        echo json_encode( $step_order );
        exit;
    }

    /**
     * Sets order of progress tracker steps
     */
    function gd_set_progress_step_order(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['step_order'] ) ){
            header("HTTP/1.0 409 Could not locate step order.");
            exit;
        }

        $gd_progress_tracker_steps = get_option('gd_progress_tracker_steps');
        $step_order = $_POST['step_order'];

        if( count( $step_order ) !== count( $gd_progress_tracker_steps ) ){
            header("HTTP/1.0 409 The number of steps do not match - try re-ordering again!");
            exit;
        }

        // create a new array with the correct order
        $new_progress_tracker_order = array();
        foreach( $step_order as $order => $step_id ){
            $new_progress_tracker_order[$step_id] = $gd_progress_tracker_steps[$step_id];
        }

        $success = update_option( 'gd_progress_tracker_steps', $new_progress_tracker_order );

        echo json_encode( array( 'success' => $success ) );
        exit;
    }

    /**
     * AJAX call to save choice settings
     */
    function gd_save_choice_settings(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['postID'] ) ){
            header("HTTP/1.0 409 Could not locate post ID.");
            exit;
        }

        if( !isset( $_POST['choiceID'] ) ){
            header("HTTP/1.0 409 Could not locate choice ID.");
            exit;
        }

        $post_id = (int) $_POST['postID'];
        $choice_id = (int) $_POST['choiceID'];
        $choice_txt = $_POST['choiceText'];
        $choice_goto_id = (int) $_POST['nextStep'];

        $choices = get_post_meta( $post_id, '_gd_progress_pt_choices', true);

        /**
         * Update the choice with 'choice_id' with a new 'choice_txt'
         * and 'choice_goto_id'
         */
        if( !empty( $choices ) && is_array( $choices ) ){
            if( isset( $choices[ $choice_id ] ) ){
                $choice = &$choices[ $choice_id ];
                $choice['choice_title'] = $choice_txt;
                $choice['choice_goto_id'] = $choice_goto_id;
            }
            $success = update_post_meta( $post_id, '_gd_progress_pt_choices', $choices );
        }

        echo json_encode( array( 'success' => $success ) );
        exit;
    }

    /**
     * AJAX call to set step visibility
     */
    function gd_set_visibility(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['postID'] ) ){
            header("HTTP/1.0 409 Could not locate post ID.");
            exit;
        }

        $post_id = (int) $_POST['postID'];
        $is_visible = $_POST['is_visible'];

        $success = update_post_meta( $post_id, '_gd_is_visible', $is_visible );
        echo json_encode( array( 'success' => $success ) );
        exit;
    }

    /**
     * AJAX call to set the progress tracker steps
     */
    function gd_set_progress_tracker_steps(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['stepText'] ) ){
            header("HTTP/1.0 409 Please enter step text before continuing.");
            exit;
        }

        if( empty( $_POST['requiredSteps'] ) ){
            header("HTTP/1.0 409 Please add at least one required step before submitting a step.");
            exit;
        }

        $step_text = $_POST['stepText'];
        $gd_progress_tracker_steps = get_option( 'gd_progress_tracker_steps' );

        if( empty( $gd_progress_tracker_steps ) ){
            $gd_progress_tracker_steps = array();
        }

        $key = $gd_progress_tracker_key = get_option( 'gd_progress_tracker_key' );
        if( empty($key) ){
            $key = 0;
        }

        // reverse array since we don't want to start with step with no logic
        $step_data = array(
            'step_text' => $step_text,
            'required_steps' => array_reverse( $_POST['requiredSteps'], true )
        );

        $gd_progress_tracker_steps[$key++] = $step_data; // Save the progress tracker step in the array

        $success = update_option( 'gd_progress_tracker_key', $key ); // update key and tracker options to the db
        $success = $success && update_option( 'gd_progress_tracker_steps', $gd_progress_tracker_steps );

        echo json_encode( array( 'success' => $success) );
        exit;
    }


	/**
	 * AJAX call that sets the Milestone boolean option
	 */
	function gd_set_milestone(){
		$nonce = $_POST[ 'gd_admin_nonce' ];
		if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
			header("HTTP/1.0 409 Security Check.");
			exit;
		}

		if( empty( $_POST['postID'] ) ){
			header("HTTP/1.0 409 Could not locate post ID.");
			exit;
		}

		if( empty( $_POST['is_milestone'] ) ){
			header("HTTP/1.0 409 Please fill in a choice title.");
			exit;
		}

		$post_id = (int) $_POST['postID'];
		$is_milestone = $_POST['is_milestone'];

		$success = update_post_meta( $post_id, '_gd_is_milestone', $is_milestone );
		echo json_encode( array( 'success' => $success ) );
		exit;
	}

    /**
     * Saves metadata of a step (uses jEditable)
     */
    function gd_save_post_title(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['stepid'] ) ){
            header("HTTP/1.0 409 Could not locate post ID.");
            exit;
        }

        $post_id = (int) $_POST['stepid'];
        $post_title = $_POST['value'];

        $the_post = array(
            'ID'         => $post_id,
            'post_title' => $post_title
        );

        wp_update_post( $the_post );

        echo $post_title;
        exit;
    }

    /**
     * Saves metadata of a step (uses jEditable)
     */
    function gd_save_metadata(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['stepid'] ) ){
            header("HTTP/1.0 409 Could not locate post ID.");
            exit;
        }

        $post_id = (int) $_POST['stepid'];
        $meta_value = $_POST['value'];

        $success = update_post_meta( $post_id, '_gd_step_metadata', $meta_value );

        echo $meta_value;
        exit;
    }

    /**
     * Adds a new choice to a decision tree step
     */
    public function gd_add_new_choice(){
        $nonce = $_POST[ 'gd_admin_nonce' ];
        if( !wp_verify_nonce( $nonce, 'gd_add_new_choice' ) ){
            header("HTTP/1.0 409 Security Check.");
            exit;
        }

        if( empty( $_POST['postID'] ) ){
            header("HTTP/1.0 409 Could not locate post ID.");
            exit;
        }

        if( empty( $_POST['choice_title'] ) ){
            header("HTTP/1.0 409 Please fill in a choice title.");
            exit;
        }

        if( empty( $_POST['choice_goto'] ) ){
            header("HTTP/1.0 409 Please select the goto location for this choice.");
            exit;
        }

        $choice_title = (string) $_POST['choice_title'];
        $choice_goto = (int) $_POST['choice_goto'];
        $post_id = (int) $_POST['postID'];

        $progress_pt_choices = get_post_meta( $post_id, '_gd_progress_pt_choices', true);

        if( empty( $progress_pt_choices ) ){
            $progress_pt_choices = array();
        }
        $new_choice = array(
            'choice_title' => stripslashes_deep( $choice_title ),
            'choice_goto_id'  => $choice_goto
        );

        // Update the step choices
        $choice_id = array_push( $progress_pt_choices, $new_choice );
        $result = update_post_meta( $post_id, '_gd_progress_pt_choices', $progress_pt_choices );

        if( $result !== false ){
            $gd_list_table = new GD_Progress_Pts_Table();
            $choice_html = $gd_list_table->render_progress_pt_choice( $new_choice, $choice_id - 1, $post_id );
            echo $choice_html;
        }
        exit;
    }

    /**
     * Returns an array of post IDs
     * @param $meta_key
     * @param $meta_value
     * @return array
     */
    public function gd_get_progress_pts_by_metadata( $meta_key, $meta_value ){

        $gd_progress_pts = get_option( 'gd_progress_pts' );

        $gd_query_args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post__in' => $gd_progress_pts,
            'posts_per_page' => -1,
            'meta_value' => $meta_value,
            'meta_key' => $meta_key
        );

        $gd_query = new WP_Query( $gd_query_args );
        $gd_progress_pts_filtered = array();
        if ( $gd_query->have_posts() ) {
            while ( $gd_query->have_posts() ) {
                $gd_query->the_post();
                array_push( $gd_progress_pts_filtered, get_the_ID() );
            }
        }
        return $gd_progress_pts_filtered;
    }

}

if( is_admin() ){
    $gd_settings_page = new GD_Settings_Page();
}