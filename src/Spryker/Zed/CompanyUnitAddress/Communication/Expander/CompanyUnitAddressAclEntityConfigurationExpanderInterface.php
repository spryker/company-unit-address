<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication\Expander;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

interface CompanyUnitAddressAclEntityConfigurationExpanderInterface
{
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer;
}
