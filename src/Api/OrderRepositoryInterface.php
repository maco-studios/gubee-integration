<?php

declare (strict_types = 1);

namespace Gubee\Integration\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderRepositoryInterface {

    /**
     * Save Order
     * @param \Gubee\Integration\Api\Data\OrderInterface $order
     * @return \Gubee\Integration\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Gubee\Integration\Api\Data\OrderInterface $order
    );

    /**
     * Retrieve Order
     * @param string $orderId
     * @return \Gubee\Integration\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($orderId);

    /**
     * Retrieve Order matching the specified criteria.
     *
     * @param string $gubeeOrderId
     * @return \Gubee\Integration\Api\Data\OrderInterface
     */
    public function getByGubeeOrderId($gubeeOrderId);

    /**
     * Retrieve Order matching the specified criteria.
     *
     * @param string $orderId
     * @return \Gubee\Integration\Api\Data\OrderInterface
     */
    public function getByOrderId($orderId);


    /**
     * Retrieve Order matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Gubee\Integration\Api\Data\OrderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Order
     * @param \Gubee\Integration\Api\Data\OrderInterface $order
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Gubee\Integration\Api\Data\OrderInterface $order
    );

    /**
     * Delete Order by ID
     * @param string $orderId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($orderId);
}