<?php

namespace Drupal\fullswiperoptions;

use Drupal\Component\Utility\Html;
use Drupal\views\ViewExecutable;
use Drupal\Core\Template\Attribute;
use Drupal\Component\Serialization\Json;
use Drupal\core\form\FormStateInterface;

class Fullswiperoptions {
  
  public static function options($k = null) {
    $options = [
      'direction' => 'horizontal',
      'effect' => 'slide',
      'speed' => 500,
      'spaceBetween' => 10,
      'loop' => false,
      'zoom' => false,
      'pagination' => [
        'el' => '.swiper-pagination',
        'type' => 'bullets',
        'clickable' => true
      ],
      'navigation' => [
        'nextEl' => '.swiper-button-next',
        'prevEl' => '.swiper-button-prev',
        'enabled' => 1
      ],
      'parallax' => false,
      "autoplay" => [
        'delay' => 8000,
        // pause in mouse enter and replay when mouse out.
        'pauseOnMouseEnter' => true,
        'disableOnInteraction' => false,
        'waitForTransition' => true,
        'stopOnLastSlide' => false
      ],
      // Le modules sont automatiquement charges : Navigation, Pagination,
      // Parallax, Autoplay, Controller, Thumbs, Scrollbar, EffectFade
      // 'module' => [],
      'centeredSlides' => false,
      'freeMode' => [
        'enabled' => false,
        'minimumVelocity' => 0.2,
        'momentum' => true,
        'momentumBounce' => true,
        'momentumBounceRatio' => 1,
        'momentumRatio' => 1,
        'momentumVelocityRatio' => 1,
        'sticky' => 1
      ]
    ];
    if ($k && isset($options[$k])) {
      return $options[$k];
    }
    return $options;
  }
  
