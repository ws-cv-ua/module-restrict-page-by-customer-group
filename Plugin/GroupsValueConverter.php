<?php

declare(strict_types=1);

namespace Wscvua\RestrictPageByCustomerGroup\Plugin;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Wscvua\RestrictPageByCustomerGroup\Helper\ConfigProvider;

/**
 * Converting groups value before saving
 */
class GroupsValueConverter
{
    /**
     * Convert into string array value
     *
     * @param PageRepositoryInterface $subject
     * @param PageInterface $page
     * @return array
     */
    public function beforeSave(
        PageRepositoryInterface $subject,
        PageInterface $page
    ): array {
        $customersGroups = $page->getData(ConfigProvider::CUSTOMER_GROUPS);
        if (is_array($customersGroups)) {
            $customersGroups = implode(",", $customersGroups);
        }
        if (is_string($customersGroups) && !strlen($customersGroups)) {
            $customersGroups = null;
        }
        $page->setData(ConfigProvider::CUSTOMER_GROUPS, $customersGroups);

        return [$page];
    }
}
