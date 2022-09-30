<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;

/**
 * Style view plugin to add some customization to a top control slider
 * the slider is linked to a titles of some content
 *
 * @ViewsStyle(
 *  id = "carrouselnav",
 *  title = @Translation(" Carrousel Nav "),
 *  help = @Translation(" Add some slider controls and dinamize slider titles "),
 *  theme = "fullswiperoptions_carrouselnav",
 *  display_types = { "normal" }
 * )
 *
 */
class CarrouselNav extends Fullswiperoptions {
  
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
      '#value' => 'fullswiperoptions/carrouselnav'
    ];
    $form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t(' Model '),
      '#options' => [
        'carrousel--left' => 'nav-left',
        'carrousel--bottom--primary' => 'nav-bottom-primary',
        'carrousel--bottom--image' => 'nav-bottom-image',
        'carrousel--right' => 'nav-right'
      ]
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
      $library = 'fullswiperoptions/carrouselnav';
    }
    
    $this->LayoutgenentitystylesServices->addStyleFromView($library, $this->view->id(), $this->view->current_display);
  }
  
}