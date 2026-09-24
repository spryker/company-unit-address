<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory getFactory()
 */
class CompanyUnitAddressRepository extends AbstractRepository implements CompanyUnitAddressRepositoryInterface
{
    /**
     * The uuid column reaches spy_company_unit_address through a Propel behavior, so the generated
     * query only gains the filter once the project has run the migration for it.
     */
    protected const string ADDRESS_UUID_FILTER_METHOD = 'filterByUuid_In';

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        $companyUnitAddressTransfer->requireIdCompanyUnitAddress();
        $query = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByIdCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->innerJoinWithCountry()
            ->leftJoinWithSpyCompanyUnitAddressToCompanyBusinessUnit()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        $companyUnitAddressEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        return $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapCompanyUnitAddressEntityTransferToCompanyUnitAddressTransfer($companyUnitAddressEntityTransfers[0], new CompanyUnitAddressTransfer());
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated Use {@link getCompanyBusinessUnitAddressesByCriteriaFilter()} and {@link getCompanyBusinessUnitAddressToBusinessUnitRelations()} instead.
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $query = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->innerJoinWithCountry()
            ->leftJoinWithSpyCompanyUnitAddressToCompanyBusinessUnit()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        if ($criteriaFilterTransfer->getUuids() !== [] && method_exists($query, static::ADDRESS_UUID_FILTER_METHOD)) {
            $query->filterByUuid_In($criteriaFilterTransfer->getUuids());
        }

        if ($criteriaFilterTransfer->getIdCompany() !== null) {
            $query->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getIdCompanyBusinessUnit() !== null) {
            $query->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                    ->filterByFkCompanyBusinessUnit($criteriaFilterTransfer->getIdCompanyBusinessUnit())
                    ->leftJoinWithCompanyBusinessUnit()
                ->endUse();
        }

        $collection = $this->buildQueryFromCriteria($query, $criteriaFilterTransfer->getFilter());
        /** @var array<\Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer> $companyUnitAddressEntityTransfers */
        $companyUnitAddressEntityTransfers = $this->getPaginatedCollection($collection, $criteriaFilterTransfer->getPagination());

        $collectionTransfer = new CompanyUnitAddressCollectionTransfer();
        foreach ($companyUnitAddressEntityTransfers as $companyUnitAddressEntityTransfer) {
            $unitAddressTransfer = $this->getFactory()
                ->createCompanyUnitAddressMapper()
                ->mapCompanyUnitAddressEntityTransferToCompanyUnitAddressTransfer(
                    $companyUnitAddressEntityTransfer,
                    new CompanyUnitAddressTransfer(),
                );

            $collectionTransfer->addCompanyUnitAddress($unitAddressTransfer);
        }

