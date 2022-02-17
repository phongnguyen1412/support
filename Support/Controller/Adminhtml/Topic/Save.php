<?php

namespace Phong\Support\Controller\Adminhtml\Topic;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Exception;
use Phong\Support\Model\TopicFactory;
use Phong\Support\Model\ResourceModel\TopicFactory as ResourceFactory;

class Save extends Action
{
    /**
     * Topic Factory
     *
     * @var TopicFactory
     */
    protected $topicFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    protected $resourceFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param TopicFactory $topicFactory
     * @param ResourceFactory $resourceFactory
     */
    public function __construct(Context $context, Registry $coreRegistry, TopicFactory $topicFactory, ResourceFactory $resourceFactory)
    {
        $this->topicFactory = $topicFactory;
        $this->coreRegistry = $coreRegistry;
        $this->resourceFactory = $resourceFactory;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|Redirect|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPost('topic')) {
            $topic = $this->initTopic();
            $topic->setData($data);
            $resource = $this->resourceFactory->create();
            try {
                $resource->save($topic);
                $this->messageManager->addSuccessMessage(__('The Topic has been saved.'));
                $this->_getSession()->setData('phong_support_topic_data', false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('phong_support/topic/edit', ['id' => $topic->getId(), '_current' => true]);
                } else {
                    $resultRedirect->setPath('phong_support/topic/index');
                }
            } catch (Exception $ex) {
                $this->messageManager->addExceptionMessage($ex, __('Something went wrong while saving the Topic.'));
                $this->_getSession()->setData(
                    'phong_support/topic/edit',
                    ['id' => $topic->getId(), '_current' => true]
                );
            }
            return $resultRedirect;
        }
        $resultRedirect->setPath('phong_support/topic/');

        return $resultRedirect;
    }

    public function initTopic($register = true)
    {
        $topicId = (int)$this->getRequest()->getParam('id');
        $topic = $this->topicFactory->create();
        if ($topicId) {
            $resource = $this->resourceFactory->create();
            $resource->load($topic, $topicId);
            if (!$topic->getId()) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                return false;
            }
        }
        if ($register) {
            $this->coreRegistry->register('phong_support_topic', $topic);
        }

        return $topic;
    }
}
