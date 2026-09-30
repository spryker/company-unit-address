<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress;

use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUnitAddressConfig extends AbstractBundleConfig
{
    protected const string SORT_FIELD_CITY = 'city';

    protected const string SORT_FIELD_ZIP_CODE = 'zipCode';

    public const string FILTER_FIELD_COMPANY_UUID = 'companyUuid';

    public const string FILTER_FIELD_COMPANY_BUSINESS_UNIT_UUID = 'companyBusinessUnitUuid';

    /**
     * Specification:
     * - Returns the map of filterable field names to the criteria filter properties they set.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCompanyUnitAddressCollectionFilterableFieldMap(): array
    {
        return [
            static::FILTER_FIELD_COMPANY_UUID => CompanyUnitAddressCriteriaFilterTransfer::ID_COMPANY,
            static::FILTER_FIELD_COMPANY_BUSINESS_UNIT_UUID => CompanyUnitAddressCriteriaFilterTransfer::ID_COMPANY_BUSINESS_UNIT,
        ];
    }

    /**
     * Specification:
     * - Returns the map of sortable field names to the columns they order by.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCompanyUnitAddressCollectionSortableFieldMap(): array
    {
        return [
            static::SORT_FIELD_CITY => SpyCompanyUnitAddressTableMap::COL_CITY,
            static::SORT_FIELD_ZIP_CODE => SpyCompanyUnitAddressTableMap::COL_ZIP_CODE,
        ];
    }

    /**
     * Specification:
     * - Returns true if the UUID feature for company unit address entities is enabled.
     * - When enabled, the uuid column and unique index are added to spy_company_unit_address table.
     *
     * @api
     *
     * @return bool
     */
    public function isUuidEnabled(): bool
    {
        return false;
    }
}
