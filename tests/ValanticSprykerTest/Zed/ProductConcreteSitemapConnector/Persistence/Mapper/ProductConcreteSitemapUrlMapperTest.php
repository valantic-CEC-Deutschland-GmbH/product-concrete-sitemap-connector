<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\ProductConcreteSitemapConnector\Persistence\Mapper;

use Codeception\Test\Unit;
use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Application\ApplicationConstants;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\Mapper\ProductConcreteSitemapUrlMapper;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConfig;
use ValanticSprykerTest\Zed\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorTester;

class ProductConcreteSitemapUrlMapperTest extends Unit
{
    public const YVES_BASE_URL = 'www.test.com';
    protected ProductConcreteSitemapConnectorTester $tester;

    private ProductConcreteSitemapUrlMapper $sut;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config = new ProductConcreteSitemapConnectorConfig();
        $this->tester->setConfig(ApplicationConstants::BASE_URL_YVES, self::YVES_BASE_URL);
        $this->sut = new ProductConcreteSitemapUrlMapper($config);
    }

    /**
     * @return void
     */
    public function testMapUrlEntitiesToSitemapUrlTransfers(): void
    {
        $url1 = (new SpyUrl())
            ->setUrl('/en/test1')
            ->setVirtualColumn('updated_at', date('Y-m-d'))
            ->setFkResourceProduct(1);
        $url2 = (new SpyUrl())
            ->setUrl('/en/test2')
            ->setVirtualColumn('updated_at', date('Y-m-d'))
            ->setFkResourceProduct(2);
        $urlEntities = new ObjectCollection([$url1, $url2]);

        $sitemapUrlTransfers = $this->sut->mapUrlEntitiesToSitemapUrlTransfers($urlEntities);

        $this->assertCount(2, $sitemapUrlTransfers);
        $urlTransfer1 = $sitemapUrlTransfers[0];
        $urlTransfer2 = $sitemapUrlTransfers[1];

        $this->assertEquals(self::YVES_BASE_URL . $url1->getUrl(), $urlTransfer1->getUrl());
        $this->assertEquals(self::YVES_BASE_URL . $url2->getUrl(), $urlTransfer2->getUrl());
    }
}
