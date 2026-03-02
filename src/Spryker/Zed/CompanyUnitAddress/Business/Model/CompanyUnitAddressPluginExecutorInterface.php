<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressPluginExecutorInterface
{
    public function executeCompanyUnitAddressHydratorPlugins(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;

    public function executePostSavePlugins(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer;
}
