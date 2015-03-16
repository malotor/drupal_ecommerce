<?php
/**
 * @file
 * Contains \Drupal\ecommerce\Controller\EcommerceController.
 */
namespace Drupal\ecommerce\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\ecommerce\Ecommerce\EcommercePrinter;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\UrlGeneratorTrait;

class EcommerceController extends ControllerBase {

  use UrlGeneratorTrait;

  public function __construct($ecommerceManager) {
    $this->ecommerceManager = $ecommerceManager;
  }

  public function addToCart($productId) {
    try {

      $this->ecommerceManager->addProductToCart($productId);
      return $this->redirectToPreviosPage();

    } catch (\Exception $e) {
      drupal_set_message ($e->getMessage (), 'error');
    }
  }

  public function showCart() {
    try {
      $shoppingCart =  $this->ecommerceManager->getCartItems();
      $printer = new EcommercePrinter();
      return $printer->printShoppingCart($shoppingCart);
    } catch (\Exception $e) {
      drupal_set_message ($e->getMessage (), 'error');
    }
  }

  public function removeFromCart($productId) {
    try {
      $this->ecommerceManager->removeProductFromCart($productId);
      return $this->redirectToPreviosPage();
    } catch (\Exception $e) {
      drupal_set_message($e->getMessage (), 'error');
    }
  }

  protected function redirectToPreviosPage() {
    //Redirect to previous page
    $request = \Drupal::request();
    $referer = $request->headers->get('referer');
    return RedirectResponse::create($this->url('<front>'));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ecommerce.manager')
    );
  }

}