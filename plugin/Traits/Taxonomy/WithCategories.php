<?php

namespace Charm\Traits\Taxonomy;

use Charm\Contracts\HasTerms;
use Charm\Models\Terms\Category;

/**
 * Adds categories to a model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithCategories
{
    /**
     * Get the categories.
     *
     * @return Category[]
     */
    public function getCategories(): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->getTerms();
    }

    // -------------------------------------------------------------------------

    /**
     * Add a category to the model.
     *
     * @param int|string|Category $category Category ID, slug, or instance
     * @return static
     */
    public function addCategory(int|string|Category $category): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->addTerm($category);

        return $this;
    }

    /**
     * Add categories to the model.
     *
     * @param array $categories Array of category IDs, slugs, or instances
     * @return static
     */
    public function addCategories(array $categories): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->addTerms($categories);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Remove a category from the model.
     *
     * @param int|string|Category $category Category ID, slug, or instance
     * @return static
     */
    public function removeCategory(int|string|Category $category): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->removeTerm($category);

        return $this;
    }

    /**
     * Remove categories from the model.
     *
     * @param array $categories Array of category IDs, slugs, or instances
     * @return static
     */
    public function removeCategories(array $categories): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->removeTerms($categories);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Set categories on the model (replaces existing categories).
     *
     * @param array $categories Array of category IDs, slugs, or instances
     * @return $this
     */
    public function setCategories(array $categories): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->setTerms($categories);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the model has the specified category.
     *
     * @param int|string|Category $category Category ID, slug, or instance
     * @return bool
     */
    public function hasCategory(int|string|Category $category): bool
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->hasTerm($category);
    }

    /**
     * Check whether the model has ALL the specified categories.
     *
     * @param array $categories Array of category IDs, slugs, or instances
     * @return bool
     */
    public function hasAllCategories(array $categories): bool
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->hasAllTerms($categories);
    }

    /**
     * Check whether the model has ANY of the specified categories.
     *
     * @param array $categories Array of category IDs, slugs, or instances
     * @return bool
     */
    public function hasAnyCategories(array $categories): bool
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->hasAnyTerms($categories);
    }
}