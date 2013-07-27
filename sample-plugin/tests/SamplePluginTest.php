<?php
require_once __DIR__ . '/../SamplePlugin.php';

class SamplePluginTest extends Ackintosh\WPTestCase
{
    public function test_insert_post()
    {

        $post = array(
            'post_title' => 'Test post title',
            'post_content' => 'Test post content',
            'post_status' => 'publish',
        );
        $insert_result_id = SamplePlugin::insert_post($post);
        $this->assertTrue($insert_result_id > 0);

        list($new_post) = get_posts(array('numberposts' => 1));

        $this->assertEquals($new_post->ID,           $insert_result_id);
        $this->assertEquals($new_post->post_title,   $post['post_title']);
        $this->assertEquals($new_post->post_content, $post['post_content']);
        $this->assertEquals($new_post->post_status,  $post['post_status']);
    }

    public function test_with_fixture()
    {
        $this->setPostFixture(__DIR__ . '/fixture.yml');
        $post = SamplePlugin::get_new_post_with_uppercase();

        $this->assertEquals('TEST POST TITLE 4', $post->post_title);
        $this->assertEquals('TEST POST CONTENT 4', $post->post_content);
    }
}

