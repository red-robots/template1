<?php
function home_options_meta_box() {
	$page_id = ( isset($_GET['post']) && $_GET['post'] ) ? $_GET['post'] : 0;
	$front_id = get_option('page_on_front');
	$is_home_page = ( $page_id==$front_id ) ? true : false;
	if( $is_home_page ) {
		add_meta_box(
            'my_mb_box_id',           // Unique ID
            'Home Options',  // Box title
            'home_meta_box_html',  // Content callback, must be of type callable
            'page',
            'normal',
            'default'               
        );
	}
}
add_action('add_meta_boxes', 'home_options_meta_box');

function home_meta_box_html($post) { ?>
	<?php 
	$post_id = $post->ID;
	$link_options['internal_link'] = 'Page Link';
	$link_options['external_link'] = 'External Link';
	$page_list = get_mb_pages_links();
	$nonce = wp_create_nonce("banner_metabox_nonce");
	//$json_data = get_post_meta($post_id, "home_banner", true);
	//$objects = ($json_data) ? json_decode($json_data) : '';
	$objects = get_mb_custom_fields($post_id,"home_banner");
	$services = get_mb_custom_fields($post_id,"featured_services");
	?>
	<input type="hidden" id="the_page_id" value="<?php echo $post_id;?>" />
	<input type="hidden" name="mb_nonce" id="mb_nonce" value="<?php echo $nonce;?>" />
	<div class="mb_tab_links">
		<a class="mb_tab_name active" href="#mb_banner_content">Banner</a>
		<a class="mb_tab_name" href="#mb_featured_services">Featured Services</a>
		<a class="mb_tab_name" href="#mb_intro">Introduction</a>
	</div>
	<div id="mb_banner_content" class="mb_tab_panel cf active">
	    <table class="mb-table sortable">
			<thead>
				<tr>
					<th style="width:30%">Image</th>
					<th style="width:62%">Information</th>
					<th style="width:8%">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if($objects) { 
					$i=1; foreach($objects as $obj) {
						echo get_mb_fields_html($i,$obj,false);
						$i++;
					}
				} ?>
			</tbody>
	    </table>
	    <div class="mb-add-rows">
	    	<a id="addItemBtn" class="addItemBtn mb_add_btn" data-type="new_row" href="#">Add Row</a>
	    </div>
	</div>

	<div id="mb_featured_services" class="mb_tab_panel cf">
		<ul class="mb_column_list">
			<?php 
				if($services) {  
					$ctr=1; foreach($services as $svc) {
						echo get_mb_services_html($ctr,$svc,false);
						$ctr++;
					}
				}
			?>
		</ul>
		 <div class="mb-svc-add-rows text-left">
	    	<a id="addSvcBtn" class="addSvcBtn mb_add_btn" data-type="new_row" href="#">Add Column</a>
	    </div>
	</div>

	<?php
	$intro = get_intro_fields($post_id);;
	$title1 = ( isset($intro->column1_title_1) ) ? $intro->column1_title_1 : '';
	$title2 = ( isset($intro->column1_title_2) ) ? $intro->column1_title_2 : '';
	$column2_text = ( isset($intro->column2_text) ) ? $intro->column2_text : '';
	$column2_attachment_id = ( isset($intro->column2_attachment_id) ) ? $intro->column2_attachment_id : '';
	$cc_image = ( isset($intro->column2_image) ) ? $intro->column2_image : '';
	?>

	<div id="mb_intro" class="mb_tab_panel cf">
		<div class="intro_col">
			<div class="inner cf">
				<div class="form-group cf">
					<label>Column 1 Title (Line 1)</label>
					<input type="text" name="column_title_1" class="form-control" value="<?php echo $title1;?>" />
				</div>
				<div class="form-group cf">
					<label>Column 1 Title (Line 2)</label>
					<input type="text" name="column_subtitle_1" class="form-control" value="<?php echo $title2;?>" />
				</div>
			</div>
		</div>

		<div class="intro_col">
			<div class="inner cf">
				<div class="form-group cf">
					<label>Column 2 Text</label>
					<textarea id="textColumn2" name="columntext2" class="form-control introEditor"><?php echo $column2_text;?></textarea>
				</div>
				<div class="form-group cf">
					<label>Column Image 2</label>
					<div class="iconDiv">
						<div class="icon-container">
							<?php if($cc_image) { ?>
								<img src="<?php echo $cc_image['url'];?>" alt="<?php echo $cc_image['title'];?>" />
							<?php } ?>
						</div>
						<a data-col="column" class="svc_upload<?php echo ($cc_image) ? ' hidediv':'';?>" href="#">Upload Image</a>
						<a data-col="column" class="svc_delete<?php echo ($cc_image) ? ' showdiv':'';?>" href="#">x</a>
					</div>
					<input type="hidden" class="column_info_image" name="column_image1" value="<?php echo $column2_attachment_id;?>" />
				</div>
			</div>
		</div>
	</div>
<?php
}

