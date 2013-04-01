<?php echo form_open($base_url, array("id" => "search_form")); ?>

<div class="searchform_box">    
    <input type="text" name='keywords' value='<?php echo $this->session->userdata("current_keywords"); ?>' placeholder="Search..." size="20"/>
    <button type="submit">Go</button>
</div>

<?php echo form_close(); ?>