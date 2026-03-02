<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business;

use Spryker\Zed\CompanyUnitAddress\Business\Expander\MerchantRelationRequestCollectionExpander;
use Spryker\Zed\CompanyUnitAddress\Business\Expander\MerchantRelationRequestCollectionExpanderInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Expander\MerchantRelationshipExpander;
use Spryker\Zed\CompanyUnitAddress\Business\Expander\MerchantRelationshipExpanderInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReader;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressWriter;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressWriterInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddress;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutor;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface;
use Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressDependencyProvider;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 */
class CompanyUnitAddressBusinessFactory extends AbstractBusinessFactory
{
    public function createCompanyUnitAddress(): CompanyUnitAddressInterface
    {
        return new CompanyUnitAddress(
            $this->getEntityManager(),
            $this->getCountryFacade(),
            $this->getLocaleFacade(),
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyBusinessUnitAddressReader(),
            $this->createCompanyUnitAddressPluginExecutor(),
        );
    }

    public function createCompanyBusinessUnitAddressWriter(): CompanyBusinessUnitAddressWriterInterface
    {
        return new CompanyBusinessUnitAddressWriter(
            $this->createCompanyBusinessUnitAddressReader(),
            $this->getEntityManager(),
        );
    }

    public function createCompanyBusinessUnitAddressReader(): CompanyBusinessUnitAddressReaderInterface
    {
        return new CompanyBusinessUnitAddressReader(
            $this->getRepository(),
            $this->createCompanyUnitAddressPluginExecutor(),
        );
    }

    public function createMerchantRelationshipExpander(): MerchantRelationshipExpanderInterface
    {
        return new MerchantRelationshipExpander($this->createCompanyBusinessUnitAddressReader());
    }

    public function createMerchantRelationRequestCollectionExpander(): MerchantRelationRequestCollectionExpanderInterface
    {
        return new MerchantRelationRequestCollectionExpander($this->getRepository());
    }

    protected function createCompanyUnitAddressPluginExecutor(): CompanyUnitAddressPluginExecutorInterface
    {
        return new CompanyUnitAddressPluginExecutor(
            $this->getCompanyUnitAddressHydratePlugins(),
            $this->getCompanyUnitAddressPostSavePlugins(),
        );
    }

    protected function getCompanyBusinessUnitFacade(): CompanyUnitAddressToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    protected function getCountryFacade(): CompanyUnitAddressToCountryFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::FACADE_COUNTRY);
    }

    protected function getLocaleFacade(): CompanyUnitAddressToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressPostSavePluginInterface>
     */
    protected function getCompanyUnitAddressPostSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::PLUGIN_ADDRESS_POST_SAVE);
    }

    /**
     * @return array<\Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratePluginInterface>
     */
    protected function getCompanyUnitAddressHydratePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::PLUGIN_ADDRESS_TRANSFER_HYDRATING);
    }
}
