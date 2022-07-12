<?php

namespace Drupal\fullswiperoptions\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\layoutgenentitystyles\Services\LayoutgenentitystylesServices;

/**
 * Style view plugin to add some customization to a top control slider
 * the slider is linked to a titles of some content
 * 
 * @ViewsStyle(
 *  id = "flashinfo_slider",
 *  title = @Translation(" Flash Info Swiper "),
 *  help = @Translation(" Add some slider controls and dinamize slider titles "),
 *  theme = "fullswiperoptions_flash_info",
 *  dispplay_types = { "normal" }
 * )
 * 
 */

class FlashInfo extends StylePluginBase
{
    /**
     * {@inheritdoc}
     */
    protected $usesRowPlugin = TRUE;

    /**
     * custom css for the rows
     * 
     * @var bool
     */
    protected $usesRowClass = TRUE;

    /**
     * help us to define some library for calling css files
     * @var LayoutgenentitystylesServices
     */
    protected $LayoutgenentitystylesServices;
    
    /**
     * to add a style ... and more {dont really have the explanation abt that just use it :(}
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
        $instance->LayoutgenentitystylesServices = $container->get('layoutgenentitystyles.add.style.theme');
        return $instance;
    }

    /**
     * build form options
     * {@inheritdoc}
     * 
     */
    public function buildOptionsForm(&$form, FormStateInterface $form_state)
    {
        parent::buildOptionsForm($form, $form_state);
        $form['layoutgenentitystyles_view'] = [
            '#type' => 'hidden',
            '#value' => 'fullswiperoptions/fullswiperflashinfo'
        ];
        $form['theme'] = [
            '#type' => 'select',
            '#title' => $this->t(' Model '),
            '#options' => [
                'top--left' => 'top-left',
                'top--right' => 'top-right',
            ]
        ];
        $form['swiper_options'] = [
            '#type' => 'details',
            '#title' => $this->t('swiper settings')
        ];
        $form['swiper_options']['speed'] = [
            '#type' => 'number',
            '#title' => $this->t('vitesse'),
            '#default_value' => $this->options['swiper_options']['speed']
        ];
        $form['swiper_options']['loop'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Loop'),
            '#default_value' => $this->options['swiper_options']['loop']
        ];
        $form['swiper_options']['navigation'] = [
            '#type' => 'details',
            '#title' => $this->t('Navigation')
        ];
        //
        $form['swiper_options']['navigation']['status'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('display navigation'),
            '#default_value' => $this->options['swiper_options']['navigation']['status']
        ];
    }

    /**
     * config library and some params
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

    /**
     * Set default options.
     */
    protected function defineOptions() {
        $options = parent::defineOptions();
        $options['layoutgenentitystyles_view'] = [
            'default' => 'fullswiperoptions/fullswiperflashinfo'
        ];
        $options['swiper_options'] = [
            'default' => config::options()
        ];
        
        return $options;
    }

}