<?php

namespace Phong\Support\Block\Topic;

use Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;

class View extends Template
{
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
}
