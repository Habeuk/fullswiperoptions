<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;

/**
 * Style view plugin to add some customization to a top control slider
 * the slider is linked to a titles of some content
 *
 * @ViewsStyle(
 *  id = "thumbsnav",
 *  title = @Translation(" Thumbs Nav "),
 *  help = @Translation(" Add some slider with thumbs to dinamize slider render "),
 *  theme = "fullswiperoptions_thumbsnav",
 *  display_types = { "normal" }
 * )
 *
 */
class ThumbsNav extends Fullswiperoptions {
  
  /**
   * build form options
   *
   * {@inheritdoc}
   *
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['layoutgenentitystyles_view'] = [
      '#type' => 'hidden',
      '#value' => 'fullswiperoptions/thumbsnav'
    ];
    $form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t(' Container Model '),
      '#options' => [
        'thumbs-carousel' => 'thumbs',
      ],
      '#default_value' => $this->options['theme']
    ];
    $this->swiper_options2($form);
  }

  /**
   *  second view config for thumbsnail 
   */
  protected function swiper_options2(&$form){
    // fields for the swiper settings
    $form['swiper_options2'] = [
      '#type' => 'details',
      '#title' => $this->t('Swiper settings')
    ];
    // field for settings direction
    $form['swiper_options2']['direction'] = [
      '#type' => 'select',
      '#title' => $this->t(' Direction '),
      '#options' => [
        'horizontal' => 'horizontal',
        'vertical' => 'vertical'
      ],
      '#default_value' => $this->options['swiper_options2']['direction']
    ];
    // for module  : 
    $form['swiper_options2']['module'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t(' Module '),
      '#options' => [
        'Controller' => 'controller',
        'Navigation' => 'navigation',
        'Pagination' => 'pagination',
        'Thumbs' => 'thumbs',
      ],
      '#default_value' => $this->options['swiper_options2']['module']
    ];
    // using supplements class : 
    $form['swiper_options2']['supplement_class_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Supplement Class'),
      '#default_value' => $this->options['swiper_options2']['supplement_class_status']
    ];
    $form['swiper_options2']['slideClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => $this->options['swiper_options2']['slideClass'],
      '#states' => [
        'visible' => [
          ':input[name="style_options[swiper_options2][supplement_class_status]"]' => [
            'checked' => TRUE
          ]
        ]
      ],
      '#title' => $this->t('slideClass'),
    ];
    $form['swiper_options2']['slideActiveClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => $this->options['swiper_options2']['slideActiveClass'],
      '#states' => [
        'visible' => [
          ':input[name="style_options[swiper_options2][supplement_class_status]"]' => [
            'checked' => TRUE
          ]
        ]
      ],
      '#title' => $this->t('slideActiveClass'),
    ];
    // form for loopedSlides : 
    $form['swiper_options2']['loopedSlides'] = [
      '#type' => 'number',
      '#title' => $this->t('loopedSlides'),
      '#default_value' => $this->options['swiper_options2']['loopedSlides']
    ];
    // form for slidesPerView : 
    $form['swiper_options2']['slidesPerView'] = [
      '#type' => 'number',
      '#title' => $this->t('slidesPerView'),
      '#default_value' => $this->options['swiper_options2']['slidesPerView']
    ];
    // form for breakpoints : 
    $form['swiper_options2']['breakpoints_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use breakpoints'),
      '#default_value' => $this->options['swiper_options2']['breakpoints_status']
    ];
    $bpts = [ 576, 769, 992, 1201, 1601];
    foreach ($bpts as $bp) {
      $form['swiper_options2']['breakpoints'][$bp] = [
        '#type' => 'details',
        '#states' => [
          'visible' => [
            ':input[name="style_options[swiper_options2][breakpoints_status]"]' => [
              'checked' => TRUE
            ]
          ]
        ],
        '#title' => $this->t('breakpoint '.$bp),
      ];
      $form['swiper_options2']['breakpoints'][$bp]['slidesPerView'] = [
        '#type' => 'number',
        '#title' => $this->t('slidesPerView '),
        '#default_value' => $this->options['swiper_options2']['breakpoints'][$bp]['slidesPerView']
      ];
      $form['swiper_options2']['breakpoints'][$bp]['spaceBetween'] = [
        '#type' => 'number',
        '#title' => $this->t('spaceBetween '),
        '#default_value' => $this->options['swiper_options2']['breakpoints'][$bp]['spaceBetween']
      ];
    }
    // form for the speed
    $form['swiper_options2']['speed'] = [
      '#type' => 'number',
      '#title' => $this->t('speed'),
      '#default_value' => $this->options['swiper_options2']['speed']
    ];
    // field for spaceBetween
    $form['swiper_options2']['spaceBetween'] = [
      '#type' => 'number',
      '#title' => $this->t('space Between'),
      '#default_value' => $this->options['swiper_options2']['spaceBetween']
    ];
    // field for loop
    $form['swiper_options2']['loop'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Loop'),
      '#default_value' => $this->options['swiper_options2']['loop']
    ];
    // field for grabCursor
    $form['swiper_options2']['grabCursor'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Grab Cursor'),
      '#default_value' => $this->options['swiper_options2']['grabCursor']
    ];
    // field for the navigation buttons
    $form['swiper_options2']['navigation'] = [
      '#type' => 'details',
      '#title' => $this->t('Navigation')
    ];
    // field for the navigation status
    $form['swiper_options2']['navigation']['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('display navigation'),
      '#default_value' => isset($this->options['swiper_options2']['navigation']['status']) ? $this->options['swiper_options2']['navigation']['status'] : false
    ];

  }
  /**
   * config library and some params
   *
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
    // On recupere la valeur de la librairie et on ajoute:
    $library = $this->options['layoutgenentitystyles_view'];
    // dump($library);
    if (empty($library)) {
      $library = 'fullswiperoptions/thumbsnav';
    }
    
    $this->LayoutgenentitystylesServices->addStyleFromView($library, $this->view->id(), $this->view->current_display);
  }
  
}