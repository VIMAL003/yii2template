
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

$('.block-content').on('click',function(){
   if(!$(this).hasClass('addtocart')) {
       $(this).addClass('addtocart');
       var dataLi = "<li id='"+$(this).attr('id')+"'>"+ $(this).attr('id') +"</li>";
       var stallUi = $("#orderStallList");
       stallUi.html('');
       stallUi.append(dataLi);
   }else{
       $(this).removeClass('addtocart');
       var dataRemove = $(this).attr('id');
       //$('#'+dataRemove).remove();
   }
});
