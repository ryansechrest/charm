<?php

namespace Charm\Tests\Integration\Models\Core;

use Charm\Models\Core\Meta;
use Charm\Models\Core\Post;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    protected Post $post;

    // *************************************************************************

    protected function setUp(): void
    {
        $this->post = new Post([
            'postAuthor' => 1,
            'postTitle' => 'Charm Test',
            'postStatus' => 'publish',
            'postType' => 'post',
        ]);
        $this->post->create();
    }

    // *************************************************************************

    public function testStaticCreate()
    {
        $result = Meta::createMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test',
        );

        $meta = Meta::getFirst(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test'
        );

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame('Charm Test', $meta->getMetaValue());
    }

    public function testStaticUpdate()
    {
        Meta::createMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test',
        );

        $result = Meta::updateMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test Updated',
            'Charm Test'
        );

        $meta = Meta::getFirst(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test'
        );

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame('Charm Test Updated', $meta->getMetaValue());
    }

    public function testStaticDelete()
    {
        Meta::createMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test'
        );

        $result = Meta::deleteMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test'
        );

        $success = Meta::hasMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test'
        );

        $this->assertTrue($result->hasSucceeded());
        $this->assertFalse($success);
    }

    public function testStaticPurge()
    {
        $post = new Post([
            'postAuthor' => 1,
            'postTitle' => 'Charm Test 2',
            'postStatus' => 'publish',
            'postType' => 'post',
        ]);
        $post->create();

        Meta::createMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test'
        );

        Meta::createMeta(
            $post->getPostType(),
            $post->getId(),
            'charm_test',
            'Charm Test'
        );

        $result = Meta::purgeMeta(
            $post->getPostType(),
            'charm_test',
            'Charm Test'
        );

        $success1 = Meta::hasMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test'
        );

        $success2 = Meta::hasMeta(
            $post->getPostType(),
            $post->getId(),
            'charm_test'
        );

        $post->delete();

        $this->assertTrue($result->hasSucceeded());
        $this->assertFalse($success1);
        $this->assertFalse($success2);
    }

    // -------------------------------------------------------------------------

    public function testGet()
    {
        Meta::createMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test 1'
        );

        Meta::createMeta(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test',
            'Charm Test 2'
        );

        $metas = Meta::get(
            $this->post->getPostType(),
            $this->post->getId(),
            'charm_test'
        );

        $this->assertCount(2, $metas);
    }

    // -------------------------------------------------------------------------

    public function testCreate()
    {
        $meta = new Meta($this->post->getPostType(), [
            'objectId' => $this->post->getId(),
            'metaKey' => 'charm_test',
            'metaValue' => 'Charm Test',
        ]);
        $result = $meta->create();

        $this->assertTrue($result->hasSucceeded());
        $this->assertTrue($meta->exists());
        $this->assertSame($this->post->getId(), $meta->getObjectId());
        $this->assertSame('charm_test', $meta->getMetaKey());
        $this->assertSame('Charm Test', $meta->getMetaValue());
    }

    public function testUpdate()
    {
        $meta = new Meta($this->post->getPostType(), [
            'objectId' => $this->post->getId(),
            'metaKey' => 'charm_test',
            'metaValue' => 'Charm Test',
        ]);
        $meta->create();

        $meta->setMetaValue('Charm Test Updated');

        $this->assertTrue($meta->hasChanged());

        $result = $meta->update();

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame('Charm Test Updated', $meta->getMetaValue());
    }

    public function testDelete()
    {
        $meta = new Meta($this->post->getPostType(), [
            'objectId' => $this->post->getId(),
            'metaKey' => 'charm_test',
            'metaValue' => 'Charm Test',
        ]);
        $meta->create();
        $result = $meta->delete();

        $this->assertTrue($result->hasSucceeded());
        $this->assertFalse($meta->exists());
    }

    // *************************************************************************

    protected function tearDown(): void
    {
        $this->post->delete();
    }
}