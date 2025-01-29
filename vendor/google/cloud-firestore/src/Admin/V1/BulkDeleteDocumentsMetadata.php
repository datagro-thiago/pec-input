<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/firestore/admin/v1/operation.proto

namespace Google\Cloud\Firestore\Admin\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Metadata for [google.longrunning.Operation][google.longrunning.Operation]
 * results from
 * [FirestoreAdmin.BulkDeleteDocuments][google.firestore.admin.v1.FirestoreAdmin.BulkDeleteDocuments].
 *
 * Generated from protobuf message <code>google.firestore.admin.v1.BulkDeleteDocumentsMetadata</code>
 */
class BulkDeleteDocumentsMetadata extends \Google\Protobuf\Internal\Message
{
    /**
     * The time this operation started.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp start_time = 1;</code>
     */
    private $start_time = null;
    /**
     * The time this operation completed. Will be unset if operation still in
     * progress.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp end_time = 2;</code>
     */
    private $end_time = null;
    /**
     * The state of the operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.OperationState operation_state = 3;</code>
     */
    private $operation_state = 0;
    /**
     * The progress, in documents, of this operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.Progress progress_documents = 4;</code>
     */
    private $progress_documents = null;
    /**
     * The progress, in bytes, of this operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.Progress progress_bytes = 5;</code>
     */
    private $progress_bytes = null;
    /**
     * The IDs of the collection groups that are being deleted.
     *
     * Generated from protobuf field <code>repeated string collection_ids = 6;</code>
     */
    private $collection_ids;
    /**
     * Which namespace IDs are being deleted.
     *
     * Generated from protobuf field <code>repeated string namespace_ids = 7;</code>
     */
    private $namespace_ids;
    /**
     * The timestamp that corresponds to the version of the database that is being
     * read to get the list of documents to delete. This time can also be used as
     * the timestamp of PITR in case of disaster recovery (subject to PITR window
     * limit).
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp snapshot_time = 8;</code>
     */
    private $snapshot_time = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Protobuf\Timestamp $start_time
     *           The time this operation started.
     *     @type \Google\Protobuf\Timestamp $end_time
     *           The time this operation completed. Will be unset if operation still in
     *           progress.
     *     @type int $operation_state
     *           The state of the operation.
     *     @type \Google\Cloud\Firestore\Admin\V1\Progress $progress_documents
     *           The progress, in documents, of this operation.
     *     @type \Google\Cloud\Firestore\Admin\V1\Progress $progress_bytes
     *           The progress, in bytes, of this operation.
     *     @type array<string>|\Google\Protobuf\Internal\RepeatedField $collection_ids
     *           The IDs of the collection groups that are being deleted.
     *     @type array<string>|\Google\Protobuf\Internal\RepeatedField $namespace_ids
     *           Which namespace IDs are being deleted.
     *     @type \Google\Protobuf\Timestamp $snapshot_time
     *           The timestamp that corresponds to the version of the database that is being
     *           read to get the list of documents to delete. This time can also be used as
     *           the timestamp of PITR in case of disaster recovery (subject to PITR window
     *           limit).
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Firestore\Admin\V1\Operation::initOnce();
        parent::__construct($data);
    }

    /**
     * The time this operation started.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp start_time = 1;</code>
     * @return \Google\Protobuf\Timestamp|null
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    public function hasStartTime()
    {
        return isset($this->start_time);
    }

    public function clearStartTime()
    {
        unset($this->start_time);
    }

    /**
     * The time this operation started.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp start_time = 1;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setStartTime($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->start_time = $var;

        return $this;
    }

    /**
     * The time this operation completed. Will be unset if operation still in
     * progress.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp end_time = 2;</code>
     * @return \Google\Protobuf\Timestamp|null
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    public function hasEndTime()
    {
        return isset($this->end_time);
    }

    public function clearEndTime()
    {
        unset($this->end_time);
    }

    /**
     * The time this operation completed. Will be unset if operation still in
     * progress.
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp end_time = 2;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setEndTime($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->end_time = $var;

        return $this;
    }

    /**
     * The state of the operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.OperationState operation_state = 3;</code>
     * @return int
     */
    public function getOperationState()
    {
        return $this->operation_state;
    }

    /**
     * The state of the operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.OperationState operation_state = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setOperationState($var)
    {
        GPBUtil::checkEnum($var, \Google\Cloud\Firestore\Admin\V1\OperationState::class);
        $this->operation_state = $var;

        return $this;
    }

    /**
     * The progress, in documents, of this operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.Progress progress_documents = 4;</code>
     * @return \Google\Cloud\Firestore\Admin\V1\Progress|null
     */
    public function getProgressDocuments()
    {
        return $this->progress_documents;
    }

    public function hasProgressDocuments()
    {
        return isset($this->progress_documents);
    }

    public function clearProgressDocuments()
    {
        unset($this->progress_documents);
    }

    /**
     * The progress, in documents, of this operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.Progress progress_documents = 4;</code>
     * @param \Google\Cloud\Firestore\Admin\V1\Progress $var
     * @return $this
     */
    public function setProgressDocuments($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Firestore\Admin\V1\Progress::class);
        $this->progress_documents = $var;

        return $this;
    }

    /**
     * The progress, in bytes, of this operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.Progress progress_bytes = 5;</code>
     * @return \Google\Cloud\Firestore\Admin\V1\Progress|null
     */
    public function getProgressBytes()
    {
        return $this->progress_bytes;
    }

    public function hasProgressBytes()
    {
        return isset($this->progress_bytes);
    }

    public function clearProgressBytes()
    {
        unset($this->progress_bytes);
    }

    /**
     * The progress, in bytes, of this operation.
     *
     * Generated from protobuf field <code>.google.firestore.admin.v1.Progress progress_bytes = 5;</code>
     * @param \Google\Cloud\Firestore\Admin\V1\Progress $var
     * @return $this
     */
    public function setProgressBytes($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Firestore\Admin\V1\Progress::class);
        $this->progress_bytes = $var;

        return $this;
    }

    /**
     * The IDs of the collection groups that are being deleted.
     *
     * Generated from protobuf field <code>repeated string collection_ids = 6;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getCollectionIds()
    {
        return $this->collection_ids;
    }

    /**
     * The IDs of the collection groups that are being deleted.
     *
     * Generated from protobuf field <code>repeated string collection_ids = 6;</code>
     * @param array<string>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setCollectionIds($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->collection_ids = $arr;

        return $this;
    }

    /**
     * Which namespace IDs are being deleted.
     *
     * Generated from protobuf field <code>repeated string namespace_ids = 7;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getNamespaceIds()
    {
        return $this->namespace_ids;
    }

    /**
     * Which namespace IDs are being deleted.
     *
     * Generated from protobuf field <code>repeated string namespace_ids = 7;</code>
     * @param array<string>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setNamespaceIds($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->namespace_ids = $arr;

        return $this;
    }

    /**
     * The timestamp that corresponds to the version of the database that is being
     * read to get the list of documents to delete. This time can also be used as
     * the timestamp of PITR in case of disaster recovery (subject to PITR window
     * limit).
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp snapshot_time = 8;</code>
     * @return \Google\Protobuf\Timestamp|null
     */
    public function getSnapshotTime()
    {
        return $this->snapshot_time;
    }

    public function hasSnapshotTime()
    {
        return isset($this->snapshot_time);
    }

    public function clearSnapshotTime()
    {
        unset($this->snapshot_time);
    }

    /**
     * The timestamp that corresponds to the version of the database that is being
     * read to get the list of documents to delete. This time can also be used as
     * the timestamp of PITR in case of disaster recovery (subject to PITR window
     * limit).
     *
     * Generated from protobuf field <code>.google.protobuf.Timestamp snapshot_time = 8;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setSnapshotTime($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->snapshot_time = $var;

        return $this;
    }

}

