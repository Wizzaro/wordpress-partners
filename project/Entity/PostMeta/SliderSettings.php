<?php
namespace Wizzaro\Partners\Entity\PostMeta;

class SliderSettings extends AbstractPostMeta {
    
    /**
     * @var string
     */
    protected $_meta_data_key = '_wizzaro_partners_slider_settings';
    
    /**
     * @var int
     */
    public $line_amount = 6;
    
    /**
     * @var int
     */
    public $transition_speed = 2000;
    
    /**
     * @var int
     */
    public $pause_betwen_transition = 1000;
    
    /**
     * @var boolean
     */
    public $pause_on_hover = false;
}