<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group UpdateTest
 * Add your own group annotations below this line
 */
class UpdateTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ADDRESS = 'TEST ADDRESS';

    protected const string COMPANY_BUSINESS_UNIT_NAME = 'Head Office';

    protected const string COMPANY_BUSINESS_UNIT_IBAN = 'DE02120300000000202051';

    protected const string COMPANY_BUSINESS_UNIT_BIC = 'BYLADEM1001';

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    public function testShouldPersistUpdatedDataToDatabase(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();
        $companyUnitAddressTransfer->setAddress1(static::TEST_ADDRESS);

        // Act
        $companyUnitAddressResponseTransfer = $this->tester->getFacade()
            ->update($companyUnitAddressTransfer);

        // Assert
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertSame(static::TEST_ADDRESS, $companyUnitAddressTransferLoaded->getAddress1());
    }

    public function testGivenAddressBelongsToCompanyBusinessUnitWhenMarkedAsDefaultBillingThenCompanyBusinessUnitDataIsPreserved(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
            CompanyBusinessUnitTransfer::NAME => static::COMPANY_BUSINESS_UNIT_NAME,
            CompanyBusinessUnitTransfer::IBAN => static::COMPANY_BUSINESS_UNIT_IBAN,
            CompanyBusinessUnitTransfer::BIC => static::COMPANY_BUSINESS_UNIT_BIC,
        ]);
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
            CompanyUnitAddressTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
            CompanyUnitAddressTransfer::IS_DEFAULT_BILLING => false,
        ]);
        $companyUnitAddressTransfer->setIsDefaultBilling(true);

        // Act
        $companyUnitAddressResponseTransfer = $this->tester->getFacade()
            ->update($companyUnitAddressTransfer);

        // Assert
        $companyBusinessUnitTransferLoaded = $this->tester->getLocator()
            ->companyBusinessUnit()
            ->facade()
            ->findCompanyBusinessUnitById($companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail());

        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertNotNull($companyBusinessUnitTransferLoaded);
        $this->assertSame(
            $companyUnitAddressTransfer->getIdCompanyUnitAddressOrFail(),
            $companyBusinessUnitTransferLoaded->getDefaultBillingAddress(),
        );
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_NAME, $companyBusinessUnitTransferLoaded->getName());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_IBAN, $companyBusinessUnitTransferLoaded->getIban());
        $this->assertSame(static::COMPANY_BUSINESS_UNIT_BIC, $companyBusinessUnitTransferLoaded->getBic());
        $this->assertSame(
            $companyTransfer->getIdCompanyOrFail(),
            $companyBusinessUnitTransferLoaded->getFkCompany(),
        );
        $this->assertSame(
            $companyBusinessUnitTransfer->getKey(),
            $companyBusinessUnitTransferLoaded->getKey(),
        );
    }
}
