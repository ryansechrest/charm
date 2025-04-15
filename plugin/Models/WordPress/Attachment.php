<?php

namespace Charm\Models\WordPress;

/**
 * Represents an attachment in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Attachment extends BasePost
{
    /**
     * Post type for an attachment in WordPress
     */
    protected const POST_TYPE = 'attachment';
}