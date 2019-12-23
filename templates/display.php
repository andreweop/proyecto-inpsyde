
<?php
$args = array(
    'post_type' => 'worker',
    'post_status' => 'publish',
    'posts_per_page' => 7,
    'meta_query' => array(
        array(
            'key' => '_overview_worker_key',
            'value' => 's:8:"approved";i:1;s:8:"featured";i:1;',
            'compare' => 'LIKE'
        )
    )
);
//var $post_id = get_the_id();

$query = new WP_Query( $args );
if ($query->have_posts()) :
    
    echo '<div class"wrapper"><div id="workers"><table >';
    echo  '<tr>
    <th>NAME</th>
    <th>IMAGE</th>
    <th>POSITION</th>
    <th>MORE INFO</th>
  </tr>';
    while ($query->have_posts()) : $query->the_post();
    $name = get_post_meta( get_the_ID(), '_overview_worker_key', true )['name'] ?? '';
    $image = get_post_meta( get_the_ID(), '_overview_worker_key', true )['image'] ?? '';
    $position = get_post_meta( get_the_ID(), '_overview_worker_key', true )['position'] ?? '';
    $description = get_post_meta( get_the_ID(), '_overview_worker_key', true )['description'] ?? '';
    $Linkedin = get_post_meta( get_the_ID(), '_overview_worker_key', true )['linkedin'] ?? '';
    $github = get_post_meta( get_the_ID(), '_overview_worker_key', true )['github'] ?? '';
    $xing = get_post_meta( get_the_ID(), '_overview_worker_key', true )['xing'] ?? '';
    $facebook = get_post_meta( get_the_ID(), '_overview_worker_key', true )['facebook'] ?? '';
    echo '<tr class="ov-worker--table" ><td>'.$name.'</td><br/><td><img src="'.$image.'"/></td><br/><td>'.$position.'</td><br/><td><button type="submit"  onclick="populateOverlay(\'<strong>NAME:</strong> '.$name.'\',\'<strong>DESCRIPTION:</strong> '.$description.'\',\'<strong>GITHUB:</strong>'.$github.'\',\'<strong>LINKEDIN:</strong>'.$Linkedin.'\',\'<strong>XING:</strong>'.$xing.'\',\'<strong>FACEBOOK:</strong>'.$facebook.'\')">More Info!</button></td></tr>';
    endwhile;
    echo '</table><button type="button" onclick="off()">Hide overlay!</button></div>
    <div id="info"></div>
   </div>';
   
    
endif;
wp_reset_postdata();
