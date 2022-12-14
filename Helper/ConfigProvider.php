<?php

declare(strict_types=1);

namespace Wscvua\RestrictPageByCustomerGroup\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * The module config provider
 */
class ConfigProvider
{
    public const CUSTOMER_GROUPS = 'customer_groups';

    private const XML_PATH_IS_ENABLED = 'wscvua/cms_page_restrict/enable';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Constructor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is enabled restriction or not
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)(int)$this->scopeConfig->getValue(
            self::XML_PATH_IS_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
