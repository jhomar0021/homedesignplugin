<?php
/*
Plugin Name: House Design Plugin
Description: A plugin to manage house designs with variations, plans, and location-based plans.
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
add_action('wp_ajax_delete_house_design', 'delete_house_design');
function delete_house_design()
{
    global $wpdb;

    // Validate and sanitize the ID
    $house_design_id = intval($_POST['house_design_id'] ?? 0);

    if ($house_design_id > 0) {
        $table_name = $wpdb->prefix . 'house_designs';
        $result = $wpdb->delete($table_name, array('id' => $house_design_id), array('%d'));

        if ($result) {
            wp_send_json_success('House design deleted successfully.');
        } else {
            wp_send_json_error('Failed to delete house design.');
        }
    } else {
        wp_send_json_error('Invalid house design ID.');
    }
}

add_action('wp_ajax_delete_design_variation', 'delete_design_variation');
function delete_design_variation()
{
    global $wpdb;

    // Validate and sanitize the ID
    $design_variation_id = intval($_POST['design_variation_id'] ?? 0);

    if ($design_variation_id > 0) {
        $table_name = $wpdb->prefix . 'house_design_variations';
        $result = $wpdb->delete($table_name, array('id' => $design_variation_id), array('%d'));

        if ($result) {
            wp_send_json_success('Design variation deleted successfully.');
        } else {
            wp_send_json_error('Failed to delete design variation.');
        }
    } else {
        wp_send_json_error('Invalid design variation ID.');
    }
}

add_action('wp_ajax_delete_house_plan', 'delete_house_plan');
function delete_house_plan()
{
    global $wpdb;

    // Validate and sanitize the ID
    $house_plan_id = intval($_POST['house_plan_id'] ?? 0);

    if ($house_plan_id > 0) {
        $table_name = $wpdb->prefix . 'house_plans';
        $result = $wpdb->delete($table_name, array('id' => $house_plan_id), array('%d'));

        if ($result) {
            wp_send_json_success('House plan deleted successfully.');
        } else {
            wp_send_json_error('Failed to delete house plan.');
        }
    } else {
        wp_send_json_error('Invalid house plan ID.');
    }
}



add_action('wp_ajax_house_design_get', 'house_design_get');
function house_design_get()
{
    global $wpdb;

    // Get the house design ID from the request
    $house_design_id = isset($_POST['house_design_id']) ? intval($_POST['house_design_id']) : 0;

    if ($house_design_id > 0) {
        // Fetch the house design details along with its variations and plans
        $query = $wpdb->prepare("
            SELECT 
                hd.id as house_design_id,
                hd.title as house_design_title,
                hd.floor_number as house_design_floor_number,
                hd.quotation_url as house_design_quotation_url,
                hd.visiting_page_url as house_design_visiting_page_url,
                dv.id as design_variation_id,
                dv.title as design_variation_title,
                dv.image_url as design_variation_image_url,
                hp.id as house_plan_id,
                hp.blueprint_image_url as house_plan_blueprint_image_url,
                hp.description as house_plan_description,
                hp.bedrooms as house_plan_bedrooms,
                hp.bathrooms as house_plan_bathrooms,
                hp.living_rooms as house_plan_living_rooms,
                hp.carport as house_plan_carport,
                hp.price as house_plan_price,
                hp.area as house_plan_area,
                hp.length as house_plan_length,
                hp.width as house_plan_width,
                hp.butlers_pantry as house_plan_butlers_pantry,
                hp.designer as house_plan_designer,
                hp.dual_living as house_plan_dual_living,
                hp.front_master as house_plan_front_master,
                hp.it_hub as house_plan_it_hub,
                hp.media_room as house_plan_media_room,
                hp.outdoor_living as house_plan_outdoor_living,
                hp.rear_master as house_plan_rear_master,
                hp.study as house_plan_study
            FROM {$wpdb->prefix}house_designs hd
            LEFT JOIN {$wpdb->prefix}house_design_variations dv ON hd.id = dv.house_design_id
            LEFT JOIN {$wpdb->prefix}house_plans hp ON hd.id = hp.house_design_id
            WHERE hd.id = %d
        ", $house_design_id);

        $results = $wpdb->get_results($query);

        if (!empty($results)) {
            $house_design = array(
                'id' => $results[0]->house_design_id,
                'title' => $results[0]->house_design_title,
                'floor_number' => $results[0]->house_design_floor_number,
                'quotation_url' => $results[0]->house_design_quotation_url,
                'visiting_page_url' => $results[0]->house_design_visiting_page_url,
                'design_variations' => array(),
                'house_plans' => array(),
            );

            foreach ($results as $row) {
                // Add design variation if it exists
                if ($row->design_variation_id) {
                    $house_design['design_variations'][$row->design_variation_id] = array(
                        'id' => $row->design_variation_id,
                        'title' => $row->design_variation_title,
                        'image_url' => $row->design_variation_image_url,
                    );
                }

                // Add house plan if it exists
                if ($row->house_plan_id) {
                    $house_design['house_plans'][$row->house_plan_id] = array(
                        'id' => $row->house_plan_id,
                        'blueprint_image_url' => $row->house_plan_blueprint_image_url,
                        'description' => $row->house_plan_description,
                        'bedrooms' => $row->house_plan_bedrooms,
                        'bathrooms' => $row->house_plan_bathrooms,
                        'living_rooms' => $row->house_plan_living_rooms,
                        'carport' => $row->house_plan_carport,
                        'price' => $row->house_plan_price,
                        'area' => $row->house_plan_area,
                        'length' => $row->house_plan_length,
                        'width' => $row->house_plan_width,
                        'butlers_pantry' => $row->house_plan_butlers_pantry,
                        'designer' => $row->house_plan_designer,
                        'dual_living' => $row->house_plan_dual_living,
                        'front_master' => $row->house_plan_front_master,
                        'it_hub' => $row->house_plan_it_hub,
                        'media_room' => $row->house_plan_media_room,
                        'outdoor_living' => $row->house_plan_outdoor_living,
                        'rear_master' => $row->house_plan_rear_master,
                        'study' => $row->house_plan_study
                    );
                }
            }

            // Re-index the design variations and house plans
            $house_design['design_variations'] = array_values($house_design['design_variations']);
            $house_design['house_plans'] = array_values($house_design['house_plans']);

            // Send the house design details as a JSON response
            wp_send_json_success($house_design);
        } else {
            wp_send_json_error('House design not found.');
        }
    } else {
        wp_send_json_error('Invalid house design ID.');
    }
}


// Save House Design
add_action('wp_ajax_save_house_design', 'save_house_design');
function save_house_design()
{
    global $wpdb;

    // Validate and sanitize input
    $title = sanitize_text_field($_POST['title'] ?? '');
    $floors = intval($_POST['floors'] ?? 0);
    $visiting_page = esc_url_raw($_POST['landing_page'] ?? '');
    $quote_page = esc_url_raw($_POST['quote_page'] ?? '');
    $id = intval($_POST['id'] ?? 0); // This will be 0 for a new design, or a valid ID for an update

    if (empty($title) || $floors <= 0 || empty($visiting_page) || empty($quote_page)) {
        wp_send_json_error('Invalid input data.');
        return;
    }

    $table_name = $wpdb->prefix . 'house_designs';

    // If an ID is provided, update the existing record
    if ($id > 0) {
        $result = $wpdb->update(
            $table_name,
            array(
                'title' => $title,
                'floor_number' => $floors,
                'visiting_page_url' => $visiting_page,
                'quotation_url' => $quote_page,
            ),
            array('id' => $id)
        );

        if ($result !== false) {
            wp_send_json_success(array('id' => $id));
        } else {
            wp_send_json_error('Failed to update House Design.');
        }
    } else {
        // Insert a new record
        $result = $wpdb->insert(
            $table_name,
            array(
                'title' => $title,
                'floor_number' => $floors,
                'visiting_page_url' => $visiting_page,
                'quotation_url' => $quote_page,
            )
        );

        if ($result) {
            $house_design_id = $wpdb->insert_id;
            wp_send_json_success(array('id' => $house_design_id));
        } else {
            wp_send_json_error('Failed to save House Design. SQL Error: ' . $wpdb->last_error);
        }
    }
}

// Save Design Variation
add_action('wp_ajax_save_design_variation', 'save_design_variation');
function save_design_variation()
{
    global $wpdb;

    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $house_design_id = isset($_POST['house_design_id']) ? intval($_POST['house_design_id']) : 0;
    $title = sanitize_text_field($_POST['title'] ?? '');

    // Handle the uploaded file for the design variation image
    $image_url = '';
    if (!empty($_FILES['design_variation_image']['name'])) {
        $uploaded_file = $_FILES['design_variation_image'];
        $upload = wp_handle_upload($uploaded_file, array('test_form' => false));

        if (isset($upload['url'])) {
            $image_url = esc_url_raw($upload['url']);
        } else {
            wp_send_json_error(array('message' => 'File upload failed.', 'upload_error' => $upload['error']));
            return;
        }
    }

    if ($variation_id > 0) {
        // Update existing variation
        $update_data = array(
            'title' => $title,
        );

        if (!empty($image_url)) {
            // Only update the image URL if a new image was uploaded
            $update_data['image_url'] = $image_url;
        }

        $result = $wpdb->update(
            "{$wpdb->prefix}house_design_variations",
            $update_data,
            array('id' => $variation_id)
        );

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Design variation updated successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update design variation.', 'sql' => $wpdb->last_query, 'error' => $wpdb->last_error));
        }
    } else {
        // Insert new variation
        $insert_data = array(
            'house_design_id' => $house_design_id,
            'title' => $title,
            'image_url' => $image_url
        );

        $result = $wpdb->insert(
            "{$wpdb->prefix}house_design_variations",
            $insert_data
        );

        if ($result) {
            wp_send_json_success(array('message' => 'Design variation saved successfully.', 'id' => $wpdb->insert_id));
        } else {
            wp_send_json_error(array('message' => 'Failed to save design variation.', 'sql' => $wpdb->last_query, 'error' => $wpdb->last_error));
        }
    }
}


// Save House Plan
add_action('wp_ajax_save_house_plan', 'save_house_plan');
function save_house_plan()
{
    global $wpdb;

    $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : 0;
    $house_design_id = isset($_POST['house_design_id']) ? intval($_POST['house_design_id']) : 0;

    // Sanitize and prepare the input data
    $description = sanitize_text_field($_POST['description']);
    $price = floatval($_POST['price']);
    $area = intval($_POST['area']);
    $width = intval($_POST['width']);
    $length = intval($_POST['length']);
    $bedrooms = intval($_POST['bedrooms']);
    $living_rooms = intval($_POST['living_rooms']);
    $bathrooms = intval($_POST['bathrooms']);
    $carport = intval($_POST['carport']);
    $butlers_pantry = isset($_POST['butlers_pantry']) ? intval($_POST['butlers_pantry']) : 0;
    $front_master = isset($_POST['front_master']) ? intval($_POST['front_master']) : 0;
    $media_room = isset($_POST['media_room']) ? intval($_POST['media_room']) : 0;
    $outdoor_living = isset($_POST['outdoor_living']) ? intval($_POST['outdoor_living']) : 0;
    $rear_master = isset($_POST['rear_master']) ? intval($_POST['rear_master']) : 0;
    $dual_living = isset($_POST['dual_living']) ? intval($_POST['dual_living']) : 0;
    $it_hub = isset($_POST['it_hub']) ? intval($_POST['it_hub']) : 0;
    $study = isset($_POST['study']) ? intval($_POST['study']) : 0;
    $designer = isset($_POST['designer']) ? intval($_POST['designer']) : 0;

    // Handle file upload for blueprint image
    $blueprint_image_url = '';
    if (!empty($_FILES['blueprint_image']['name'])) {
        $uploaded_file = $_FILES['blueprint_image'];
        $upload = wp_handle_upload($uploaded_file, array('test_form' => false));

        if (isset($upload['url'])) {
            $blueprint_image_url = esc_url_raw($upload['url']);
        } else {
            wp_send_json_error('File upload failed: ' . $upload['error']);
            return;
        }
    }

    if ($plan_id > 0) {
        // Update existing plan
        $update_data = array(
            'description' => $description,
            'price' => $price,
            'area' => $area,
            'width' => $width,
            'length' => $length,
            'bedrooms' => $bedrooms,
            'living_rooms' => $living_rooms,
            'bathrooms' => $bathrooms,
            'carport' => $carport,
            'butlers_pantry' => $butlers_pantry,
            'front_master' => $front_master,
            'media_room' => $media_room,
            'outdoor_living' => $outdoor_living,
            'rear_master' => $rear_master,
            'dual_living' => $dual_living,
            'it_hub' => $it_hub,
            'study' => $study,
            'designer' => $designer,
        );

        if (!empty($blueprint_image_url)) {
            // Only update the image URL if a new image was uploaded
            $update_data['blueprint_image_url'] = $blueprint_image_url;
        }

        $result = $wpdb->update(
            "{$wpdb->prefix}house_plans",
            $update_data,
            array('id' => $plan_id)
        );

        if ($result !== false) {
            wp_send_json_success(array('message' => 'Plan updated successfully.'));
        } else {
            wp_send_json_error('Failed to update plan.');
        }
    } else {
        // Insert new plan
        $insert_data = array(
            'house_design_id' => $house_design_id,
            'description' => $description,
            'price' => $price,
            'area' => $area,
            'width' => $width,
            'length' => $length,
            'bedrooms' => $bedrooms,
            'living_rooms' => $living_rooms,
            'bathrooms' => $bathrooms,
            'carport' => $carport,
            'butlers_pantry' => $butlers_pantry,
            'front_master' => $front_master,
            'media_room' => $media_room,
            'outdoor_living' => $outdoor_living,
            'rear_master' => $rear_master,
            'dual_living' => $dual_living,
            'it_hub' => $it_hub,
            'study' => $study,
            'designer' => $designer,
        );

        if (!empty($blueprint_image_url)) {
            $insert_data['blueprint_image_url'] = $blueprint_image_url;
        }

        $result = $wpdb->insert(
            "{$wpdb->prefix}house_plans",
            $insert_data
        );

        if ($result) {
            wp_send_json_success(array('message' => 'Plan saved successfully.', 'id' => $wpdb->insert_id));
        } else {
            wp_send_json_error('Failed to save plan.');
        }
    }
}

// Fetch all house designs with their variations and plans
add_action('wp_ajax_house_design_all', 'house_design_all');
function house_design_all()
{
    global $wpdb;

    $query = "
        SELECT 
            hd.id as house_design_id,
            hd.title as house_design_title,
            hd.floor_number as house_design_floor_number,
            hd.quotation_url as house_design_quotation_url,
            hd.visiting_page_url as house_design_visiting_page_url,
            dv.id as design_variation_id,
            dv.title as design_variation_title,
            dv.image_url as design_variation_image_url,
            hp.id as house_plan_id,
            hp.blueprint_image_url as house_plan_blueprint_image_url,
            hp.description as house_plan_description,
            hp.bedrooms as house_plan_bedrooms,
            hp.bathrooms as house_plan_bathrooms,
            hp.living_rooms as house_plan_living_rooms,
            hp.carport as house_plan_carport,
            hp.price as house_plan_price,
            hp.area as house_plan_area,
            hp.length as house_plan_length,
            hp.width as house_plan_width
        FROM {$wpdb->prefix}house_designs hd
        LEFT JOIN {$wpdb->prefix}house_design_variations dv ON hd.id = dv.house_design_id
        LEFT JOIN {$wpdb->prefix}house_plans hp ON hd.id = hp.house_design_id
    ";

    $results = $wpdb->get_results($query);

    if (empty($results)) {
        wp_send_json_error('No data found.');
        return;
    }

    $structured_results = array();

    foreach ($results as $row) {
        if (!isset($structured_results[$row->house_design_id])) {
            $structured_results[$row->house_design_id] = array(
                'id' => $row->house_design_id,
                'title' => $row->house_design_title,
                'floor_number' => $row->house_design_floor_number,
                'quotation_url' => $row->house_design_quotation_url,
                'visiting_page_url' => $row->house_design_visiting_page_url,
                'design_variations' => array(),
                'house_plans' => array(),
            );
        }

        if ($row->design_variation_id) {
            $structured_results[$row->house_design_id]['design_variations'][$row->design_variation_id] = array(
                'id' => $row->design_variation_id,
                'title' => $row->design_variation_title,
                'image_url' => $row->design_variation_image_url,
            );
        }

        if ($row->house_plan_id) {
            $structured_results[$row->house_design_id]['house_plans'][$row->house_plan_id] = array(
                'id' => $row->house_plan_id,
                'blueprint_image_url' => $row->house_plan_blueprint_image_url,
                'description' => $row->house_plan_description,
                'bedrooms' => $row->house_plan_bedrooms,
                'bathrooms' => $row->house_plan_bathrooms,
                'living_rooms' => $row->house_plan_living_rooms,
                'carport' => $row->house_plan_carport,
                'price' => $row->house_plan_price,
                'area' => $row->house_plan_area,
                'length' => $row->house_plan_length,
                'width' => $row->house_plan_width,
            );
        }
    }

    foreach ($structured_results as &$house_design) {
        $house_design['design_variations'] = array_values($house_design['design_variations']);
        $house_design['house_plans'] = array_values($house_design['house_plans']);
    }

    wp_send_json_success(array_values($structured_results));
}

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', 'hdp_enqueue_admin_assets');
function hdp_enqueue_admin_assets($hook)
{
    if ($hook != 'toplevel_page_house-designs') {
        return;
    }

    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
    wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/77ec53770a.js', array(), null, true);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('smarter-website-hdp', plugin_dir_url(__FILE__) . 'js/smarter-website-hdp.js', array('jquery'), null, true);
}

// Create or update custom tables on plugin activation
register_activation_hook(__FILE__, 'hdp_update_tables');
function hdp_update_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // House Designs table
    $table_name = $wpdb->prefix . 'house_designs';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        floor_number tinyint(1) NOT NULL,
        quotation_url varchar(255) DEFAULT NULL,
        visiting_page_url varchar(255) DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // House Design Variations table
    $table_name = $wpdb->prefix . 'house_design_variations';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        house_design_id mediumint(9) NOT NULL,
        title varchar(255) NOT NULL,
        image_url varchar(255) NOT NULL,
        PRIMARY KEY  (id),
        FOREIGN KEY (house_design_id) REFERENCES {$wpdb->prefix}house_designs(id) ON DELETE CASCADE
    ) $charset_collate;";
    dbDelta($sql);

    // House Plans table
    $table_name = $wpdb->prefix . 'house_plans';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        house_design_id mediumint(9) NOT NULL,
        blueprint_image_url varchar(255) NOT NULL,
        description text DEFAULT NULL,
        bedrooms tinyint(1),
        bathrooms tinyint(1),
        living_rooms tinyint(1),
        carport tinyint(1),
        price decimal(10,2) NOT NULL,
        area int(10) NOT NULL,
        length int(10) NOT NULL,
        width int(10) NOT NULL,
        butlers_pantry tinyint(1) DEFAULT 0,
        designer tinyint(1) DEFAULT 0,
        dual_living tinyint(1) DEFAULT 0,
        front_master tinyint(1) DEFAULT 0,
        it_hub tinyint(1) DEFAULT 0,
        media_room tinyint(1) DEFAULT 0,
        outdoor_living tinyint(1) DEFAULT 0,
        rear_master tinyint(1) DEFAULT 0,
        study tinyint(1) DEFAULT 0,
        PRIMARY KEY  (id),
        FOREIGN KEY (house_design_id) REFERENCES {$wpdb->prefix}house_designs(id) ON DELETE CASCADE
    ) $charset_collate;";
    dbDelta($sql);
}

// Add custom admin menu
add_action('admin_menu', 'hdp_add_admin_menu');
function hdp_add_admin_menu()
{
    add_menu_page(
        'House Designs',
        'House Designs',
        'manage_options',
        'house-designs',
        'hdp_render_admin_page',
        'dashicons-admin-home',
        6
    );
}

// Render the custom admin page
function hdp_render_admin_page()
{
    include plugin_dir_path(__FILE__) . 'templates/admin-page.php';
}



function house_design_filter_shortcode()
{
    ob_start(); ?>

    <div class="house-design-filter">
        <form id="house-design-filter-form">
            <div class="filter-group">
                <label for="filter-bedrooms">Bedrooms:</label>
                <input type="number" id="filter-bedrooms" name="bedrooms" min="1">
            </div>
            <div class="filter-group">
                <label for="filter-bathrooms">Bathrooms:</label>
                <input type="number" id="filter-bathrooms" name="bathrooms" min="1">
            </div>
            <div class="filter-group">
                <label for="filter-living-rooms">Living Rooms:</label>
                <input type="number" id="filter-living-rooms" name="living_rooms" min="1">
            </div>
            <div class="filter-group">
                <label for="filter-price">Max Price:</label>
                <input type="number" id="filter-price" name="price" min="0">
            </div>
            <!-- Add more filter fields as needed -->

            <button type="submit">Filter</button>
        </form>
    </div>

    <div id="house-design-results">
        <!-- Filtered results will be displayed here -->
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('house_design_filter', 'house_design_filter_shortcode');
