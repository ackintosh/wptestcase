<?php

class WPTestCaseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $wpdb, $table_prefix;
        $wpdb = new stdClass();
        $table_prefix = 'wp_';
        $this->wptc = new Ackintosh\WPTestCase();
    }

    /**
     * @test
     */
    public function sets_to_true_show_errors()
    {
        global $wpdb;
        $this->assertTrue($wpdb->show_errors);
    }

    /**
     * @test
     */
    public function setUp_sets_prefix_for_testing()
    {
        global $wpdb;
        $wpdb = $this->getMock('wpdb', array('set_prefix'));

        // For truncate method.
        $wpdb->tables = array();
        $wpdb->global_tables = array();

        $wpdb->expects($this->once())
             ->method('set_prefix')
             ->with('wptest_');

        $this->wptc->setUp();
    }

    /**
     * @test
     */
    public function tearDown_sets_default_prefix()
    {
        global $wpdb, $table_prefix;
        $wpdb = $this->getMock('wpdb', array('set_prefix'));
        $wpdb->expects($this->once())
             ->method('set_prefix')
             ->with($table_prefix);

        $this->wptc->tearDown();
    }

    /**
     * @test
     */
    public function truncate_tables()
    {
        global $wpdb;
        $wpdb = $this->getmock('wpdb', array('query'));
        $wpdb->tables = array(
            'posts',
        );
        $wpdb->global_tables = array(
        );
        $wpdb->prefix = 'wptest_';
        $wpdb->expects($this->once())
             ->method('query')
             ->with('TRUNCATE TABLE wptest_posts');

        $this->wptc->truncate();
    }

    /**
     * @test
     */
    public function truncate_global_tables()
    {
        global $wpdb;
        $wpdb = $this->getmock('wpdb', array('query'));
        $wpdb->tables = array(
        );
        $wpdb->global_tables = array(
            'usermeta',
        );
        $wpdb->prefix = 'wptest_';
        $wpdb->expects($this->once())
             ->method('query')
             ->with('TRUNCATE TABLE wptest_usermeta');

        $this->wptc->truncate();
    }

    /**
     * @test
     */
    public function setPostFixture_calls_truncate_and_insert_posts()
    {
        global $wpdb;

        // Verfy 'truncate' method has been called.
        $wpdb = $this->getMock('wpdb', array('query'));
        $wpdb->prefix = 'wptest_';
        $wpdb->tables = array('posts');
        $wpdb->global_tables = array();
        $wpdb->expects($this->once())
             ->method('query')
             ->with('TRUNCATE TABLE wptest_posts');

        // Test data
        $expects = array(
            'post_title' => 'test title',
            'post_content' => 'test content',
        );
        $yaml = <<< __EOS__
- 
  post_title: test title
  post_content: test content
__EOS__;

        // Replace to mock the WP function.
        $this->wptc->wpfunctions = $this->getMock('WPFunctions', array('wp_insert_post'));
        $this->wptc->wpfunctions->expects($this->once())
             ->method('wp_insert_post')
             ->with($expects);

        $this->wptc->setPostFixture($yaml);
    }
}

