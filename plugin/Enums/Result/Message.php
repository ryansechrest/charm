<?php

namespace Charm\Enums\Result;

/**
 * Indicates the operational message a result can return.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
enum Message: string
{
    // --- Meta ----------------------------------------------------------------

    case CreateMetaSuccess = 'create_meta_success';
    case CreateMetaFailed = 'create_meta_failed';
    case UpdateMetaSuccess = 'update_meta_success';
    case UpdateMetaFailed = 'update_meta_failed';
    case DeleteMetaSuccess = 'delete_meta_success';
    case DeleteMetaFailed = 'delete_meta_failed';
    case MetaAlreadyExists = 'meta_already_exists';
    case MetaNotFound = 'meta_not_found';

    // --- Post ----------------------------------------------------------------

    case CreatePostSuccess = 'create_post_success';
    case CreatePostFailed = 'create_post_failed';
    case UpdatePostSuccess = 'update_post_success';
    case UpdatePostFailed = 'update_post_failed';
    case TrashPostSuccess = 'trash_post_success';
    case TrashPostFailed = 'trash_post_failed';
    case RestorePostSuccess = 'restore_post_success';
    case RestorePostFailed = 'restore_post_failed';
    case DeletePostSuccess = 'delete_post_success';
    case DeletePostFailed = 'delete_post_failed';
    case PostAlreadyExists = 'post_already_exists';
    case PostNotFound = 'post_not_found';

    // --- Term ----------------------------------------------------------------

    case CreateTermSuccess = 'create_term_success';
    case UpdateTermSuccess = 'update_term_success';
    case DeleteTermSuccess = 'delete_term_success';
    case DeleteTermFailed = 'delete_term_failed';
    case TermAlreadyExists = 'term_already_exists';
    case TermNotFound = 'term_not_found';
    case TaxonomyNotFound = 'taxonomy_not_found';

    // --- User ----------------------------------------------------------------

    case CreateUserSuccess = 'create_user_success';
    case CreateUserFailed = 'create_user_failed';
    case UpdateUserSuccess = 'update_user_success';
    case UpdateUserFailed = 'update_user_failed';
    case DeleteUserSuccess = 'delete_user_success';
    case DeleteUserFailed = 'delete_user_failed';
    case UserAlreadyExists = 'user_already_exists';
    case UserNotFound = 'user_not_found';

    // *************************************************************************

    /**
     * Message code of the operational result.
     *
     * @return string
     */
    public function code(): string
    {
        return $this->value;
    }

    /**
     * Message text of the operational result.
     *
     * @return string
     */
    public function message(): string {
        return match ($this) {

            // --- Meta --------------------------------------------------------

            self::CreateMetaSuccess => __(
                'WordPress function `add_metadata()` did not return false.',
                'charm'
            ),
            self::CreateMetaFailed => __(
                'WordPress function `add_metadata()` returned false.',
                'charm'
            ),
            self::UpdateMetaSuccess => __(
                'WordPress function `update_metadata()` did not return false.',
                'charm'
            ),
            self::UpdateMetaFailed => __(
                'WordPress function `update_metadata()` returned false.',
                'charm'
            ),
            self::DeleteMetaSuccess => __(
                'WordPress function `delete_metadata()` did not return false.',
                'charm'
            ),
            self::DeleteMetaFailed => __(
                'WordPress function `delete_metadata()` returned false.',
                'charm'
            ),
            self::MetaAlreadyExists => __(
                'Meta already exists; cannot create.',
                'charm'
            ),
            self::MetaNotFound => __(
                'Meta not found; cannot update or delete.',
                'charm'
            ),

            // --- Post --------------------------------------------------------

            self::CreatePostSuccess => __(
                'WordPress function `wp_insert_post()` returned a post ID.',
                'charm'
            ),
            self::CreatePostFailed => __(
                'WordPress function `wp_insert_post()` did not return a post ID.',
                'charm'
            ),
            self::UpdatePostSuccess => __(
                'WordPress function `wp_update_post()` returned a post ID.',
                'charm'
            ),
            self::UpdatePostFailed => __(
                'WordPress function `wp_update_post()` did not return a post ID.',
                'charm'
            ),
            self::TrashPostSuccess => __(
                'WordPress function `wp_trash_post()` returned a `WP_Post` instance.',
                'charm'
            ),
            self::TrashPostFailed => __(
                'WordPress function `wp_trash_post()` did not return a `WP_Post` instance.',
                'charm'
            ),
            self::RestorePostSuccess => __(
                'WordPress function `wp_untrash_post()` return a `WP_Post` instance.',
                'charm'
            ),
            self::RestorePostFailed => __(
                'WordPress function `wp_untrash_post()` did not return a `WP_Post` instance.',
                'charm'
            ),
            self::DeletePostSuccess => __(
                'WordPress function `wp_delete_post()` returned a `WP_Post` instance.',
                'charm'
            ),
            self::DeletePostFailed => __(
                'WordPress function `wp_delete_post()` did not return a `WP_Post` instance.',
                'charm'
            ),
            self::PostAlreadyExists => __(
                'Post already exists; cannot create a post with an existing post ID.',
                'charm'
            ),
            self::PostNotFound => __(
                'Post not found; cannot update or delete a post without a post ID.',
                'charm'
            ),

            // --- Post --------------------------------------------------------

            self::CreateTermSuccess => __(
                'WordPress function `wp_insert_term()` returned a term data array.',
                'charm'
            ),
            self::UpdateTermSuccess => __(
                'WordPress function `wp_update_term()` returned a term data array.',
                'charm'
            ),
            self::DeleteTermSuccess => __(
                'WordPress function `wp_delete_term()` returned true.',
                'charm'
            ),
            self::DeleteTermFailed => __(
                'WordPress function `wp_delete_term()` did not return true.',
                'charm'
            ),
            self::TermAlreadyExists => __(
                'Term already exists; cannot create a term with an existing taxonomy term ID.',
                'charm'
            ),
            self::TermNotFound => __(
                'Term not found; cannot update or delete a term without a taxonomy term ID.',
                'charm'
            ),
            self::TaxonomyNotFound => __(
                'Taxonomy not found; cannot create, update or delete a term without a taxonomy.',
                'charm'
            ),

            // --- User --------------------------------------------------------

            self::CreateUserSuccess => __(
                'WordPress function `wp_insert_user()` returned a user ID.',
                'charm'
            ),
            self::CreateUserFailed => __(
                'WordPress function `wp_insert_user()` did not return a user ID.',
                'charm'
            ),
            self::UpdateUserSuccess => __(
                'WordPress function `wp_update_user()` returned a user ID.',
                'charm'
            ),
            self::UpdateUserFailed => __(
                'WordPress function `wp_update_user()` did not return a user ID.',
                'charm'
            ),
            self::DeleteUserSuccess => __(
                'WordPress function `delete_metadata()` did not return false.',
                'charm'
            ),
            self::DeleteUserFailed => __(
                'WordPress function `delete_metadata()` returned false.',
                'charm'
            ),
            self::UserAlreadyExists => __(
                'User already exists; cannot create a user with an existing user ID.',
                'charm'
            ),
            self::UserNotFound => __(
                'User not found; cannot update or delete a user without a user ID.',
                'charm'
            ),
        };
    }
}