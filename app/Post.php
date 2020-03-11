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
     * Post user
     *
     * @var User|null
     */
    protected $user = null;

    /**
     * Post parent
     *
     * @var Post|null
     */
    protected $parent = null;

    /**
     * Post metas
     *
     * @var array
     */
    protected $metas = [];

    /**
     * Created date
     *
     * @var DateTime|null
     */
    protected $created_date = null;

    /**
     * Created date (UTC)
     *
     * @var DateTime|null
     */
    protected $created_date_utc = null;

    /**
     * Updated date
     *
     * @var DateTime|null
     */
    protected $updated_date = null;

    /**
     * Updated date (UTC)
     *
     * @var DateTime|null
     */
    protected $updated_date_utc = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get user
     *
     * @return User|null
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }
        if (!$this->post_author) {
            return null;
        }

        return $this->user = User::init($this->post_author);
    }

    /**
     * Get parent
     *
     * @return Post|null
     */
    public function parent()
    {
        if ($this->parent) {
            return $this->parent;
        }
        if (!$this->post_parent) {
            return null;
        }

        return $this->parent = Post::init($this->post_parent);
    }

    /**
     * Get metas
     *
     * @param string $key
     * @return Meta|Meta[]|null
     */
    public function meta($key)
    {
        if (isset($this->metas[$key])) {
            return $this->metas[$key];
        }
        if (!$this->id) {
            return null;
        }
        $this->metas = Meta::init([
            'meta_type' => 'post',
            'object_id' => $this->id,
        ]);
        if (!isset($this->metas[$key])) {
            return null;
        }

        return $this->metas[$key];
    }

    /**
     * Get created date
     *
     * @return DateTime
     */
    public function created_date()
    {
        $timezone = get_option('timezone_string');

        return $this->created_date = DateTime::init($this->post_date, $timezone);
    }

    /**
     * Get created date
     *
     * @return DateTime
     */
    public function created_date_utc()
    {
        return $this->created_date_utc = DateTime::init($this->post_date_gmt);
    }

    /**
     * Get updated date
     *
     * @return DateTime
     */
    public function updated_date()
    {
        $timezone = get_option('timezone_string');

        return $this->updated_date = DateTime::init($this->post_modified, $timezone);
    }

    /**
     * Get updated date (UTC)
     *
     * @return DateTime
     */
    public function updated_date_utc()
    {
        return $this->updated_date_utc = DateTime::init($this->post_modified_gmt);
    }
}