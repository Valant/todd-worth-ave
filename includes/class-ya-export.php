<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Yatco YA_Export
 *
 *
 * @class 		YA_Export
 * @version		1.0.0
 * @package		Yatco/Classes
 * @category	Class
 * @author 		Valant
 */
class YA_Export {

	/**
	 * Hook in methods
	 */
	public static function init() {
        add_action('yatco_init', array(__CLASS__, 'export'));
	}

	public static function export()
	{
        if(isset($_GET['yatco-export']) && $_GET['yatco-export'] == 1 && is_admin() ){
            global $wpdb;
            $table_headers = array(
                'Name',
                'Year Built',
                'Year Refits',
                'Builder',
                'Stylist',
                'Int Designer',
                'Naval Architect',
                'Flag',
                'Gross Tonnage',
                'Length',
                'Beam',
                'Normal Draft (not max draft)',
                'Air Draft',
                'Hull Material',
                'Classification',
                'No. Guests',
                'Staterooms',
                'No. Crew',
                'Speed'
                );
            $t1 = "SELECT relationships.object_id, terms.name FROM {$wpdb->terms} terms 
                    LEFT JOIN {$wpdb->term_taxonomy} term_taxonomy ON ( terms.term_id = term_taxonomy.term_id AND term_taxonomy.taxonomy = 'vessel_builder')
                    LEFT JOIN {$wpdb->term_relationships} relationships ON ( term_taxonomy.term_taxonomy_id = relationships.term_taxonomy_id)
                    WHERE relationships.object_id IS NOT NULL 
            ";
            $t2 = "SELECT relationships.object_id, terms.name FROM {$wpdb->terms} terms 
                    LEFT JOIN {$wpdb->term_taxonomy} term_taxonomy ON ( terms.term_id = term_taxonomy.term_id AND term_taxonomy.taxonomy = 'naval_architect')
                    LEFT JOIN {$wpdb->term_relationships} relationships ON ( term_taxonomy.term_taxonomy_id = relationships.term_taxonomy_id)
                    WHERE relationships.object_id IS NOT NULL 
            ";
            #echo '<textarea cols="300" rows="300">'.$t1.'</textarea>'; die;
            /*terms
            term_taxonomy
            term_relationships*/
            $query = "SELECT p1.post_title as 'Name',
                p2.meta_value as 'Year Built',
                p3.meta_value as 'Year Refits',
                p4.name as 'Builder',
                p5.meta_value as 'Stylist',
                p6.meta_value as 'Int Designer',
                p7.name as 'Naval Architect',
                p8.meta_value as 'Flag',
                p9.meta_value as 'Gross Tonnage',
                CONCAT( p10.meta_value, ' ', p20.meta_value) as 'Length',
                p11.meta_value as 'Beam',
                p12.meta_value as 'Normal Draft',
                p13.meta_value as 'Air Draft',
                p14.meta_value as 'Hull Material',
                p15.meta_value as 'Classification',
                p16.meta_value as 'No. Guests',
                p17.meta_value as 'State rooms',
                p18.meta_value as 'No. Crew',
                p19.meta_value as 'Speed'
                FROM {$wpdb->posts} p1
                LEFT JOIN {$wpdb->postmeta} p2 ON (p1.ID = p2.post_id AND p2.meta_key = 'YearBuilt')
                LEFT JOIN {$wpdb->postmeta} p3 ON (p1.ID = p3.post_id AND p3.meta_key = 'RefitYear')
                LEFT JOIN ({$t1}) as p4 ON (p1.ID = p4.object_id )
                LEFT JOIN {$wpdb->postmeta} p5 ON (p1.ID = p5.post_id AND p5.meta_key = 'HullExteriorDesigner')
                LEFT JOIN {$wpdb->postmeta} p6 ON (p1.ID = p6.post_id AND p6.meta_key = 'HullInteriorDesigner')
                LEFT JOIN ({$t2}) as p7 ON (p1.ID = p7.object_id )
                LEFT JOIN {$wpdb->postmeta} p8 ON (p1.ID = p8.post_id AND p8.meta_key = 'Flag')
                LEFT JOIN {$wpdb->postmeta} p9 ON (p1.ID = p9.post_id AND p9.meta_key = 'GrossTonnage')
                LEFT JOIN {$wpdb->postmeta} p10 ON (p1.ID = p10.post_id AND p10.meta_key = 'LOA')
                LEFT JOIN {$wpdb->postmeta} p11 ON (p1.ID = p11.post_id AND p11.meta_key = 'Beam')
                LEFT JOIN {$wpdb->postmeta} p12 ON (p1.ID = p12.post_id AND p12.meta_key = 'LOD')
                LEFT JOIN {$wpdb->postmeta} p13 ON (p1.ID = p13.post_id AND p13.meta_key = 'BridgeClearance')
                LEFT JOIN {$wpdb->postmeta} p14 ON (p1.ID = p14.post_id AND p14.meta_key = 'HullHullMaterial')
                LEFT JOIN {$wpdb->postmeta} p15 ON (p1.ID = p15.post_id AND p15.meta_key = 'Classification')
                LEFT JOIN {$wpdb->postmeta} p16 ON (p1.ID = p16.post_id AND p16.meta_key = 'NumSleeps')
                LEFT JOIN {$wpdb->postmeta} p17 ON (p1.ID = p17.post_id AND p17.meta_key = 'StateRooms')
                LEFT JOIN {$wpdb->postmeta} p18 ON (p1.ID = p18.post_id AND p18.meta_key = 'NumCrewSleeps')
                LEFT JOIN {$wpdb->postmeta} p19 ON (p1.ID = p19.post_id AND p19.meta_key = 'CruiseSpeed')
                LEFT JOIN {$wpdb->postmeta} p20 ON (p1.ID = p20.post_id AND p20.meta_key = 'LOA_unit')
                LEFT JOIN {$wpdb->postmeta} p21 ON (p1.ID = p21.post_id AND p21.meta_key = 'LOAFeet')
                LEFT JOIN {$wpdb->postmeta} p22 ON (p1.ID = p22.post_id AND p22.meta_key = 'LOAMeters')
                WHERE p21.meta_value >= 33 OR p22.meta_value >= 100
                GROUP BY p1.post_title
            ";
            $result = $wpdb->get_results($query, ARRAY_N);

            $result = array($table_headers) + $result;
            #var_dump($result); die;
            #echo '<textarea cols="300" rows="300">'.$query.'</textarea>'; die;

            self::array_to_csv_download($result);
            die;
            
        }
    }

    public static function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    // open the "output" stream
    $f = fopen('php://output', 'w');
    #$f = fopen('php://memory', 'w'); 

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
} 

	
}

YA_Export::init();