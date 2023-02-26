<script src="{{ url('assets/js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('assets/js/js.cookie.js') }}"></script>
<script src="{{ url('assets/js/input-mask/dist/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ url('assets/js/clipboard.min.js') }}"></script>
<style type="text/css">
.btn-refresh{
    border-radius: 17px;
}
.label-purple {
    background-color: purple;
}
.btn-purple{
    background-color: purple;
    border: 1px solid #5a005a;
    color: #fff;
}
.btn-purple:hover{
    background-color: #ad00ad;
    border: 1px solid #9c009c;
    color: #fff;
}
.msg{}
.msg .list{
    height: 286px;
    overflow: auto;
    padding: 5px;
    border: 1px solid #ddd;
    margin-bottom: 0px;
}
.msg .list .message-list{
    margin-bottom: 0px;
}
.msg .list .message-list>li{
    display: inline-block;
    width: 100%;
    line-height: 1.1;
    margin-bottom: 8px;
    padding-bottom: 2px;
    border-bottom: 1px solid #ddd;
}
.msg .list .message-list>li:last-child{
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
}
.msg .list .message-list>li div.title{
    display: inline-block;
    width: 100%;
}
.msg .list .message-list>li div.title strong{
    float: left;
}
.msg .list .message-list>li div.title span.date{
    float: right;
    font-size: 12px;
    font-style: italic;
}
.msg .list .message-list>li .content{
    /*float: left;*/
}
.msg .grp-inp{
    position: relative;
}
.msg .inp-text{
    width: 100%;
    padding: 7px 10px;
    border: 1px solid #ddd;
    padding-right: 30px;
}
.msg .inp-text:focus{
    outline: 0;
}
.msg .grp-inp .fa.fa-send{
    position: absolute;
    top: 0;
    right: 0;
    padding: 11px 10px;
    cursor: pointer;
    color: #0000ff;
}

.reminder {}
.reminder .list{
    height: 286px;
    overflow: auto;
    border: 1px solid #ddd;
    margin-bottom: 0px;
}
.reminder .list .message-list{
    margin-bottom: 0px;
}
.reminder .list .message-list>li{
    display: inline-block;
    width: 100%;
    line-height: 1.1;
    padding: 5px;
    padding-top: 10px;
    border-bottom: 1px solid #ddd;
    position: relative;
}
.reminder .list .message-list>li:nth-of-type(odd){
    background-color: #f5f5f5;
}
.reminder .list .message-list>li:last-child{
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
}
.reminder .list .message-list>li div.title{
    display: inline-block;
    width: 100%;
    margin-top: 5px;
}
.reminder .list .message-list>li div.title strong{
    /*float: left;*/
}
.reminder .list .message-list>li div.title span.date{
    float: right;
    font-size: 14px;
    font-style: italic;
}
.reminder .list .message-list>li:hover .three-dot{
    display: block;
}
.reminder .list .message-list>li .three-dot{
    line-height: 0;
    position: absolute;
    right: 0;
    top: 5px;
    font-size: 25px;
    display: none; 
    font-weight: bold;
    cursor: pointer;
    user-select: none;
    color: red;
}
.reminder .list .message-list>li .three-dot:hover{
    color: blue;
}
.message-control{
    position: absolute;
    display: none;
    right: 0;
    top: 23px;
    z-index: 99;
    padding: 5px 0;
    background-color: #fff;
    text-align: right;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 12px -4px;
}
.message-control.show{
    display: block;
}
.message-control>li{
    padding: 5px 10px;
}
.message-control>li>i.fa{
    margin-left: 5px;
}
.message-control>li:hover{
    background-color: #005de8;
    color: #fff;
    cursor: pointer;
    user-select: none;
}
.message-control>li.del:hover{
    background-color: #ff0000;
}
.reminder .grp-inp{
    position: relative;
}
.reminder .inp-text{
    width: 100%;
    padding: 7px 10px;
    border: 1px solid #ddd;
    padding-right: 30px;
}
.reminder .inp-text:focus{
    outline: 0;
}
.reminder .grp-inp .input-group-addon{
    cursor: pointer;
}
.reminder .grp-inp .fa.fa-send{
    color: #0000ff;
}

