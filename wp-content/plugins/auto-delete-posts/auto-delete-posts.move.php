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
 
if (MOVE_POSTS == $this->settings['PostAction']) {
  // Set input name
  $inputName = 'MoveCategory';
  // Grab the selected category
  $selCat = $this->settings['MoveCategory'];
  echo "<h2>Post Move Options</h2>\r\n";
 } else {
  // Set input name
  $inputName = 'AddCategory';
  // Grab the selected category
  $selCat = $this->settings['AddCategory'];
  echo "<h2>Post Add Categories Options</h2>\r\n";
 }
			
$cats = get_all_category_ids();
if (!$cats) {
  echo "<p align='center'><strong>No Categories Found</strong></p>";
  return;
 }
?>
<fieldset class='move_options'>
  <table width="100%" cellpadding="3" cellspacing="3" style="text-align: left; vertical-align: top;">
  <form method='POST'>
  <tr class="alternate"><th width="75%">Category</th><th width="25%">Destination</th></tr>
<input type='hidden' name='wpadp_move_options' value='update'>
  <?php
foreach($cats as $i => $value) {
  echo "<tr>";
  $catname = get_catname($cats[$i]);
  if ($cats[$i] == $selCat) {
    $preMod = "<strong>";
    $postMod = "</strong>";
    $checked = "checked='checked'";
  } else {
    $preMod = "";
    $postMod = "";
    $checked = "";
  }
  echo "<td>$preMod$catname$postMod</td>";
  echo "<td><input type='radio' name='$inputName' value='$cats[$i]' $checked></td>";
  echo "</tr>";
}
?>
<tr><td align='right' colspan='2'><input type='submit' 	name='submit' value='Update Options'></td></tr>
</form>
</table></fieldset>