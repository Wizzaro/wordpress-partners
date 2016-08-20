<?php
namespace Wizzaro\Partners\Entity\PostMeta;

class ListElements extends AbstractPostMeta {
    
    /**
     * @var string
     */
    protected $_meta_data_key = '_wizzaro_partners_list_elements';
    
    /**
     * @var array
     */
     public $elements = array();
     
     public function getElements() {
        $return_elements = array();

        if ( is_array( $this->elements ) && count( $this->elements ) > 0 ) {
            $args = array(
                'post_type' => get_post_types(),
                'posts_per_page' => -1,
                'post__in' => $this->elements,
                'orderby' => 'ID',
                'order' => 'ASC'
            );
            
            $elements = get_posts( $args );
            
            foreach ( $elements as $element ) {
                $key = array_search( $element->ID, $this->elements );
                if ( $key !== false ) {
                    $return_elements[(string) $key] = $element;
                }
            }
            
            foreach( array_keys( $this->elements, 'break' ) as $break_key ) {
                $return_elements[(string) $break_key] = 'break';
            }
            
            ksort( $return_elements );
        }
        
        return $return_elements;
     }
}