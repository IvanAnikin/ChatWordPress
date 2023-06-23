

<?php
/**
 * Plugin Name: My Chat Plugin
 * Plugin URI: [Plugin homepage URL]
 * Description: [Plugin description]
 * Version: 1.0.0
 * Author: [Your name]
 * Author URI: [Your website or profile URL]
 */

class My_Chat_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'my_chat_widget',
            'My Chat Widget',
            array('description' => 'Display a chat widget for user interaction')
        );
    }

    public function widget($args, $instance) {
        wp_enqueue_script('my-chat-script', plugins_url('chat_script.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_enqueue_style('my-chat-style', plugins_url('styles.css', __FILE__), array(), '1.0.0');

        // Output the widget content
            echo $args['before_widget'];

            // Widget title
            $title = apply_filters('widget_title', $instance['title']);
            if (!empty($title)) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            // Widget content
            echo '<div class="my-chat-widget">';
            echo '<div class="my-chat-widget-answer"></div>';
            echo '<input class="widefat" type="text">';
            echo '<button class="widefat my-chat-submit-button">Send</button>';
            echo '</div>';

            echo $args['after_widget'];
        
    }

    public function form($instance) {
        $prompt = !empty($instance['prompt']) ? $instance['prompt'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('prompt'); ?>">Input Prompt:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('prompt'); ?>"
                   name="<?php echo $this->get_field_name('prompt'); ?>" type="text"
                   value="<?php echo esc_attr($prompt); ?>"/>
        </p>
        <p>
            <button class="widefat my-chat-submit-button">Send</button>
        </p>
        <script>
            jQuery(document).ready(function ($) {
                $('.my-chat-submit-button').click(function (event) {
                    event.preventDefault();
                    var input = $(this).siblings('input').val();

                    console.log(input)


                    // Send the input to the ChatGPT API using AJAX
                    $.ajax({
                        url: ajax_object.ajax_url, // The AJAX endpoint URL
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'my_chat_send_message',
                            input: input,
                        },
                        beforeSend: function () {
                            // Show loading indicator or disable the button
                        },
                        success: function (response) {
                            // Handle the response from the ChatGPT API
                            if (response.success) {
                                var answer = response.data.answer;
                                // Update the widget display with the answer
                                $('.my-chat-widget-answer').text(answer);


                                cho $args['before_widget'];

                                // Widget title
                                $title = apply_filters('widget_title', $instance['title']);
                                if (!empty($title)) {
                                    echo $args['before_title'] . $title . $args['after_title'];
                                }

                                // Widget content
                                echo '<div class="my-chat-widget">';
                                echo '<div class="my-chat-widget-answer"></div>';
                                echo '<input class="widefat" type="text">';
                                echo '<button class="widefat my-chat-submit-button">Send</button>';
                                echo '</div>';

                                echo $args['after_widget'];

                                
                            } else {
                                // Handle the error case
                                console.log(response.data.error);
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle the AJAX request error
                            console.log(error);
                        },
                        complete: function () {
                            // Hide the loading indicator or enable the button
                        }
                    });

                    // Send the input to the ChatGPT API using AJAX
                    // Handle the response and update the widget display
                });
            });
        </script>
        <?php
    }

    public function update($new_instance, $old_instance) {
        // Save widget settings
    }
}

add_action('widgets_init', function () {
    register_widget('My_Chat_Widget');
});


add_action('wp_ajax_my_chat_send_message', 'my_chat_send_message_callback');
add_action('wp_ajax_nopriv_my_chat_send_message', 'my_chat_send_message_callback');

function my_chat_send_message_callback() {
    $response = array();

    // Get the input from the AJAX request
    $input = $_POST['input'];

    // TODO: Call the ChatGPT API and get the response
    // Replace the placeholder code below with your implementation
    $api_response = call_chat_gpt_api($input);

    if ($api_response) {
        // Process the API response and prepare the answer
        $answer = process_api_response($api_response);

        // Prepare the response to send back
        $response['success'] = true;
        $response['data']['answer'] = $answer;
    } else {
        // If there was an error with the API request
        $response['success'] = false;
        $response['data']['error'] = 'API request failed';
    }

    wp_send_json($response);
}

