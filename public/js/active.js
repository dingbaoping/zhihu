$(function(){
	// $(".select-active").find('li').each(function(){
 //            // $(this).removeClass('active');
 //            $(this).click(function(){

 //                console.log($(this).attr('data-id'));
 //                $(this).addClass('active');
 //            })

 //        })
	$(".select-active").find('li').each(function(){
            // 
            var _this=$(this);
            $(this).click(function(){
            	_this.removeClass('active');
                console.log($(this).attr('data-id'));
                $(this).addClass('active');
            })

        })
})