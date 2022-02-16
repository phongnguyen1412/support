<?php

namespace Phong\Support\Model;

use Magento\Framework\Model\AbstractModel;

class Topic extends AbstractModel
{
    const CACHE_TAG = 'support_topic';

    protected $_cacheTag = 'support_topic';

    protected $_eventPrefix = 'support_topic';

    protected function _construct()
    {
        $this->_init('Phong\Support\Model\ResourceModel\Topic');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }
}
