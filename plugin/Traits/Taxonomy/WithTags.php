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
    /**
     * Add tags to the model.
     *
     * $tags -> Array of tag IDs, slugs, or `Tag` instances.
     *
     * @param array $tags [1, 'foobar', $tag]
     * @return static
     */
    public function addTags(array $tags): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->addTerms($tags);

        return $this;
    }

    /**
     * Add a tag to the model.
     *
     * $tag -> Category ID, slug, or `Tag` instance.
     *
     * @param int|string|Tag $tag foobar
     * @return static
     */
    public function addTag(int|string|Tag $tag): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->addTerm($tag);

        return $this;
    }

    /**
     * Remove tags from the model.
     *
     * $tags -> Array of tag IDs, slugs, or `Tag` instances.
     *
     * @param array $tags [1, 'foobar', $tag]
     * @return static
     */
    public function removeTags(array $tags): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->removeTerms($tags);

        return $this;
    }

    /**
     * Remove a tag from the model.
     *
     * $tag -> Tag ID, slug, or `Tag` instance.
     *
     * @param int|string|Tag $tag foobar
     * @return static
     */
    public function removeTag(int|string|Tag $tag): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->removeTerm($tag);

        return $this;
    }

    /**
     * Set tags on the model (replaces existing tags).
     *
     * $tags -> Array of tag IDs, slugs, or `Tag` instances.
     *
     * @param array $tags [1, 'foobar', $tag]
     * @return static
     */
    public function setTags(array $tags): static
    {
        /** @var HasTerms $this */
        $this->taxonomy(Tag::class)->setTerms($tags);

        return $this;
    }
}