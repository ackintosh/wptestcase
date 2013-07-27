<?php
namespace Ackintosh;
use Symfony\Component\Yaml\Yaml;

class WPTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * The table prefix for testing.
     * @var string
     */
    protected $test_prefix = 'wptest_';

    /**
     * Disable the backup and restore of the super global variable.
     * Because, MySQL resource contained in $wpdb can not be serialize.
     * @var boolean
     */
    protected $backupGlobals = false;

    /**
     * @var WPFunctions
     */
    public    $wpfunctions;

    public function __construct()
    {
        parent::__construct();

        $this->wpfunctions = new WPFunctions();

        global $wpdb;
        if (!$wpdb) $this->loadWpFunctions(__DIR__);
        $wpdb->show_errors = true;
    }

    /**
     * Search wp-load.php recursively.
     *
     * @param  string Target directory
     * @return void
     */
    protected function loadWpFunctions($dir)
    {
        if (file_exists($dir . '/wp-load.php')) {
            require_once($dir . '/wp-load.php');
            return;
        }

        if ($dir === '/') {
            throw new Exception('wp-load.php not found.');
        }
        return $this->loadWpFunctions(realpath($dir . '/../'));
    }

    public function setUp()
    {
        parent::setUp();

        // Set the table prefix for testing.
        global $wpdb;
        $wpdb->set_prefix($this->test_prefix);
        $this->truncate();
    }

    public function tearDown()
    {
        parent::tearDown();

        // Restore the default table prefix.
        global $wpdb, $table_prefix;
        $wpdb->set_prefix($table_prefix);
    }

    /**
     * Truncate the test tables.
     */
    public function truncate()
    {
        global $wpdb;
        $tables = array_merge($wpdb->tables, $wpdb->global_tables);
        foreach ($tables as $t) {
            $result = $wpdb->query(sprintf('TRUNCATE TABLE %s%s', $wpdb->prefix, $t));
            if ($result === false) throw new Exception('faild truncate tables.');
        }
        return;
    }

    /**
     * Insert fixture data for posts.
     *
     * @param  string $yaml                 Path to YAML file or a string containing YAML
     * @return array  $inserted_post_ids
     * @throws RuntimeException If failed wp_insert_post
     */
    public function setPostFixture($yaml)
    {
        $this->truncate();

        $parsed = Yaml::parse($yaml);
        $inserted_post_ids = array();
        foreach ($parsed as $p) {
            $result = $this->wpfunctions->wp_insert_post($p);
            if ($result === 0) throw new RuntimeException('failed insert post');
            $inserted_post_ids[] = $result;
        }
        return $inserted_post_ids;
    }
}

