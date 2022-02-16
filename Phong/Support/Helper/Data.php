<?php

namespace Phong\Support\Helper;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Filter\TranslitUrl;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var TranslitUrl
     */
    public $translitUrl;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var HttpContext
     */
    protected $_httpContext;

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        TranslitUrl $translitUrl,
        ProductMetadataInterface $productMetadata,
        Session $customerSession,
        HttpContext $httpContext,
        DateTime $dateTime
    ) {
        $this->translitUrl = $translitUrl;
        $this->dateTime = $dateTime;
        $this->customerSession = $customerSession;
        $this->_httpContext = $httpContext;

        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isLogin()
    {
        return $this->_httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }
}
