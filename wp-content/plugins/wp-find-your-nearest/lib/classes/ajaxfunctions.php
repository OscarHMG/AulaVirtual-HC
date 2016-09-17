<?php
class AjaxFunctions{
    //process ajax data and send response
    function returnSearchResults(){
        //echo var_export($_POST, true);
        $options= get_option('aphs_FYN_options');
        $Distance_Units=$options['Distance_Units'];
        $Display_Results=$options['Display_Results'];
        $lat1=$_POST['lat'];
        $lng1=$_POST['lng'];
        $args=array(
                    'post_type' => 'find_your_nearest',
                    'posts_per_page' => '-1'
                );
        // The Query
        $the_query = new WP_Query( $args );

        // build array from The Loop
        $results=array();
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $lng2=get_post_meta (get_the_ID(), '_aphs_FYN_longitude', TRUE );
            $lat2=get_post_meta (get_the_ID(), '_aphs_FYN_latitude', TRUE );
            //calculate distance from
            if (is_numeric($lng2) && is_numeric($lat2)) {
                $theta = $lng1 - $lng2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                if ($dist >= 1) {
                    $dist = 1;
                }
                if ($dist <= -1) {
                    $dist = -1;
                }
                $miles = $dist * 60 * 1.1515;
                $content = get_the_content();
                $content = nl2br($content);
                $content = str_replace('><br />', '>', $content);
                $title=get_the_title();
                switch ($Distance_Units) {
                    case "miles":$results["$miles"][$title]="<h2>$title</h2><em>Distance ".round($miles)." miles</em> <span class='FYN_viewmap' style='cursor: pointer' id='".get_the_ID()."'>view map</span><p>".$content."</p>";
                    break;
                    case "kilometres":$results["$miles"][$title]="<h2>$title</h2><em>Distance ".round($miles * 1.609344)."km</em> <span class='FYN_viewmap' style='cursor: pointer' id='".get_the_ID()."'>view map</span><p>".$content."</p>";
                    break;
                    default:$results["$miles"][$title]="<h2>$title</h2> <span class='FYN_viewmap' style='cursor: pointer' id='".get_the_ID()."'>view map</span><p>".$content."</p>";
                    break;
                }
            }
        endwhile;
        // Reset Post Data
        wp_reset_postdata();
        ksort($results);
        if (count($results)>0) {
            if ($Display_Results!=0 AND $Display_Results<count($results)) {
                $results = array_slice($results, 0, $Display_Results);
            }
            foreach ($results as $distance=>$content) {
                ksort($content);
                foreach ($content as $item) {
                    echo $item;
                }
            }
        } else {
            return "Your Search Criteria Returned No Results";
        }
        die();
    }

    //process ajax data and send response
    function returnPostData(){
        $aphs_FYN_postcode = get_post_meta ($_GET['postID'], '_aphs_FYN_postcode', TRUE );
        $aphs_FYN_latitude = get_post_meta ($_GET['postID'], '_aphs_FYN_latitude', TRUE );
        $aphs_FYN_longitude = get_post_meta ($_GET['postID'], '_aphs_FYN_longitude', TRUE );
        $title = get_the_title($_GET['postID']);
        $arr = array(
            'postcode'=>$aphs_FYN_postcode,
            'latitude'=>$aphs_FYN_latitude,
            'longitude'=>$aphs_FYN_longitude,
            'title'=>$title
        );
        echo json_encode($arr);
        die();
    }
}