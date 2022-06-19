<?php

namespace Drupal\fullswiperoptions;

use Drupal\Component\Utility\Html;
use Drupal\views\ViewExecutable;

class Fullswiperoptions {
  
  public static function options($k = null) {
    $options = [
      'direction' => 'horizontal',
      'speed' => 500,
      'spaceBetween' => 10,
      'loop' => true,
      'pagination' => [
        'el' => '.swiper-pagination',
        'type' => 'bullets',
        'clickable' => true
      ],
      'navigation' => [
        'nextEl' => '.swiper-button-next',
        'prevEl' => '.swiper-button-prev'
      ],
      'parallax' => true,
      "autoplay" => [
        'delay' => 8000
      ]
    ];
    if ($k && isset($options[$k])) {
      return $options[$k];
    }
    return $options;
  }
  
  /**
   *
   * @param ViewExecutable $view
   * @return string
   */
  public static function getUniqueId(ViewExecutable $view) {
    $id = $view->storage->id() . '-' . $view->current_display;
    return Html::getUniqueId('swiper-' . $id);
  }
  
  public static function formatOptions(array $values) {
    $defauls = self::options();
    foreach ($values as $k => $value) {
      if (isset($values[$k])) {
        self::formatValue($k, $values[$k]);
        $defauls[$k] = $values[$k];
      }
    }
    return $defauls;
  }
  
  public static function formatValue($key, &$value) {
    switch ($key) {
      case 'speed':
      case 'spaceBetween':
        $value = (int) $value;
        break;
      case 'navigation':
        if (isset($value['status'])) {
          if (!$value['status'])
            $value = false;
          else
            $value = self::options($key);
        }
        break;
      default:
        ;
        break;
    }
  }
  
}
