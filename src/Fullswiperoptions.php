<?php

namespace Drupal\fullswiperoptions;

use Drupal\Component\Utility\Html;
use Drupal\views\ViewExecutable;
use Drupal\Core\Template\Attribute;
use Drupal\Component\Serialization\Json;

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
      case 'breakpoints':
        foreach ($value as $key => $v) {
          $v['spaceBetween'] = (int) $v['spaceBetween'];
          $value[$key] = $v;
        }
        break;
      case 'module':
        foreach ($value as $key => $v) {
          if($v){
            $value[] = $key;
          }
          unset($value[$key]);
        }
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
  
  public static function FullswiperoptionsTheme(&$vars) {
    $wrappers_attributes = new Attribute();
    $wrappers_paginations = new Attribute([
      'class'=>[
        'swiper-pagination',
        'carousel-nav'
        ]
      ]
    );
    $view = $vars['view'];
    $handler = $vars['view']->style_plugin;
    $settings = $handler->options;
    $swiper_options = Fullswiperoptions::formatOptions($settings['swiper_options']);
    //dump($swiper_options);
    $vars['swiper_options'] = $swiper_options;
    $id = Fullswiperoptions::getUniqueId($view);
    $wrappers_attributes->setAttribute('id', $id);
    $vars['wrappers_attributes'] = $wrappers_attributes;
    // if (!empty($settings['row_class'])) {
    foreach ($vars['rows'] as $num => $row) {
      $vars['rows'][$num]['attributes'] = [];
      if ($row_class = $handler->getRowClass($num)) {
        $vars['rows'][$num]['attributes']['class'][] = $row_class;
      }
      $vars['rows'][$num]['attributes'] = new Attribute($vars['rows'][$num]['attributes']);
    }
    // }
    // disable breakpoints options : 
    if(isset($swiper_options['breakpoints_status']))
    if (!$swiper_options['breakpoints_status'])
    {
      unset($swiper_options['breakpoints']);
    }
    $vars['swipper_attributes'] = new Attribute([
      'class' => [
        'swiper',
        'swiper-full-options',
        $settings['theme']
      ],
      'data-swiper' => Json::encode($swiper_options)
    ]);
  }
  
}
