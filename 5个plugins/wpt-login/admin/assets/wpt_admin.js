jQuery(document).ready(($) => {

	$("#wpt_bg_video_btn").on('click',(e) => {

		const frame = wp.media({
			title:'SELECT',
			button:{
				text:'SELECT'
			},
			library:{
				type:['video','image']
			}
		}); 

		frame.on('select',function(){
			const attachment = frame.state().get('selection').first().toJSON(); 

			$("#wpt_admin_bg_video").val(attachment.url); 
		});

		frame.open();

	}); 

});