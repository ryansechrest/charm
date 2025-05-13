<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\HasProxyPost;

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
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getMenuOrder();
    }

    /**
     * Set menu order
     *
     * @param int $menuOrder
     * @return $this
     */
    public function setMenuOrder(int $menuOrder): static
    {
        /** @var HasProxyPost $this */
        $this->proxyPost()->setMenuOrder($menuOrder);

        return $this;
    }
}