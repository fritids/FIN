<?php
  /*
   * Copyright (c) 2007-2009 Ashwin Bihari
   * http://www.ashwinbihari.com/
   *
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the "Software"), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   *
   * The above copyright notice and this permission notice shall be included in
   * all copies or substantial portions of the Software.
   *
   * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   */
 
	if (PER_POST == $this->settings['PerPostOrSite'])
		echo "<h2>Preview Of Per Post Expiration</h2>\r\n";
	else
		echo "<h2>Preview Of Post Deletion/Move/Add</h2>\r\n";
	
	  $ids = $wpdb->get_results($this->gen_query());
	  if (!$ids) {
	    echo "<p align='center'><strong>No Posts Found</strong></p>";
	    return;
	  }
	  
	  // Figure out how many posts we have and only preview a few..
	  $postCount = count($ids);
	  $index = 0;	  
	  
	  echo '<table width="100%" cellpadding="3" cellspacing="3" style="text-align: left; vertical-align: 	top;">';
	  echo '<tr class="alternate"><th width="25%">Post ID</th><th width="25%">Post Title</th>
	  <th 	width="25%">Post Category</th><th width="25%">No. of Comments</th></tr>';
	  foreach($ids as $id) {
	    if ('' == $id->ID)
	      continue;
	      
		// Once we show 50 posts, let's stop..
	    if ($index++ == 50)
	    	break;
	    
	    $query = "SELECT DISTINCT post_title FROM $wpdb->posts WHERE ID=". $id->ID;
	    $title = $wpdb->get_var($query);
	    $commentCount = get_comments_number($id->ID);
	    $catnames = get_the_category($id->ID);
	    echo "<tr>";
	    echo "<td>$id->ID</td> <td>$title</td>";
	    echo "<td>";
	    $count = 0;
    	foreach($catnames as $catname) {
	      if ($count > 0)
			echo ", ";

	      echo "$catname->cat_name";
	      $count++;
	    }
    	echo "</td>";
	    if ($commentCount)
    	  echo "<td>$commentCount</td>\r\n";
	    else
	      echo "<td>0</td>\r\n";
    	}
	    echo "</tr>";
	    
	    if (($index + 10) < $postCount) {
	    	echo "<tr>";
	    	echo "<td colspan='4' style='padding-left: 300px;'>";
	    	$remainder = $postCount - $index;
	    	echo "<strong>..and $remainder more posts</strong>";
	    	echo "</td></tr>";
	    }
  ?>
    <tr>
       <td>
       <?php

      $adp_mvcategoryid = $this->settings['MoveCategory'];
	  $adp_addcategoryid = $this->settings['AddCategory'];
	  $adp_mvcategory = get_the_category_by_ID($adp_mvcategoryid);
	  $adp_addcategory = get_the_category_by_ID($adp_addcategoryid);
	  
	  ?>
	
    <form method="post"/>
       <input name="performdelete" class="button" type="submit" id="performdelete" tabindex="10" value="Delete These Posts Now!" onclick="return confirm('You are about to delete all these posts \'Cancel\' to stop, \'OK\' to delete.')" />
       <?php
       if ((SITE_WIDE == $this->settings['PerPostOrSite']) && (DEL_PUB_POSTS == $this->settings['DelPostType'])) {
	       	$blog_version = get_bloginfo('version');
			$major = (int) substr($blog_version, 0, 1);
	  		$minor = (int) substr($blog_version, 2, 3);
	  		if ((2 == $major) && ( 7 > $minor)) {
    			if (!is_wp_error($adp_mvcategory) && ("" != $adp_mvcategory)) {
			      echo "<input name=\"performmove\" class=\"button\" type=\"submit\" id=\"performmove\" tabindex=\"10\" value=\"Move These Posts to '".$adp_mvcategory."' Now!\" onclick=\"return confirm('You are about to move all these posts  \'Cancel\' to stop, \'OK\' to move.') \" />\r\n";
			    }
			    if (!is_wp_error($adp_addcategory) && ("" != $adp_addcategory)) {
			      echo "<input name=\"performaddcat\" class=\"button\" type=\"submit\" id=\"performaddcat\" tabindex=\"10\" value=\"Add '".$adp_addcategory."' to These Posts Now!\" onClick=\"return confirm('You are about to add a new category to all these posts \'Cancel\' to stop, \'OK\' to add.')\" />\r\n";
			    }
		 	} else { 
			    if (!is_wp_error($adp_mvcategory) ) {
			      echo "<input name=\"performmove\" class=\"button\" type=\"submit\" id=\"performmove\" tabindex=\"10\" value=\"Move These Posts to '".$adp_mvcategory."' Now!\" onclick=\"return confirm('You are about to move all these posts  \'Cancel\' to stop, \'OK\' to move.') \" />\r\n";
			    }
			    if (!is_wp_error($adp_addcategory)) {
			      echo "<input name=\"performaddcat\" class=\"button\" type=\"submit\" id=\"performaddcat\" tabindex=\"10\" value=\"Add '".$adp_addcategory."' to These Posts Now!\" onClick=\"return confirm('You are about to add a new category to all these posts \'Cancel\' to stop, \'OK\' to add.')\" />\r\n";
			    }
			}
		}
?>
</form>
</td>
</tr>
</table>
