<?php
use Drupal\fullswiperoptions\Fullswiperoptions;
use Drupal\Core\Template\Attribute;
use Drupal\Component\Serialization\Json;

function template_preprocess_fullswiperoptions_swipper(&$vars) {
  $wrappers_attributes = new Attribute();
  $view = $vars['view'];
  $handler = $vars['view']->style_plugin;
  $settings = $handler->options;
  $swiper_options = Fullswiperoptions::formatOptions($settings['swiper_options']);
  $vars['swiper_options'] = $swiper_options;
  $id = Fullswiperoptions::getUniqueId($view);
  $wrappers_attributes->setAttribute('id', $id);
  $vars['wrappers_attributes'] = $wrappers_attributes;
  if (!empty($settings['row_class'])) {
    foreach ($vars['rows'] as $num => $row) {
      $vars['rows'][$num]['attributes'] = [];
      if ($row_class = $handler->getRowClass($num)) {
        $vars['rows'][$num]['attributes']['class'][] = $row_class;
      }
      $vars['rows'][$num]['attributes'] = new Attribute($vars['rows'][$num]['attributes']);
    }
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