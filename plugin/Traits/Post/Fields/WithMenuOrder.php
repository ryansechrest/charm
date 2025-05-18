<?php

namespace Charm\Traits\Post\Fields;

use Charm\Contracts\Proxy\HasProxyPost;

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
        /** @var HasProxyPost $this */
        return $this->proxyPost()->getMenuOrder();
    }

    /**
     * Set the menu order.
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