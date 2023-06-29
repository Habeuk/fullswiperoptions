<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;

/**
 * Style view plugin to add some customization to a top control slider
 * the slider is linked to a titles of some content
 *
 * @ViewsStyle(
 *  id = "carrouselnav",
 *  title = @Translation(" Carrousel Nav (Swipper) "),
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
        'project-tabs' => 'project-tabs(two type images)',
        'project-card' => 'project-card(project-with-teasers)',
        'blog-carousel' => 'blog-carousel(no-bullets)',
        'carousel-testy-nav' => 'testy-nav(square-center-bullets)',
        'carousel-testy' => 'testy(round-center-black)',
        'carousel-testy' => 'testy(round-center-black)',
        'carousel-nav-testy' => 'nav-testy(square-center-black)',
        'carousel-testy-nav-rond' => 'testy-nav-rond(square-center-black)'
      ],
      '#default_value' => $this->options['theme']
    ];
  }
  
  /**
   * config library and some params
   *
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
    // La librairie est sauvegardé par : Fullswiperoptions
  }
  
}