
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
$('document').ready(function(){
     $("#orderStallList").html('');
     var i = 0;
     $('.block-content').on('click',function(){
        var stallUi = $("#orderStallList");

        if(!$(this).hasClass('addtocart')) {
            $(this).addClass('addtocart');
             var datainput = "<input type='hidden' name='selectedStall[]' value='"+$(this).attr('id')+"'/>"
             var dataLi = "<li id='select_"+$(this).attr('id')+"' class='displayStall'>"+ $('#label_'+$(this).attr('id')).html() + datainput+"</li>";
             stallUi.append(dataLi);
             i++;
             $('#selectStallVal').val(i);

        }else{
            i--;
            $('#selectStallVal').val(i);
            $(this).removeClass('addtocart');
            $('#select_'+$(this).attr('id')).remove();

        }
    });
});

