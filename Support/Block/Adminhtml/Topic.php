<?php

namespace Phong\Support\Block\Adminhtml;

class Topic extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_topic';
        $this->_blockGroup = 'Phong_Support';
        $this->_headerText = __('Topics');
        $this->_addButtonLabel = __('Create New Topic');
        parent::_construct();
    }
}
