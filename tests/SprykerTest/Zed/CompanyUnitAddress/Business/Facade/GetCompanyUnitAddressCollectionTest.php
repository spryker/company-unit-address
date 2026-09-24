<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group GetCompanyUnitAddressCollectionTest
 * Add your own group annotations below this line
 */
class GetCompanyUnitAddressCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_COUNT = 3;

    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_MAX_PER_PAGE = 2;

    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_PAGE = 2;

    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_COUNT_EXPECTED = 1;

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    public function testShouldReturnCollectionWhenAssigned(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressCollection(),
        ]);
        $this->tester->getFacade()
            ->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
            );

        // Assert
        $this->assertGreaterThan(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    public function testShouldReturnEmptyCollectionWhenNotAssigned(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
            );

        // Assert
        $this->assertSame(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    public function testShouldReturnPaginatedCollectionWhenPaginationIsSet(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT),
        ]);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        $paginationTransfer = (new PaginationTransfer())
            ->setMaxPerPage(static::VALUE_COMPANY_UNIT_ADDRESSES_MAX_PER_PAGE)
            ->setPage(static::VALUE_COMPANY_UNIT_ADDRESSES_PAGE);

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setPagination($paginationTransfer);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            $companyUnitAddressCriteriaFilterTransfer,
        );

        // Assert
        $this->assertCount(
            static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT_EXPECTED,
            $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses(),
        );

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->getIterator()->current();

        $this->assertCount(
            1,
            $companyUnitAddressTransfer->getCompanyBusinessUnits()->getCompanyBusinessUnits(),
        );

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransferLoaded */
        $companyBusinessUnitTransferLoaded = $companyUnitAddressTransfer
            ->getCompanyBusinessUnits()
            ->getCompanyBusinessUnits()
            ->getIterator()
            ->current();

        $this->assertEquals(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            $companyBusinessUnitTransferLoaded->getIdCompanyBusinessUnit(),
        );
    }

    public function testShouldReturnCompanyUnitAddressesWithCountry(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountry();
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::COUNTRY => $countryTransfer,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => (new CompanyUnitAddressCollectionTransfer())->addCompanyUnitAddress($companyUnitAddressTransfer),
        ]);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail());

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses());

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $actualCompanyUnitAddressTransfer */
        $actualCompanyUnitAddressTransfer = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->getIterator()->current();
        $this->assertSame($countryTransfer->toArray(), $actualCompanyUnitAddressTransfer->getCountryOrFail()->toArray());
    }

    public function testShouldReturnCompanyUnitAddressesByCompanyBusinessUnitIds(): void
    {
        // Arrange
        $companyBusinessUnitTransfer1 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT),
        ]);
        $companyBusinessUnitTransfer2 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT),
        ]);

        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer1);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer2);

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->addIdCompanyBusinessUnit($companyBusinessUnitTransfer1->getIdCompanyBusinessUnit());

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            $companyUnitAddressCriteriaFilterTransfer,
        );

        // Assert
        $this->assertCount(
            static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT,
            $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses(),
        );
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->getIterator()->current();

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransferLoaded */
        $companyBusinessUnitTransferLoaded = $companyUnitAddressTransfer
            ->getCompanyBusinessUnits()
            ->getCompanyBusinessUnits()
            ->getIterator()
            ->current();

        $this->assertEquals(
            $companyBusinessUnitTransfer1->getIdCompanyBusinessUnit(),
            $companyBusinessUnitTransferLoaded->getIdCompanyBusinessUnit(),
        );
    }

    public function testOrdersTheCollectionByASortableFieldAscending(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Zwolle',
        ]);
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Aachen',
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())
                ->setIdCompany($companyTransfer->getIdCompany())
                ->addSort((new SortTransfer())->setField('city')->setIsAscending(true)),
        );

        // Assert
        $this->assertSame(
            ['Aachen', 'Zwolle'],
            $this->extractCities($companyUnitAddressCollectionTransfer),
        );
    }

    public function testOrdersTheCollectionByASortableFieldDescending(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Aachen',
        ]);
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Zwolle',
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())
                ->setIdCompany($companyTransfer->getIdCompany())
                ->addSort((new SortTransfer())->setField('city')->setIsAscending(false)),
        );

        // Assert
        $this->assertSame(
            ['Zwolle', 'Aachen'],
            $this->extractCities($companyUnitAddressCollectionTransfer),
        );
    }

    public function testOrdersTheCollectionByEverySortFieldItWasGiven(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Aachen',
            CompanyUnitAddressTransfer::ZIP_CODE => '52070',
        ]);
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Aachen',
            CompanyUnitAddressTransfer::ZIP_CODE => '52062',
        ]);
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Zwolle',
            CompanyUnitAddressTransfer::ZIP_CODE => '8011',
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())
                ->setIdCompany($companyTransfer->getIdCompany())
                ->addSort((new SortTransfer())->setField('city')->setIsAscending(true))
                ->addSort((new SortTransfer())->setField('zipCode')->setIsAscending(false)),
        );

        // Assert
        $zipCodes = [];

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $zipCodes[] = $companyUnitAddressTransfer->getZipCode();
        }

        $this->assertSame(['52070', '52062', '8011'], $zipCodes);
    }

    public function testSortOnANonUniqueColumnStillOrdersTiedRowsDeterministically(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $first = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Aachen',
        ]);
        $second = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUnitAddressTransfer::CITY => 'Aachen',
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())
                ->setIdCompany($companyTransfer->getIdCompany())
                ->addSort((new SortTransfer())->setField('city')->setIsAscending(true)),
        );

        // Assert
        $this->assertSame(
            [$second->getIdCompanyUnitAddress(), $first->getIdCompanyUnitAddress()],
            $this->extractIds($companyUnitAddressCollectionTransfer),
        );
    }

    public function testWithoutASortTheMostRecentlyCreatedAddressLeads(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $older = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $newer = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())->setIdCompany($companyTransfer->getIdCompany()),
        );

        // Assert
        $this->assertSame(
            [$newer->getIdCompanyUnitAddress(), $older->getIdCompanyUnitAddress()],
            $this->extractIds($companyUnitAddressCollectionTransfer),
        );
    }

    public function testIgnoresASortFieldOutsideTheSortableFieldMap(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())
                ->setIdCompany($companyTransfer->getIdCompany())
                ->addSort((new SortTransfer())->setField('spy_company_unit_address.id_company_unit_address; --')),
        );

        // Assert
        $this->assertCount(
            1,
            $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses(),
            'A sort field absent from the sortable field map resolves to no column and is skipped.',
        );
    }

    /**
     * @return array<int, int>
     */
    protected function extractIds(CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer): array
    {
        $ids = [];

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $ids[] = $companyUnitAddressTransfer->getIdCompanyUnitAddress();
        }

        return $ids;
    }

    /**
     * @return array<int, string>
     */
    protected function extractCities(CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer): array
    {
        $cities = [];

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $cities[] = $companyUnitAddressTransfer->getCity();
        }

        return $cities;
    }
}
