<?php

class YA_Meta_Box_Vessel_Status
{


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
            update_post_meta( $post_id, $key, $val);
            if ($val) {
                $tags[] = $val;
            }
        }
        update_post_meta( $post_id, 'times_sold', (isset($_POST['times_sold']) &&  $_POST['times_sold']) ? (int)$_POST['times_sold'] : 0);
        wp_set_post_terms($post_id, $tags, 'vessel_sale_status', false);

    }

    /**
     * Vessel status check fields list
     * @return array
     */
    public static function statusFields()
    {

        return array(
            'vessel_status_for_sale' => 'For Sale',
            'vessel_status_for_charter' => 'For Charter',
            'vessel_status_featured_for_sale' => 'Featured For Sale',
            'vessel_status_featured_for_charter' => 'Featured For Charter',
            'vessel_status_sold_as_new' => 'Sold As New Construction',
        );
    }

}