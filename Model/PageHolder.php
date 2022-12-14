<?php

declare(strict_types=1);

namespace Wscvua\RestrictPageByCustomerGroup\Model;

use Magento\Cms\Model\Page;

/**
 * Holder page for avoiding the getting page one more time
 */
class PageHolder
{
    private Page $page;

    /**
     * @return Page
     */
    public function getPage(): Page
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page): void
    {
        $this->page = $page;
    }
}
