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
        add_action( 'wp_ajax_gd_add_new_choice', array( $this, 'gd_add_new_choice') );
        add_action( 'wp_ajax_gd_delete_step_choice', array( $this, 'gd_delete_step_choice') );
        add_action( 'admin_head', array( &$this, 'admin_header' ) );

        if( is_admin() ){
            wp_enqueue_script( 'jquery-form' );
            wp_enqueue_script( 'gd_admin_js', get_template_directory_uri() . '/js/gd_admin.js' );
            wp_enqueue_style( 'gd_admin_css', get_template_directory_uri() . '/css/gd_admin.css' );
        }
    }

    function admin_header(){
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'gd-setting-admin' != $page )
            return;

        echo '<style type="text/css">';
        echo '.wp-list-table .column-post_title { width: 80%; }';
        echo '.wp-list-table .column-post_step_metadata { width: 10%; }';
        echo '.wp-list-table .column-post_is_milestone { width: 10%; }';
        echo '</style>';
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
            'Is this step a milestone? <small>(determines if it\'s displayed in the team page):</small>', // Title
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
        //Our class extends the WP_List_Table class, so we need to make sure that it's there
        require_once( __DIR__ . '/gd-admin-progress-pts-table.php' );
    }

    /**
     * Options page callback
     */
    public function gd_create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'gd_progress_pts' );
        ?>
        <div class="wrap">
            <h2>Great Diseases Site Settings</h2>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'gd_option_group' );
                do_settings_sections( 'gd-setting-admin' );
                submit_button( 'Create new step' );
                ?>
            </form>

            <div id="gd-steps">
                <h3>Current progress/steps:</h3>
                <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
                <form id="gd_list_progress_pts-filter" method="get">
                    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <?php
                    $wp_list_table = new GD_Progress_Pts_Table();
                    $wp_list_table->prepare_items();
                    $wp_list_table->display();

                    wp_nonce_field( 'gd_add_new_choice', 'gd_admin_nonce', false );
                    ?>
                </form>
            </div>
        </div>
    <?php
    print_r( $this->options );
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

        if( $new_value['gd_is_milestone'] == 'true' ){
            add_post_meta( $step_id, '_gd_is_milestone', true );
        }else{
            add_post_meta( $step_id, '_gd_is_milestone', false );
        }

        add_post_meta( $step_id, '_gd_step_metadata', $new_value['gd_step_metadata'], true );

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