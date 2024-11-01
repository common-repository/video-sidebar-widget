<?php
add_action('widgets_init', 'video_widget');

function video_widget() {
    register_widget('Video_Widget');
}

class Video_Widget extends WP_Widget {

    function Video_Widget() {
        $widget_ops = array('classname' => 'youtube_url', 'description' => __('A widget that displays Video ', 'youtube_url'));

        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'video-widget');

        $this->WP_Widget('video-widget', __('Video Url', 'youtube_url'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title']);
        $desc = $instance['desc'];
        echo $before_widget;

        // Display the widget title 
        if ($title)
            echo $before_title . $title . $after_title;
        videourl_view();
        echo $after_widget;
    }

    //Update the widget 

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        //Strip tags from title and name to remove HTML 
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form($instance) {
?><p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'example'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

<?php
    }

}