function get_mb_fields_html( $tr_count=1, $obj=null, $is_hide=false ) { 
	ob_start();
	$link_options['internal_link'] = 'Page Link';
	$link_options['external_link'] = 'External Link';
	$page_list = get_mb_pages_links(); 
	$isHideAtt = ($is_hide) ? 'style="display:none"':'';
	// 'title'			
	// 'caption'		
	// 'button_label'	
	// 'button_type'	
	// 'button_link'
	// custom_class
	$banner_title = (isset($obj->title)) ? $obj->title : '';
	$banner_caption = (isset($obj->caption)) ? $obj->caption : '';
	$button_label = (isset($obj->button_label)) ? $obj->button_label : '';
	$button_type = (isset($obj->button_type)) ? $obj->button_type : 'internal_link';
	$button_link = (isset($obj->button_link)) ? $obj->button_link : '';
	$attachmentID = (isset($obj->attachment_id) && $obj->attachment_id) ? $obj->attachment_id : '';
	$custom_class = (isset($obj->custom_class) && $obj->custom_class) ? $obj->custom_class : '';
	$page_link = ($button_link && is_numeric($button_link)) ? get_permalink($button_link) : $button_link;
	$image = ( isset($obj->image) && $obj->image ) ? $obj->image : '';
	if($is_hide) {
		$button_label = 'Read More';
	}
	$style_image = '';
	$imageClass = ($image) ? 'hasImage':'no-image';
	if($image) {
		$imageSrc = $image['url'];
		$style_image = ' style="background-image:url('.$imageSrc.')"';
	}
	
	?>
	<tr id="table_row_<?php echo $tr_count;?>" class="mb_row<?php echo ($isHideAtt) ? ' new':''?>"<?php echo $isHideAtt?>>
		<td>
			<span class="counter_text"><?php echo $tr_count; ?></span>
			<input type="hidden" name="field_counter[]" class="field_counter" value="<?php echo $tr_count?>" />
			<input type="hidden" name="banner_image[]" class="banner_image" value="<?php echo $attachmentID;?>" />
			<div class="mbfield skip image mb-Image">
				<div class="img upload-image-div <?php echo $imageClass;?>"<?php echo $style_image;?>>
					<div class="uploadbtndiv"><a class="upload-media-image" href="#">Upload</a></div>
					<div class="img-button"><a class="img-deleted" href="#">Delete</a></div>
				</div>
			</div>
		</td>
		<td>
			<div class="mbfield first">
				<label>Title</label>
				<input type="text" class="mb_field_control" name="banner_title[]" value="<?php echo $banner_title;?>" />
			</div>
			<div class="mbfield skip">
				<label>Caption</label>
				<div class="textarea_wrapper">
					<textarea id="banner_caption<?php echo $tr_count;?>" class="mb_field_control mb_textarea mb_mceEditor" name="banner_caption[]" aria-hidden="true"><?php echo $banner_caption; ?></textarea>
				</div>
			</div>
			<div class="mbfield">
				<label>Button Label</label>
				<input type="text" class="mb_field_control" name="banner_button_label[]" value="<?php echo $button_label; ?>" />
			</div>
			<div class="mbfield">
				<label>Button Link</label>
				<div class="mb_link_options">
				<?php $p=1; foreach($link_options as $val=>$name) {
					$default_link_opt = ($val==$button_type) ? 'checked':'';
					?>
					<label for="mb_<?php echo $val;?>_<?php echo $tr_count;?>">
						<span class="label"><?php echo $name;?></span>
						<input type="radio" id="mb_<?php echo $val;?>_<?php echo $tr_count;?>" class="mb_button_link_type" name="button_link_type_<?php echo $tr_count;?>" value="<?php echo $val;?>" data-checked="<?php echo $default_link_opt;?>" <?php echo $default_link_opt;?>/>
					</label>
				<?php $p++; } ?>
				</div>

				<?php $j=1; foreach($link_options as $val=>$name) { 
					$default_div = ($val==$button_type) ? ' showdiv':'';
				?>
				<div class="link-field <?php echo $val.$default_div ?>">
					<?php 
						$name_att = '';
						if($button_type==$val) {
							$name_att = 'name=banner_button_link[]';
						}
					?>
					<?php if($val=='external_link') { ?>
					<input type="text" class="mb_field_control <?php echo $val?>" data-name="banner_button_link[]" <?php echo $name_att;?> placeholder="http://" value="<?php echo $page_link; ?>" />
					<?php } else { ?>
						<?php if($page_list) { ?>
						<select data-name="banner_button_link[]" <?php echo $name_att;?> class="mb_field_control <?php echo $val?> jsselect2">
							<option value="-1">Select...</option>
							<?php foreach($page_list as $pg) { 
								$page_id = $pg->ID;
								$page_name = $pg->post_title;
								$selected_link = '';
								if($button_link && is_numeric($button_link)) {
									if($page_id==$button_link) {
										$selected_link = ' selected';
									}
								}
							?>
							<option value="<?php echo $page_id; ?>"<?php echo $selected_link; ?>><?php echo $page_name; ?></option>
							<?php } ?>
						</select>
						<?php } ?>
					<?php } ?>
				</div>
				<?php $j++; } ?>
			</div>
			<div class="mbfield">
				<label>Custom Class</label>
				<input type="text" class="mb_field_control" name="banner_custom_class[]" value="<?php echo $custom_class; ?>" />
			</div>
		</td>
		<td class="handle-sort">
			<div class="btndiv one"><a class="mb-action plus" data-type="plus" href="#"><span>+</span></a></div>
			<div class="btndiv two"><a class="mb-action minus" data-type="minus" href="#"><span>-</span></a></div>
		</td>
	</tr>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function get_mb_services_html($rowNum=1,$obj=null,$clone=false) { 
	ob_start(); 
	$title = (isset($obj->title)) ? $obj->title : '';
	$caption = (isset($obj->caption)) ? $obj->caption : '';
	$image = ( isset($obj->image) && $obj->image ) ? $obj->image : '';
	$attachmentID = ( isset($obj->attachment_id) && $obj->attachment_id ) ? $obj->attachment_id : '';
	?>
	<li class="svcItem<?php echo ($clone) ? ' new':'';?>">
		<div class="fieldGroup">
			<div class="list_field">
				<label>Icon</label>
				<div class="iconDiv">
					<div class="icon-container">
						<?php if($image) { ?>
						<img src="<?php echo $image['url'];?>" alt="<?php echo $image['title'];?>" />
						<?php } ?>
					</div>
					<a class="svc_upload<?php echo ($image) ? ' hidediv':'';?>" href="#">Upload Image</a>
					<a class="svc_delete<?php echo ($image) ? ' showdiv':'';?>" href="#">x</a>
				</div>
				<input type="hidden" class="svc_icon" name="icon[]" value="<?php echo $attachmentID;?>" />
			</div>
			<div class="list_field">
				<label>Title</label>
				<input type="text" name="svc_title[]" class="formControl" value="<?php echo $title;?>" />
			</div>
			<div class="list_field last">
				<label>Text</label>
				<div class="editorWrapper">
					<textarea id="svcText<?php echo $rowNum;?>" name="svc_info[]" class="mmSvcEditor formControl"><?php echo $caption;?></textarea>
				</div>
			</div>
			<div class="field-buttons">
				<a href="#" data-type="plus" class="addSvcCol">+</a>
				<a href="#" data-type="minus" class="deleteSvcCol">-</a>
			</div>
		</div>
	</li>
	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_action("wp_ajax_nopriv_mb_form_fields", "mb_must_login");
add_action("wp_ajax_mb_form_fields", "mb_form_fields");
function mb_form_fields() {
	if ( !wp_verify_nonce( $_REQUEST['mb_nonce'], "banner_metabox_nonce")) {
		exit("Access Forbidden!");
	}   

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$new_row = ($_POST['rownum']) ? $_POST['rownum'] : 1;
		$response['fields'] = get_mb_fields_html($new_row,null,true);
		echo json_encode($response);
	}
	else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}

	die();
}

