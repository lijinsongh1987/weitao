<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace Ecs\Request\V20140526;

class DescribeDisksRequest extends \RpcAcsRequest
{
	function  __construct()
	{
		parent::__construct("Ecs", "2014-05-26", "DescribeDisks");
		$this->setMethod("POST");
	}

	private  $enableAutomatedSnapshotPolicy;

	private  $pageSize;

	private  $portable;

	private  $zoneId;

	private  $filter2Key;

	private  $deleteWithInstance;

	private  $deleteAutoSnapshot;

	private  $tag5Value;

	private  $tag3Key;

	private  $filter2Value;

	private  $resourceOwnerAccount;

	private  $snapshotId;

	private  $filter1Value;

	private  $tag1Key;

	private  $tag1Value;

	private  $diskChargeType;

	private  $resourceOwnerId;

	private  $tag4Value;

	private  $ownerAccount;

	private  $diskName;

	private  $resourceGroupId;

	private  $diskIds;

	private  $status;

	private  $pageNumber;

	private  $ownerId;

	private  $tag5Key;

	private  $lockReason;

	private  $category;

	private  $diskType;

	private  $enableAutoSnapshot;

	private  $tag2Key;

	private  $filter1Key;

	private  $autoSnapshotPolicyId;

	private  $tag3Value;

	private  $instanceId;

	private  $tag2Value;

	private  $tag4Key;

	public function getEnableAutomatedSnapshotPolicy() {
		return $this->enableAutomatedSnapshotPolicy;
	}

	public function setEnableAutomatedSnapshotPolicy($enableAutomatedSnapshotPolicy) {
		$this->enableAutomatedSnapshotPolicy = $enableAutomatedSnapshotPolicy;
		$this->queryParameters["EnableAutomatedSnapshotPolicy"]=$enableAutomatedSnapshotPolicy;
	}

	public function getPageSize() {
		return $this->pageSize;
	}

	public function setPageSize($pageSize) {
		$this->pageSize = $pageSize;
		$this->queryParameters["PageSize"]=$pageSize;
	}

	public function getPortable() {
		return $this->portable;
	}

	public function setPortable($portable) {
		$this->portable = $portable;
		$this->queryParameters["Portable"]=$portable;
	}

	public function getZoneId() {
		return $this->zoneId;
	}

	public function setZoneId($zoneId) {
		$this->zoneId = $zoneId;
		$this->queryParameters["ZoneId"]=$zoneId;
	}

	public function getFilter2Key() {
		return $this->filter2Key;
	}

	public function setFilter2Key($filter2Key) {
		$this->filter2Key = $filter2Key;
		$this->queryParameters["Filter.2.Key"]=$filter2Key;
	}

	public function getDeleteWithInstance() {
		return $this->deleteWithInstance;
	}

	public function setDeleteWithInstance($deleteWithInstance) {
		$this->deleteWithInstance = $deleteWithInstance;
		$this->queryParameters["DeleteWithInstance"]=$deleteWithInstance;
	}

	public function getDeleteAutoSnapshot() {
		return $this->deleteAutoSnapshot;
	}

	public function setDeleteAutoSnapshot($deleteAutoSnapshot) {
		$this->deleteAutoSnapshot = $deleteAutoSnapshot;
		$this->queryParameters["DeleteAutoSnapshot"]=$deleteAutoSnapshot;
	}

	public function getTag5Value() {
		return $this->tag5Value;
	}

	public function setTag5Value($tag5Value) {
		$this->tag5Value = $tag5Value;
		$this->queryParameters["Tag.5.Value"]=$tag5Value;
	}

	public function getTag3Key() {
		return $this->tag3Key;
	}

	public function setTag3Key($tag3Key) {
		$this->tag3Key = $tag3Key;
		$this->queryParameters["Tag.3.Key"]=$tag3Key;
	}

	public function getFilter2Value() {
		return $this->filter2Value;
	}

	public function setFilter2Value($filter2Value) {
		$this->filter2Value = $filter2Value;
		$this->queryParameters["Filter.2.Value"]=$filter2Value;
	}

	public function getResourceOwnerAccount() {
		return $this->resourceOwnerAccount;
	}

	public function setResourceOwnerAccount($resourceOwnerAccount) {
		$this->resourceOwnerAccount = $resourceOwnerAccount;
		$this->queryParameters["ResourceOwnerAccount"]=$resourceOwnerAccount;
	}

	public function getSnapshotId() {
		return $this->snapshotId;
	}

	public function setSnapshotId($snapshotId) {
		$this->snapshotId = $snapshotId;
		$this->queryParameters["SnapshotId"]=$snapshotId;
	}

	public function getFilter1Value() {
		return $this->filter1Value;
	}

	public function setFilter1Value($filter1Value) {
		$this->filter1Value = $filter1Value;
		$this->queryParameters["Filter.1.Value"]=$filter1Value;
	}

	public function getTag1Key() {
		return $this->tag1Key;
	}

	public function setTag1Key($tag1Key) {
		$this->tag1Key = $tag1Key;
		$this->queryParameters["Tag.1.Key"]=$tag1Key;
	}

	public function getTag1Value() {
		return $this->tag1Value;
	}

