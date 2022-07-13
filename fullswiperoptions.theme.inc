<?php
use Drupal\fullswiperoptions\Fullswiperoptions;

function template_preprocess_fullswiperoptions_swipper(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
}

function template_preprocess_fullswiperoptions_flashinfo_slider(&$vars) {
  Fullswiperoptions::FullswiperoptionsTheme($vars);
}