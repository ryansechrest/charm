<?php

namespace Charm\App;

use Charm\App\DataType\DateTime;
use Charm\WordPress\Post as WpPost;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\App
 */
class Post extends WpPost
{
    /**
     * Post type
     */
    const POST_TYPE = 'post';

    /**
     * User class
     */
    const USER = 'Charm\App\User';

    /**
     * Post class
     */
    const POST = 'Charm\App\Post';

    /**
     * Meta class
     */
    const META = 'Charm\App\Meta';

    /**
     * DateTime class
     */
    const DATETIME = 'Charm\App\DataType\DateTime';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (!isset($data['post_type'])) {
            $data['post_type'] = static::POST_TYPE;
        }
        parent::load($data);
    }

    /************************************************************************************/
    // Object properties

    /**
     * Post author object
     *
     * @var User|null
     */
    protected $post_author_obj = null;

    /**
     * Post parent object
     *
     * @var Post|null
     */
    protected $post_parent_obj = null;

    /**
     * Post metas
     *
     * @var array
     */
    protected $metas = [];

    /**
     * Post date object
     *
     * @var DateTime|null
     */
    protected $post_date_obj = null;

    /**
     * Post date object (GMT)
     *
     * @var DateTime|null
     */
    protected $post_date_gmt_obj = null;

    /**
     * Post modified object
     *
     * @var DateTime|null
     */
    protected $post_modified_obj = null;

    /**
     * Post modified object (GMT)
     *
     * @var DateTime|null
     */
    protected $post_modified_gmt_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get post author
     *
     * @return User|null
     */
    public function post_author(): User
    {
        if ($this->post_author_obj) {
            return $this->post_author_obj;
        }
        if (!$this->post_author) {
            return null;
        }

        return $this->post_author_obj = call_user_func(
            static::USER . '::init', $this->post_author
        );
    }

    /**
     * Get post parent
     *
     * @return Post|null
     */
    public function post_parent(): Post
    {
        if ($this->post_parent_obj) {
            return $this->post_parent_obj;
        }
        if (!$this->post_parent) {
            return null;
        }

        return $this->post_parent_obj = call_user_func(
            static::POST . '::init', $this->post_parent
        );
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get or create meta(s)
     *
     * @param string $key
     * @param mixed $value
     * @return Meta|Meta[]|null
     */
    public function meta($key, $value = null)
    {
        if ($value !== null) {
            $this->save_meta($key, $value);
        }

        return $this->get_meta($key);
    }

    /**
     * Get post meta from Post
     *
     * @param string $key
     * @return Meta|Meta[]
     */
    private function get_meta(string $key)
    {
        if (count($this->metas) === 0) {
            $this->metas = $this->get_metas();
        }
        if (!isset($this->metas[$key])) {
            return null;
        }

        return $this->metas[$key];
    }

    /**
     * Get post metas from database
     *
     * @return array
     */
    private function get_metas(): array
    {
        return call_user_func(
            static::META . '::init', [
                'meta_type' => 'post',
                'object_id' => $this->id,
            ]
        );
    }

    /**
     * Create post meta instance
     *
     * @param string $key
     * @param mixed $value
     * @return Meta
     */
    private function create_meta(string $key, $value)
    {
        $meta = static::META;

        return new $meta([
            'meta_type' => 'post',
            'meta_key' => $key,
            'meta_value' => $value,
        ]);
    }

    /**
     * Save post meta in Post
     *
     * @param string $key
     * @param mixed $value
     */
    private function save_meta(string $key, $value): void
    {
        $meta = $this->create_meta($key, $value);
        if (!isset($this->metas[$key])) {
            $this->metas[$key] = $meta;
            return;
        }
        if (is_array($this->metas[$key])) {
            $this->metas[$key][] = $meta;
            return;
        }
        $this->metas[$key] = [$this->metas[$key], $meta];
    }

    /**
     * Save post metas in database
     */
    private function save_metas(): void
    {
        if (count($this->metas) === 0) {
            return;
        }
        foreach ($this->metas as $key => $meta) {
            if (!is_array($meta)) {
                $meta->set_object_id($this->id);
                $meta->save();
                continue;
            }
            foreach ($meta as $single_meta) {
                $single_meta->set_object_id($this->id);
                $single_meta->save();
            }
        }
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get post date
     *
     * @return DateTime
     */
    public function post_date(): DateTime
    {
        $timezone = get_option('timezone_string');

        return $this->post_date_obj = call_user_func(
            static::DATETIME, $this->post_date, $timezone
        );
    }

    /**
     * Get post date (GMT)
     *
     * @return DateTime
     */
    public function post_date_gmt(): DateTime
    {
        return $this->post_date_gmt_obj = call_user_func(
            static::DATETIME, $this->post_date_gmt
        );
    }

    /**
     * Get post modified
     *
     * @return DateTime
     */
    public function post_modified(): DateTime
    {
        $timezone = get_option('timezone_string');

        return $this->post_modified_obj = call_user_func(
            static::DATETIME, $this->post_modified, $timezone
        );
    }

    /**
     * Get post modified (GMT)
     *
     * @return DateTime
     */
    public function post_modified_gmt(): DateTime
    {
        return $this->post_modified_gmt_obj = call_user_func(
            static::DATETIME, $this->post_modified_gmt
        );
    }

    /************************************************************************************/
    // Action methods

    /**
     * Create post with metas
     *
     * @return bool
     */
    public function create(): bool
    {
        if (!parent::create()) {
            return false;
        }
        $this->save_metas();

        return true;
    }

    /**
     * Update post with metas
     */
    public function update(): bool
    {
        if (!parent::update()) {
            return false;
        }
        $this->save_metas();

        return true;
    }
}