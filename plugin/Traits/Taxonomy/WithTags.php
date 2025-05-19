<?php

namespace Charm\Traits\Taxonomy;

use Charm\Contracts\HasTerms;
use Charm\Models\Terms\Tag;
use Charm\Support\Result;

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
     * @return Result[]
     */
    public function addTags(array $tags): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->addTerms($tags);
    }

    /**
     * Add a tag to the model.
     *
     * $tag -> Category ID, slug, or `Tag` instance.
     *
     * @param int|string|Tag $tag foobar
     * @return Result
     */
    public function addTag(int|string|Tag $tag): Result
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->addTerm($tag);
    }

    /**
     * Remove tags from the model.
     *
     * $tags -> Array of tag IDs, slugs, or `Tag` instances.
     *
     * @param array $tags [1, 'foobar', $tag]
     * @return Result[]
     */
    public function removeTags(array $tags): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->removeTerms($tags);
    }

    /**
     * Remove a tag from the model.
     *
     * $tag -> Tag ID, slug, or `Tag` instance.
     *
     * @param int|string|Tag $tag foobar
     * @return Result
     */
    public function removeTag(int|string|Tag $tag): Result
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->removeTerm($tag);
    }

    /**
     * Set tags on the model (replaces existing tags).
     *
     * $tags -> Array of tag IDs, slugs, or `Tag` instances.
     *
     * @param array $tags [1, 'foobar', $tag]
     * @return Result[]
     */
    public function setTags(array $tags): array
    {
        /** @var HasTerms $this */
        return $this->taxonomy(Tag::class)->setTerms($tags);
    }
}