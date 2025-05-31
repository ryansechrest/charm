<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Core\HasCorePost;

/**
 * Adds the menu order to a post model.
 *
 * Table: `wp_posts`
 * Column: `menu_order`
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithMenuOrder
{
    /**
     * Get the menu order.
     *
     * @return int
     */
    public function getMenuOrder(): int
    {
        /** @var HasCorePost $this */
        return $this->corePost()->getMenuOrder();
    }

    /**
     * Set the menu order.
     *
     * @param int $menuOrder
     * @return $this
     */
    public function setMenuOrder(int $menuOrder): static
    {
        /** @var HasCorePost $this */
        $this->corePost()->setMenuOrder($menuOrder);

        return $this;
    }
}