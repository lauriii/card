<?php

namespace Drupal\card\Element;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides node render element.
 *
 * @RenderElement("node")
 */
class Node extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      // Default #theme in case it is not overrided at all.
      '#theme' => 'node',
      '#entity' => NULL,
      '#view_mode' => NULL,
      '#pre_render' => [
        [$class, 'preRenderCacheableMetadata'],
      ]
    ];
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
