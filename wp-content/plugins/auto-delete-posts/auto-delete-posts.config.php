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
?>

<style type="text/css">
  table.optiontable {
  border-width: 1px 1px 1px 1px;
  border-spacing: 2px;
  border-style: none;
  border-color: gray gray gray gray;
  border-collapse: separate;
  background-color: white;
  }
  table.optiontable tr {
  border-style: none;
  }
  table.optiontable th {
  border-width: 1px 1px 1px 1px;
  padding: 1px 1px 1px 1px;
  border-style: none none dashed none;
  border-color: gray gray gray gray;
  background-color: #dddddd;
  color: #000000;
  -moz-border-radius: 0px 0px 0px 0px;
  }
  table.optiontable td {
  border-width: 1px 1px 1px 1px;
  padding: 1px 1px 1px 1px;
  border-style: none none dashed none;
  border-color: gray gray gray gray;
  background-color: #dddddd;
  color: #000000;
  -moz-border-radius: 0px 0px 0px 0px;
  }
</style>
<div class='wrap'>
  <h2>Auto Delete Posts Options</h2>
  <fieldset class='options'>
    <table class='optiontable' cellspacing='10' width='100%'>
      <tr valign="top">
	<td width="50%">
	  <table width="100%">
	    <form method='POST'>
	    <tr valign="top">
	      <th style='text-align: left'>
		<strong>Plugin Function</strong>
	      </th>
	      <td style='text-align: left'>
		<input type='radio' name='PerPostOrSite' value='<?php echo SITE_WIDE; ?>'
		<?php
		if (SITE_WIDE == $this->settings['PerPostOrSite']) {
		echo " checked='checked'";
		}
		echo "/>";
		?>
		&nbsp; Site Wide<br/>
		<input type='radio' name='PerPostOrSite' value='<?php echo PER_POST; ?>'
		<?php
		if (PER_POST == $this->settings['PerPostOrSite']) {
		echo " checked='checked'";
		}
		echo "/>";
		?>
		&nbsp; Per Post
	      </td>
	    </tr>
	    <tr valign="top">
	      <th style='text-align:left;'>
		<strong>Plugin Action</strong>
	      </th>
	      <td style='text-align:left;'>
		<input type='radio' name='PostAction' value='<?php echo DELETE_POSTS; ?>'
		<?php
		if (DELETE_POSTS == $this->settings['PostAction']) {
			echo " checked='checked'";
		}

		echo "/>";
		?>
		&nbsp; Delete<br/>
		<input type='radio' name='PostAction' value='<?php echo MOVE_POSTS; ?>'
		<?php
		if (MOVE_POSTS == $this->settings['PostAction']) {
			echo " checked='checked'";
		}

		echo "/>";
		?>
		&nbsp;  Move<br/>
		<input type='radio' name='PostAction' value='<?php echo ADD_CAT; ?>'
		<?php
		if (ADD_CAT == $this->settings['PostAction']) {
			echo " checked='checked'";
		}
		if (PER_POST == $this->settings['PerPostOrSite']) {
			echo " disabled";
		}
		echo "/>";
		?>
		&nbsp;  Add Category<br/>
		<input type='radio' name='PostAction' value='<?php echo PREVIEW; ?>'
		<?php
		if (PREVIEW == $this->settings['PostAction']) {
			echo " checked='checked'";
		}
		if (PER_POST == $this->settings['PerPostOrSite']) {
			echo " disabled";
		}
		echo "/>";
		?>
		&nbsp; Preview
	      </td>
	    </tr>
	    <tr valign="top">
	      <th style='text-align:left;'>
		<strong>How long to keep posts</strong>
	      </th>
	      <td>
		<input type='text' size='10' name='PostAge' value=" <?php echo $this->settings['PostAge']; ?>"
		<?php if (PER_POST == $this->settings['PerPostOrSite']) {
		echo " disabled";
		}
		echo "/>";
		?>
		&nbsp; in days
	      </td>
	    </tr>
	    <tr valign="top">
	      <td align='right' colspan='2'>
		<input type='submit' name='submit' value='Update Options'>
	      </td>
	    </tr>
	  </form>
	  </table>
	</td>
	<td width="50%" align="center" valign="middle" style="background-color: #eeeeee; color: #000000;">
	  This plugin is made and maintained purely on a volunteer basis. If you feel that you get some value in using 
	  this plugin, please consider a donation.
	  <br clear="all">
	  <em>All feature requests welcome.</em>.
	  <br clear="all">
	  <!-- Begin PayPal generated code for the donation button //-->
	  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	    <input type="hidden" name="cmd" value="_s-xclick">
	    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHNwYJKoZIhvcNAQcEoIIHKDCCByQCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYA0C5IrCs03aQXJhxAPdZrPgRSbe6J+FBm/RGPNuBI6EEA1CUSHPEijBaGCO9wSoqL9uPRhdHGp+GgXVaYz/l3d8lFB6gmAzd5vlW49MP/UyQNw2kdqBx8Mk3K413VCK/37oyfPqxoMl4HIU82UvOapu48PfDlq7g4uVxawfgm3MzELMAkGBSsOAwIaBQAwgbQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIuObVf7Wj5yCAgZCssJZkxIunDyzrRqay0Mr7iQ9owUi8A4kNad6MMQ1DerncLHZsggxgEiLUfRiX/Owxppnco71UjKTk1DbjiV7M4BZcdjzVwx6tQmlMo89zOwOuM0R4Um5NZyUunalKFx62l+eCffSuDsvzTHNYdYUTmh/vun1v1coSI6lROpNMCMiW0AUOBd+qyl9WoWisDKWgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0wOTA4MTIxNTAxMjdaMCMGCSqGSIb3DQEJBDEWBBS40/VlgYDxlvHBreA3KdUtHOrJ6jANBgkqhkiG9w0BAQEFAASBgLKq0Fp60jA6r3CzDPgnhiknU952Cy1pzr50CA6LRFvgyBBwoKblVSSXJ7w9pHR/r8Z3uRNwZ5m6Kkv/efuIa97NsHtDzMxsYjb8Evzo7rhGH9gjeV42p2PaprJMqG5ZghjWjHfuHkiGUG2dKuxRamuWjBkttHmNCg9WAV2Kae8D-----END PKCS7-----
	      ">
	    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	  </form>
	  <br clear="all">
	  Thank you!
	</td>
      </tr>
    </table>
  </fieldset>
  <div style='clear: both;'>&nbsp;</div>
  
  <?php
  if (SITE_WIDE == $this->settings['PerPostOrSite']) {

	  /* Choose which category to delete from */
	  if (DELETE_POSTS == $this->settings['PostAction']) {
	  		
		  require_once(dirname(__FILE__) . '/auto-delete-posts.delete.php');
	  } 

	  /* Choose which category to move posts to */
	  if ((MOVE_POSTS == $this->settings['PostAction']) || (ADD_CAT == $this->settings['PostAction'])) {
		  require_once(dirname(__FILE__) . '/auto-delete-posts.move.php');		
	  }

	  /* Preview what posts would be deleted. */ 
	  if (PREVIEW == $this->settings['PostAction']) {
		  require_once(dirname(__FILE__) . '/auto-delete-posts.preview.php');		
	  }
	} else {
		  if (MOVE_POSTS == $this->settings['PostAction']) {
		  		require_once(dirname(__FILE__) . '/auto-delete-posts.move.php');
		  }
		  require_once(dirname(__FILE__) . '/auto-delete-posts.preview.php');		
	}
  ?>
		</div>