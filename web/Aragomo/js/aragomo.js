$(document).ready(function() {
});
function click_2(){
	$(".modal-body").find('.photo').on("click", function() {
		$('#myModal').modal('hide');
		console.log($data_modal);
		var sel_data = '';
		if($data_modal.lenght==0){
			sel_data = $data_modal;
		}else{
			sel_data = $data_modal[$(this).attr("data-id")];
		}
		console.log($('#myModal2').find(".modal-body-left img"));
		$('#myModal2').find(".modal-body-left").removeAttr("style");
		console.log(sel_data['thumbnail']);
		if(sel_data['thumbnail']==null || sel_data['thumbnail']==''){
			$('#myModal2').find(".modal-body-left").css({
				"clear": "both",
				"margin-right": "20px",
				"overflow": "hidden",
				"position": "relative",
				"width": 0
			});
		}else{
			$('#myModal2').find(".modal-body-left img").attr('src',sel_data['thumbnail']);
		}
		$('#myModal2').find(".modal-body-right h2").html(sel_data['author']);
		$('#myModal2').find(".modal-body-right p").html(sel_data['description']);
		$('#myModal2').modal('show');
	});
	$(".second-close").on("click", function() {
		$('#myModal').modal('show');
	});
}