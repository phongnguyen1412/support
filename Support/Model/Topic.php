<?php

namespace Phong\Support\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\UrlInterface;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteFactory as RewriteResourceFactory;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;

/**
 * Undocumented class
 */
class Topic extends AbstractModel
{
    const CACHE_TAG = 'support_topic';

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $_cacheTag = 'support_topic';

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $_eventPrefix = 'support_topic';

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $url;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $urlRewrite;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $rewriteResource;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $storeManager;

    protected $urlRewriteCollection;

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Phong\Support\Model\ResourceModel\Topic');
    }

    /**
     * Undocumented function
     *
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param UrlInterface $url
     * @param UrlRewriteFactory $urlRewrite
     * @param RewriteResourceFactory $rewriteResource
     * @param UrlRewriteCollectionFactory $urlRewriteCollection
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        UrlInterface $url,
        UrlRewriteFactory $urlRewrite,
        RewriteResourceFactory $rewriteResource,
        UrlRewriteCollectionFactory $urlRewriteCollection,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->urlRewrite = $urlRewrite;
        $this->url = $url;
        $this->storeManager = $storeManager;
        $this->rewriteResource = $rewriteResource;
        $this->urlRewriteCollection = $urlRewriteCollection;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function afterSave()
    {
        $this->generateRewriteUrl();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUrl()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $urlRewrite = $this->getUrlRewrite($storeId);
        return !empty($urlRewrite->getId()) ? $urlRewrite->request_path() :  $this->url->getDirectUrl('support/index/view/id/' . $this->getId());
    }

    /**
     * Undocumented function
     *
     * @param [type] $storeId
     * @return UrlRewrite
     */
    protected function getUrlRewrite($storeId)
    {
        $urlRewiteCollection = $this->urlRewriteCollection->create();
        return $urlRewiteCollection->addFieldToFilter('target_path', 'support/index/view/id/' . $this->getId())
            ->addFieldToFilter('store_id', $storeId)
            ->getFirstItem();
    }

    /**
     * Undocumented function
     *
     * @return Topic
     */
    public function generateRewriteUrl()
    {
        $stores = $this->storeManager->getStores();
        if ($this->hasDataChanges('url_key')) {
            foreach ($stores as $store) {
                $resourceRewrite = $this->rewriteResource->create();
                $urlRewrite = $this->getUrlRewrite($store->getId());
                if (!empty($urlRewrite)) {
                    $urlRewrite->setRequestPath('support/' . $this->getUrlKey());
                } else {
                    $urlRewrite->setData([
                        'store_id' => $store->getId(),
                        'is_system' => 0,
                        'entity_type' => 'support',
                        'entity_id' => $this->getId(),
                        'request_path' => 'support/' . $this->getUrlKey(),
                        'target_path' => 'support/index/view/id/' . $this->getId(),
                        'redirect_type' => 0,
                        'description' => null,
                        'is_autogenerated' => 1,
                        'meta_data' => null
                    ]);
                }

                $resourceRewrite->save($urlRewrite);
            }
        }
        return $this;
    }
}
