<?php
/**
 * Craftsman Test. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace CraftsmanTest\RelatedProduct\Observer;

use CraftsmanTest\RelatedProduct\Api\RelatedProductManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;

class Message implements ObserverInterface
{
    /**
     * @param RelatedProductManagerInterface $relatedManager
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     * @param MessageManagerInterface $managerMessage
     */
    public function __construct(
         private RelatedProductManagerInterface $relatedManager,
         private RequestInterface $request,
         private LoggerInterface $logger,
         private MessageManagerInterface $managerMessage
     ) {}


    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $productId = $this->request->getParam('product');
    
        if ($this->relatedManager->hasProductDependency($productId)) {
            $this->managerMessage->addSuccessMessage(
                $this->relatedManager->getDependencyMessage($productId)
            );
        }
    }
}