	public function setTag1Value($tag1Value) {
		$this->tag1Value = $tag1Value;
		$this->queryParameters["Tag.1.Value"]=$tag1Value;
	}

	public function getDiskChargeType() {
		return $this->diskChargeType;
	}

	public function setDiskChargeType($diskChargeType) {
		$this->diskChargeType = $diskChargeType;
		$this->queryParameters["DiskChargeType"]=$diskChargeType;
	}

	public function getResourceOwnerId() {
		return $this->resourceOwnerId;
	}

	public function setResourceOwnerId($resourceOwnerId) {
		$this->resourceOwnerId = $resourceOwnerId;
		$this->queryParameters["ResourceOwnerId"]=$resourceOwnerId;
	}

	public function getTag4Value() {
		return $this->tag4Value;
	}

	public function setTag4Value($tag4Value) {
		$this->tag4Value = $tag4Value;
		$this->queryParameters["Tag.4.Value"]=$tag4Value;
	}

	public function getOwnerAccount() {
		return $this->ownerAccount;
	}

	public function setOwnerAccount($ownerAccount) {
		$this->ownerAccount = $ownerAccount;
		$this->queryParameters["OwnerAccount"]=$ownerAccount;
	}

	public function getDiskName() {
		return $this->diskName;
	}

	public function setDiskName($diskName) {
		$this->diskName = $diskName;
		$this->queryParameters["DiskName"]=$diskName;
	}

	public function getResourceGroupId() {
		return $this->resourceGroupId;
	}

	public function setResourceGroupId($resourceGroupId) {
		$this->resourceGroupId = $resourceGroupId;
		$this->queryParameters["ResourceGroupId"]=$resourceGroupId;
	}

	public function getDiskIds() {
		return $this->diskIds;
	}

	public function setDiskIds($diskIds) {
		$this->diskIds = $diskIds;
		$this->queryParameters["DiskIds"]=$diskIds;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
		$this->queryParameters["Status"]=$status;
	}

	public function getPageNumber() {
		return $this->pageNumber;
	}

	public function setPageNumber($pageNumber) {
		$this->pageNumber = $pageNumber;
		$this->queryParameters["PageNumber"]=$pageNumber;
	}

	public function getOwnerId() {
		return $this->ownerId;
	}

	public function setOwnerId($ownerId) {
		$this->ownerId = $ownerId;
		$this->queryParameters["OwnerId"]=$ownerId;
	}

	public function getTag5Key() {
		return $this->tag5Key;
	}

	public function setTag5Key($tag5Key) {
		$this->tag5Key = $tag5Key;
		$this->queryParameters["Tag.5.Key"]=$tag5Key;
	}

	public function getLockReason() {
		return $this->lockReason;
	}

	public function setLockReason($lockReason) {
		$this->lockReason = $lockReason;
		$this->queryParameters["LockReason"]=$lockReason;
	}

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		$this->category = $category;
		$this->queryParameters["Category"]=$category;
	}

	public function getDiskType() {
		return $this->diskType;
	}

	public function setDiskType($diskType) {
		$this->diskType = $diskType;
		$this->queryParameters["DiskType"]=$diskType;
	}

	public function getEnableAutoSnapshot() {
		return $this->enableAutoSnapshot;
	}

	public function setEnableAutoSnapshot($enableAutoSnapshot) {
		$this->enableAutoSnapshot = $enableAutoSnapshot;
		$this->queryParameters["EnableAutoSnapshot"]=$enableAutoSnapshot;
	}

	public function getTag2Key() {
		return $this->tag2Key;
	}

	public function setTag2Key($tag2Key) {
		$this->tag2Key = $tag2Key;
		$this->queryParameters["Tag.2.Key"]=$tag2Key;
	}

	public function getFilter1Key() {
		return $this->filter1Key;
	}

	public function setFilter1Key($filter1Key) {
		$this->filter1Key = $filter1Key;
		$this->queryParameters["Filter.1.Key"]=$filter1Key;
	}

	public function getAutoSnapshotPolicyId() {
		return $this->autoSnapshotPolicyId;
	}

	public function setAutoSnapshotPolicyId($autoSnapshotPolicyId) {
		$this->autoSnapshotPolicyId = $autoSnapshotPolicyId;
		$this->queryParameters["AutoSnapshotPolicyId"]=$autoSnapshotPolicyId;
	}

	public function getTag3Value() {
		return $this->tag3Value;
	}

	public function setTag3Value($tag3Value) {
		$this->tag3Value = $tag3Value;
		$this->queryParameters["Tag.3.Value"]=$tag3Value;
	}

	public function getInstanceId() {
		return $this->instanceId;
	}

	public function setInstanceId($instanceId) {
		$this->instanceId = $instanceId;
		$this->queryParameters["InstanceId"]=$instanceId;
	}

	public function getTag2Value() {
		return $this->tag2Value;
	}

	public function setTag2Value($tag2Value) {
		$this->tag2Value = $tag2Value;
		$this->queryParameters["Tag.2.Value"]=$tag2Value;
	}

	public function getTag4Key() {
		return $this->tag4Key;
	}

	public function setTag4Key($tag4Key) {
		$this->tag4Key = $tag4Key;
		$this->queryParameters["Tag.4.Key"]=$tag4Key;
	}
	
}