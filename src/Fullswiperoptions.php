<?php

namespace Drupal\fullswiperoptions;

use Drupal\Component\Utility\Html;
use Drupal\views\ViewExecutable;
use Drupal\Core\Template\Attribute;
use Drupal\Component\Serialization\Json;
use Symfony\Component\Validator\Constraints\IsNull;

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
      case 'loopedSlides':
        if (empty($value)) {
          $value = 0;
        }
        else {
          $value = (int) $value;
        }
        break;
      case 'breakpoints':
        foreach ($value as $key => $v) {
          $v['spaceBetween'] = (int) $v['spaceBetween'];
          $value[$key] = $v;
        }
        break;
      case 'module':
        foreach ($value as $key => $v) {
          if ($v) {
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
        break;
    }
  }
  
  public static function FullswiperoptionsTheme(&$vars) {
    $wrappers_attributes = new Attribute();
    $view = $vars['view'];
    $handler = $vars['view']->style_plugin;
    $settings = $handler->options;
    $swiper_options = Fullswiperoptions::formatOptions($settings['swiper_options']);
    // dump($swiper_options);
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
    if (isset($swiper_options['breakpoints_status']) && !$swiper_options['breakpoints_status'])
      unset($swiper_options['breakpoints']);
    // disable supplement_class_status
    if (isset($swiper_options['supplement_class_status']) && !$swiper_options['supplement_class_status']) {
      unset($swiper_options['slideClass']);
      unset($swiper_options['slideActiveClass']);
      unset($swiper_options['supplement_class_status']);
    }
    // Remove loopedSlides if is empty.
    if (isset($swiper_options['loopedSlides']) && !$swiper_options['loopedSlides']) {
      unset($swiper_options['loopedSlides']);
    }
    // remove slidesPerView
    if (isset($swiper_options['slidesPerView']) && empty($swiper_options['slidesPerView'])) {
      unset($swiper_options['slidesPerView']);
    }
    // remove breakpoints_status
    if (isset($swiper_options['breakpoints_status']) && !$swiper_options['breakpoints_status']) {
      unset($swiper_options['breakpoints_status']);
    }
    // remove or add the swiper class
    if (isset($settings['swiper']))
      $swiper_class = $settings['swiper'];
    else
      $swiper_class = 'swiper';
    // define the default values for swipper_attributes
    $vars['swipper_attributes'] = new Attribute([
      'class' => [
        $swiper_class,
        'swiper-full-options',
        $settings['theme']
      ],
      'data-swiper' => Json::encode($swiper_options)
    ]);
    // checking value for the bullets and set the corresponding types of
    $class_pagination = '';
    switch ($settings['theme']) {
      case 'carousel-testy':
        $class_pagination = 'carousel-nav--black d-flex justify-content-center';
        break;
      case 'project-tabs':
        $class_pagination = 'carousel-nav--carree carousel-nav--black d-flex justify-content-center';
        break;
      case 'project-card':
        $class_pagination = '';
        break;
      case 'blog-carousel':
        $class_pagination = 'carousel-rond';
        break;
      case 'carousel-nav-testy':
        $class_pagination = 'carousel-nav--carree carousel-nav--black d-flex justify-content-center';
        break;
      case 'carousel-testy-nav':
        $class_pagination = 'carousel-nav--carree carousel-nav--black d-flex justify-content-center';
        break;
      case 'carousel-testy-nav-rond':
        $class_pagination = 'carousel-nav--black d-flex justify-content-center';
        break;
      default:
        break;
    }
    // remove the class carousel-nav for certain view
    $class_theme = 'carousel-nav';
    if ($settings['theme'] == 'project-card')
      $class_theme = 'd-none';
    // set the definitive Attributes for the swipers
    $vars['swipper_attributes_paginations'] = new Attribute([
      'class' => [
        'swiper-pagination',
        $class_theme,
        // 'carousel-nav',
        $class_pagination
      ]
    ]);
  }
  
}
