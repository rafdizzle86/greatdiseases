<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class GD_Progress_Pts_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct( array(
            'singular' => 'gd_list_progress_pt', //Singular label
            'plural'   => 'gd_list_progress_pts', //plural label, also this well be one of the table css class
            'ajax'     => false //We won't support Ajax for this table
        ) );

    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'post_is_milestone':
                $is_milestone = get_post_meta( $item->ID, '_gd_is_milestone', true);
                $is_milestone = $is_milestone ? 'Yes' : '';
                return $is_milestone;
            case 'post_step_metadata':
                $post_step_metadata = get_post_meta( $item->ID, '_gd_step_metadata', true);
                return $post_step_metadata;
            case 'post_title':
                return $item->$column_name;
            default:
                return ''; //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Generates content for a single row of the table
     *
     * @since 3.1.0
     * @access protected
     *
     * @param object $item The current item
     */
    function single_row( $item ) {
        static $row_class = '';
        $row_class = ( $row_class == '' ? ' class="alternate"' : '' );

        echo '<tr id="' . $item->ID . '" ' . $row_class . '>';
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    /**
     * Renders the progress point choices
     * @param $progress_pt_id
     * @return string
     */
    function render_progress_pt_choice_form( $progress_pt_id ){

        $choice_form = '<div id="gd-progress-pt-choices-' . $progress_pt_id . '" class="gd-progress-pt-choices">';
            $choice_form .= '<p><b>Add a new choice:</b></p>';
            $choice_form .= 'Choice text: <input type="text" id="new-progress-pt-choice-title-' . $progress_pt_id . '"> ';

            $add_new_choice_button = ' <button type="button" class="new-progress-pt-choice button" id="new-progress-pt-choice-' . $progress_pt_id .'" data-postid="' . $progress_pt_id . '">Add new choice</button>';
            $choice_form .= $this->render_progress_pts_dropdown( 'new-progress-pt-choice-goto-' . $progress_pt_id, 'Go to step:', '', $add_new_choice_button );

            $choice_form .= '<div id="gd-progress-pt-existing-choices-' . $progress_pt_id .'" class="gd-progress-pt-existing-choices">';
                $choice_form .= $this->render_progress_pt_choices( $progress_pt_id, '<p><b>Existing Choices:</b></p><form id="choice-form-' . $progress_pt_id  .'">', '</form>' );
            $choice_form .= '</div>';
            //$choice_form .= '<button id="save-progress-pt-choice-settings-' . $progress_pt_id .'" data-postid="' . $progress_pt_id .'" class="save-progress-pt-choice-settings button button-primary" type="button">Save changes</button>';

        $choice_form .= '</div>';

        return $choice_form;
    }

    /**
     * Renders the choices of a progress point/step
     */
    function render_progress_pt_choices( $progress_pt_id, $before_txt = '', $after_txt = '' ){
        $progress_pt_choices = get_post_meta( $progress_pt_id, '_gd_progress_pt_choices', true);
        $choices = '';
        if( !empty( $progress_pt_choices ) && is_array( $progress_pt_choices ) ){
            $choices .= $before_txt;
            foreach( $progress_pt_choices as $choice_id => $choice ){
                $choices .= $this->render_progress_pt_choice( $choice, $choice_id, $progress_pt_id);
            }
            $choices .= $after_txt;
        }
        return $choices;
    }

    /**
     * Renders a single choice
     * @param $choice
     * @param $choice_id
     * @param $progress_pt_id
     * @return string
     */
    function render_progress_pt_choice( $choice, $choice_id, $progress_pt_id ){
        $choice_html = '<p id="choice-' . $choice_id .'-' . $progress_pt_id . '" class="gd-progress-pt-choice">';
            $choice_html .= 'Text: <input type="text" id="choice-text-' . $choice_id . '" value="' . $choice['choice_title'] . '"> ';
            $delete_choice = ' <span id="delete-choice-' . $choice_id . '" class="delete-choice delete" data-choiceid="' . $choice_id . '" data-postid="' . $progress_pt_id . '">Delete</span>';
            $choice_html .= $this->render_progress_pts_dropdown( 'choice-goto-' . $choice_id, 'Goes to step: ', $choice['choice_goto_id'], $delete_choice);
        $choice_html .= '</p>';
        return $choice_html;
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
    function render_progress_pts_dropdown( $drop_down_id, $before_txt = '', $select_id = '', $after_txt = '' ){

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

    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     *
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     *
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_post_title( $item ){

        //Build row actions
        $actions = array(
            'edit_choices' => sprintf('<a href="#" class="edit-gd-choice-inline" id="edit-gd-choice-inline-%s" data-postid="%s">Edit Choices</a>' , $item->ID, $item->ID),
            'edit' => sprintf( '<a href="%s">Edit Step</a>', get_edit_post_link( $item->ID ) ),
            'view' => sprintf( '<a href="%s">View Step</a>', get_permalink( $item->ID ) ),
            'delete' => sprintf( '<a href="?page=%s&action=%s&gd_list_progress_pt[]=%s">Delete</a>',$_REQUEST['page'],'delete',$item->ID )
        );

        //Return the title contents
        return sprintf('%1$s %2$s %3$s',
            /*$1%s*/ $item->post_title,
            /*$2%s*/ $this->row_actions($actions),
            /*$3%s*/ $this->render_progress_pt_choice_form( $item->ID )
        );
    }

    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->ID                //The value of the checkbox should be the record's id
        );
    }

    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     *
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     *
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     *
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     *
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if( 'delete' === $this->current_action() ) {

            $gd_pts_to_delete = $_GET['gd_list_progress_pt'];
            $gd_progress_pts = get_option( 'gd_progress_pts' );

            if( !empty( $gd_progress_pts ) && !empty( $gd_pts_to_delete ) ){
                foreach( $gd_pts_to_delete as $post_id ){
                    if( ($post_key = array_search( $post_id, $gd_progress_pts )) !== false  ){
                        unset( $gd_progress_pts[ $post_key ] );
                        wp_delete_post( $post_id, true );
                    }
                }
                global $wpdb;
                // use wpdb to update the option, can't use update_option since we're filtering it in gd-admin-settings.php
                $wpdb->update( $wpdb->options,
                    array( 'option_value' => maybe_serialize( $gd_progress_pts ) ),
                    array( 'option_name' => 'gd_progress_pts' )
                );
            }
        }
    }
    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'post_title' => __('Step Title'),
            'post_step_metadata' => __('Meta Data'),
            'post_is_milestone' => __('Is Milestone')
            //'post_is_visible'   => __('Is Visible')
        );
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'post_title' => array('post_title',false),     //true means it's already sorted
            'post_step_metadata' => array( 'post_step_metadata', false),
            'post_is_milestone'  => array( 'post_is_milestone', false)
        );
        return $sortable_columns;
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 20;


        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);


        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();

        /* -- Preparing your query -- */
        $gd_progress_pts = get_option( 'gd_progress_pts' );
        if( !empty( $gd_progress_pts ) ){

            $orderby = ( !empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'wp_posts.post_date'; //If no sort, default to title
            $order = ( !empty($_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'ASC'; //If no order, default to asc

            $orderby_args = array();
            switch( $orderby ){
                case 'post_is_milestone':
                    $orderby_args = array(
                        'orderby' => 'meta_value',
                        'meta_key' => '_gd_is_milestone'
                    );
                    break;
                case 'post_step_metadata':
                    $orderby_args = array(
                        'orderby' => 'meta_value',
                        'meta_key' => '_gd_step_metadata'
                    );
                    break;


                default:
                    $orderby_args = array(
                        'orderby' => 'meta_value_num',
                        'meta_key' => '_gd_step_order'
                    );
            }

            $gd_query_args = array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'post__in' => $gd_progress_pts,
                'order' => strtoupper( $order ),
                'posts_per_page' => -1
            );
            $gd_query_args = array_merge( $gd_query_args, $orderby_args );
            $gd_query = new WP_Query( $gd_query_args );

            $data = $wpdb->get_results( $gd_query->request );
        }else{
            $data = array();
        }

        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */
        $total_items = count($data);
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to
         */
        $data = array_slice( $data,( ($current_page-1) * $per_page ), $per_page );

        $this->items = $data;

        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }

}