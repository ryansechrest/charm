<?php

namespace Charm\Traits\Taxonomy;

use Charm\Contracts\HasTerms;
use Charm\Models\Terms\Tag;

/**
 * Adds tags to a model.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
trait WithTags
{
    /**
     * Get the tags.
     *
     * @return Tag[]
     */
    public function getTags(): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->getTerms();
    }

    // -------------------------------------------------------------------------

    /**
     * Add a tag to the model.
     *
     * @param int|string|Tag $tag Tag ID, slug, or instance
     * @return static
     */
    public function addTag(int|string|Tag $tag): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->addTerm($tag);

        return $this;
    }

    /**
     * Add tags to the model.
     *
     * @param array $tags Array of tag IDs, slugs, or instances
     * @return static
     */
    public function addTags(array $tags): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->addTerms($tags);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Remove a tag from the model.
     *
     * @param int|string|Tag $tag Tag ID, slug, or instance
     * @return static
     */
    public function removeTag(int|string|Tag $tag): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->removeTerm($tag);

        return $this;
    }

    /**
     * Remove tags from the model.
     *
     * @param array $tags Array of tag IDs, slugs, or instances
     * @return static
     */
    public function removeTags(array $tags): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->removeTerms($tags);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Set tags on the model (replaces existing tags).
     *
     * @param array $tags Array of tag IDs, slugs, or instances
     * @return static
     */
    public function setTags(array $tags): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->setTerms($tags);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the model has the specified tag.
     *
     * @param int|string|Tag $tag Tag ID, slug, or instance
     * @return bool
     */
    public function hasTag(int|string|Tag $tag): bool
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->hasTerm($tag);
    }

    /**
     * Check whether the model has ALL the specified tags.
     *
     * @param array $tags Array of tag IDs, slugs, or instances
     * @return bool
     */
    public function hasAllTags(array $tags): bool
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->hasAllTerms($tags);
    }

    /**
     * Check whether the model has ANY of the specified tags.
     *
     * @param array $tags Array of tag IDs, slugs, or instances
     * @return bool
     */
    public function hasAnyTags(array $tags): bool
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->hasAnyTerms($tags);
    }
}