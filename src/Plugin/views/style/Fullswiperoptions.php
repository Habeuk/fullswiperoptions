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
 *   title = @Translation("Swiper api : Swipper full options (Default) "),
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
    // $form['theme'] = [
    // '#type' => 'select',
    // '#title' => $this->t(' Model '),
    // '#options' => [
    // 'none' => 'Default',
    // 'swiper--left swiper--left--primary' => 'swiper-left primary-color',
    // 'swiper--left swiper--left--background' => 'swiper-left
    // background-color',
    // 'swiper--bottom swiper--bottom--primary' => 'swiper--bottom
    // primary-color',
    // 'swiper--bottom swiper--bottom--background' => 'swiper--bottom
    // background-color'
    // ],
    // '#default_value' => $this->options['theme']
    // ];
    config::buildGeneralOptionsForm($form, $this->options);
    // adding swiper or not :
    $form['swiper'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => $this->options['swiper'],
      '#title' => $this->t('Définition de Swiper'),
      '#description' => $this->t('La plupart des modèles de sliders utilisatnt Swiper utilise la classe swiper pour l\'initialisation')
    ];
    // $this->swiperjs_options($form);
    config::buildSwiperjsOptions($form, $this->options['swiperjs_options']);
  }
  
  /**
   *
   * @deprecated doit etre supprimer
   * @param array $form
   */
  protected function swiperjs_options(&$form) {
    // fields for the swiper settings
    $form['swiperjs_options'] = [
      '#type' => 'details',
      '#title' => $this->t('Swiper settings')
    ];
    // field for settings direction
    $form['swiperjs_options']['direction'] = [
      '#type' => 'select',
      '#title' => $this->t(' Direction '),
      '#options' => [
        'horizontal' => 'horizontal',
        'vertical' => 'vertical'
      ],
      '#default_value' => $this->options['swiperjs_options']['direction']
    ];
    // for module :
    $form['swiperjs_options']['module'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t(' Module '),
      '#options' => [
        'Controller' => 'controller',
        'Navigation' => 'navigation',
        'Pagination' => 'pagination',
        'Thumbs' => 'thumbs'
      ],
      '#default_value' => $this->options['swiperjs_options']['module']
    ];
    // using supplements class :
    $form['swiperjs_options']['supplement_class_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Supplement Class'),
      '#default_value' => $this->options['swiperjs_options']['supplement_class_status']
    ];
    $form['swiperjs_options']['slideClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => $this->options['swiperjs_options']['slideClass'],
      '#states' => [
        'visible' => [
          ':input[name="style_options[swiperjs_options][supplement_class_status]"]' => [
            'checked' => TRUE
          ]
        ]
      ],
      '#title' => $this->t('slideClass')
    ];
    $form['swiperjs_options']['slideActiveClass'] = [
      '#type' => 'textfield',
      '#size' => 60,
      '#maxlength' => 128,
      '#default_value' => $this->options['swiperjs_options']['slideActiveClass'],
      '#states' => [
        'visible' => [
          ':input[name="style_options[swiperjs_options][supplement_class_status]"]' => [
            'checked' => TRUE
          ]
        ]
      ],
      '#title' => $this->t('slideActiveClass')
    ];
    // form for loopedSlides :
    $form['swiperjs_options']['loopedSlides'] = [
      '#type' => 'number',
      '#title' => $this->t('loopedSlides'),
      '#default_value' => $this->options['swiperjs_options']['loopedSlides']
    ];
    // form for slidesPerView :
    $form['swiperjs_options']['slidesPerView'] = [
      '#type' => 'number',
      '#title' => $this->t('slidesPerView'),
      '#default_value' => $this->options['swiperjs_options']['slidesPerView']
    ];
    // form for breakpoints :
    $form['swiperjs_options']['breakpoints_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use breakpoints'),
      '#default_value' => $this->options['swiperjs_options']['breakpoints_status']
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
        '#title' => $this->t('breakpoint ' . $bp)
      ];
      $form['swiperjs_options']['breakpoints'][$bp]['centeredSlides'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('centeredSlides'),
        '#default_value' => $this->options['swiperjs_options']['breakpoints'][$bp]['centeredSlides']
      ];
      $form['swiperjs_options']['breakpoints'][$bp]['slidesPerView'] = [
        '#type' => 'number',
        '#title' => $this->t('slidesPerView '),
        '#default_value' => $this->options['swiperjs_options']['breakpoints'][$bp]['slidesPerView']
      ];
      $form['swiperjs_options']['breakpoints'][$bp]['spaceBetween'] = [
        '#type' => 'number',
        '#title' => $this->t('spaceBetween '),
        '#default_value' => $this->options['swiperjs_options']['breakpoints'][$bp]['spaceBetween']
      ];
    }
    // form for the speed
    $form['swiperjs_options']['speed'] = [
      '#type' => 'number',
      '#title' => $this->t('speed'),
      '#default_value' => $this->options['swiperjs_options']['speed']
    ];
    // field for spaceBetween
    $form['swiperjs_options']['spaceBetween'] = [
      '#type' => 'number',
      '#title' => $this->t('space Between'),
      '#default_value' => $this->options['swiperjs_options']['spaceBetween']
    ];
    // field for loop
    $form['swiperjs_options']['loop'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Loop'),
      '#default_value' => $this->options['swiperjs_options']['loop']
    ];
    // field for grabCursor
    $form['swiperjs_options']['grabCursor'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Grab Cursor'),
      '#default_value' => $this->options['swiperjs_options']['grabCursor']
    ];
    // field for the navigation buttons
    $form['swiperjs_options']['navigation'] = [
      '#type' => 'details',
      '#title' => $this->t('Navigation')
    ];
    // field for the navigation status
    $form['swiperjs_options']['navigation']['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('display navigation'),
      '#default_value' => isset($this->options['swiperjs_options']['navigation']['status']) ? $this->options['swiperjs_options']['navigation']['status'] : false
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
    $options['swiper'] = [
      'default' => 'swiper'
    ];
    $options['row_class'] = [
      'default' => 'swiper-slide'
    ];
    $options['swiperjs_options'] = [
      'default' => config::options()
    ];
    
    return $options;
  }
  
}