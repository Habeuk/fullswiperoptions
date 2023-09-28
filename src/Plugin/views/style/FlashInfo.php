<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;

/**
 * Style view plugin to add some customization to a top control slider
 * the slider is linked to a titles of some content
 *
 * @ViewsStyle(
 *  id = "flashinfo_slider",
 *  title = @Translation("Swiper api : Flash Info Swiper "),
 *  help = @Translation(" Add some slider controls and dinamize slider titles "),
 *  theme = "fullswiperoptions_flashinfo_slider",
 *  dispplay_types = { "normal" }
 * )
 *
 */
class FlashInfo extends Fullswiperoptions {
  
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
      '#value' => 'fullswiperoptions/fullswiperflashinfo'
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
      $library = 'fullswiperoptions/fullswiperflashinfo';
    }
    
    $this->LayoutgenentitystylesServices->addStyleFromView($library, $this->view->id(), $this->view->current_display);
  }
  
}