<?php

namespace Phong\Support\Block\Topic\Sidebar;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Phong\Support\Model\ResourceModel\Topic\CollectionFactory;

class Topic extends Template
{
    protected $collectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }

    public function getList($excludeId = [])
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('id', ['nin' => $excludeId]);
        return $collection;
    }
}
