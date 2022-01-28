<?php

namespace Charm\Entity;

use Charm\DataType\DateTime;
use Charm\Feature\Meta as MetaFeature;
use Charm\Feature\Taxonomy as TaxonomyFeature;
use Charm\Module\PostType;
use Charm\WordPress\Post as WpPost;
use WP_Query;

/**
 * Class BasePost
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class BasePost extends WpPost
{
    use MetaFeature;
    use TaxonomyFeature;

    /************************************************************************************/
    // Constants

    /**
     * User class
     *
     * @var string
     */
    const USER = 'Charm\Entity\User';

    /**
     * Meta class
     *
     * @var string
     */
    const META = 'Charm\Entity\PostMeta';

    /**
     * DateTime class
     *
     * @var string
     */
    const DATE_TIME = 'Charm\DataType\DateTime';

    /************************************************************************************/
    // Properties

    /**
     * URL
     *
     * @var string
     */
    protected string $url = '';

    /**
     * Edit URL
     *
     * @var string
     */
    protected string $edit_url = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * Author object
     *
     * @var User|null
     */
    protected ?User $author_obj = null;

    /**
     * Parent object
     *
     * @var BasePost|null
     */
    protected ?BasePost $parent_obj = null;

    /**
     * Created date object
     *
     * @var DateTime|null
     */
    protected ?DateTime $created_date_obj = null;

    /**
     * Created date object (GMT)
     *
     * @var DateTime|null
     */
    protected ?DateTime $created_date_gmt_obj = null;

    /**
     * Updated date object
     *
     * @var DateTime|null
     */
    protected ?DateTime $updated_date_obj = null;

    /**
     * Update date object (GMT)
     *
     * @var DateTime|null
     */
    protected ?DateTime $updated_date_gmt_obj = null;

    /*----------------------------------------------------------------------------------*/

    /**
     * Post type object
     *
     * @var PostType|null
     */
    protected ?PostType $post_type_obj = null;

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
     * Blank to accommodate instantiating any entity regardless of post type.
     *
     * @return string
     */
    public static function post_type(): string
    {
        return '';
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get post ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Get author
     *
     * @return User|null
     */
    public function author(): ?User
    {
        if ($this->author_obj) {
            return $this->author_obj;
        }
        if (!$this->post_author) {
            return null;
        }

        return $this->author_obj = call_user_func(
            static::USER . '::init', $this->post_author
        );
    }

    /**
     * Get parent
     *
     * @return BasePost|null
     */
    public function parent(): ?BasePost
    {
        if ($this->parent_obj) {
            return $this->parent_obj;
        }
        if (!$this->post_parent) {
            return null;
        }

        return $this->parent_obj = static::init($this->post_parent);
    }

    /**
     * Get created date
     *
     * @return DateTime
     */
    public function created_date(): DateTime
    {
        if ($this->created_date_obj) {
            return $this->created_date_obj;
        }
        $option = Option::init('timezone_string');

        return $this->created_date_obj = call_user_func(
            static::DATE_TIME . '::init',
            $this->post_date,
            $option->cast()->string()
        );
    }

    /**
     * Get created date (GMT)
     *
     * @return DateTime
     */
    public function created_date_gmt(): DateTime
    {
        if ($this->created_date_gmt_obj) {
            return $this->created_date_gmt_obj;
        }

        return $this->created_date_gmt_obj = call_user_func(
            static::DATE_TIME . '::init', $this->post_date_gmt
        );
    }

    /**
     * Get updated date
     *
     * @return DateTime
     */
    public function updated_date(): DateTime
    {
        if ($this->updated_date_obj) {
            return $this->updated_date_obj;
        }
        $option = Option::init('timezone_string');

        return $this->updated_date_obj = call_user_func(
            static::DATE_TIME . '::init',
            $this->post_modified,
            $option->cast()->string()
        );
    }

    /**
     * Get updated date (GMT)
     *
     * @return DateTime
     */
    public function updated_date_gmt(): DateTime
    {
        if ($this->updated_date_gmt_obj) {
            return $this->updated_date_gmt_obj;
        }

        return $this->updated_date_gmt_obj = call_user_func(
            static::DATE_TIME . '::init', $this->post_modified_gmt
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
            ['metas' => array_map(function($meta) {
                if (!is_array($meta)) {
                    return $meta->get_meta_value();
                }
                return array_map(function($meta) {
                    return $meta->get_meta_value();
                }, $meta);
            }, $this->meta())]
        );
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get URL
     *
     * @see get_permalink()
     * @return string
     */
    public function get_url(): string
    {
        if ($this->url !== '') {
            return $this->url;
        }
        if ($this->id === 0) {
            return '';
        }
        $url = get_permalink($this->id);
        if ($url === false) {
            return '';
        }

        return $this->url = $url;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get edit URL
     *
     * @see get_edit_post_link()
     * @return string
     */
    public function get_edit_url(): string
    {
        if ($this->edit_url !== '') {
            return $this->edit_url;
        }
        if ($this->id === 0) {
            return '';
        }
        $edit_url = get_edit_post_link($this->id);
        if ($edit_url === null) {
            return '';
        }

        return $this->edit_url = $edit_url;
    }
}