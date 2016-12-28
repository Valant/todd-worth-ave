<?php

class YA_Meta_Box_Vessel_Status
{

    const K_FOR_SALE = 'vessel_status_for_sale';
    const T_FOR_SALE = 'For Sale';
    const K_FOR_CHARTER = 'vessel_status_for_charter';
    const T_FOR_CHARTER = 'For Charter';
    const K_FEATURED_FOR_SALE = 'vessel_status_featured_for_sale';
    const T_FEATURED_FOR_SALE = 'Featured For Sale';
    const K_FEATURED_FOR_CHARTER = 'vessel_status_featured_for_charter';
    const T_FEATURED_FOR_CHARTER = 'Featured For Charter';

    const K_SOLD_AS_NEW_CONSTRUCTION = 'vessel_status_sold_as_new';
    const T_SOLD_AS_NEW_CONSTRUCTION = 'Sold As New Construction';
    const K_NEW_CONSTRUCTION = 'vessel_status_new_construction';
    const T_NEW_CONSTRUCTION = 'New Construction';

    const TAXONOMY = 'vessel_sale_status';

    const META_YEAR_BUILT  = 'YearBuilt';


    /**
     * Status checkboxes
     * @param WP_Post $post
     */
    public static function output($post)
    {

        $meta = get_post_meta( $post->ID );
        $timesSoldMax = 4;
        $fieldList = self::statusFields();
        ?>

        <div class="misc-pub-section misc-pub-post-status">
            <?php $c=0; ?>
            <?php foreach ($fieldList as $fieldName => $fieldCaption) : $c++; ?>
                <input type="checkbox" name="<?= $fieldName ?>" id="<?= $fieldName ?>" value="1" <?php if ( isset ( $meta[$fieldName] ) && $meta[$fieldName][0] ) echo 'checked'; ?> />
                <label for="<?= $fieldName ?>" class="vessel-status-row-title"><?php _e( $fieldCaption, 'yatco' )?></label>
                <?php if ($c<count($fieldList)) : ?><br><?php endif; ?>
            <?php endforeach; ?>
        </div>
        <hr>
        <h2 class="" style="font-weight: 600;"><span><?= __('Times Sold', 'yatco') ?></span></h2>
        <hr>
        <?php
        for ($i=0;$i<=$timesSoldMax; $i++) : ?>
            <input type="radio" name="times_sold" id="times_sold_<?= $i ?>" value="<?= $i ?>" <?php if (isset($meta['times_sold']) && $meta['times_sold'][0] == $i) echo 'checked'; ?> ><label for="times_sold_<?= $i ?>"><?= $i ?></label>&nbsp;&nbsp;&nbsp;
        <?php endfor;
    }

    /**
     * @param integer $post_id
     * @param WP_Post $post
     */
    public static function save( $post_id, $post ) {

        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );

        // Exits script depending on save status
        if ( $is_autosave || $is_revision ) {
            return;
        }

        $fields = self::statusFields();
        $metaKeys = array_keys($fields);
        $tags = array();

        foreach ($metaKeys as $key) {
            $val = (isset($_POST[$key]) &&  $_POST[$key]) ? 1 : 0;
            if (!$val && $key === self::K_NEW_CONSTRUCTION) {
                $m_val = get_post_meta($post_id, $key, true);
                if ($m_val === '') {
                    $year = (int)get_post_meta($post_id, self::META_YEAR_BUILT, true);
                    if ($year && $year >= date('Y')) {
                        $val = 1;
                    }
                }
            }
            update_post_meta( $post_id, $key, $val);
            if ($val) {
                $tags[] = $fields[$key];
            }
        }
        update_post_meta( $post_id, 'times_sold', (isset($_POST['times_sold']) &&  $_POST['times_sold']) ? (int)$_POST['times_sold'] : 0);
        wp_set_post_terms($post_id, $tags, self::TAXONOMY, false);

    }

    /**
     * Set taxonomy terms by existing post meta values
     * @param $post_id
     */
    public static function setStatusTerms($post_id)
    {
        $fields = self::statusFields();
        $metaKeys = array_keys($fields);
        $tags = array();
        foreach ($metaKeys as $key) {
            $val = get_post_meta($post_id, $key, true);
            if ($val) {
                $tags[] = $fields[$key];
            }
        }
        wp_set_post_terms($post_id, $tags, self::TAXONOMY, false);
    }

    /**
     * Vessel status check fields list
     * @return array
     */
    public static function statusFields()
    {

        return array(
            self::K_FOR_SALE => self::T_FOR_SALE,
            self::K_FOR_CHARTER => self::T_FOR_CHARTER,
            self::K_FEATURED_FOR_SALE => self::T_FEATURED_FOR_SALE,
            self::K_FEATURED_FOR_CHARTER => self::T_FEATURED_FOR_CHARTER,
            self::K_NEW_CONSTRUCTION => self::T_NEW_CONSTRUCTION,
            self::K_SOLD_AS_NEW_CONSTRUCTION => self::T_SOLD_AS_NEW_CONSTRUCTION,
        );
    }

    public static function setForSale($post_id, $for_sale)
    {
        update_post_meta( $post_id, self::K_FOR_SALE, $for_sale );
        if ($for_sale) {
            wp_set_post_terms($post_id, array(self::T_FOR_SALE), self::TAXONOMY, true);
        } else {
            wp_remove_object_terms($post_id, array(self::T_FOR_SALE), self::TAXONOMY);
        }
    }

}