<?php
/*  Copyright 2009  Michael
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

/*
Plugin Name: Connect2site
Plugin URI: http://tech.kounoupaki.gr/?p=6
Description: Allows you to display another's subblog posts from multiple categories including post summary, comment count and category link. Short codes included.
Version: 1.2.3
Author: mlazarid
Author URI: http://www.kounoupaki.gr
*/

function get_site_tags($pwherefrom,$pcat,$pnum,$pchars)
{
        global $wpdb;
//$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset"; // Check The Default CharSet if not UTF8 problem ...
//echo $charset_collate;
        $options = get_option('widget_sitecat');

        $title = $options['connect2-title'];
        if ($pwherefrom == 1){
        	$connect2_include_category = strip_tags(stripslashes($options['connect2-categories']));
        }else{
			    $connect2_include_category = $pcat;
		    }
        $connect2_dbpwd            = strip_tags(stripslashes($options['connect2-dbpwd']));
 		    $connect2_dbName           = strip_tags(stripslashes($options['connect2-dbName']));
 		    $connect2_pre              = strip_tags(stripslashes($options['connect2-pre']));
 		    $connect2_dbuser           = strip_tags(stripslashes($options['connect2-dbuser']));
        $connect2_domain           = strip_tags(stripslashes($options['connect2-domain']));
        if ($pwherefrom == 1){
        	$connect2_limit = strip_tags(stripslashes($options['connect2-limit']));
        }else{
        	$connect2_limit = $pnum;
        }
        $connect2_win = strip_tags(stripslashes($options['connect2-win']));
        if ($pwherefrom == 1){ 
	    	$connect2_strLimit = strip_tags(stripslashes($options['connect2-strLimit']));
        }else{
        	$connect2_strLimit = $pchars;
        }
        $connect2_catdisp          = strip_tags(stripslashes($options['connect2-catdisp']));
        $connect2_comdisp          = strip_tags(stripslashes($options['connect2-comdisp']));  
        // some vars
        $strLimit = $connect2_strLimit;  
		//Get category IDs
 		$connect2_array = explode (",", $connect2_include_category); //get the default db prefix (on local db) to be changed in order to get the remote db prefix   
 		$wherebuild .= "("; // start sql filter construction
		for($i = 0; $i < count($connect2_array); $i++){
			if ($i<count($connect2_array)-1){
				$wherebuild .= $connect2_pre . "terms.term_id='" .$connect2_array[$i]. "' or ";
			}else{
				$wherebuild .= $connect2_pre . "terms.term_id='" .$connect2_array[$i]. "' ";
			}
		}
		$wherebuild .= ")"; // close sql filter
		//GET db CONNECTION fields
        $pdbhost =  "localhost";
        $pdbuser = $connect2_dbuser;
        $pdbpassword = $connect2_dbpwd;
        $pdbname = $connect2_dbName;
        $pdomain = $connect2_domain;
  		  // set up our own db connection 		
   		  $db = mysql_connect($pdbhost,$pdbuser,$pdbpassword, true);
   		  mysql_query("SET NAMES UTF8");
        mysql_select_db($pdbname, $db);
        if($connect2_limit==0){        	
       	  $sql = 'select object_id as pepe, post_title as jojo, post_content as pContent, comment_count as pComments, name as pCatName, '.$connect2_pre.'term_taxonomy.term_id as idCat from '.$connect2_pre.'posts, '.$connect2_pre.'term_relationships, '.$connect2_pre.'terms, '.$connect2_pre.'term_taxonomy where  '.$connect2_pre.'terms.term_id = '.$connect2_pre.'term_taxonomy.term_id and '.$connect2_pre.'term_taxonomy.taxonomy="category"  and '.$wherebuild.' and  '.$connect2_pre.'term_relationships.term_taxonomy_id =  '.$connect2_pre.'term_taxonomy.term_taxonomy_id and '.$connect2_pre.'term_relationships.object_id = '.$connect2_pre.'posts.id  and '.$connect2_pre.'posts.post_status="publish" order by post_date DESC';
        }else{
       	  $sql = 'select object_id as pepe, post_title as jojo, post_content as pContent, comment_count as pComments, name as pCatName, '.$connect2_pre.'term_taxonomy.term_id as idCat from '.$connect2_pre.'posts, '.$connect2_pre.'term_relationships, '.$connect2_pre.'terms, '.$connect2_pre.'term_taxonomy where  '.$connect2_pre.'terms.term_id = '.$connect2_pre.'term_taxonomy.term_id and '.$connect2_pre.'term_taxonomy.taxonomy="category"  and '.$wherebuild.' and  '.$connect2_pre.'term_relationships.term_taxonomy_id =  '.$connect2_pre.'term_taxonomy.term_taxonomy_id and '.$connect2_pre.'term_relationships.object_id = '.$connect2_pre.'posts.id  and '.$connect2_pre.'posts.post_status="publish" order by post_date DESC LIMIT ' .$connect2_limit;
        }
        $reststring .= "<div class='connect2site'>";
//echo mysql_client_encoding();	
        $rst = mysql_query($sql, $db);
	      if ($rst = mysql_query($sql, $db)) {		    	
		        $reststring .= "<ul>";
            while ($row = mysql_fetch_assoc($rst)) {
                $tagmlid = $row['pepe'];
                $targetframe = ($connect2_win==1)?"_blank":"_self";
                // Display Category name
                if ($connect2_catdisp==1){
                	$reststring .=  "<li><u><a href=//" .$pdomain. "/?cat=" . $row['idCat'] . " target=" .$targetframe. ">" . $row['pCatName'] . "</a></u> - ";
                }else{
                	$reststring .=  "<li>";
                }
               	//title link and comments
               	if ($connect2_comdisp==1){
               		$reststring .= "<a href=//".$pdomain."/?p=".$tagmlid." target=".$targetframe.">".$row['jojo']."</a><em>"." (".$row['pComments'].")</em>";
               	}else{
               		$reststring .= "<a href=//" .$pdomain. "/?p=" . $tagmlid. " target=" .$targetframe. ">" .$row['jojo']. "</a>";
               	}
               	// post summary
               	if ($strLimit > 0 ){
               		$summary = strip_tags(stripslashes($row['pContent']));
               		if (strlen($summary) > $strLimit){
               			$summary = substr($summary,0,strpos($summary," ",$strLimit));
               			if (strlen($summary) > 0){
               				$reststring .= "<ul><li><p style='font-size: 0.9em'><em>" . $summary . "...</em></p></li></ul>"; // content summary
               			}
               		}
               	}
               	$reststring .= "</li>";
            }
            $reststring .=  "</ul>";
        } else {
        $reststring .=  "Error: " . htmlentities(mysql_error($db));
        }
        $reststring .=  "</div>";
        mysql_close($db);
        return $reststring;
}

