<?php


declare(strict_types=1);

namespace Gubee\Integration\Api\Message;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DetailRepositoryInterface
{

    /**
     * Save Detail
     * @param \Gubee\Integration\Api\Data\Message\DetailInterface $detail
     * @return \Gubee\Integration\Api\Data\Message\DetailInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Gubee\Integration\Api\Data\Message\DetailInterface $detail
    );

    /**
     * Retrieve Detail
     * @param string $detailId
     * @return \Gubee\Integration\Api\Data\Message\DetailInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($detailId);

    /**
     * Retrieve Detail matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Gubee\Integration\Api\Data\Message\DetailSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Detail
     * @param \Gubee\Integration\Api\Data\Message\DetailInterface $detail
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Gubee\Integration\Api\Data\Message\DetailInterface $detail
    );

    /**
     * Delete Detail by ID
     * @param string $detailId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($detailId);
}