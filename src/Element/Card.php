<?php

namespace Drupal\card\Element;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\node\NodeInterface;
use Drupal\user\UserInterface;

/**
 * Provides card render element.
 *
 * @RenderElement("card")
 */
class Card extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#theme' => 'card',
      '#entity' => NULL,
      '#pre_render' => [
        [$class, 'preRenderNode'],
        [$class, 'preRenderUser'],
        [$class, 'preRenderCacheableMetadata'],
      ]
    ];
  }

  /**
   * #pre_render callback: Manages node entity type.
   */
  public static function preRenderNode($element) {
    $entity = $element['#entity'];
    if ($entity instanceof NodeInterface) {
      $element['#title'] = $entity->getTitle();
      $element['#summary'] = $entity->get('body')->view(['type' => 'text_summary_or_trimmed', 'label' => 'hidden']);
      $element['#link_text'] = t('Show full node');
      $element['#link_url'] = $entity->toUrl();
    }

    return $element;
  }

  /**
   * #pre_render callback: Manages user entity type.
   */
  public static function preRenderUser($element) {
    $entity = $element['#entity'];
    if ($entity instanceof UserInterface) {
      $element['#title'] = $entity->getAccountName();
      $element['#summary'] = t('Placeholder for user summary');
      $element['#link_text'] = t('Show user profile');
      $element['#link_url'] = $entity->toUrl();
    }

    return $element;
  }

  /**
   * #pre_render callback: Attaches cacheable metadata from the entity.
   */
  public static function preRenderCacheableMetadata($element) {
    $entity = $element['#entity'];
    if ($entity instanceof CacheableDependencyInterface) {
      $cacheable_metadata = CacheableMetadata::createFromObject($element['#entity']);
      $cacheable_metadata->applyTo($element);
    }

    return $element;
  }

}
