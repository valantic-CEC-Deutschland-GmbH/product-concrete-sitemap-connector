<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\ProductConcreteSitemapConnector;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\SitemapFileBuilder;
use Generated\Shared\DataBuilder\SitemapUrlBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;
use ValanticSpryker\Shared\ProductConcreteSitemapConnector\ProductConcreteSitemapConnectorConstants;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Communication\Plugin\ProductConcreteSitemapCreatorPlugin;
use ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepository;

class ProductConcreteSitemapCreatorPluginTest extends Unit
{
    protected ProductConcreteSitemapConnectorTester $tester;

    private ProductConcreteSitemapCreatorPlugin $sut;

    /**
     * @var \ValanticSpryker\Zed\ProductConcreteSitemapConnector\Persistence\ProductConcreteSitemapConnectorRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    private ProductConcreteSitemapConnectorRepository $repositoryMock;

    /**
     * @var \ValanticSpryker\Service\Sitemap\SitemapServiceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private SitemapServiceInterface $sitemapServiceMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ProductConcreteSitemapCreatorPlugin();

        $this->mockStoreFacade();
        $this->mockSitemapService();
        $this->mockProductListFacade();
        $this->mockRepository();

        $facade = $this->tester->getFacade();
        $facade->setFactory($this->tester->getFactory())
            ->setRepository($this->repositoryMock);
        $this->sut->setFacade($facade);
    }

    /**
     * @return void
     */
    public function testCreateSitemapXml(): void
    {
        $url1 = (new SitemapUrlBuilder(['url' => '/test1']))->build();
        $url2 = (new SitemapUrlBuilder(['url' => '/test2']))->build();
        $this->repositoryMock->expects($this->exactly(2))
            ->method('findActiveConcreteProductUrls')
            ->willReturnOnConsecutiveCalls([$url1, $url2], []);

        $sitemapFileTransfer = (new SitemapFileBuilder(['yvesBaseUrl' => 'test1.test']))->build();
        $this->sitemapServiceMock->expects($this->exactly(2))
            ->method('createSitemapXmlFileTransfer')
            ->withConsecutive(
                [[$url1, $url2], 1, 'DE', ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE],
                [[], 2, 'DE', ProductConcreteSitemapConnectorConstants::RESOURCE_TYPE],
            )
            ->willReturnOnConsecutiveCalls($sitemapFileTransfer, null);

        $sitemapList = $this->sut->createSitemapXml();

        $this->assertCount(1, $sitemapList);
        $this->assertEquals($sitemapFileTransfer, $sitemapList[0]);
    }

    //@todo add test cases with filter
    //@todo move blacklist logic to separate packgae because it requires product lists

    /**
     * @return void
     */
    private function mockStoreFacade(): void
    {
        $storeTransfer = (new StoreTransfer())
            ->setName('DE')
            ->setIdStore(1);

        $storeFacadeMock = $this->createMock(StoreFacadeInterface::class);
        $storeFacadeMock->method('getCurrentStore')
            ->willReturn($storeTransfer);

        $this->tester->mockFactoryMethod('getStoreFacade', $storeFacadeMock);
    }

    /**
     * @return void
     */
    private function mockSitemapService(): void
    {
        $this->sitemapServiceMock = $this->createMock(SitemapServiceInterface::class);
        $this->tester->mockFactoryMethod('getSitemapService', $this->sitemapServiceMock);
    }

    /**
     * @return void
     */
    private function mockProductListFacade(): void
    {
        $productListFacade = $this->createMock(ProductListFacadeInterface::class);
        $this->tester->mockFactoryMethod('getProductListFacade', $productListFacade);
    }

    /**
     * @return void
     */
    private function mockRepository(): void
    {
        $this->repositoryMock = $this->createMock(ProductConcreteSitemapConnectorRepository::class);
        $this->tester->mockFactoryMethod('getRepository', $this->repositoryMock);
    }
}
