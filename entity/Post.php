<?php

namespace Charm\Entity;

use Charm\DataType\DateTime;
use Charm\Entity\PostMeta as CharmPostMeta;
use Charm\Feature\Meta as MetaFeature;
use Charm\Module\PostType;
use Charm\WordPress\Post as WpPost;
use WP_Query;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Post extends WpPost
{
    use MetaFeature;

    /************************************************************************************/
    // Constants

    /**
     * User class
     *
     * @var string
     */
    const USER = 'Charm\Entity\User';

    /**
     * Post class
     *
     * @var string
     */
    const POST = 'Charm\Entity\Post';

    /**
     * Meta class
     *
     * @var string
     */
    const META = 'Charm\Entity\PostMeta';

    /**
     * Taxonomy class
     *
     * @var string
     */
    const TAXONOMY = 'Charm\Entity\Taxonomy';

    /**
     * DateTime class
     *
     * @var string
     */
    const DATE_TIME = 'Charm\DataType\DateTime';

    /************************************************************************************/
    // Properties

    /**
     * Permalink
     *
     * @var string
     */
    protected $permalink = '';

    /**
     * Edit post link
     *
     * @var string
     */
    protected $edit_post_link = '';

    /*----------------------------------------------------------------------------------*/

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
     * Post date object
     *
     * @var DateTime
     */
    protected $post_date_obj = null;

    /**
     * Post date object (GMT)
     *
     * @var DateTime
     */
    protected $post_date_gmt_obj = null;

    /**
     * Post modified object
     *
     * @var DateTime
     */
    protected $post_modified_obj = null;

    /**
     * Post modified object (GMT)
     *
     * @var DateTime
     */
    protected $post_modified_gmt_obj = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * Taxonomy objects
     *
     * @var array
     */
    protected $taxonomy_objs = [];

    /**
     * Post type object
     *
     * @var PostType
     */
    protected $post_type_obj = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        $data['post_type'] = static::post_type();
        parent::load($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get posts
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        return self::query($params)->posts;
    }

    /**
     * Query using WP_Query
     *
     * @param array $params
     * @return WP_Query
     */
    public static function query(array $params = []): WP_Query
    {
        $params['post_type'] = static::post_type();

        return parent::query($params);
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get post type
     *
     * @return string
     */
    public static function post_type(): string
    {
        return 'post';
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get post author
     *
     * @return User|null
     */
    public function post_author(): ?User
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
    public function post_parent(): ?Post
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

    /**
     * Get post date
     *
     * @return DateTime
     */
    public function post_date(): DateTime
    {
        if ($this->post_date_obj) {
            return $this->post_date_obj;
        }
        $timezone = get_option('timezone_string');

        return $this->post_date_obj = call_user_func(
            static::DATE_TIME . '::init', $this->post_date, $timezone
        );
    }

    /**
     * Get post date (GMT)
     *
     * @return DateTime
     */
    public function post_date_gmt(): DateTime
    {
        if ($this->post_date_gmt_obj) {
            return $this->post_date_gmt_obj;
        }

        return $this->post_date_gmt_obj = call_user_func(
            static::DATE_TIME . '::init', $this->post_date_gmt
        );
    }

    /**
     * Get post modified
     *
     * @return DateTime
     */
    public function post_modified(): DateTime
    {
        if ($this->post_modified_obj) {
            return $this->post_modified_obj;
        }
        $timezone = get_option('timezone_string');

        return $this->post_modified_obj = call_user_func(
            static::DATE_TIME . '::init', $this->post_modified, $timezone
        );
    }

    /**
     * Get post modified (GMT)
     *
     * @return DateTime
     */
    public function post_modified_gmt(): DateTime
    {
        if ($this->post_modified_gmt_obj) {
            return $this->post_modified_gmt_obj;
        }

        return $this->post_modified_gmt_obj = call_user_func(
            static::DATE_TIME . '::init', $this->post_modified_gmt
        );
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get taxonomy
     *
     * @param string $name
     * @return Taxonomy|null
     */
    public function taxonomy(string $name): ?Taxonomy
    {
        if (isset($this->taxonomy_objs[$name])) {
            return $this->taxonomy_objs[$name];
        }
        if (!$taxonomy = Taxonomy::init($name)) {
            return null;
        }
        $taxonomy->set_object_id($this->id);

        return $this->taxonomy_objs[$name] = $taxonomy;
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

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        return array_merge(
            parent::to_array(),
            ['metas' => array_map(function(CharmPostMeta $meta) {
                return $meta->get_meta_value();
            }, $this->meta())]
        );
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get permalink
     *
     * @see get_permalink()
     * @return string
     */
    public function get_permalink(): string
    {
        if ($this->permalink !== '') {
            return $this->permalink;
        }
        if ($this->id === 0) {
            return '';
        }
        $permalink = get_permalink($this->id);
        if ($permalink === false) {
            return '';
        }

        return $this->permalink = $permalink;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get permalink
     *
     * @see get_edit_post_link()
     * @return string
     */
    public function get_edit_post_link(): string
    {
        if ($this->edit_post_link !== '') {
            return $this->edit_post_link;
        }
        if ($this->id === 0) {
            return '';
        }
        $edit_post_link = get_edit_post_link($this->id);
        if ($edit_post_link === null) {
            return '';
        }

        return $this->edit_post_link = $edit_post_link;
    }
}