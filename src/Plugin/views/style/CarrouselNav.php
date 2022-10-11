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
      '#title' => $this->t(' Container Model '),
      '#options' => [
        'carousel-hero' => 'hero(round-left-white)',
        'carousel-testy' => 'testy(round-center-black)',
        'carousel-nav-testy' => 'nav-testy(square-center-black)',
        'carousel-testy-nav-rond' => 'testy-nav-rond(square-center-black)'
      ],
      '#default_value' => $this->options['theme']
    ];
    /*$form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t(' Bullets Model '),
      '#options' => [
        'carousel-nav--black d-flex justify-content-center' => 'round-center-black',
        'carousel-nav--black' => 'round-left-black',
        'carousel-nav--carree carousel-nav--black d-flex justify-content-center' => 'square-center-black',
        'carousel-nav--carree carousel-nav--black' => 'square-left-black',
        'carrousel--bottom--primary' => 'nav-black'
      ]
    ];*/
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