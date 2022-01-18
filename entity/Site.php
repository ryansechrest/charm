<?php

namespace Charm\Entity;

use Charm\DataType\DateTime;
use Charm\WordPress\Site as WpSite;

/**
 * Class Site (from wp_blogs)
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Site extends WpSite
{
    /**
     * DateTime class
     *
     * @var string
     */
    const DATE_TIME = 'Charm\DataType\DateTime';

    /************************************************************************************/
    // Properties

    /**
     * Registered object
     *
     * @var DateTime|null
     */
    protected ?DateTime $registered_obj = null;

    /**
     * Last updated object
     *
     * @var DateTime|null
     */
    protected ?DateTime $last_updated_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get site ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Get registered
     *
     * @return DateTime
     */
    public function registered(): DateTime
    {
        if ($this->registered_obj) {
            return $this->registered_obj;
        }
        $option = Option::init('timezone_string');

        return $this->registered_obj = call_user_func(
            static::DATE_TIME . '::init',
            $this->registered,
            $option->cast()->string()
        );
    }

    /**
     * Get last updated
     *
     * @return DateTime
     */
    public function last_updated(): DateTime
    {
        if ($this->last_updated_obj) {
            return $this->last_updated_obj;
        }
        $option = Option::init('timezone_string');

        return $this->last_updated_obj = call_user_func(
            static::DATE_TIME . '::init',
            $this->last_updated,
            $option->cast()->string()
        );
    }
}