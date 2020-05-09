<?php

namespace Phong\Support\Controller\Adminhtml\Topic;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\Redirect;
use Phong\Support\Model\TopicFactory;

class Edit extends Action
{
    /**
     * Page factory
     *
     * @var PageFactory
     */
    public $resultPageFactory;

    public $topicFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;


    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param PageFactory $resultPageFactory
     * @param TopicFactory $topicFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        PageFactory $resultPageFactory,
        TopicFactory $topicFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->topicFactory = $topicFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $topic = $this->initTopic(true, false);
        $duplicate = $this->getRequest()->getParam('duplicate');

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('topics'));

        $title = $topic->getId() && !$duplicate ? $topic->getName() : __('New topic');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }

    public function initTopic($register = true, $isSave = false)
    {
        $topicId = (int)$this->getRequest()->getParam('topic_id');
        $duplicate = $this->getRequest()->getParam('topic')['duplicate'] ?? null;

        $topic = $this->topicFactory->create();
        if ($topicId) {
            if (!$isSave || !$duplicate) {
                $topic->load($topicId);
                if (!$topic->getId()) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                }
                return false;
            }
        }
        if ($register) {
            $this->coreRegistry->register('phong_support_topic', $topic);
        }

        return $topic;
    }
}

