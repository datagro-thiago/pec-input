<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/firestore/admin/v1/firestore_admin.proto

namespace Google\Cloud\Firestore\Admin\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request for
 * [FirestoreAdmin.UpdateBackupSchedule][google.firestore.admin.v1.FirestoreAdmin.UpdateBackupSchedule].
 *
 * Generated from protobuf message <code>google.firestore.admin.v1.UpdateBackupScheduleRequest</code>
 */
class UpdateBackupScheduleRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The backup schedule to update.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.BackupSchedule backup_schedule = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $backup_schedule = null;
    /**
     * The list of fields to be updated.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 2;</code>
     */
    private $update_mask = null;

    /**
     * @param \Google\Cloud\Firestore\Admin\V1\BackupSchedule $backupSchedule Required. The backup schedule to update.
     * @param \Google\Protobuf\FieldMask                      $updateMask     The list of fields to be updated.
     *
     * @return \Google\Cloud\Firestore\Admin\V1\UpdateBackupScheduleRequest
     *
     * @experimental
     */
    public static function build(\Google\Cloud\Firestore\Admin\V1\BackupSchedule $backupSchedule, \Google\Protobuf\FieldMask $updateMask): self
    {
        return (new self())
            ->setBackupSchedule($backupSchedule)
            ->setUpdateMask($updateMask);
    }

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\Firestore\Admin\V1\BackupSchedule $backup_schedule
     *           Required. The backup schedule to update.
     *     @type \Google\Protobuf\FieldMask $update_mask
     *           The list of fields to be updated.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Firestore\Admin\V1\FirestoreAdmin::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The backup schedule to update.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.BackupSchedule backup_schedule = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Cloud\Firestore\Admin\V1\BackupSchedule|null
     */
    public function getBackupSchedule()
    {
        return $this->backup_schedule;
    }

    public function hasBackupSchedule()
    {
        return isset($this->backup_schedule);
    }

    public function clearBackupSchedule()
    {
        unset($this->backup_schedule);
    }

    /**
     * Required. The backup schedule to update.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.BackupSchedule backup_schedule = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param \Google\Cloud\Firestore\Admin\V1\BackupSchedule $var
     * @return $this
     */
    public function setBackupSchedule($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Firestore\Admin\V1\BackupSchedule::class);
        $this->backup_schedule = $var;

        return $this;
    }

    /**
     * The list of fields to be updated.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 2;</code>
     * @return \Google\Protobuf\FieldMask|null
     */
    public function getUpdateMask()
    {
        return $this->update_mask;
    }

    public function hasUpdateMask()
    {
        return isset($this->update_mask);
    }

    public function clearUpdateMask()
    {
        unset($this->update_mask);
    }

    /**
     * The list of fields to be updated.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 2;</code>
     * @param \Google\Protobuf\FieldMask $var
     * @return $this
     */
    public function setUpdateMask($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\FieldMask::class);
        $this->update_mask = $var;

        return $this;
    }

}

