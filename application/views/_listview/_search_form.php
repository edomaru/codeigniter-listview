<?php echo form_open($base_url, array("id" => "search_form")); ?>
<style type="text/css">
.add-on .input-group-btn > .btn {
  border-right-width:0;
  left:-2px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  height: 34px;
}
/* stop the glowing blue shadow */
.add-on .form-control:focus {
 box-shadow:none;
 -webkit-box-shadow:none; 
 border-color:#cccccc; 
}
</style>
<div class="searchform_box"> 
 <div class="input-group add-on  col-md-4 col-sm-10">   
    <input class="form-control col-md-4 col-sm-10" type="text" name='keywords' value='<?php echo $this->session->userdata("current_keywords"); ?>' placeholder="Search..." size="20"/>
    <div class="input-group-btn">
   		 <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
    </div>
     </div>
</div>

<?php echo form_close(); ?>
