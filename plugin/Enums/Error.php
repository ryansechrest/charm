<?php

namespace Charm\Enums;

/**
 * List all possible error messages that Charm can return.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum Error: string
{
    case AddMetadataFailed = 'add_metadata_failed';
    case UpdateMetadataFailed = 'update_metadata_failed';
    case DeleteMetadataFailed = 'delete_metadata_failed';
    case MetaExists = 'meta_exists';
    case MetaUpdateFailed = 'meta_update_failed';
    case MetaDeleteFailed = 'meta_delete_failed';
    case WpInsertPostFailed = 'wp_insert_post_failed';
    case WpUpdatePostFailed = 'wp_update_post_failed';
    case WpTrashPostFailed = 'wp_trash_post_failed';
    case WpUntrashPostFailed = 'wp_untrash_post_failed';
    case WpDeletePostFailed = 'wp_delete_post_failed';
    case PostIdExists = 'post_id_exists';
    case PostUpdateFailed = 'post_update_failed';
    case PostTrashFailed = 'post_trash_failed';
    case PostRestoreFailed = 'post_restore_failed';
    case PostDeleteFailed = 'post_delete_failed';

    public function code(): string {
        return $this->value;
    }

    public function message(): string {
        return match ($this) {
            self::AddMetadataFailed => __('add_metadata() returned false.', 'charm'),
            self::UpdateMetadataFailed => __('update_metadata() returned false.', 'charm'),
            self::DeleteMetadataFailed => __('delete_metadata() returned false.', 'charm'),
            self::MetaExists => __('Meta already exists.', 'charm'),
            self::MetaUpdateFailed => __('Cannot update meta that does not exist.', 'charm'),
            self::MetaDeleteFailed => __('Cannot delete meta that does not exist.', 'charm'),
            self::WpInsertPostFailed => __('wp_insert_post() did not return an ID.', 'charm'),
            self::WpUpdatePostFailed => __('wp_update_post() did not return an ID.', 'charm'),
            self::WpTrashPostFailed => __('wp_trash_post() did not return a post.', 'charm'),
            self::WpUntrashPostFailed => __('wp_untrash_post() did not return a post.', 'charm'),
            self::WpDeletePostFailed => __('wp_delete_post() did not return a post.', 'charm'),
            self::PostIdExists => __('Post already exists.', 'charm'),
            self::PostUpdateFailed => __('Cannot update post with blank ID.', 'charm'),
            self::PostTrashFailed => __('Cannot trash post with blank ID.', 'charm'),
            self::PostRestoreFailed => __('Cannot restore post with blank ID.', 'charm'),
            self::PostDeleteFailed => __('Cannot delete post with blank ID.', 'charm'),
        };
    }
}