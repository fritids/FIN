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
echo "<h2>Post Delete Options</h2><br/>";
$cats = get_all_category_ids();
if (!$cats) {
  echo "<p align='center'><strong>No Categories Found</strong></p>";
  return;
 }
?>
<fieldset class='delete_options'>
  <script type="text/javascript">
  function toggleCheckboxes() {
  var inputlist = document.getElementsByTagName("input");
  
  for (i = 0; i < inputlist.length; i++) {
    // look only at input elements that are checkboxes
    if ( inputlist[i].getAttribute("type") == 'checkbox' && 
	 inputlist[i].getAttribute("name") != 'DeleteCats' ) { 
      if (inputlist[i].checked) {
	inputlist[i].checked = false
	  } else {
	inputlist[i].checked = true;
      }
    }
  }
}
</script>
<table width="100%" cellpadding="3" cellspacing="3" style="text-align: left; vertical-align: top;">
  <form method='POST'>
  <tr class="alternate"><th width="75%">Post Type</th><th width="25%">Selected</th></tr>
  <tr><td colspan='2'><hr/></td></tr>
  <tr>
  <td style='padding-left: 15px'>Published</td>
  <td><input type="radio" name='DelPostType' value=<?php echo DEL_PUB_POSTS; ?> 
  <?php if (DEL_PUB_POSTS == $this->settings['DelPostType']) echo "checked"; ?> ></td>
  </tr>
  <tr>
  <td style='padding-left: 15px'>Drafts</td>
  <td><input type="radio" name='DelPostType' value=<?php echo DEL_DRAFT_POSTS; ?> 
  <?php if (DEL_DRAFT_POSTS == $this->settings['DelPostType']) echo "checked"; ?>></td>
  </tr>
  <tr class="alternate"><th width="75%">Category</th><th width="25%">Selected</th></tr>
<tr><td colspan='2'><hr /></td></tr>
<?php
$catArrayDiff = array_diff($cats, $this->settings['DeleteCategory']);
foreach($cats as $i => $value) {
  echo "<tr>";
  $catname = get_catname($cats[$i]);
  //if (catInList($cats[$i], $this->settings['DeleteCategory'])) {
  if (!in_array($cats[$i], $catArrayDiff)) {
    $preMod = "<strong>";
    $postMod = "</strong>";
    $checked = "checked='checked'";
  } else {
    $preMod = "";
    $postMod = "";
    $checked = "";
  }
  echo "<td style='padding-left: 15px'>$preMod$catname$postMod</td>";
  echo "<td><input type='checkbox' name='DeleteCategory[]' value='$cats[$i]' $checked></td>";
  echo "</tr>";
}
?>
<tr>
<td>&nbsp;</td>
<td style="border-top: 1px solid #cccccc;"><a href="#" onClick="toggleCheckboxes();">Toggle Categories</a></td>
</tr>
<tr><td align='right' colspan='2'><input type='submit' 	name='submit' value='Update Options'></td></tr>
</form>
</table></fieldset>
