<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductConcreteSitemapConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const SITEMAP_SERVICE = 'SITEMAP_SERVICE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addSitemapService($container);
        $this->addProductListFacade($container);
        $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSitemapService(Container $container): void
    {
        $container->set(
            static::SITEMAP_SERVICE,
            fn (Container $container) => $container->getLocator()->sitemap()->service(),
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addStoreFacade(Container $container): void
    {
        $container->set(
            static::FACADE_STORE,
            fn (Container $container) => $container->getLocator()->store()->facade(),
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductListFacade(Container $container): void
    {
        $container->set(
            static::FACADE_PRODUCT_LIST,
            fn (Container $container) => $container->getLocator()->productList()->facade(),
        );
    }
}