.m-25{margin:25px!important}.ml-25{margin-left:25px!important}.mr-25{margin-right:25px!important}.mt-25{margin-top:25px!important}.mb-25{margin-bottom:25px!important}.mx-25{margin-left:25px!important;margin-right:25px!important}.my-25{margin-top:25px!important;margin-bottom:25px!important}
.m-20{margin:20px!important}.ml-20{margin-left:20px!important}.mr-20{margin-right:20px!important}.mt-20{margin-top:20px!important}.mb-20{margin-bottom:20px!important}.mx-20{margin-left:20px!important;margin-right:20px!important}.my-20{margin-top:20px!important;margin-bottom:20px!important}
.m-15{margin:15px!important}.ml-15{margin-left:15px!important}.mr-15{margin-right:15px!important}.mt-15{margin-top:15px!important}.mb-15{margin-bottom:15px!important}.mx-15{margin-left:15px!important;margin-right:15px!important}.my-15{margin-top:15px!important;margin-bottom:15px!important}
.m-10{margin:10px!important}.ml-10{margin-left:10px!important}.mr-10{margin-right:10px!important}.mt-10{margin-top:10px!important}.mb-10{margin-bottom:10px!important}.mx-10{margin-left:10px!important;margin-right:10px!important}.my-10{margin-top:10px!important;margin-bottom:10px!important}
.m-5{margin:5px!important}.ml-5{margin-left:5px!important}.mr-5{margin-right:5px!important}.mt-5{margin-top:5px!important}.mb-5{margin-bottom:5px!important}.mx-5{margin-left:5px!important;margin-right:5px!important}.my-5{margin-top:5px!important;margin-bottom:5px!important}
.m-3{margin:3px!important}.ml-3{margin-left:3px!important}.mr-3{margin-right:3px!important}.mt-3{margin-top:3px!important}.mb-3{margin-bottom:3px!important}.mx-3{margin-left:3px!important;margin-right:3px!important}.my-3{margin-top:3px!important;margin-bottom:3px!important}
.m-2{margin:2px!important}.ml-2{margin-left:2px!important}.mr-2{margin-right:2px!important}.mt-2{margin-top:2px!important}.mb-2{margin-bottom:2px!important}.mx-2{margin-left:2px!important;margin-right:2px!important}.my-2{margin-top:2px!important;margin-bottom:2px!important}
.m-0{margin:0!important}.ml-0{margin-left:0!important}.mr-0{margin-right:0!important}.mt-0{margin-top:0!important}.mb-0{margin-bottom:0!important}.mx-0{margin-left:0!important;margin-right:0!important}.my-0{margin-top:0!important;margin-bottom:0!important}
.p-25{padding:25px!important}.pl-25{padding-left:25px!important}.pr-25{padding-right:25px!important}.pt-25{padding-top:25px!important}.pb-25{padding-bottom:25px!important}.px-25{padding-left:25px!important;padding-right:25px!important}.py-25{padding-top:25px!important;padding-bottom:25px!important}
.p-20{padding:20px!important}.pl-20{padding-left:20px!important}.pr-20{padding-right:20px!important}.pt-20{padding-top:20px!important}.pb-20{padding-bottom:20px!important}.px-20{padding-left:20px!important;padding-right:20px!important}.py-20{padding-top:20px!important;padding-bottom:20px!important}
.p-15{padding:15px!important}.pl-15{padding-left:15px!important}.pr-15{padding-right:15px!important}.pt-15{padding-top:15px!important}.pb-15{padding-bottom:15px!important}.px-15{padding-left:15px!important;padding-right:15px!important}.py-15{padding-top:15px!important;padding-bottom:15px!important}
.p-10{padding:10px!important}.pl-10{padding-left:10px!important}.pr-10{padding-right:10px!important}.pt-10{padding-top:10px!important}.pb-10{padding-bottom:10px!important}.px-10{padding-left:10px!important;padding-right:10px!important}.py-10{padding-top:10px!important;padding-bottom:10px!important}
.p-5{padding:5px!important}.pl-5{padding-left:5px!important}.pr-5{padding-right:5px!important}.pt-5{padding-top:5px!important}.pb-5{padding-bottom:5px!important}.px-5{padding-left:5px!important;padding-right:5px!important}.py-5{padding-top:5px!important;padding-bottom:5px!important}
.p-2{padding:2px!important}.pl-2{padding-left:2px!important}.pr-2{padding-right:2px!important}.pt-2{padding-top:2px!important}.pb-2{padding-bottom:2px!important}.px-2{padding-left:2px!important;padding-right:2px!important}.py-2{padding-top:2px!important;padding-bottom:2px!important}
.p-0{padding:0!important}.pl-0{padding-left:0!important}.pr-0{padding-right:0!important}.pt-0{padding-top:0!important}.pb-0{padding-bottom:0!important}.px-0{padding-left:0!important;padding-right:0!important}.py-0{padding-top:0!important;padding-bottom:0!important}
.w-20{width:20px!important;}

