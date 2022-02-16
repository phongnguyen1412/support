<?php

namespace Phong\Support\Controller\Adminhtml\Topic;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Phong\Support\Model\TopicFactory;
use Phong\Support\Model\ResourceModel\TopicFactory as ResourceFactory;

class Delete extends Action
{
    /**
     * Topic Factory
     *
     * @var TopicFactory
     */
    protected $topicFactory;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $resourceFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

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
        $this->resourceFactory = $resourceFactory;
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Json|Redirect|ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $resource = $this->resourceFactory->create();
                $topic = $this->topicFactory->create();
                $resource->load($topic, $id);
                $resource->delete($topic);
                $this->messageManager->addSuccessMessage(__('The Topic has been deleted.'));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect->setPath('phong_support/topic/edit', ['id' => $id]);
                return $resultRedirect;
            }
        } else {
            $this->messageManager->addErrorMessage(__('Topic to delete was not found.'));
        }
        $resultRedirect->setPath('phong_support/topic/index');
        return $resultRedirect;
    }
}