function widget_sitecat($args) {
  extract($args);
  $options = get_option('widget_sitecat');
  $title                     = $options['connect2-title'];
  $connect2_include_category = $options['connect2-categories'];
  $connect2_dbName           = $options['connect2-dbName'];
  $connect2_pre              = $options['connect2-pre'];
  $connect2_dbuser           = $options['connect2-dbuser'];
  $connect2_dbpwd            = $options['connect2-dbpwd'];
  $connect2_domain           = $options['connect2-domain'];
  $connect2_limit            = $options['connect2-limit'];
  $connect2_strLimit         = $options['connect2-strLimit'];
  $connect2_win              = (isset($options['connect2-win']))? 1 : 0;
  $connect2_catdisp          = (isset($options['connect2-catdisp']))? 1 : 0;
  $connect2_comdisp          = (isset($options['connect2-comdisp']))? 1 : 0;
  echo $before_widget;
  echo $before_title;
  echo $options['connect2-title'];
  echo $after_title;
  // Widget Content
  echo get_site_tags(1,'','','');
  echo $after_widget;
}

function widget_site_control() {
		$options = get_option('connect2_site_options');
		echo '<p style="text-align:center;">nothing interesting here !</p>';
}
// main function for categories for the target blog
function get_target_post($ppost,$pexcerpt)
{
        $options = get_option('widget_sitecat');
        $connect2_dbpwd            = $options['connect2-dbpwd'];
 	     	$connect2_dbName           = $options['connect2-dbName'];
 		    $connect2_pre              = $options['connect2-pre'];
 		    $connect2_dbuser           = $options['connect2-dbuser'];
        $connect2_domain           = $options['connect2-domain'];
        $connect2_win              = $options['connect2-win'];
		//GET db CONNECTION fields
        $pdbhost =  "localhost";
        $pdbuser = $connect2_dbuser;
        $pdbpassword = $connect2_dbpwd;
        $pdbname = $connect2_dbName;
        $pdomain = $connect2_domain;
  		// set up our own db connection 		
   		  $db = mysql_connect($pdbhost,$pdbuser,$pdbpassword, true);
   		  mysql_query("SET NAMES UTF8");
        mysql_select_db($pdbname, $db);
        $sql = 'select id as s_id, post_title as s_title, post_content as s_Content, post_excerpt as s_Excerpt, comment_count as s_Comments from '.$connect2_pre.'posts where '.$connect2_pre.'posts.post_type="post"  and '.$connect2_pre.'posts.id = '.$ppost;
        $reststring .= "<div class='connect2site'>";
        $rst = mysql_query($sql, $db);
	      if ($rst = mysql_query($sql, $db)) {	
	         $row = mysql_fetch_assoc($rst);	    	
           $tagmlid = $row['s_id'];
           $targetframe = ($connect2_win==1)?"_blank":"_self";                	
           //title link and comments         
           $reststring .= "<a href=//".$pdomain."/?p=".$tagmlid." target=".$targetframe."><h3>".$row['s_title']."</h3></a>";
           // the post or excerpt
           if ($pexcerpt==1){
           		$reststring .= "<p>".$row['s_Excerpt']."</p>"; // content excerpt
           }else{
           		$reststring .= "<p>".$row['s_Content']."</p>"; // content summary
           }
        } else {
        $reststring .=  "Error: " . htmlentities(mysql_error($db));
        }
        $reststring .=  "</div>";
        mysql_close($db);
        return $reststring;
}
//
// INIT WIDGET
//	
function widget_sitecat_init() {
		
	register_sidebar_widget('Connect2site Cat Display', 'widget_sitecat');
	//register_widget_control('Connect2site Cat Display', 'widget_site_control', 300, 100);
}
add_action('plugins_loaded', 'widget_sitecat_init');
// Adding short codes
// short code [c2s] show output in page or post
add_shortcode('c2s', 'c2s_short');
function c2s_short($atts){
	    extract(shortcode_atts(array(
	       'type' => 'widget',    
         'num' => '5',
         'chars' => '100',
         'cat' => '1',
         'post' => '1',
         'exc' => '0'
        ), $atts));
        $notready = "<div><strong>Not Implemented yet</strong></div>";
        if($type == 'widget'){
        	return get_site_tags(1,'','','');
        }else{
        	if($type == 'custom'){
        		return get_site_tags(2,$cat, $num, $chars);
        	}else{
        		if($type == 'post'){
        			return get_target_post($post,$exc);
        		}else{
        			return $notready;
        		}
        	}
        }
}

