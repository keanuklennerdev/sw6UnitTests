<?php

declare(strict_types=1);

namespace Conne\CustomerGroupExtensions;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class ConneCustomerGroupExtensions extends Plugin
{
    public function activate(ActivateContext $activateContext): void
    {
        /** @var EntityRepository $customFieldSetRepository */
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');

        $customFieldSetRepository->upsert([
            [
                'name' => 'conne_customer_group_currency_set',
                'config' => [
                    'label' => [
                        'de-DE' => 'customer group currency',
                        'en-GB' => 'customer group currency'
                    ]
                ],
                'customFields' => [
                    [
                        'name' => 'conne_customer_group_currency',
                        'type' => CustomFieldTypes::ENTITY,
                        'config' => [
                            'label' => [
                                'de-DE' => 'Währung für Kundengruppe',
                                'en-GB' => 'currency for customer group'
                            ],
                            'componentName' => 'sw-entity-single-select',
                            'type' => 'select',
                            'entity' => 'currency',
                            'customFieldType' => 'entity',
                            'customFieldPosition' => 1
                        ]
                    ]
                ],
                'relations' => [
                    [
                        'id' => UUid::randomHex(),
                        'entityName' => 'customer_group'
                    ]
                ]
            ]
        ], $activateContext->getContext());
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        $connection = $this->container->get(Connection::class);

        $connection->executeUpdate('
            DELETE FROM `custom_field_set`
            WHERE name LIKE \'conne_customer_group_currency_set\'
	    ');

        $connection->executeUpdate('
            DELETE FROM `custom_field`
            WHERE name LIKE \'conne_customer_group_currency\'
	    ');

        $connection->executeUpdate('
            UPDATE `customer_group_translation`
            SET custom_fields = null
        ');
    }
}
