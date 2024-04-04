<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Creator\ProductConcreteSitemapCreator;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter\BlacklistFilter;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter\FilterInterface;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor\UrlFilterExecutor;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor\UrlFilterExecutorInterface;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepositoryInterface getRepository()
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig getConfig()
 */
class ProductConcreteSitemapConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Creator\ProductConcreteSitemapCreator
     */
    public function createProductSitemapCreator(): ProductConcreteSitemapCreator
    {
        return new ProductConcreteSitemapCreator(
            $this->getSitemapService(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getStoreFacade(),
            $this->createUrlFilterExecutor(),
        );
    }

    /**
     * @return \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\FilterExecutor\UrlFilterExecutorInterface
     */
    public function createUrlFilterExecutor(): UrlFilterExecutorInterface
    {
        return new UrlFilterExecutor(
            $this->createFilters(),
        );
    }

    /**
     * @return array<\ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter\FilterInterface>
     */
    protected function createFilters(): array
    {
        return [
            $this->createBlackListFilter(),
        ];
    }

    /**
     * @return \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Business\Model\Filter\FilterInterface
     */
    protected function createBlackListFilter(): FilterInterface
    {
        return new BlacklistFilter(
            $this->getProductListFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getProductListFacade(): ProductListFacadeInterface
    {
        return $this->getProvidedDependency(ProductConcreteSitemapConnectorDependencyProvider::FACADE_PRODUCT_LIST);
    }

    /**
     * @return \ValanticSpryker\Service\Sitemap\SitemapServiceInterface
     */
    protected function getSitemapService(): SitemapServiceInterface
    {
        return $this->getProvidedDependency(ProductConcreteSitemapConnectorDependencyProvider::SITEMAP_SERVICE);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductConcreteSitemapConnectorDependencyProvider::FACADE_STORE);
    }
}
