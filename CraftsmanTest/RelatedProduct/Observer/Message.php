<?php declare(strict_types=1);
/**
 * Craftsman Test. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace CraftsmanTest\RelatedProduct\Observer;

use CraftsmanTest\RelatedProduct\Model\RelatedProductManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class Message implements ObserverInterface
{
    /**
     * @param RelatedProductManager $relatedManager
     * @param RequestInterface $request
     */
    public function __construct(
         private RelatedProductManager $relatedManager,
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
        $this->logger->info('fdsfsfs '.(int)$this->relatedManager->hasProductDependency($productId));
        if ($this->relatedManager->hasProductDependency($productId))
            $this->managerMessage->addSuccessMessage(
                $this->relatedManager->getDependencyMessage($productId)
            );
    }
}
