<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasWpPost;

/**
 * Adds menu order to post model.
 *
 * Table: wp_posts
 * Column: menu_order
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMenuOrder
{
    /**
     * Get menu order
     *
     * @return int
     */
    public function getMenuOrder(): int
    {
        /** @var HasWpPost $this */
        return $this->wp()->getMenuOrder();
    }

    /**
     * Set menu order
     *
     * @param int $menuOrder
     * @return $this
     */
    public function setMenuOrder(int $menuOrder): static
    {
        /** @var HasWpPost $this */
        $this->wp()->setMenuOrder($menuOrder);

        return $this;
    }
}