.br-0{ border-radius: 0px !important; }

.text-red{ color: red !important; }
.text-blue{ color: blue !important; }
.text-green{ color: green !important; }
.text-orange{ color: orange !important; }
.text-yellow{ color: #f3d700 !important; }
.text-purple{ color: purple !important; }
.text-default{ color: #888 !important; }
.text-white{ color: white !important; }
.text-black{ color: black !important; }

.hover-bg-red:hover{ background-color: red !important; }
.hover-bg-blue:hover{ background-color: blue !important; }
.hover-bg-green:hover{ background-color: green !important; }
.hover-bg-orange:hover{ background-color: orange !important; }
.hover-bg-yellow:hover{ background-color: #f3d700 !important; }
.hover-bg-purple:hover{ background-color: purple !important; }
.hover-bg-default:hover{ background-color: #888 !important; }

.hover-text-red:hover{ color: red !important; }
.hover-text-blue:hover{ color: blue !important; }
.hover-text-green:hover{ color: green !important; }
.hover-text-orange:hover{ color: orange !important; }
.hover-text-yellow:hover{ color: #f3d700 !important; }
.hover-text-purple:hover{ color: purple !important; }
.hover-text-default:hover{ color: #888 !important; }

.text-bold{ font-weight: bold !important; } .text-uppercase{ text-transform: uppercase !important; }
.border-radius-none{ border-radius: 0px !important; }
.label-purple { background-color: purple; }
.bg-red{ background-color: red !important; }
.bg-blue{ background-color: blue !important; }
.bg-green{ background-color: green !important; }
.bg-orange{ background-color: orange !important; }
.bg-purple{ background-color: purple !important; }
.bg-green-light{ background-color: #00bb00 !important; }

.center-middle{ text-align: center !important; vertical-align: middle !important; }
.c_pointer{ cursor: pointer !important; }
.btn-circle{ border-radius: 50% !important; }
.fa-share-custom{
  padding: 8px 10px;
    border-radius: 8px;
    background-color: #337ab7;
    color: #fff;
    cursor: pointer;
    transition: .2s;
}
.fa-share-custom:hover{
  background-color: #4aabff;
}
.line_height_2{
    line-height: 2 !important;
}

.toggle-label {
  position: relative;
  display: block;
  width: 130px;
  height: 35px;
  margin-top: 8px;
  border: 1px solid #808080;
  margin: 0 auto;
  user-select: none;
}
.toggle-label input[type=checkbox] { 
  opacity: 0;
  position: absolute;
  width: 100%;
  height: 100%;
}
.toggle-label input[type=checkbox]+.back {
  position: absolute;
  width: 100%;
  height: 100%;
  background: #ed1c24;
  transition: background 150ms linear;  
}
.toggle-label input[type=checkbox]:checked+.back {
  background: #00a651; /*green*/
}

.toggle-label input[type=checkbox]+.back .toggle {
  display: block;
  position: absolute;
  content: ' ';
  background: #fff;
  width: 50%; 
  height: 100%;
  transition: margin 150ms linear;
  border: 1px solid #808080;
  border-radius: 0;
}
.toggle-label input[type=checkbox]:checked+.back .toggle {
  margin-left: 64px;
}
.toggle-label .label {
  display: block;
  position: absolute;
  width: 50%;
  color: #ddd;
  line-height: 25px;
  text-align: center;
  font-size: 1.5em;
}
.toggle-label .label.on { left: 0px; }
.toggle-label .label.off { right: 0px; }

.toggle-label input[type=checkbox]:checked+.back .label.on {
  color: #fff;
}
.toggle-label input[type=checkbox]+.back .label.off {
  color: #fff;
}
.toggle-label input[type=checkbox]:checked+.back .label.off {
  color: #ddd;
}

.media-center{ display: block !important;margin-left: auto;margin-right: auto; }
.modal_material .modal-content, .modal_material .btn, .modal_material .form-control{ border-radius: 0px !important; } .modal_material .modal-header{ background-color: purple; color: #fff; }
.btn-purple{
    background-color: purple;
    border: 1px solid #a000a0;
    color: #fff;
    transition: 0.1s all;
}
.btn-purple:hover{
    background-color: #a900a9;
    border: 1px solid #9c009c;
    color: #fff;
}
.list-image .image-item{
    width: 150px;
    height: 100px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    background-color: #efefef;
    border: 1px solid #ccc;
    float: left;
    margin-right: 10px;
    margin-bottom: 10px;
    cursor: pointer;
}

.table-sm-custom{
    margin-top: 10px;
    margin-bottom: 0;
}
.table-sm-custom>tbody>tr>td, .table-sm-custom>thead>tr>th{
    padding: 4px 8px;
}
.grid-container table>tbody>tr>td{
    position: relative;
}
.grid-container .absolute-100{
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
}

.holder-img{
    height: 150px;
    width: 100%;
    display: block;
    font-size: 30px;
    font-weight: 400;
    text-align: center;
    line-height: 150px;
    user-select: none;
    border: 1px solid #ddd;
    background-color: #ddd;
    background-size: cover;
    background-position: center;
}
.filename-limit{
    white-space: nowrap;
    overflow: hidden;
    display: block;
    text-overflow: ellipsis;
}

@media only screen and (min-width: 992px) {
    .mt-25md{ margin-top: 25px; } .mb-25md{ margin-bottom: 25px; } .ml-25md{ margin-left: 25px; } .mr-25md{ margin-right: 25px; }
    .mt-20md{ margin-top: 20px; } .mb-20md{ margin-bottom: 20px; } .ml-20md{ margin-left: 20px; } .mr-20md{ margin-right: 20px; }
    .mt-15md{ margin-top: 15px; } .mb-15md{ margin-bottom: 15px; } .ml-15md{ margin-left: 15px; } .mr-15md{ margin-right: 15px; }
    .mt-10md{ margin-top: 10px; } .mb-10md{ margin-bottom: 10px; } .ml-10md{ margin-left: 10px; } .mr-10md{ margin-right: 10px; }
}
.blocks{ position: relative; } .blocks .pull-left{ width: 200px; } .blocks .pull-right{ width: calc(100% - 200px); min-height: 300px; padding: 15px; border-left: 1px solid #ddd; } .blocks .pull-left .item, .blocks .pull-left .item-head{ padding: 5px 10px; position: relative; border-top: 1px solid rgba(255, 255, 255, 0); border-bottom: 1px solid rgba(255, 255, 255, 0); border-left: 3px solid rgba(255, 255, 255, 0); cursor: pointer; } .blocks .pull-left .item-head{ color: #333; font-weight: 700; font-size: 1.2em; margin-top: 10px; padding-left: 0; } .blocks .pull-left .item:hover{ background: #f5f5f5; } .blocks .pull-left .item.active{ background: #fff; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 3px solid rgb(0, 161, 255); } .blocks .pull-left .item.active:before{ content: ''; position: absolute; right: -1px; top: 0; height: 100%; width: 1px; background: #fff; } #block-social-network .sn-instance{ padding: 15px; position: relative; } #block-product-category .block-product:hover, #block-social-network .sn-instance:hover{ background-color: #f5f1f1; } #block-product-category .btn-del{ position: absolute; top: -35px; right: -10px; cursor: pointer; display: none; } #block-social-network .sn-instance .btn-del{ position: absolute; top: 0px; right: 5px; cursor: pointer; display: none; } #block-product-category .block-product:hover .btn-del, #block-social-network .sn-instance:hover .btn-del{ display: block; } /* scrollbar */ .link-collection::-webkit-scrollbar, .s-n-modal::-webkit-scrollbar { width: 6px; } .link-collection::-webkit-scrollbar-track, .s-n-modal::-webkit-scrollbar-track { -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); } .link-collection::-webkit-scrollbar-thumb, .s-n-modal::-webkit-scrollbar-thumb { background-color: darkgrey; outline: 1px solid slategrey; } /* end scrollbar */ .s-n-modal{ margin: 15px; width: 880px; height: 380px; overflow: auto; } .s-n-modal .fa-hover{ cursor: pointer; } .faq-holder{ margin-top: 60px; height: 510px; padding: 0 10px; overflow: auto; } .faq-input{ font-weight: 700; display: inline-block; height: 34px; width: 86%; padding: 6px 12px; padding-right: 5px; font-size: 14px; line-height: 1.42857; border: 1px solid #ddd; border-radius: 0; box-shadow: rgba(0, 0, 0, 0.075) 0px 0px 0px inset; cursor: pointer; transition: all ease-in-out 400ms; } .faq-subject-holder{ padding: 5px; background: #f88d21; } .faq-subject{ width: 50%; border: 1px solid rgb(248, 141, 33); background: rgb(248, 141, 33); color: #fff; text-transform: uppercase; } .faq-question-holder{ padding: 5px; border: 1px solid #e3e3e3; border-top: 0px; border-bottom: 0px; position: relative; } .faq-answers-holder{ padding: 5px; border: 1px solid #e3e3e3; border-top: 0px; position: relative; } .faq-input:focus, .faq-input:active, .faq-answers:focus, .faq-answers:active{ background: #fff; color: #000; outline: 0px !important; } .faq-set{ border-bottom: 1px solid #e3e3e3; } .faq-answers{ width: 97%; border-radius: 0; min-height: 20px; padding: 10px; margin-bottom: 10px; margin-left: 25px; border: 1px solid #e3e3e3; } .add-faq-set, .del-faq { color: #fff; float: right; margin: 6px; padding: 4px; cursor: pointer; } .del-set{ padding: 10px 0px 0px 10px; width: 32px; height: 34px; background: rgb(230, 86, 86); color: #fff; margin-left: -4px; float: none; cursor: pointer; transition: all ease-in-out 400ms; } .del-set:hover{ background: rgb(236, 100, 100); } .faq-answers-save{ padding: 10px 4px 4px 12px; width: 36px; height: 34px; cursor: pointer; margin-left: -4px; background: #247ac3; color: #fff; transition: all ease-in-out 400ms; } .faq-answers-save:hover{ background: #0e8bf5; }
.inventory-toolbar{ background-color: #fff; -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.18); box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.18); padding: 5px 15px; } .overview{ margin-top: 50px; text-align: center; padding: 15px; } .overview .icon{ width: 100px; height: 100px; display: inline-block; border-radius: 50%; background: #888; color: #fff; font-size: 57px; padding: 8px 0px; margin-bottom: 20px; } .overview ul{ font-size: 1.2em; } .stat-container{ padding: 15px; } .nano-s { overflow: auto; } #chartContainer, .stat-main{ -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.18); box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.18); background-color: #fff; } .canvasjs-chart-credit{ display: none !important; } .schedule-detail .head-title{ text-align: center; text-transform: uppercase; margin: 0; font-family: 'Time new roman' } .schedule-detail .head-hr{ width: 30%; margin-left: 35%; border-style: dashed; margin-top: 7px; } .no-border td{ border: 0px !important; } .no-border td.td-title{ } .no-border td.td-content{ font-weight: bold; }

.widget.widget-stats { position: relative; } .widget { border-radius: 3px; margin-bottom: 20px; color: #fff; padding: 15px; overflow: hidden; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.41); } .bg-green { background: #00acac !important; } .bg-blue { background: #348fe2 !important; } .bg-purple { background: #727cb6 !important; } .bg-red { background: #DF3D39 !important; } .widget-stats .stats-icon { font-size: 42px; height: 56px; width: 56px; text-align: center; line-height: 56px; margin-left: 15px; color: #fff; position: absolute; right: 15px; top: 15px; opacity: .2; filter: alpha(opacity = 20); } .widget-stats .stats-link a { display: block; margin: 15px -15px -15px; padding: 7px 15px; background: rgba(0, 0, 0, .2); text-align: right; color: #ddd; font-weight: 300; text-decoration: none; } .widget-stats .stats-link a:hover { background: rgba(0, 0, 0, .4); color: #fff; } .widget-stats .stats-info h4 { font-size: 14px; margin: 5px 0; color: #fff; } .widget-stats .stats-info p { font-size: 30px; font-weight: 700; margin-bottom: 0; } .well{ padding: 10px; min-height: 112px; } .canvasjs-chart-credit{ display: none !important; } .table.table-none-border>tbody>tr>td{ border: none; } .table.table-none-padding>tbody>tr>td{ padding: 0; } .select2-selection { height: 36px !important; border-color: #ccc !important; box-shadow: inset 0 1px 1px rgba(0,0,0,.075); } .select2-selection__rendered { padding-top: 3px; } .select2-selection__choice { height: 24px !important; padding-top: 1px !important; } .select2-selection__arrow { height: 34px !important; width: 25px !important; } .select2-container--open .select2-dropdown--below, .select2-container--open .select2-dropdown--above { box-shadow: 0 6px 12px rgba(0,0,0,.175); } .select2-search__field { transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s; } .touchspin { text-align: right; border-right: none; } .panel-inno { border-top: none; border-top-left-radius: 0px; border-top-right-radius: 0px; } .panel-inno .panel-heading { background-color: #fff; } .nano>.nano-pane>.nano-slider { background: #337ab7; } .panel-default .nano .nano-content table>tbody>tr>td:nth-child(2) {} .panel-default .nano .nano-content table>tbody>tr>td:nth-child(3) { text-align: center; } .panel-default .nano .nano-content table>tbody>tr>td:nth-child(4) { text-align: center; } .block-header { border-bottom: 2px solid #337ab7; height: 35px; margin: 0px 10px; color: #337ab7; text-transform: uppercase; margin-bottom: 30px; margin-top: 10px; } .block-header h3 { background: white; display: inline-block; padding: 0px 15px; margin-left: 70px; } .more_detail{ color: #fff; float: right; font-size: 18px; }

.text-red{ color: red; }
.text-blue{ color: blue; }
.text-green{ color: green; }
.text-orange{ color: orange; }
.text-purple{ color: purple; }
.bg-purple-row{ background-color: purple !important; color: #fff }
.bg-purple-row:hover{ color: #000 }
.text-bold{ font-weight: bold; }
.label-purple { background-color: purple; }
.btn-add { transition: .2s; padding: 10px 12px; border-radius: 50%; background: #52b940; margin-left: 5px; margin-bottom: 5px; border: 1px solid #3cce00; cursor: pointer; }
.btn-add:hover { background: #56d240; border: 1px solid #45ef00; }
.over-img{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-color: #ffffff00;
    margin-bottom: 10px;
    background-position: center;
    background-size: contain;
    display: inline-block;
}
.border-orange{
    border: 3px solid orange;
}
.border-gbrown{
    border: 3px solid #50b748;
}
.border-simon{
    border: 3px solid #da00da;
}
.border-image {
    border: 3px solid #37a8d6;
}
.border-light-blue{
    border: 3px solid #64c5b1;
}
#event_fast_search::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    color: #000;
    opacity: 0.8;
    text-transform: uppercase;
}
#event_fast_search::-moz-placeholder { /* Firefox 19+ */
    color: #000;
    opacity: 0.8;
    text-transform: uppercase;
}
#event_fast_search:-ms-input-placeholder { /* IE 10+ */
    color: #000;
    opacity: 0.8;
    text-transform: uppercase;
}
#event_fast_search:-moz-placeholder { /* Firefox 18- */
    color: #000;
    opacity: 0.8;
    text-transform: uppercase;
}
@keyframes placeHolderShimmer{
    0%{
        background-position: -468px 0
    }
    100%{
        background-position: 468px 0
    }
}
.linear-background {
    animation-duration: 1s;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
    animation-name: placeHolderShimmer;
    animation-timing-function: linear;
    background: #f6f7f8;
    background: linear-gradient(to right, #eeeeee 8%, #dddddd 18%, #eeeeee 33%);
    background-size: 1000px 104px;
    height: 338px;
    position: relative;
    overflow: hidden;
}
.btn-notif{
    position: relative;
}
.btn-notif sup{
    position: absolute;
    top: -.3em;
    background: red;
    font-size: 100%;
    font-weight: bold;
    color: #fff;
    padding: 8px 5px;
    border-radius: 4px;
}
/*.app-menu {
    background-color: #f0ad4e;
    border-bottom: 1px solid #da9c45;
}
.navbar-default .navbar-brand {
    color: #fff;
}
.navbar-inverse .navbar-brand {
    color: #eaeaea;
}
.app-sidebar {
    background-color: #fbead3;
}
.app-navi>li>a {
    color: #4a4a4a;
}
.app-navi>li.header {
    color: #444;
}*/
</style>