add_action("wp_ajax_nopriv_mb_service_fields", "mb_must_login");
add_action("wp_ajax_mb_service_fields", "mb_service_fields");
function mb_service_fields() {
	if ( !wp_verify_nonce( $_REQUEST['mb_nonce'], "banner_metabox_nonce")) {
		exit("Access Forbidden!");
	}   

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$new_row = ($_POST['rownum']) ? $_POST['rownum'] : 1;
		$response['fields'] = get_mb_services_html($new_row,null,true);
		echo json_encode($response);
	}
	else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}

	die();
}


function mb_must_login() {
   echo "You must log in!";
   die();
}

function get_mb_pages_links() {
	global $wpdb;
	$results = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' ORDER BY post_title ASC", OBJECT );
	return ($results) ? $results : false;
}

function mb_custom_admin_scripts() {
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
	wp_enqueue_style('admin-styles', get_stylesheet_directory_uri().'/inc/custom_meta/css/admin.css');
	wp_enqueue_style('jsselect2-styles', get_stylesheet_directory_uri().'/inc/custom_meta/css/select2.min.css');
	wp_enqueue_script('jsselect2', get_stylesheet_directory_uri().'/inc/custom_meta/js/select2.full.min.js', array('jquery'), '4.0.6', true );
	wp_enqueue_script('meta-script', get_stylesheet_directory_uri().'/inc/custom_meta/js/init.js', array('jquery'), '1.0', true );
	wp_localize_script( 'meta-script', 'metajs', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	));
}
add_action('admin_enqueue_scripts', 'mb_custom_admin_scripts');

