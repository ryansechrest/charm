<?php

namespace Charm\Tests\Integration\Models\Core;

use Charm\Models\Core\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected Post $post;

    // *************************************************************************

    protected function setUp(): void
    {
        $this->post = new Post([
            'postAuthor' => 1,
            'postTitle' => 'Charm Test',
            'postContent' => 'Lorem ipsum post content.',
            'postExcerpt' => 'Lorem ipsum post excerpt.',
            'postStatus' => 'publish',
            'commentStatus' => 'closed',
            'pingStatus' => 'closed',
            'postPassword' => 'charming',
            'postType' => 'post',
        ]);
        $this->post->create();
    }

    // *************************************************************************

    public function testFromId()
    {
        $post = Post::fromId($this->post->getID());

        $this->assertNotNull($post);
        $this->assertSame($this->post->getPostTitle(), $post->getPostTitle());
    }

    public function testFromPath()
    {
        $post = Post::fromPath(
            $this->post->getPostName(), $this->post->getPostType()
        );

        $this->assertNotNull($post);
        $this->assertSame($this->post->getPostTitle(), $post->getPostTitle());
    }

    // -------------------------------------------------------------------------

    public function testGet()
    {
        $posts = Post::get([
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);

        $this->assertNotSame(0, count($posts));
    }

    // -------------------------------------------------------------------------

    public function testCreate()
    {
        $this->assertSame(
            1, $this->post->getPostAuthor()
        );
        $this->assertSame(
            'Charm Test', $this->post->getPostTitle()
        );
        $this->assertSame(
            'Lorem ipsum post content.', $this->post->getPostContent()
        );
        $this->assertSame(
            'Lorem ipsum post excerpt.', $this->post->getPostExcerpt()
        );
        $this->assertSame(
            'publish', $this->post->getPostStatus()
        );
        $this->assertSame(
            'closed', $this->post->getCommentStatus()
        );
        $this->assertSame(
            'closed', $this->post->getPingStatus()
        );
        $this->assertSame(
            'charming', $this->post->getPostPassword()
        );
        $this->assertSame(
            'charm-test', $this->post->getPostName()
        );
        $this->assertSame(
            'post', $this->post->getPostType()
        );
    }

    public function testUpdate()
    {
        $post = Post::fromId($this->post->getID());

        $post->setPostTitle('Charm Test Updated');
        $result = $post->update();

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame('Charm Test Updated', $post->getPostTitle());
    }

    public function testTrash()
    {
        $post = Post::fromId($this->post->getID());

        $result = $post->trash();

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame('trash', $post->getPostStatus());
    }

    public function testRestore()
    {
        $post = Post::fromId($this->post->getID());

        $post->trash();
        $result = $post->restore();

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame('draft', $post->getPostStatus());
    }

    public function testDelete()
    {
        $post = Post::fromId($this->post->getID());

        $result = $post->delete();

        $this->assertTrue($result->hasSucceeded());
        $this->assertSame(0, $post->getId());
    }

    // *************************************************************************

    protected function tearDown(): void
    {
        $this->post->delete();
    }
}