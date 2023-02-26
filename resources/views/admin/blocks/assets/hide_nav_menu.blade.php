<style type="text/css">
.w-0{
	width: 0px !important;
}
.ml-0{
	margin-left: 0px !important;
}
.btn_nav_show_hide_menu{
	position: fixed;
    top: 50%;
    left: 180px;
    background-color: white;
    padding: 15px 5px 15px 19px;
    border-radius: 50%;
    cursor: pointer;
    border: 1px solid #CCC;
    transition: .25s;
    z-index: 1;
}
.btn_nav_show_hide_menu.do_hide{
	left: -20px;
}
.btn_nav_show_hide_menu:hover{
	background-color: purple;
	color: #fff;
}
.btn_nav_show_hide_menu>.js_chevron_left{ display: block; }
.btn_nav_show_hide_menu>.js_chevron_right{ display: none; }

.btn_nav_show_hide_menu.do_hide>.js_chevron_left{ display: none; }
.btn_nav_show_hide_menu.do_hide>.js_chevron_right{ display: block; }
@media only screen and (max-width: 768px) {
	.btn_nav_show_hide_menu{
		display: none;
	}
}
</style>
<div class="btn_nav_show_hide_menu">
	<i class="fa fa-chevron-left js_chevron_left"></i>
	<i class="fa fa-chevron-right js_chevron_right"></i>
</div>
<script type="text/javascript">
function do_btn_nav_show_hide_menu(){
	$('.app-main .app-sidebar').toggleClass('w-0');
	$('.app-main .app-content').toggleClass('ml-0');
	$('.btn_nav_show_hide_menu').toggleClass('do_hide');
}
function auto_hide_menu(){
	do_btn_nav_show_hide_menu();
}
$('.btn_nav_show_hide_menu').click(function(){
	do_btn_nav_show_hide_menu();
});
</script>
