<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  ${MAGENTO_MODULE_NAMESPACE}
 * @package   ${MAGENTO_MODULE_NAMESPACE}_${MAGENTO_MODULE}
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * @author    Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */

namespace BitTools\SkyHub\Observer\Sales\Order\Status;

use BitTools\SkyHub\Observer\Sales\AbstractSales;
use Magento\Framework\Event\Observer;

class ProcessCompleteStatusOrder extends AbstractSales
{
    
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        $order = $observer->getData('order');
        
        if (!$this->validateOrder($order)) {
            return;
        }
        
        $this->processDeliveredCustomerStatus($order);
    }
    
    
    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this
     */
    protected function processDeliveredCustomerStatus(\Magento\Sales\Model\Order $order)
    {
        $configStatus = $this->context
            ->configContext()
            ->salesOrderStatus()
            ->getDeliveredOrdersStatus();
        
        if (!$this->statusMatches($configStatus, $order->getStatus())) {
            return $this;
        }
        
        try {
            $this->storeIterator->call($this->orderIntegrator, 'delivery', [$order->getEntityId()], $order->getStore());
        } catch (\Exception $e) {
            $this->context->logger()->critical($e);
        }
        
        return $this;
    }
    
    
    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return bool
     */
    protected function validateOrder(\Magento\Sales\Model\Order $order)
    {
        if (!$order || !$order->getEntityId()) {
            return false;
        }
        
        if ($order->getState() != \Magento\Sales\Model\Order::STATE_COMPLETE) {
            return false;
        }
        
        return true;
    }
}