function mb_custom_head_scripts() { 
	$includes_url = includes_url(); ?>
	<script type="text/javascript">var includesURL = '<?php echo $includes_url?>'</script>
<?php
}
add_action('admin_head','mb_custom_head_scripts');

function mb_save_meta_box( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }

    /* Banner fields */
    $items = array();
    $titles = $_POST['banner_title'];
    $captions = $_POST['banner_caption'];
    $labels = $_POST['banner_button_label'];
    $button_links = $_POST['banner_button_link'];
    $attachmentImage = $_POST['banner_image'];
    $banner_custom_class = $_POST['banner_custom_class'];
    $i=0; foreach($titles as $title) {
    	$k=$i+1;
    	$button_types = isset($_POST['button_link_type_'.$k]) ? $_POST['button_link_type_'.$k] : '';
    	$caption = $captions[$i];
    	$caption = preg_replace( "/\r|\n/", "", $caption );
    	$items[] = array(
    			'title'			=> $title,
    			'caption'		=> $caption,
    			'button_label'	=> $labels[$i],
    			'button_type'	=> $button_types,
    			'button_link'	=> $button_links[$i],
    			'attachment_id'	=> $attachmentImage[$i],
    			'custom_class'	=> $banner_custom_class[$i]
    		);
    	$i++;
    }

    if($items) {
    	$field_key = 'home_banner';
    	$json_data = json_encode($items);
    	update_post_meta( $post_id, $field_key, $json_data );
    }
    

    /* Services fields */
    $featured_services = array();
    $svc_icons = $_POST['icon'];
    $svc_titles = $_POST['svc_title'];
    $svc_details = $_POST['svc_info'];
    $k=0; foreach($svc_titles as $svtitle) {
    	$svc_caption = $svc_details[$k];
    	$svc_caption = preg_replace( "/\r|\n/", "", $svc_caption );
    	$featured_services[] = array(
    			'title'			=> $svtitle,
    			'caption'		=> $svc_caption,
    			'attachment_id'	=> $svc_icons[$k]
    		);
    	$k++;
    }

    if($featured_services) {
    	$svc_field_key = 'featured_services';
    	$svc_json = json_encode($featured_services);
    	update_post_meta( $post_id, $svc_field_key, $svc_json );
    }
    
    /* Intro fields */
    $column_title1_a = $_POST['column_title_1'];
    $column_title1_b = $_POST['column_subtitle_1'];
    $columntext2 = $_POST['columntext2'];
    $columntext2 = preg_replace( "/\r|\n/", "", $columntext2 );
    $column_image1 = $_POST['column_image1'];
    $text_fields = array(
    				'column1_title_1'=>$column_title1_a,
    				'column1_title_2'=>$column_title1_b,
    				'column2_text'=>$columntext2,
    				'column2_attachment_id'=>$column_image1
    			);
    $intro_field_key = 'home_intro';
    $intro_json = json_encode($text_fields);
    update_post_meta( $post_id, $intro_field_key, $intro_json );
}
add_action( 'save_post', 'mb_save_meta_box' );


function get_intro_fields($post_id) {
	$intro_json = get_post_meta($post_id,'home_intro',true);
	$intro = ($intro_json) ? json_decode($intro_json) : '';
	if($intro) {
		foreach($intro as $k=>$val) {
			if($k=='column2_attachment_id') {
				$image = wp_get_attachment($val);
				if($image) {
					$intro->column2_image = $image;
				}
			}
		}
		return $intro;
	}
}

function get_mb_custom_fields($post_id,$fieldkey) {
	$json = get_post_meta($post_id, $fieldkey, true);
	if($json) {
		$objects = json_decode($json);
		foreach($objects as $obj) {
			foreach($obj as $k=>$v) {
				if($k=='attachment_id') {
					if($v && is_numeric($v)) {
						$obj->image = wp_get_attachment( $v );
					}
				}
			}
		}
		return $objects;
	}
}

function wp_get_attachment( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'url' => $attachment->guid,
		'title' => $attachment->post_title
	);
}


