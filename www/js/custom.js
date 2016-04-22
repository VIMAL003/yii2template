
// frontend realted css added start
$(window).load(function () {
        if(location.hash.slice(1) && location.hash.slice(1) != "menu-1"){
            var divId = location.hash.slice(1);
            //var id =  $("#"+location.hash.slice(1)).attr('class');
            var id = divId.split('-');
            $('a.active').removeClass('active');
            $("#show-"+id[1]).addClass('active');
            $("#menu-container .content").slideUp('slow');
            $("#menu-container #menu-"+id[1]).slideDown('slow');		
            $("#menu-container .homepage").slideUp('slow');
            return false;
        }
            
});
