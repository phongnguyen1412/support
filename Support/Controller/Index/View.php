<?php

namespace Phong\Support\Controller\Index;

use Magento\Framework\App\Action\Action;
use Phong\Support\Model\TopicFactory;
use Phong\Support\Model\ResourceModel\TopicFactory as ResourceFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;

class View extends Action
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $topicFactory;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $resourceFactory;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $context;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $pageFactory;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $storeManager;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $resultForwardFactory;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $coreRegistry;

   /**
    * Undocumented function
    *
    * @param Context $context
    * @param TopicFactory $topicFactory
    * @param ResourceFactory $resourceFactory
    * @param PageFactory $pageFactory
    * @param StoreInterface $storeManager
    * @param ForwardFactory $resultForwardFactory
    * @param Registry $coreRegistry
    */
    public function __construct(
        Context $context,
        TopicFactory $topicFactory,
        ResourceFactory $resourceFactory,
        PageFactory $pageFactory,
        StoreInterface $storeManager,
        ForwardFactory $resultForwardFactory,
        Registry  $coreRegistry
    ) {
        parent::__construct($context);
        $this->topicFactory = $topicFactory;
        $this->resourceFactory = $resourceFactory;
        $this->pageFactory = $pageFactory;
        $this->storeManager = $storeManager;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $topic = $this->initTopic();
        if ($topic->getId()) {
            $result = $this->pageFactory->create();
        } else {
            $result = $this->resultForwardFactory->create()->forward('noroute');
        }
        return $result;
    }

    protected function initTopic()
    {
        $id = $this->getRequest()->getParam('id');
        $topic = $this->topicFactory->create();
        $resource = $this->resourceFactory->create();
        $resource->load($topic, $id);
        $this->coreRegistry->register('current_topic', $topic);
        return $topic;
    }
}
