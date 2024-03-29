<?php
use Drupal\fullswiperoptions\Fullswiperoptions;
use Drupal\Core\Template\Attribute;
use Drupal\Component\Serialization\Json;

function template_preprocess_fullswiperoptions_swipper(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
}

function template_preprocess_fullswiperoptions_flashinfo_slider(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
}

function template_preprocess_fullswiperoptions_carrouselnav(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
}

function template_preprocess_fullswiperoptions_blogcarrousel(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
}
function template_preprocess_fullswiperoptions_thumbsnav(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
  $wrappers_attributes2 = new Attribute();
  $view = $vars['view'];
  $handler = $vars['view']->style_plugin;
  $settings = $handler->options;
  //dump($vars);
  $entity_type = 'block_content';
  $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
  $vars['rows2'] = [];
  foreach ($vars['rows'] as $key => $value) {
    if(isset($value['#block_content']))
      $vars['rows2'][$key] = $view_builder->view($value['#block_content'], 'thumbsnail_slide_image');
      $vars['rows2'][$key]['#attributes']['class'] = ['swiper-slide'];
      $vars['rows2'][$key]['attributes'] = new Attribute($vars['rows2'][$key]['#attributes']);
  }
  //dd($vars['rows2']);
  if (isset($settings['swiper_options2']))
  {
    $swiper_options2 = Fullswiperoptions::formatOptions($settings['swiper_options2']);
    $vars['swiper_options2'] = $swiper_options2;
    $id = Fullswiperoptions::getUniqueId($view);
    $wrappers_attributes2->setAttribute('id', $id.rand(0,100));
    $vars['wrappers_attributes2'] = $wrappers_attributes2;
    // remove or add the swiper class
    if(isset($settings['swiper2']))
      $swiper_class2 = $settings['swiper2'];
    else
      $swiper_class2 = 'swiper';
    // disable breakpoints options : 
    if(isset($swiper_options2['breakpoints_status']) && !$swiper_options2['breakpoints_status'])
      unset($swiper_options2['breakpoints']);
    // disable supplement_class_status 
    if(isset($swiper_options2['supplement_class_status']) && !$swiper_options2['supplement_class_status'])
    { 
      unset($swiper_options2['slideClass']);
      unset($swiper_options2['slideActiveClass']);
    }
    // define the default values for swipper_attributes2 
    $vars['swipper_attributes2'] = new Attribute([
      'class' => [
        //
        $swiper_class2,
        'swiper-full-options',
      ],
      'data-swiper' => Json::encode($swiper_options2)
    ]);
  }
}