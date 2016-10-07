<?php

namespace Drupal\card;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CardDemoController extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new CardDemoController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Builds a card demo page.
   *
   * @return array
   *   A render array to build card demo page.
   */
  public function build() {
    $content = [];

    $node_storage = $this->entityTypeManager->getStorage('node');
    $node = $node_storage->load(1);
    $content['node'] = [
      '#type' => 'card',
      '#entity' => $node,
    ];

    $user_storage = $this->entityTypeManager->getStorage('user');
    $user = $user_storage->load(1);
    $content['user'] = [
      '#type' => 'card',
      '#entity' => $user,
    ];

    // Random data that is not reusable.
    $content['random_data_coming_from_anywhere'] = [
      '#theme' => 'card',
      '#title' => t('This card contains random data'),
      '#summary' => t('Which is totally not reusable.'),
      '#link_text' => t('Read more'),
      '#link_url' => Url::fromUri('http://example.com'),
    ];

    // Demonstrate how this workflow could work when something is coming from
    // core.
    $node = $node_storage->load(2);
    $content['node_coming_from_core'] = [
      '#type' => 'node',
      '#entity' => $node,
      '#view_mode' => 'teaser',
    ];

    return $content;
  }

}
