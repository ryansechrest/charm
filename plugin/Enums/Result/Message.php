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

    case MetaCreateSuccess = 'meta_create_success';
    case MetaCreateFailed = 'meta_created_failed';
    case MetaUpdateSuccess = 'meta_update_success';
    case MetaUpdateFailed = 'meta_update_failed';
    case MetaDeleteSuccess = 'meta_delete_success';
    case MetaDeleteFailed = 'meta_delete_failed';
    case MetaPurgeSuccess = 'meta_purge_success';
    case MetaPurgeFailed = 'meta_purge_failed';
    case MetaAlreadyExists = 'meta_already_exists';
    case MetaNotFound = 'meta_not_found';

    // --- Post ----------------------------------------------------------------

    case PostCreateSuccess = 'post_create_success';
    case PostCreateFailed = 'post_create_failed';
    case PostUpdateSuccess = 'post_update_success';
    case PostUpdateFailed = 'post_update_failed';
    case PostTrashSuccess = 'post_trash_success';
    case PostTrashFailed = 'post_trash_failed';
    case PostRestoreSuccess = 'post_restore_success';
    case PostRestoreFailed = 'post_restore_failed';
    case PostDeleteSuccess = 'post_delete_success';
    case PostDeleteFailed = 'post_delete_failed';
    case PostAlreadyExists = 'post_already_exists';
    case PostNotFound = 'post_not_found';

    // --- Term ----------------------------------------------------------------

    case TermCreateSuccess = 'term_create_success';
    case TermCreateFailed = 'term_create_failed';
    case TermUpdateSuccess = 'term_update_success';
    case TermUpdateFailed = 'term_update_failed';
    case TermDeleteSuccess = 'term_delete_success';
    case TermDeleteFailed = 'term_delete_failed';
    case TermAlreadyExists = 'term_already_exists';
    case TermNotFound = 'term_not_found';
    case TaxonomyNotFound = 'taxonomy_not_found';
    case TermIsDefault = 'term_is_default';

    // --- User ----------------------------------------------------------------

    case UserCreateSuccess = 'user_create_success';
    case UserCreateFailed = 'user_create_failed';
    case UserUpdateSuccess = 'user_update_success';
    case UserUpdateFailed = 'user_update_failed';
    case UserDeleteSuccess = 'user_delete_success';
    case UserDeleteFailed = 'user_delete_failed';
    case UserAlreadyExists = 'user_already_exists';
    case UserNotFound = 'user_not_found';

    // --- TermRelationship ----------------------------------------------------

    case TermRelationshipAddSuccess = 'term_relationship_add_success';
    case TermRelationshipAddFailed = 'term_relationship_add_failed';
    case TermRelationshipRemoveSuccess = 'term_relationship_remove_success';
    case TermRelationshipRemoveFailed = 'term_relationship_remove_failed';
    case TermRelationshipSetSuccess = 'term_relationship_set_success';
    case TermRelationshipSetFailed = 'term_relationship_set_failed';
    case TermRelationshipNormalizedSuccess = 'term_relationship_normalized_success';
    case TermRelationshipAlreadyNormalized = 'term_relationship_already_normalized';
    case TermRelationshipInvalidSubclass = 'term_relationship_invalid_subclass';
    case TermRelationshipNotFound = 'term_relationship_not_found';
    case TermRelationshipObjectIdNotFound = 'term_relationship_object_id_not_found';

    // --- WithPersistenceState ------------------------------------------------

    

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

            self::MetaCreateSuccess => __(
                'Meta was successfully created.',
                'charm'
            ),
            self::MetaCreateFailed => __(
                'Meta was not created.',
                'charm'
            ),
            self::MetaUpdateSuccess => __(
                'Meta was successfully updated.',
                'charm'
            ),
            self::MetaUpdateFailed => __(
                'Meta was not updated; possibly because it already exists.',
                'charm'
            ),
            self::MetaDeleteSuccess => __(
                'Meta was successfully deleted.',
                'charm'
            ),
            self::MetaDeleteFailed => __(
                'Meta was not deleted.',
                'charm'
            ),
            self::MetaPurgeSuccess => __(
                'Metas were successfully purged.',
                'charm'
            ),
            self::MetaPurgeFailed => __(
                'Metas were not purged.',
                'charm'
            ),
            self::MetaAlreadyExists => __(
                'Meta was not created because it already exists.',
                'charm'
            ),
            self::MetaNotFound => __(
                'Meta was not updated or deleted because it does not exist.',
                'charm'
            ),

            // --- Post --------------------------------------------------------

            self::PostCreateSuccess => __(
                'Post was successfully created.',
                'charm'
            ),
            self::PostCreateFailed => __(
                'Post was not created.',
                'charm'
            ),
            self::PostUpdateSuccess => __(
                'Post was successfully updated.',
                'charm'
            ),
            self::PostUpdateFailed => __(
                'Post was not updated.',
                'charm'
            ),
            self::PostTrashSuccess => __(
                'Post was successfully trashed.',
                'charm'
            ),
            self::PostTrashFailed => __(
                'Post was not trashed.',
                'charm'
            ),
            self::PostRestoreSuccess => __(
                'Post was successfully restored.',
                'charm'
            ),
            self::PostRestoreFailed => __(
                'Post was not restored.',
                'charm'
            ),
            self::PostDeleteSuccess => __(
                'Post was successfully deleted.',
                'charm'
            ),
            self::PostDeleteFailed => __(
                'Post was not deleted.',
                'charm'
            ),
            self::PostAlreadyExists => __(
                'Post was not created because it already exists.',
                'charm'
            ),
            self::PostNotFound => __(
                'Post was not updated or deleted because it does not exist.',
                'charm'
            ),

            // --- Term --------------------------------------------------------

            self::TermCreateSuccess => __(
                'Term was successfully created.',
                'charm'
            ),
            self::TermCreateFailed => __(
                'Term was not created..',
                'charm'
            ),
            self::TermUpdateSuccess => __(
                'Term was successfully updated.',
                'charm'
            ),
            self::TermUpdateFailed => __(
                'Term was not updated.',
                'charm'
            ),
            self::TermDeleteSuccess => __(
                'Term was successfully deleted.',
                'charm'
            ),
            self::TermDeleteFailed => __(
                'Term not deleted.',
                'charm'
            ),
            self::TermAlreadyExists => __(
                'Term was not created because it already exists.',
                'charm'
            ),
            self::TermNotFound => __(
                'Term was not updated or deleted because it does not exist.',
                'charm'
            ),
            self::TaxonomyNotFound => __(
                'Term was not created, updated, or deleted because the taxonomy does not exist.',
                'charm'
            ),
            self::TermIsDefault => __(
                'Term was not deleted because it is the default term for the taxonomy.',
                'charm'
            ),

            // --- User --------------------------------------------------------

            self::UserCreateSuccess => __(
                'User was successfully created.',
                'charm'
            ),
            self::UserCreateFailed => __(
                'User was not created.',
                'charm'
            ),
            self::UserUpdateSuccess => __(
                'User was successfully updated.',
                'charm'
            ),
            self::UserUpdateFailed => __(
                'User was not updated.',
                'charm'
            ),
            self::UserDeleteSuccess => __(
                'User was successfully deleted.',
                'charm'
            ),
            self::UserDeleteFailed => __(
                'User was not deleted.',
                'charm'
            ),
            self::UserAlreadyExists => __(
                'User was not created because they already exist.',
                'charm'
            ),
            self::UserNotFound => __(
                'User was not updated or deleted because they do not exist.',
                'charm'
            ),

            // --- TermRelationship --------------------------------------------

            self::TermRelationshipAddSuccess => __(
                'Term relationship was successfully added.',
                'charm'
            ),
            self::TermRelationshipAddFailed => __(
                'Term relationship was not added.',
                'charm'
            ),
            self::TermRelationshipRemoveSuccess => __(
                'Term relationship was successfully removed.',
                'charm'
            ),
            self::TermRelationshipRemoveFailed => __(
                'Term relationship was not removed.',
                'charm'
            ),
            self::TermRelationshipSetSuccess => __(
                'Term relationship was successfully set.',
                'charm'
            ),
            self::TermRelationshipSetFailed => __(
                'Term relationship was not set.',
                'charm'
            ),
            self::TermRelationshipNormalizedSuccess => __(
                'Term was successfully normalized.',
                'charm'
            ),
            self::TermRelationshipAlreadyNormalized => __(
                'Term was already normalized.',
                'charm'
            ),
            self::TermRelationshipInvalidSubclass => __(
                'Term class must be subclass of `Charm\Models\Base\Term`.',
                'charm'
            ),
            self::TermRelationshipNotFound => __(
                'Term was not initialized because term ID or slug does not exist.',
                'charm'
            ),
            self::TermRelationshipObjectIdNotFound => __(
                'Object ID is required to persist terms.',
                'charm'
            )
        };
    }
}