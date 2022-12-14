<?php

declare(strict_types=1);

namespace Wscvua\RestrictPageByCustomerGroup\Plugin;

use Magento\Cms\Controller\Page\View;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\ForwardFactory as ResultForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Wscvua\RestrictPageByCustomerGroup\Helper\ConfigProvider;
use Wscvua\RestrictPageByCustomerGroup\Model\PageHolder;

/**
 * Restrict access to cms page
 */
class RestrictAccessToPage
{
    /**
     * @var PageHolder
     */
    private PageHolder $pageHolder;

    /**
     * @var ResultForwardFactory
     */
    private ResultForwardFactory $resultForwardFactory;

    /**
     * @var CustomerSession
     */
    private CustomerSession $customerSession;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param PageHolder $pageHolder
     * @param ResultForwardFactory $resultForwardFactory
     * @param CustomerSession $customerSession
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        PageHolder $pageHolder,
        ResultForwardFactory $resultForwardFactory,
        CustomerSession $customerSession,
        RequestInterface $request,
        LoggerInterface $logger
    ) {
        $this->pageHolder = $pageHolder;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * Check if customer has access to page by customer group
     *
     * @param View $subject
     * @param ResultInterface $result
     * @return ResultInterface
     */
    public function afterExecute(
        View $subject,
        ResultInterface $result
    ): ResultInterface {
        $page = $this->pageHolder->getPage();
        $customerGroupIds = $page->getData(ConfigProvider::CUSTOMER_GROUPS);
        $customerGroupIds = strlen($customerGroupIds)
            ? array_map('intval', explode(',', $customerGroupIds))
            : [];
        if (!count($customerGroupIds)) {
            return $result;
        }

        try {
            $groupId = $this->customerSession->getCustomerGroupId();
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->logger->warning("Can not get customer group id: " . $e->getMessage());
            return $result;
        }

        if (!in_array($groupId, $customerGroupIds)) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }

        return $result;
    }
}