        $collectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $collectionTransfer;
    }

    /**
     * @module CompanyBusinessUnit
     * @module Country
     */
    public function getCompanyBusinessUnitAddressesByCriteriaFilter(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $companyUnitAddressQuery = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->innerJoinWithCountry();

        if ($criteriaFilterTransfer->getWithCompanyBusinessUnits()) {
            $companyUnitAddressQuery->leftJoinWithSpyCompanyUnitAddressToCompanyBusinessUnit()
                ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithCompanyBusinessUnit()
                ->endUse();
        }

        if ($criteriaFilterTransfer->getIdCompany() !== null) {
            $companyUnitAddressQuery->filterByFkCompany($criteriaFilterTransfer->getIdCompany());
        }

        if ($criteriaFilterTransfer->getIdCompanyBusinessUnit() !== null) {
            $companyUnitAddressQuery->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery()
                    ->filterByFkCompanyBusinessUnit($criteriaFilterTransfer->getIdCompanyBusinessUnit())
                ->endUse();
        }

        if ($criteriaFilterTransfer->getCompanyBusinessUnitIds()) {
            $companyUnitAddressQuery->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery()
                    ->filterByFkCompanyBusinessUnit_In($criteriaFilterTransfer->getCompanyBusinessUnitIds())
                ->endUse();
        }

        if (
            $criteriaFilterTransfer->getUuids() !== []
            && method_exists($companyUnitAddressQuery, static::ADDRESS_UUID_FILTER_METHOD)
        ) {
            $companyUnitAddressQuery->filterByUuid_In($criteriaFilterTransfer->getUuids());
        }

        $this->applySearchTermToQuery($companyUnitAddressQuery, $criteriaFilterTransfer);
        $this->applySortToQuery($companyUnitAddressQuery, $criteriaFilterTransfer);

        $companyUnitAddressCollection = $this->buildQueryFromCriteria($companyUnitAddressQuery, $criteriaFilterTransfer->getFilter());
        /** @var array<\Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer> $companyUnitAddressEntityTransfers */
        $companyUnitAddressEntityTransfers = $this->getPaginatedCollection($companyUnitAddressCollection, $criteriaFilterTransfer->getPagination());

        $companyUnitAddressCollectionTransfer = new CompanyUnitAddressCollectionTransfer();
        foreach ($companyUnitAddressEntityTransfers as $companyUnitAddressEntityTransfer) {
            $companyUnitAddressTransfer = $this->getFactory()
                ->createCompanyUnitAddressMapper()
                ->mapCompanyUnitAddressEntityTransferToCompanyUnitAddressTransfer(
                    $companyUnitAddressEntityTransfer,
                    new CompanyUnitAddressTransfer(),
                );

            $companyUnitAddressCollectionTransfer->addCompanyUnitAddress($companyUnitAddressTransfer);
        }

        $companyUnitAddressCollectionTransfer->setPagination($criteriaFilterTransfer->getPagination());

        return $companyUnitAddressCollectionTransfer;
    }

    /**
     * @module CompanyBusinessUnit
     *
     * @param array<int> $companyUnitAddressIds
     *
     * @return array<\Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer>
     */
    public function getCompanyBusinessUnitAddressToBusinessUnitRelations(
        array $companyUnitAddressIds
    ): array {
        $companyUnitAddressToCompanyBusinessUnitQuery = $this->getFactory()
            ->createCompanyUnitAddressToCompanyBusinessUnitQuery()
            ->filterByFkCompanyUnitAddress_In(
                $companyUnitAddressIds,
            )
            ->leftJoinWithCompanyBusinessUnit();

        $companyBusinessUnitEntity = $this->buildQueryFromCriteria($companyUnitAddressToCompanyBusinessUnitQuery)->find();

        $indexedCompanyBusinessUnitTransfers = $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapEntitiesToCompanyBusinessUnitTransfers($companyBusinessUnitEntity);

        return $indexedCompanyBusinessUnitTransfers;
    }

    /**
     * @module Country
     * @module CompanyBusinessUnit
     * @module Company
     *
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function findCompanyUnitAddressById(int $idCompanyUnitAddress): ?CompanyUnitAddressTransfer
    {
        $companyUnitAddressQuery = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByIdCompanyUnitAddress($idCompanyUnitAddress)
            ->leftJoinWithCountry()
            ->leftJoinWithCompany()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCompanyBusinessUnit()
            ->endUse();

        /** @var \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress|null $companyUnitAddressEntity */
        $companyUnitAddressEntity = $companyUnitAddressQuery->findOne();
        if (!$companyUnitAddressEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer($companyUnitAddressEntity, new CompanyUnitAddressTransfer());
    }

    /**
     * @module CompanyBusinessUnit
     * @module Country
     * @module Company
     *
     * @param string $companyBusinessUnitAddressUuid
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function findCompanyBusinessUnitAddressByUuid(string $companyBusinessUnitAddressUuid): ?CompanyUnitAddressTransfer
    {
        /** @var \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress|null $companyUnitAddressEntity */
        $companyUnitAddressEntity = $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByUuid($companyBusinessUnitAddressUuid)
            ->leftJoinWithCountry()
            ->leftJoinWithCompany()
            ->useSpyCompanyUnitAddressToCompanyBusinessUnitQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinCompanyBusinessUnit()
            ->endUse()
            ->findOne();

        if (!$companyUnitAddressEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCompanyUnitAddressMapper()
            ->mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer(
                $companyUnitAddressEntity,
                new CompanyUnitAddressTransfer(),
            );
    }

    protected function applySortToQuery(
        SpyCompanyUnitAddressQuery $companyUnitAddressQuery,
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        $sortableFieldMap = $this->getFactory()->getConfig()->getCompanyUnitAddressCollectionSortableFieldMap();

        foreach ($criteriaFilterTransfer->getSortCollection() as $sortTransfer) {
            $column = $sortableFieldMap[$sortTransfer->getField()] ?? null;

            if ($column === null) {
                continue;
            }

            $companyUnitAddressQuery->orderBy(
                $column,
                $sortTransfer->getIsAscending() === false ? Criteria::DESC : Criteria::ASC,
            );
        }

        $companyUnitAddressQuery->orderBy(
            SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS,
            Criteria::DESC,
        );
    }

    protected function applySearchTermToQuery(
        SpyCompanyUnitAddressQuery $companyUnitAddressQuery,
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): void {
        $searchTerm = $criteriaFilterTransfer->getSearchTerm();

        if ($searchTerm === null || $searchTerm === '') {
            return;
        }

        $companyUnitAddressQuery->joinCompany(null, Criteria::LEFT_JOIN);

        $pattern = sprintf('%%%s%%', mb_strtolower($searchTerm));

        $conditions = [
            'city' => SpyCompanyUnitAddressTableMap::COL_CITY,
            'zipCode' => SpyCompanyUnitAddressTableMap::COL_ZIP_CODE,
            'street' => SpyCompanyUnitAddressTableMap::COL_ADDRESS1,
            'number' => SpyCompanyUnitAddressTableMap::COL_ADDRESS2,
            'additionToAddress' => SpyCompanyUnitAddressTableMap::COL_ADDRESS3,
            'countryName' => SpyCountryTableMap::COL_NAME,
            'companyName' => SpyCompanyTableMap::COL_NAME,
        ];

        foreach ($conditions as $name => $column) {
            $companyUnitAddressQuery->condition($name, sprintf('LOWER(%s) LIKE ?', $column), $pattern);
        }

        $companyUnitAddressQuery->where(array_keys($conditions), Criteria::LOGICAL_OR);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Propel\Runtime\Collection\Collection<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    protected function getPaginatedCollection(ModelCriteria $query, ?PaginationTransfer $paginationTransfer = null)
    {
        if ($paginationTransfer !== null) {
            $page = $paginationTransfer
                ->requirePage()
                ->getPage();

            $maxPerPage = $paginationTransfer
                ->requireMaxPerPage()
                ->getMaxPerPage();

            $paginationModel = $query->paginate($page, $maxPerPage);

            $paginationTransfer->setNbResults($paginationModel->getNbResults());
            $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
            $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
            $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
            $paginationTransfer->setLastPage($paginationModel->getLastPage());
            $paginationTransfer->setNextPage($paginationModel->getNextPage());
            $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getResults();
        }

        return $query->find();
    }
}
