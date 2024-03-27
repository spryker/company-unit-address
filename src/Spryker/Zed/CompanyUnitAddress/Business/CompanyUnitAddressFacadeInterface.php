<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;

interface CompanyUnitAddressFacadeInterface
{
    /**
     * Specification:
     * - Finds a company unit address by CompanyUnitAddressTransfer::idCompanyUnitAddress in the transfer
     * - Extends returned company unit address transfer with country ISO code.
     * - Extends returned company unit address transfer with country transfer.
     * - Extends returned company unit address transfer with company business unit collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;

    /**
     * Specification:
     * - Creates a company unit address
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function create(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer;

    /**
     * Specification:
     * - Finds a company unit address by CompanyUnitAddressTransfer::idCompanyUnitAddress in the transfer
     * - Updates fields in a company unit address entity
     * - Updates a list addresses assignment for company business unit
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function update(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer;

    /**
     * Specification:
     * - Finds a company unit address by CompanyUnitAddressTransfer::idCompanyUnitAddress in the transfer
     * - Deletes the company unit address
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function delete(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void;

    /**
     * Specification:
     * - Returns the company unit address collection.
     * - Extends each returned company unit address transfer with country ISO code.
     * - Extends each returned company unit address transfer with country transfer.
     * - Extends each returned company unit address transfer with company business unit collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer;

    /**
     * Specification:
     * - Saves Company Business Unit address collection
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function saveCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): void;

    /**
     * Specification:
     * - Finds a company unit address by id.
     * - Returns null if unit address does not exist.
     * - Expands company business unit with extra data using plugins (CompanyUnitAddressHydratePluginInterface).
     *
     * @api
     *
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function findCompanyUnitAddressById(int $idCompanyUnitAddress): ?CompanyUnitAddressTransfer;

    /**
     * Specification:
     * - Retrieves a company unit address by uuid.
     * - Requires uuid field to be set in CompanyUnitAddressTransfer taken as parameter.
     * - Executes `CompanyUnitAddressHydratePluginInterface` plugin stack.
     *
     * @api
     *
     * {@internal will work if UUID field is provided.}
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function findCompanyBusinessUnitAddressByUuid(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer;

    /**
     * Specification:
     * - Expects `MerchantRelationshipCollectionTransfer.merchantRelationships` to be set.
     * - Requires `MerchantRelationshipCollectionTransfer.merchantRelationships.assigneeCompanyBusinessUnits` to be set.
     * - Requires `MerchantRelationshipCollectionTransfer.merchantRelationships.assigneeCompanyBusinessUnits.companyBusinessUnits.idCompanyBusinessUnit` to be set.
     * - Expands `MerchantRelationshipCollectionTransfer.merchantRelationships.assigneeCompanyBusinessUnits` with
     *   the corresponding company business unit addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function expandMerchantRelationshipCollectionWithCompanyUnitAddress(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer;

    /**
     * Specification:
     * - Requires `MerchantRelationRequestCollectionTransfer.merchantRelationRequest.assigneeCompanyBusinessUnits.companyBusinessUnitTransfer` to be set.
     * - Expands `MerchantRelationRequestCollectionTransfer.merchantRelationRequest.assigneeCompanyBusinessUnits` with
     * the corresponding company business unit addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandMerchantRelationRequestCollectionWithAssigneeCompanyBusinessUnitAddress(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer;
}
