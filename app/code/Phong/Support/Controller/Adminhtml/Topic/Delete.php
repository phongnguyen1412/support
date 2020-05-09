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

class Delete extends Action
{
    /**
     * Topic Factory
     *
     * @var TopicFactory
     */
    public $topicFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    public function __construct(Context $context, Registry $coreRegistry, TopicFactory $topicFactory)
    {
        $this->topicFactory = $topicFactory;
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
                $this->topicFactory->create()->load($id)->delete();
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
