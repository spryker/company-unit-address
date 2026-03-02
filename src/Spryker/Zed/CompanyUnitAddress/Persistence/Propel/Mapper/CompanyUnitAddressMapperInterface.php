<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress;

interface CompanyUnitAddressMapperInterface
{
    public function mapCompanyUnitAddressEntityTransferToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer;

    public function mapCompanyUnitAddressTransferToEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
    ): SpyCompanyUnitAddressEntityTransfer;

    public function mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddress $companyUnitAddressEntity,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer;

    public function mapCompanyUnitAddressTransferToCompanyUnitAddressEntity(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddress $companyUnitAddressEntity
    ): SpyCompanyUnitAddress;

    /**
     * @param array<\Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer> $companyUnitAddressToCompanyBusinessUnitEntities
     *
     * @return array<\Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer>
     */
    public function mapEntitiesToCompanyBusinessUnitTransfers(
        array $companyUnitAddressToCompanyBusinessUnitEntities
    ): array;
}
