<?php

declare(strict_types=1);

namespace Conne\CustomerGroupExtensions\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Conne\CustomerGroupExtensions\Service\CurrencyFilterService;
use Shopware\Core\Framework\Adapter\Twig\Filter\CurrencyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

final class CurrencyFilterTest extends TestCase
{
    #[DataProvider('currencyEntityDataProvider')]
    public function testGetCurrencyEntity(array $return, ?CurrencyEntity $expected): void
    {
        $systemConfigServiceMock = $this->createMock(SystemConfigService::class);
        $decoratedServiceMock    = $this->createMock(CurrencyFilter::class);

        $entityCollectionMock = $this->getMockBuilder(EntitySearchResult::class)
            ->disableOriginalConstructor()
            ->disableAutoReturnValueGeneration()
            ->onlyMethods(['getElements'])
            ->getMock();
        $entityCollectionMock->method('getElements')->willReturn($return);

        $currencyRepositoryMock = $this->getMockBuilder(EntityRepository::class)
            ->onlyMethods(['search'])
            ->disableOriginalConstructor()
            ->getMock();
        $currencyRepositoryMock->method('search')->willReturn($entityCollectionMock);

        $currencyFilterService = new CurrencyFilterService(
            $systemConfigServiceMock,
            $decoratedServiceMock,
            $currencyRepositoryMock
        );

        $this->assertEquals($expected, $currencyFilterService->getCurrencyEntity('3'));
    }

    public static function currencyEntityDataProvider(): array
    {
        $currencyEntity = new CurrencyEntity();

        return [
            'Test for currencyEntity return'      => [['3' => $currencyEntity], $currencyEntity],
            'Test for currencyEntity null return' => [[], null]
        ];
    }
}
