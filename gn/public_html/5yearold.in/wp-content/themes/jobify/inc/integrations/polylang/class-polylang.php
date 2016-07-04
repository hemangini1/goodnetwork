<?php
/**
 * Polylang
 *
 * @package Jobify
 * @category Integration
 * @since 3.0.0
 */
class Jobify_Polylang extends Jobify_Integration {

    public function __construct() {
        parent::__construct( dirname( __FILE__ ) );
    }

    public function setup_actions() {
        add_filter( 'get_job_listings_query_args', array( $this, 'add_language' ), 9999, 2 );
        add_filter( 'get_terms_args', array( $this, 'add_term_language' ) );
    }

    public function add_term_language( $args ) {
        $args[ 'lang' ] = pll_current_language();

        return $args;
    }

    function add_language( $query_args, $args ) {
        if ( apply_filters( 'jobify_polylang_only_selected', false ) ) {
            return $query_args;
        }

        $def_lang = pll_default_language();
        $cur_lang = isset( $args[ 'lang' ] ) ? $args[ 'lang' ] : '';
        $mixed    = $cur_lang != $def_lang;

        $query_args[ 'lang' ] = $cur_lang . ',' . $def_lang;

        if ( isset( $query_args[ 'tax_query' ] ) && $mixed ) {
            $taxes = $query_args[ 'tax_query' ];

            foreach ( $taxes as $key => $tax ) {
                $terms = $tax[ 'terms' ];
                $trans = array();

                foreach ( $terms as $term ) {
                    // annoying since we have slugs but get an id but need back to slugs
                    $obj   = get_term_by( 'slug', $term, $tax[ 'taxonomy' ] );
                    $trans = pll_get_term( $obj->term_id, $def_lang );
                    $trans = get_term_by( 'id', $trans, $tax[ 'taxonomy' ] );

                    $query_args[ 'tax_query' ][ $key ][ 'terms' ] = array_merge( $terms, array( $trans->slug ) );
                }
            }
        }

        $terms   = get_terms( 'post_translations' );
        $exclude = array();

        foreach( $terms as $translation ){
            $trans = unserialize( $translation->description );

            if( $mixed ) {
                $exclude[] = $trans[$def_lang];
            }
        }

        if ( $mixed ) {
            if ( isset( $query_args[ 'post__in' ] ) ) {
                $query_args[ 'post__in' ] = array_diff( $query_args[ 'post__in' ], $exclude );
                unset( $query_args[ 'post__not_in' ] );
            } else {
                $query_args[ 'post__not_in' ] = $exclude;
            }
        }

        return $query_args;
    }
}
