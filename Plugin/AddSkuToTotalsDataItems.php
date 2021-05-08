<?php declare(strict_types=1);

namespace Yireo\ExampleCheckoutChanges\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;

class AddSkuToTotalsDataItems
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Session
     */
    private $session;

    /**
     * AddSkuToTotalsDataItems constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param Session $session
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Session $session
    ) {
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    public function afterGetConfig(DefaultConfigProvider $defaultConfigProvider, array $config): array
    {
        if (!isset($config['totalsData'])) {
            return $config;
        }

        $totalsData = $config['totalsData'];
        if (empty($totalsData['items'])) {
            return $config;
        }

        $items = $totalsData['items'];
        foreach ($items as &$item) {
            try {
                $product = $this->getProductByItemId((int)$item['item_id']);
                $item['sku'] = $product->getSku();
                $item['description'] = $product->getDescription();
            } catch (NoSuchEntityException $e) {
                $item['sku'] = $e->getMessage();
                $item['description'] = $e->getMessage();
            }
        }

        $totalsData['items'] = $items;
        $config['totalsData'] = $totalsData;

        return $config;
    }

    /**
     * @param int $itemId
     * @return ProductInterface
     * @throws NoSuchEntityException
     * @throws NotFoundException
     * @throws LocalizedException
     */
    private function getProductByItemId(int $itemId): ProductInterface
    {
        $cartId = $this->session->getQuoteId();
        $cartItems = $this->session->getQuote()->getItems();

        foreach ($cartItems as $cartItem) {
            if ((int)$cartItem->getItemId() === $itemId) {
                return $this->getProductBySku($cartItem->getSku());
            }
        }

        throw new NotFoundException(__('No product found in cart with ID "' . $cartId . '"'));
    }

    /**
     * @param string $sku
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getProductBySku(string $sku): ProductInterface
    {
        return $this->productRepository->get($sku);
    }
}