//
// Settings/admin panel
//
add_action('admin_menu', 'connect2_site_menu');
function connect2_site_menu() {
	add_options_page('Connect2site Options', 'Connect2Site', 10, 'Connect2Site', 'connect2_site_options');
	function connect2_site_options() { ?>
		<div class="wrap" style="margin: 2mm;">
		<?php
			if ( $_POST['tribetagcat-submit'] )  {
				$options['connect2-title']      = strip_tags(stripslashes($_POST['connect2-title']));
				$options['connect2-categories'] = strip_tags(stripslashes($_POST['connect2-categories']));
				$options['connect2-dbName']     = strip_tags(stripslashes($_POST['connect2-dbName']));
				$options['connect2-pre']        = strip_tags(stripslashes($_POST['connect2-pre']));
				$options['connect2-dbuser']     = strip_tags(stripslashes($_POST['connect2-dbuser']));
				$options['connect2-dbpwd']      = strip_tags(stripslashes($_POST['connect2-dbpwd']));
				$options['connect2-domain']     = strip_tags(stripslashes($_POST['connect2-domain']));
				$options['connect2-limit']      = strip_tags(stripslashes($_POST['connect2-limit']));
				$options['connect2-strLimit']   = strip_tags(stripslashes($_POST['connect2-strLimit']));
		    $options['connect2-win']        = (isset($_POST['connect2-win']))? 1 : 0;
  		  $options['connect2-catdisp']    = (isset($_POST['connect2-catdisp']))? 1 : 0;
  		  $options['connect2-comdisp']    = (isset($_POST['connect2-comdisp']))? 1 : 0; 
				update_option('widget_sitecat', $options);		
			}
		$options = get_option('widget_sitecat');
		$ck_connect2_catdisp = $options['connect2-catdisp'] == 1 ? 'checked="checked" ' : '';
		$ck_connect2_comdisp = $options['connect2-comdisp'] == 1 ? 'checked="checked" ' : '';
		$ck_connect2_win     = $options['connect2-win'] == 1 ? 'checked="checked" ' : '';
		?>
		<h2>Connect2site</h2>
		<form action="" method="post">
		<?php wp_nonce_field('tribetagcat-submit'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Widget Title:</th>
				<td><input type="text" style="width: 280px;" id="connect2-title" name="connect2-title" value="<?php echo $options['connect2-title']; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Category ID:</th>
				<td><input type="text" style="width: 280px;" id="connect2-categories" name="connect2-categories" value="<?php echo $options['connect2-categories']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">dbName:</th>
				<td><input type="text" style="width: 180px;" id="connect2-dbName" name="connect2-dbName" value="<?php echo $options['connect2-dbName']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">DB prefix:</th>
				<td><input type="text" style="width: 100px;" id="connect2-pre" name="connect2-pre" value="<?php echo $options['connect2-pre']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">dbUser:</th>
				<td><input type="text" style="width: 180px;" id="connect2-dbuser" name="connect2-dbuser" value="<?php echo $options['connect2-dbuser']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">dbPassword:</th>
				<td><input type="password" style="width: 180px;" id="connect2-dbpwd" name="connect2-dbpwd" value="<?php echo $options['connect2-dbpwd']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Domain (no / at the end):</th>
				<td><input type="text" style="width: 250px;" id="connect2-domain" name="connect2-domain" value="<?php echo $options['connect2-domain']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Limit Posts:</th>
				<td><input type="text" style="width: 100px;" id="connect2-limit" name="connect2-limit" value="<?php echo $options['connect2-limit']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Limit Summary:</th>
				<td><input type="text" style="width: 180px;" id="connect2-strLimit" name="connect2-strLimit" value="<?php echo $options['connect2-strLimit']; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Display category name:</th>
				<td><input type="checkbox" id="connect2-catdisp" name="connect2-catdisp" value="1" <?php echo $ck_connect2_catdisp ?> /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Display comment count:</th>
				<td><input type="checkbox" id="connect2-comdisp" name="connect2-comdisp" value="1" <?php echo $ck_connect2_comdisp ?>  /></td>
			</tr>

			<tr valign="top">
				<th scope="row">Open in new window:</th>
				<td><input type="checkbox" id="connect2-win" name="connect2-win" value="1" <?php echo $ck_connect2_win ?> /></td>
			</tr>
	
			<tr valign="top">
				<th scope="row">Short codes:</th>
				<td>
				<p>[c2s type="" num="" chars="" cat="" post=" " exc=""] - Note you can define multiple short codes in a page. <a href="http://tech.kounoupaki.gr">Visit developer's site for more details and examples.</a></p>
				<p><strong>1)</strong> if type parameter= <strong>widget</strong> ,it will display the content as in this settings page, the rest of parameters are ignored</p>
				<p><strong>2)</strong> if type parameter= <strong>custom</strong> , you should define the :</p>
					<p>-<em>num</em> = the number of posts to display</p>
					<p>-<em>chars</em> = the number of characters from the posts to display</p>
					<p>-<em>cat</em> = the category id or multiple category ids delimited by space i.e. 44,56,66</p>
					<p>-<em>exc</em> = not applied for this selection</p>
					<p><strong>3)</strong> if type parameter=<strong>post</strong> you should define the <strong>post id</strong> of the target post. Additional parameter is the <em>exc</em> = 0 for the full post content or 1 for the excerpt</p></p>
				<p><strong>Default parameteres if not specified are type='widget' num='5' chars='100' cat='1' post='1' exc='0'</strong></p>
				</td>
			</tr>
		</table>
	<p class="submit"><input type="submit" id="tribetagcat-submit" name="tribetagcat-submit" value="Update settings"></p>
	</form>
	</div>
<?php
	}
}


?>
