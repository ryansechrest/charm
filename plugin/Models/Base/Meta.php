<?php

namespace Charm\Models;

/**
 * Represents a base meta field in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class BaseMeta
{
    /**
     * Meta type
     *
     * WordPress supports: `comment`, `post`, `term`, and `user`.
     */
    protected const META_TYPE = '';

    /**
     * Meta ID field
     *
     * comment -> meta_id
     * post    -> meta_id
     * term    -> meta_id
     * user    -> umeta_id
     */
    protected const META_ID_FIELD = 'meta_id';

    /*------------------------------------------------------------------------*/

    /**
     * WordPress meta
     *
     * @var ?WordPress\Meta
     */
    protected ?WordPress\Meta $wpMeta = null;

    /**************************************************************************/

    /**
     * BaseMeta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpMeta = new WordPress\Meta(static::META_TYPE, $data);
    }

    /**
     * Get WordPress meta instance
     *
     * @return ?WordPress\Meta
     */
    public function wp(): ?WordPress\Meta
    {
        return $this->wpMeta;
    }
}