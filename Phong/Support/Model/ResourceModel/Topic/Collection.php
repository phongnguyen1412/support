<?php

namespace Phong\Support\Model\ResourceModel\Topic;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'topic_id';
    protected $_eventPrefix = 'support_topic_collection';
    protected $_eventObject = 'topic_collection';

    protected function _construct()
    {
        $this->_init('Phong\Support\Model\Topic', 'Phong\Support\Model\ResourceModel\Topic');
    }
}
