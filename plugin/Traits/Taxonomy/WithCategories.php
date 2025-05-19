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
     * @return Result[]
     */
    public function addCategories(array $categories): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->addTerms($categories);
    }

    /**
     * Add a category to the model.
     *
     * $category -> Category ID, slug, or `Category` instance.
     *
     * @param int|string|Category $category uncategorized
     * @return Result
     */
    public function addCategory(int|string|Category $category): Result
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->addTerm($category);
    }

    /**
     * Remove categories from the model.
     *
     * $categories -> Array of category IDs, slugs, or `Category` instances.
     *
     * @param array $categories [1, 'uncategorized', $category]
     * @return Result[]
     */
    public function removeCategories(array $categories): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->removeTerms($categories);
    }

    /**
     * Remove a category from the model.
     *
     * $category -> Category ID, slug, or `Category` instance.
     *
     * @param int|string|Category $category uncategorized
     * @return Result
     */
    public function removeCategory(int|string|Category $category): Result
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->removeTerm($category);
    }

    /**
     * Set categories on the model (replaces existing categories).
     *
     * $categories -> Array of category IDs, slugs, or `Category` instances.
     *
     * @param array $categories [1, 'uncategorized', $category]
     * @return Result[]
     */
    public function setCategories(array $categories): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Category::class)->setTerms($categories);
    }
}