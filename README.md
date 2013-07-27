#WPTestCase

Testing tool with fixture feature for WordPress plugin development, based on the PHPUnit.

<a href="http://phpunit.de/manual/3.7/en/index.html" target="_blank">http://phpunit.de/manual/3.7/en/index.html</a>

##Installation
`composer.json`

```
{
    "require": {
        "ackintosh/wptestcase": "dev-master"
    }
}
```

```
$ php composer.phar install
```

##Usage

+ Install this tool.

```
$ cd wordpress/wp-content/plugins
$ php composer.phar install
```

+ Set up tables for testing that was changed to 'wptest_' a prefix.

![Set up tables](https://dl.dropboxusercontent.com/u/22083548/github/wptestcase/prepare_tables.png)

+ Prepare the fixture file written in YAML.

`post_fixture.yml`

```yaml
- 
  post_title: Test post title 1
  post_content: Test post content 1
  post_status: publish
- 
  post_title: Test post title 2
  post_content: Test post content 2
  post_status: publish
```
Available parameters are the same as 'wp_insert_post' function.

<a href="http://codex.wordpress.org/Function_Reference/wp_insert_post" target="_blank">Function Reference/wp insert post</a>


+ Write test code.

```php
class SamplePluginTest extends Ackintosh\WPTestCase
{

    // The table prefix for testing can be changed.
    // protected $test_prefix = 'wptest_';

    public function test_insert_post()
    {
        // Loading data.
        $this->setPostFixture(__DIR__ . '/post_fixture.yml');

        // We can get the post data that inserted by the fixture function.
        $posts = get_posts();

        // Can use all features in PHPUnit.
        $this->assertEquals($expect, $result);
    }
}
```

+ Run the tests.

```
$ vendor/bin/phpunit
```


##Requirements
- PHP 5.3 or greater
