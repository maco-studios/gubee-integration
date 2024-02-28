<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product\Attribute;

use Exception;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;
use Gubee\SDK\Resource\Catalog\Product\Attribute\BrandResource;

class Brand extends \Gubee\SDK\Model\Catalog\Product\Attribute\Brand
{
    protected BrandResource $brandResource;

    public function __construct(
        BrandResource $brandResource
    ) {
        $this->brandResource = $brandResource;
    }

    public function load(string $id, string $field = 'external_id'): self
    {
        switch ($field) {
            case 'external_id':
                $brand = $this->getBrandApi()
                    ->loadByExternalId($id);
                break;
            case 'name':
                $brand = $this->getBrandApi()
                    ->loadByName($id);
                break;
        }

        $this->getBrandApi()
            ->getHydrator()
            ->hydrate(
                $brand,
                $this
            );

        return $this;
    }

    public function save(): self
    {
        try {
            $response = $this->getBrandApi()
                ->loadByName(
                    $this->getName()
                );
            if ($response) {
                $response = $this->getBrandApi()
                    ->updateByName(
                        $this->getName(),
                        $this
                    );
            }
        } catch (NotFoundException $e) {
            $response = $this->getBrandApi()
                ->create($this);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->getBrandApi()
                ->getHydrator()
                ->hydrate(
                    $response,
                    $this
                );
        }
        return $this;
    }

    public function getBrandApi(): BrandResource
    {
        return $this->brandResource;
    }
}
