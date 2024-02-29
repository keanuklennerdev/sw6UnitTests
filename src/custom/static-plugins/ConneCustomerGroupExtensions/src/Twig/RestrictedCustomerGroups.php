<?php

declare(strict_types=1);

namespace Conne\CustomerGroupExtensions\Twig;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RestrictedCustomerGroups extends AbstractExtension
{
    private SystemConfigService $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService)
    {
        $this->systemConfigService = $systemConfigService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction("isRestrictedCustomerGroup", [$this, "isRestrictedCustomerGroup"]),
        ];
    }

    public function isRestrictedCustomerGroup(string $customerGroupId, string $salesChannelId): bool
    {
        $restrictedGroups = $this->systemConfigService->get(
            'ConneCustomerGroupExtensions.config.restrictedCustomerGroups',
            $salesChannelId
        );

        if ($restrictedGroups) {
            return in_array($customerGroupId, $restrictedGroups);
        } else {
            return false;
        }
    }
}
