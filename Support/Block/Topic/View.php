<?php

namespace Phong\Support\Block\Topic;

use Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

class View extends Template
{
    protected $registry;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }

    public function getTopic()
    {
        return $this->registry->registry('current_topic');
    }
}
