<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\layoutgenentitystyles\Services\LayoutgenentitystylesServices;
use Drupal\fullswiperoptions\Fullswiperoptions as config;

/**
 * Style plugin to render a list of years and months
 * in reverse chronological order linked to content.
 *
 * @see https://swiperjs.com/swiper-api
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "fullswiperoptions_swipper",
 *   title = @Translation(" Swipper full options "),
 *   help = @Translation(" Help to bull a beautifull slider options "),
 *   theme = "fullswiperoptions_swipper",
 *   display_types = { "normal" }
 * )
 */
class Fullswiperoptions extends StylePluginBase {
  /**
   *
   * {@inheritdoc}
   */
  protected $usesRowPlugin = TRUE;
  
  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;
  
  /**
   *
   * @var LayoutgenentitystylesServices
   */
  protected $LayoutgenentitystylesServices;
  
  /**
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->LayoutgenentitystylesServices = $container->get('layoutgenentitystyles.add.style.theme');
    return $instance;
  }
  
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['layoutgenentitystyles_view'] = [
      '#type' => 'hidden',
      '#value' => 'fullswiperoptions/fullswiperoptions'
    ];
    // this config add some options to the container swiper
    $form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t(' Model '),
      '#options' => [
        'clothing--left' => 'clothing-left',
        'clothing--left--primary' => 'clothing--left--primary',
        'clothing--bottom--primary' => 'clothing--bottom--primary',
        'clothing--right' => 'clothing-right'
      ]
    ];
    //
    $form['swiper_options'] = [
      '#type' => 'details',
      '#title' => $this->t('Swiper settings')
    ];
    //
    $form['swiper_options']['direction'] = [
      '#type' => 'select',
      '#title' => $this->t(' Direction '),
      '#options' => [
        'horizontal' => 'horizontal',
        'vertical' => 'vertical'
      ],
      '#default_value' => $this->options['swiper_options']['direction']
    ];
    // for module  : 
    $form['swiper_options']['module'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t(' Module '),
      '#options' => [
        'Controller' => 'controller',
        'Navigation' => 'navigation',
        'Pagination' => 'pagination',
        'Thumbs' => 'thumbs',
      ],
      '#default_value' => $this->options['swiper_options']['module']
    ];
    // form for slideClass : 
    $form['swiper_options']['slideClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#title' => $this->t('slideClass'),
      '#default_value' => $this->options['swiper_options']['slideClass']
    ];
    // form for slideActiveClass : 
    $form['swiper_options']['slideActiveClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#title' => $this->t('slideActiveClass'),
      '#default_value' => $this->options['swiper_options']['slideActiveClass']
    ];
    // form for loopedSlides : 
    $form['swiper_options']['loopedSlides'] = [
      '#type' => 'number',
      '#title' => $this->t('loopedSlides'),
      '#default_value' => $this->options['swiper_options']['loopedSlides']
    ];
    // form for slidesPerView : 
    $form['swiper_options']['slidesPerView'] = [
      '#type' => 'number',
      '#title' => $this->t('slidesPerView'),
      '#default_value' => $this->options['swiper_options']['slidesPerView']
    ];
    // form for breakpoints : 
    $form['swiper_options']['breakpoints_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use breakpoints'),
      '#default_value' => $this->options['swiper_options']['breakpoints_status']
    ];
    $bpts = [ 576, 769, 992, 1201, 1601];
    foreach ($bpts as $bp) {
      $form['swiper_options']['breakpoints'][$bp] = [
        '#type' => 'details',
        '#states' => [
          'visible' => [
            ':input[name="style_options[swiper_options][breakpoints_status]"]' => [
              'checked' => TRUE
            ]
          ]
        ],
        '#title' => $this->t('breakpoint '.$bp),
      ];
      $form['swiper_options']['breakpoints'][$bp]['slidesPerView'] = [
        '#type' => 'number',
        '#title' => $this->t('slidesPerView '),
        '#default_value' => $this->options['swiper_options']['breakpoints'][$bp]['slidesPerView']
      ];
      $form['swiper_options']['breakpoints'][$bp]['spaceBetween'] = [
        '#type' => 'number',
        '#title' => $this->t('spaceBetween '),
        '#default_value' => $this->options['swiper_options']['breakpoints'][$bp]['spaceBetween']
      ];
    }
    // form for the speed
    $form['swiper_options']['speed'] = [
      '#type' => 'number',
      '#title' => $this->t('speed'),
      '#default_value' => $this->options['swiper_options']['speed']
    ];
    //
    $form['swiper_options']['spaceBetween'] = [
      '#type' => 'number',
      '#title' => $this->t('space Between'),
      '#default_value' => $this->options['swiper_options']['spaceBetween']
    ];
    //
    $form['swiper_options']['loop'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Loop'),
      '#default_value' => $this->options['swiper_options']['loop']
    ];
    //
    $form['swiper_options']['navigation'] = [
      '#type' => 'details',
      '#title' => $this->t('Navigation')
    ];
    //
    $form['swiper_options']['navigation']['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('display navigation'),
      '#default_value' => isset($this->options['swiper_options']['navigation']['status']) ? $this->options['swiper_options']['navigation']['status'] : false
    ];
  }
  
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
    // On recupere la valeur de la librairie et on ajoute:
    $library = $this->options['layoutgenentitystyles_view'];
    // dump($library);
    if (empty($library)) {
      $library = 'fullswiperoptions/fullswiperoptions';
    }
    
    $this->LayoutgenentitystylesServices->addStyleFromView($library, $this->view->id(), $this->view->current_display);
  }
  
  /**
   * Set default options.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['layoutgenentitystyles_view'] = [
      'default' => 'fullswiperoptions/fullswiperoptions'
    ];
    $options['swiper_options'] = [
      'default' => config::options()
    ];
    
    return $options;
  }
  
}