  /**
   * Build general config.
   *
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public static function buildSwiperjsOptions(&$form, $options) {
    if (!empty($options['swiperjs_options']))
      $options = $options['swiperjs_options'];
    $form['swiperjs_options'] = [
      '#title' => t('Swiper settings'),
      '#type' => 'details',
      '#open' => false
    ];
    $form['swiperjs_options']['direction'] = [
      '#title' => t('Direction'),
      '#type' => 'select',
      '#default_value' => isset($options['direction']) ? $options['direction'] : 'horizontal',
      '#options' => [
        'horizontal' => 'Horizontal',
        'vertical' => 'Vertical'
      ]
    ];
    // using supplements class :
    $form['swiperjs_options']['supplement_class_status'] = [
      '#type' => 'checkbox',
      '#title' => t('Use Supplement Class'),
      '#default_value' => isset($options['swiperjs_options']['supplement_class_status']) ? $options['swiperjs_options']['supplement_class_status'] : false
    ];
    $form['swiperjs_options']['slideClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => isset($options['swiperjs_options']['slideClass']) ? $options['swiperjs_options']['slideClass'] : '',
      '#states' => [
        'visible' => [
          ':input[name="style_options[swiperjs_options][supplement_class_status]"]' => [
            'checked' => TRUE
          ]
        ]
      ],
      '#title' => t('slideClass')
    ];
    $form['swiperjs_options']['slideActiveClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => isset($options['swiperjs_options']['slideActiveClass']) ? $options['swiperjs_options']['slideActiveClass'] : '',
      '#states' => [
        'visible' => [
          ':input[name="style_options[swiperjs_options][supplement_class_status]"]' => [
            'checked' => TRUE
          ]
        ]
      ],
      '#title' => t('slideActiveClass')
    ];
    // form for loopedSlides :
    $form['swiperjs_options']['loopedSlides'] = [
      '#type' => 'number',
      '#title' => t('loopedSlides'),
      '#default_value' => isset($options['swiperjs_options']['loopedSlides']) ? $options['swiperjs_options']['loopedSlides'] : null
    ];
    // form for slidesPerView :
    $form['swiperjs_options']['slidesPerView'] = [
      '#type' => 'number',
      '#title' => t('slidesPerView'),
      '#default_value' => isset($options['swiperjs_options']['slidesPerView']) ? $options['swiperjs_options']['slidesPerView'] : null
    ];
    // form for breakpoints :
    $form['swiperjs_options']['breakpoints_status'] = [
      '#type' => 'checkbox',
      '#title' => t('Use breakpoints'),
      '#default_value' => isset($options['swiperjs_options']['breakpoints_status']) ? $options['swiperjs_options']['breakpoints_status'] : null
    ];
    $bpts = [
      576,
      769,
      992,
      1201,
      1601
    ];
    foreach ($bpts as $bp) {
      $form['swiperjs_options']['breakpoints'][$bp] = [
        '#type' => 'details',
        '#states' => [
          'visible' => [
            ':input[name="style_options[swiperjs_options][breakpoints_status]"]' => [
              'checked' => TRUE
            ]
          ]
        ],
        '#title' => t('breakpoint ' . $bp)
      ];
      $form['swiperjs_options']['breakpoints'][$bp]['centeredSlides'] = [
        '#type' => 'checkbox',
        '#title' => t('centeredSlides'),
        '#default_value' => $options['swiperjs_options']['breakpoints'][$bp]['centeredSlides']
      ];
      $form['swiperjs_options']['breakpoints'][$bp]['slidesPerView'] = [
        '#type' => 'number',
        '#title' => t('slidesPerView '),
        '#default_value' => $options['swiperjs_options']['breakpoints'][$bp]['slidesPerView']
      ];
      $form['swiperjs_options']['breakpoints'][$bp]['spaceBetween'] = [
        '#type' => 'number',
        '#title' => t('spaceBetween '),
        '#default_value' => $options['swiperjs_options']['breakpoints'][$bp]['spaceBetween']
      ];
    }
    $form['swiperjs_options']['effect'] = [
      '#title' => t('effect'),
      '#type' => 'select',
      '#default_value' => isset($options['effect']) ? $options['effect'] : 'slide',
      '#options' => [
        'slide' => 'Slide',
        'fade' => 'fade'
        // Les modules en relation ne sont pas activées au niveau js.
        // 'cube' => 'coverflow',
        // 'flip' => 'flip',
        // 'creative' => 'creative'
      ]
    ];
    $form['swiperjs_options']['speed'] = [
      '#title' => t('Speed'),
      '#type' => 'number',
      '#default_value' => isset($options['speed']) ? $options['speed'] : 500
    ];
    
    $form['swiperjs_options']['spaceBetween'] = [
      '#title' => t('spaceBetween'),
      '#type' => 'textfield',
      '#default_value' => isset($options['spaceBetween']) ? $options['spaceBetween'] : 10
    ];
    $form['swiperjs_options']['slidesPerView'] = [
      '#title' => t('slidesPerView'),
      '#type' => 'number',
      '#default_value' => isset($options['slidesPerView']) ? $options['slidesPerView'] : 4
    ];
    $form['swiperjs_options']['loop'] = [
      '#title' => t('Loop'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['loop']) ? $options['loop'] : false
    ];
    // field for grabCursor
    $form['swiperjs_options']['grabCursor'] = [
      '#type' => 'checkbox',
      '#title' => t('Grab Cursor'),
      '#default_value' => isset($options['swiperjs_options']['grabCursor']) ? $options['swiperjs_options']['grabCursor'] : null
    ];
    $form['swiperjs_options']['zoom'] = [
      '#title' => t('Zoom'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['zoom']) ? $options['zoom'] : false
    ];
    $form['swiperjs_options']['parallax'] = [
      '#title' => t('Parallax'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['parallax']) ? $options['parallax'] : false
    ];
    $form['swiperjs_options']['pagination'] = [
      '#title' => t('pagination'),
      '#type' => 'details',
      '#open' => false
    ];
    $form['swiperjs_options']['pagination']['enabled'] = [
      '#title' => t('Enabled pagination'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['pagination']['enabled']) ? $options['pagination']['enabled'] : true
    ];
    $form['swiperjs_options']['navigation'] = [
      '#title' => t('navigation'),
      '#type' => 'details',
      '#open' => false
    ];
    $form['swiperjs_options']['navigation'] = [
      '#title' => t('navigation'),
      '#type' => 'details',
      '#open' => false
    ];
    $form['swiperjs_options']['navigation']['enabled'] = [
      '#title' => t('Enabled navigation'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['navigation']['enabled']) ? $options['navigation']['enabled'] : false
    ];
    $form['swiperjs_options']['autoplay'] = [
      '#title' => t('autoplay'),
      '#type' => 'details',
      '#open' => false
    ];
    $form['swiperjs_options']['autoplay']['delay'] = [
      '#title' => t('Autoplay delay'),
      '#type' => 'textfield',
      '#default_value' => isset($options['autoplay']['delay']) ? $options['autoplay']['delay'] : 8000
    ];
    $form['swiperjs_options']['centeredSlides'] = [
      '#title' => t('centeredSlides'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['centeredSlides']) ? $options['centeredSlides'] : false
    ];
    $form['swiperjs_options']['freeMode'] = [
      '#title' => t('freeMode'),
      '#type' => 'details',
      '#open' => false
    ];
    $form['swiperjs_options']['freeMode']['enabled'] = [
      '#title' => t('Enabled'),
      '#type' => 'checkbox',
      '#default_value' => isset($options['freeMode']['enabled']) ? $options['freeMode']['enabled'] : false
    ];
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
    if (!empty($values['swiperjs_options']))
      $values = $values['swiperjs_options'];
    $defauls = self::options();
    // disable breakpoints options :
    if (isset($values['breakpoints_status']) && !$values['breakpoints_status'])
      unset($values['breakpoints']);
    // remove breakpoints_status
    if (isset($values['breakpoints_status']))
      unset($values['breakpoints_status']);
    // disable supplement_class_status
    if (isset($values['supplement_class_status']) && !$values['supplement_class_status']) {
      unset($values['slideClass']);
      unset($values['slideActiveClass']);
      unset($values['supplement_class_status']);
    }
    // Remove loopedSlides if is empty.
    if (isset($values['loopedSlides']) && !$values['loopedSlides']) {
      unset($values['loopedSlides']);
    }
    // remove slidesPerView
    if (isset($values['slidesPerView']) && empty($values['slidesPerView'])) {
      unset($values['slidesPerView']);
    }
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
      case 'parallax':
        $value = $value ? true : false;
        break;
      case 'zoom':
        $value = (int) $value ? true : false;
        break;
      case 'speed':
      case 'spaceBetween':
        $value = (int) $value;
        break;
      case 'loop':
        $value = $value ? true : false;
        break;
      case 'freeMode':
        $value['enabled'] = $value['enabled'] ? true : false;
        break;
      case 'autoplay':
        $value['delay'] = (int) $value['delay'];
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
        if (isset($value['enabled'])) {
          if (!$value['enabled'])
            $value = false;
          else
            $value = self::options($key);
        }
        break;
      default:
        break;
    }
  }
  
  /**
   * Build general config.
   *
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public static function buildGeneralOptionsForm(&$form, $options) {
    $form['pagination_color'] = [
      '#type' => 'select',
      '#title' => t(' Pagination color '),
      '#options' => [
        '' => 'Default',
        'swiper-pagination--primary' => 'Coleur primaire',
        'swiper-pagination--background' => 'Coleur du background',
        'swiper-pagination--secondary' => 'Coleur secondaire'
      ],
      '#default_value' => $options['pagination_color']
    ];
    $form['pagination_postion'] = [
      '#type' => 'select',
      '#title' => t(' Pagination position '),
      '#options' => [
        '' => 'Default',
        'swiper-pagination--center-bottom' => 'Center bottom'
      ],
      '#default_value' => $options['pagination_postion']
    ];
    $form['buttons_color'] = [
      '#type' => 'select',
      '#title' => t(' Buttons color (next&prev) '),
      '#options' => [
        '' => 'Default',
        'swiper-button--primary' => 'Coleur primaire',
        'swiper-button--background' => 'Coleur du background',
        'swiper-button--secondary' => 'Coleur secondaire'
      ],
      '#default_value' => $options['buttons_color']
    ];
    $form['buttons_position'] = [
      '#type' => 'select',
      '#title' => t(' Buttons position (next&prev) '),
      '#options' => [
        '' => 'Default',
        'swiper-button--align-bottom-y-mobile' => 'Bottom mobile',
        'swiper-button--align-bottom-y-tablet' => 'Bottom tablet',
        'swiper-button--align-bottom-y' => 'Bottom'
      ],
      '#default_value' => $options['buttons_position']
    ];
  }
  
  /**
   * Permet de formater les views.
   *
   * @param array $vars
   */
  public static function FullswiperoptionsTheme(&$vars) {
    $wrappers_attributes = new Attribute();
    $view = $vars['view'];
    $handler = $vars['view']->style_plugin;
    $settings = $handler->options;
    if (empty($settings['theme'])) {
      \Drupal::messenger()->addWarning("le module swipper n'est pas correctement configurer");
      return;
    }
    $swiper_options = Fullswiperoptions::formatOptions($settings['swiperjs_options']);
    
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
    /**
     * Cette logique n'est pas ok, car cela necessite qu'on se souvienne du
     * resultat.
     * cela doit etre paramettre.
     *
     * @var string $class_pagination
     */
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
    if (!empty($settings['pagination_color'])) {
      $class_pagination .= ' ' . $settings['pagination_color'];
    }
    if (!empty($settings['pagination_postion'])) {
      $class_pagination .= ' ' . $settings['pagination_postion'];
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
    // buttons_color
    $buttons_pagination = '';
    if (!empty($settings['buttons_color'])) {
      $buttons_pagination .= ' ' . $settings['buttons_color'];
    }
    if (!empty($settings['buttons_position'])) {
      $buttons_pagination .= ' ' . $settings['buttons_position'];
    }
    $vars['swipper_attributes_buttons_next'] = new Attribute([
      'class' => [
        'swiper-button',
        'swiper-button-next',
        $buttons_pagination
      ]
    ]);
    $vars['swipper_attributes_buttons_prev'] = new Attribute([
      'class' => [
        'swiper-button',
        'swiper-button-prev',
        $buttons_pagination
      ]
    ]);
  }
  
}