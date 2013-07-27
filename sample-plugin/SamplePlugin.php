<?php

class SamplePlugin
{
    public static function insert_post($post)
    {
        $result = wp_insert_post($post);
        return $result;
    }

    public static function get_new_post_with_uppercase()
    {
        list($post) = get_posts(array('numberposts' => 1));
        $post->post_title = strtoupper($post->post_title);
        $post->post_content = strtoupper($post->post_content);
        return $post;
    }
}
