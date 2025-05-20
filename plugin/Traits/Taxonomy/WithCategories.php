<?php

namespace Charm\Traits\Taxonomy;

use Charm\Contracts\HasTerms;
use Charm\Models\Terms\Category;
use Charm\Support\Result;

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

    /**
     * Add categories to the model.
     *
     * $categories -> Array of category IDs, slugs, or `Category` instances.
     *
     * @param array $categories [1, 'uncategorized', $category]
     * @return static
     */
    public function addCategories(array $categories): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->addTerms($categories);

        return $this;
    }

    /**
     * Add a category to the model.
     *
     * $category -> Category ID, slug, or `Category` instance.
     *
     * @param int|string|Category $category uncategorized
     * @return static
     */
    public function addCategory(int|string|Category $category): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->addTerm($category);

        return $this;
    }

    /**
     * Remove categories from the model.
     *
     * $categories -> Array of category IDs, slugs, or `Category` instances.
     *
     * @param array $categories [1, 'uncategorized', $category]
     * @return static
     */
    public function removeCategories(array $categories): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->removeTerms($categories);

        return $this;
    }

    /**
     * Remove a category from the model.
     *
     * $category -> Category ID, slug, or `Category` instance.
     *
     * @param int|string|Category $category uncategorized
     * @return static
     */
    public function removeCategory(int|string|Category $category): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->removeTerm($category);

        return $this;
    }

    /**
     * Set categories on the model (replaces existing categories).
     *
     * $categories -> Array of category IDs, slugs, or `Category` instances.
     *
     * @param array $categories [1, 'uncategorized', $category]
     * @return $this
     */
    public function setCategories(array $categories): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Category::class)->setTerms($categories);

        return $this;
    }
}