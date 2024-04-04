<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\Mapper;

use Generated\Shared\Transfer\SitemapUrlNodeTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Collection\ObjectCollection;
use ValanticSpryker\Shared\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConstants;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig;

class ProductConcreteSitemapUrlMapper
{
    /**
     * @var string
     */
    protected const URL_FORMAT = '%s%s';

    /**
     * @var \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig
     */
    protected ProductConcreteSitemapConnectorConfig $config;

    /**
     * @param \ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig $config
     */
    public function __construct(
        ProductConcreteSitemapConnectorConfig $config
    ) {
        $this->config = $config;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $urlEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer>
     */
    public function mapUrlEntitiesToSitemapUrlNodeTransfers(ObjectCollection $urlEntities): array
    {
        $transfers = [];

        /** @var \Orm\Zed\Url\Persistence\SpyUrl $urlEntity */
        foreach ($urlEntities as $urlEntity) {
            $transfers[] = $this->createSitemapUrlNodeTransfer($urlEntity);
        }

        return $transfers;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\SitemapUrlNodeTransfer
     */
    protected function createSitemapUrlNodeTransfer(SpyUrl $urlEntity): SitemapUrlNodeTransfer
    {
        return (new SitemapUrlNodeTransfer())
            ->setUrl($this->formatUrl($urlEntity))
            ->setUpdatedAt($urlEntity->getVirtualColumn('updated_at'))
            ->setResourceId($urlEntity->getFkResourceProduct())
            ->setResourceType(ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return string
     */
    protected function formatUrl(SpyUrl $urlEntity): string
    {
        return sprintf(
            static::URL_FORMAT,
            $this->config->getYvesBaseUrl(),
            $urlEntity->getUrl(),
        );
    }
}
