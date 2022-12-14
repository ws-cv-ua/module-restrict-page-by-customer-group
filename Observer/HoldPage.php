<?php

declare(strict_types=1);

namespace Wscvua\RestrictPageByCustomerGroup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Wscvua\RestrictPageByCustomerGroup\Model\PageHolder;

/**
 * Hold page for the future restriction validation
 */
class HoldPage implements ObserverInterface
{
    private PageHolder $pageHolder;

    public function __construct(
        PageHolder $pageHolder
    ) {
        $this->pageHolder = $pageHolder;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->pageHolder->setPage(
            $observer->getPage()
        );
    }
}
