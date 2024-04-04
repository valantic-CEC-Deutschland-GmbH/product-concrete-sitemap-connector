<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence;

use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\Mapper\ProductConcreteSitemapUrlMapper;

/**
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig getConfig()
 * @method \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepositoryInterface getRepository()
 */
class ProductConcreteSitemapConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\Mapper\ProductConcreteSitemapUrlMapper
     */
    public function createSitemapUrlMapper(): ProductConcreteSitemapUrlMapper
    {
        return new ProductConcreteSitemapUrlMapper(
            $this->getConfig(),
        );
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function createSpyUrlQuery(): SpyUrlQuery
    {
        return SpyUrlQuery::create();
    }
}
