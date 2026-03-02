<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;

class CompanyBusinessUnitAddressReader implements CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface
     */
    protected $companyUnitAddressPluginExecutor;

    public function __construct(
        CompanyUnitAddressRepositoryInterface $repository,
        CompanyUnitAddressPluginExecutorInterface $companyUnitAddressPluginExecutor
    ) {
        $this->repository = $repository;
        $this->companyUnitAddressPluginExecutor = $companyUnitAddressPluginExecutor;
    }

    public function getCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $criteriaFilterTransfer = new CompanyUnitAddressCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompanyBusinessUnit(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );

        return $this->getCompanyBusinessUnitAddressesByCriteriaFilter($criteriaFilterTransfer);
    }

    public function getCompanyBusinessUnitAddressesByCriteriaFilter(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $companyUnitAddressCollectionTransfer = $this->repository->getCompanyBusinessUnitAddressesByCriteriaFilter($criteriaFilterTransfer);
        $companyUnitAddressIds = $this->getCompanyUnitAddressIds($companyUnitAddressCollectionTransfer);
        $addressRelationsToBusinessUnit = $this->repository->getCompanyBusinessUnitAddressToBusinessUnitRelations($companyUnitAddressIds);

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddress) {
            $idCompanyUnitAddress = $companyUnitAddress->getIdCompanyUnitAddress();

            if (isset($addressRelationsToBusinessUnit[$idCompanyUnitAddress])) {
                $companyUnitAddress->setCompanyBusinessUnits($addressRelationsToBusinessUnit[$idCompanyUnitAddress]);
            }
        }

        return $companyUnitAddressCollectionTransfer;
    }

    public function getCompanyUnitAddressById(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $companyUnitAddress = $this->repository->getCompanyUnitAddressById($companyUnitAddressTransfer);
        $companyUnitAddress = $this->companyUnitAddressPluginExecutor
            ->executeCompanyUnitAddressHydratorPlugins($companyUnitAddress);

        return $companyUnitAddress;
    }

    public function findCompanyUnitAddressById(int $idCompanyUnitAddress): ?CompanyUnitAddressTransfer
    {
        $companyUnitAddressTransfer = $this->repository->findCompanyUnitAddressById($idCompanyUnitAddress);

        if (!$companyUnitAddressTransfer) {
            return null;
        }

        return $this->companyUnitAddressPluginExecutor->executeCompanyUnitAddressHydratorPlugins($companyUnitAddressTransfer);
    }

    public function findCompanyBusinessUnitAddressByUuid(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        $companyUnitAddressTransfer->requireUuid();

        $companyUnitAddressTransfer = $this->repository
            ->findCompanyBusinessUnitAddressByUuid($companyUnitAddressTransfer->getUuid());

        $companyUnitAddressResponseTransfer = new CompanyUnitAddressResponseTransfer();
        if (!$companyUnitAddressTransfer) {
            return $companyUnitAddressResponseTransfer->setIsSuccessful(false);
        }

        $companyUnitAddressTransfer = $this->companyUnitAddressPluginExecutor
            ->executeCompanyUnitAddressHydratorPlugins($companyUnitAddressTransfer);

        return $companyUnitAddressResponseTransfer
            ->setIsSuccessful(true)
            ->setCompanyUnitAddressTransfer($companyUnitAddressTransfer);
    }

    /**
     * @param list<int> $companyBusinessUnitIds
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollectionByCompanyBusinessUnitIds(
        array $companyBusinessUnitIds
    ): CompanyUnitAddressCollectionTransfer {
        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setCompanyBusinessUnitIds($companyBusinessUnitIds)
            ->setWithCompanyBusinessUnits(true);

        return $this->repository
            ->getCompanyBusinessUnitAddressesByCriteriaFilter($companyUnitAddressCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return array<int>
     */
    protected function getCompanyUnitAddressIds(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): array {
        $companyUnitAddressIds = [];
        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddress) {
            $companyUnitAddressIds[] = $companyUnitAddress->getIdCompanyUnitAddress();
        }

        return $companyUnitAddressIds;
    }
}
