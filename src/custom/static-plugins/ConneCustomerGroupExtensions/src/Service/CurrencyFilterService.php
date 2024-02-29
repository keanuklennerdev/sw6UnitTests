<?php

declare(strict_types=1);

namespace Conne\CustomerGroupExtensions\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Adapter\Twig\Filter\CurrencyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class CurrencyFilterService extends CurrencyFilter
{
    private SystemConfigService $systemConfigService;
    private CurrencyFilter $decoratedService;
    private EntityRepository $currencyRepository;

    public function __construct(
        SystemConfigService $systemConfigService,
        CurrencyFilter $decoratedService,
        EntityRepository $currencyRepository
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->decoratedService = $decoratedService;
        $this->currencyRepository = $currencyRepository;
    }

    public function formatCurrency(
        $twigContext,
        $price,
        $isoCode = null,
        $languageId = null,
        ?int $decimals = null
    ): string
    {
        $customFieldIdent = 'conne_customer_group_currency';
        $salesChannelId = null;

        if (
            isset($twigContext['context'])
            && $twigContext['context'] instanceof Context
            && method_exists($twigContext['context'], 'getSalesChannel')
        ) {
            $salesChannelId = $twigContext['context']->getSalesChannel()->getId();
        } elseif (!empty($twigContext['salesChannelId'])) {
            $salesChannelId = $twigContext['salesChannelId'];
        }

        $restrictedGroups = $this->systemConfigService->get(
            'ConneCustomerGroupExtensions.config.restrictedCustomerGroups',
            $salesChannelId
        );

        if (isset($twigContext['context']) && isset($restrictedGroups)) {
            if ($twigContext['context'] instanceof SalesChannelContext) {
                $context = $twigContext['context'];
                $customerGroup = $context->getCurrentCustomerGroup();
                $currentCustomerGroupId = $customerGroup->getId();

                if (!is_null($customerGroup->getCustomFields())) {
                    $currencyId = $customerGroup->getCustomFields()[$customFieldIdent];
                    $languageId = $context->getSalesChannel()->getLanguageId();
                    $isoCode    = $this->getCurrencyEntity($currencyId)->getIsoCode();
                }

                if (
                    $context->getCustomer() !== null
                    && $context->getCustomer()->getCustomerNumber() !== '-'
                    && !in_array($currentCustomerGroupId, $restrictedGroups)
                ) {
                    return $this->decoratedService->formatCurrency(
                        $twigContext, $price, $isoCode, $languageId, $decimals
                    );
                } else {
                    return '';
                }
            } else {
                return $this->decoratedService->formatCurrency(
                    $twigContext, $price, $isoCode, $languageId, $decimals
                );
            }
        } else {
            return '';
        }
    }

    public function getCurrencyEntity($currencyId): ?CurrencyEntity
    {
        $currencies = $this->currencyRepository->search(
            new Criteria(),
            Context::createDefaultContext()
        )->getElements();

        return $currencies[$currencyId] ?? null;
    }
}
