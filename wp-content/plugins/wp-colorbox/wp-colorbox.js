jQuery(document).ready(function($) {
    $(".wp-colorbox-image").colorbox({maxWidth:"99%", maxHeight: "99%"});
    $(".wp-colorbox-youtube").colorbox({iframe:true, width:"90%", height:"90%", maxWidth:640, maxHeight: 480});
    $(".wp-colorbox-vimeo").colorbox({iframe:true, width:"90%", height:"90%", maxWidth:640, maxHeight: 480});
    $(".wp-colorbox-iframe").colorbox({iframe:true, fastIframe:false, width:"97%", height:"90%"});
    $(".wp-colorbox-inline").colorbox({inline:true, width:"97%", maxWidth:"90%"});
});


