jQuery(document).ready(function($){

        /**
         * list-user page status update via ajax.
         * @param {Number} a
         * @param {Number} b
         * @return Updates the status html.
         */
        $('.delete').click(function(){
                return false;
		var parentTr = $(this).parent('tr');
		$.ajax({
				type: "POST",
				url : "update-status",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  },
				data : {db_table : db_table, id : id, status : status},
				success : function(data){
					if(labelSpan.hasClass('label-important'))
					{
						elem.html('<span id="active-'+id+'" class="label label-success">Active</span>');
					} else {
						elem.html('<span id="deactive-'+id+'" class="label label-important">In-Active</span>');
					}

				}
			},"json");
	});


	/**
	 * list-user page status update via ajax.
	 * @param {Number} a
	 * @param {Number} b
	 * @return Updates the status html.
	 */


	/**
	 * add access-rights page.
	 * @param role_id
	 * @return Updates the status html.
	 */

	 $(document).on('click','.access-roles',function(){
		var role_radio_button = $(this);
		var role_id = $(this).attr('role-id');
		$.ajax({
					type: "POST",
					url : "get-role-details",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
					data : {role_id : role_id},
					beforeSend : function(data){
						$('.permission-table-contents').html('');
						 $("#loader").show();
					},
					success : function(data){
						 setTimeout(function() {$("#loader").hide();
						 $('.permission-table-contents').html(data);}, 2000);
					}
				},"json");
	});

	/**
	 * save access rights via ajax.
	 * @param none
	 * @return Updates the status html.
	 */
	$(document).on('click', '#save-access-rights',function(){

		var data = $( '#access-rights-form' ).serializeArray();
             console.log(data);
		data = data.concat(
            jQuery('#access-rights-form input[type=checkbox]:not(:checked)').map(
                    function() {
                        return {"name": this.name, "value": 0}
                    }).get()
    );
		var role_id = $('#role_id').val();
		$.ajax({
					type: "POST",
					url : "update-access-rights",
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
					data : {data : data, role_id : role_id},
					beforeSend : function(data){

					},
					success : function(data){
						$("#success-message").slideDown("slow");
						setTimeout(function() {$("#success-message").slideUp("slow");}, 3000);
					}
				},"json");

	});


	// hack for making the Bootstrap checkboxes work uploaded via ajax.
	$(document).on('click','.switch-input',function(){
		console.log(this);
		var checked = $(this).parents('span:first').hasClass('checked');
		var classs = $(this).parents('span:first').attr('class');
		console.log('checked '+checked+ ' class ');
		if(checked)
		{
			$(this).parents('span').removeClass('checked');
			//$(this).parents('span').find('input[type="hidden"]').val('0');
		}
		else
		{
			$(this).parents('span').addClass('checked');
			//$(this).parents('span').find('input[type="hidden"]').val('1');
		}
	});

});

$(document).ready(function(){

	/**
	 * delete alert box
	 * @param {Number} a
	 * @param {Number} b
	 * @return Updates the status html.
	 */
	$('a[data-confirm]').click(function(ev) {
		var href = $(this).attr('href');
		if (!$('#dataConfirmModal').length) {
			$('#dataConfirmModal').show();
		}
		$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
		$('#dataConfirmOK').attr('href', href);
		$('#dataConfirmModal').modal({show:true});
		return false;
	});

});

function changeStatus(obj,type,status,id, db_table)
{     
    var labelSpan = $(obj).find('span');
    var parentDiv=$(obj).parent('div');

    $.ajax({
        type: "POST",
		url : "update-status",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  },
        data : {
            db_table : db_table, 
            id : id, 
            status : status,
            type:type
        },
        success : function(data){
            if(data == 1)
            {
                if($(obj).hasClass('label-important'))
                {
                    parentDiv.html('<span onclick="changeStatus(this,\''+type+'\',0, '+id+',\''+db_table+'\' )" class="label label-success">Active</span>');
                    $('#access-success').slideUp("slow");
                    $('#access-success').slideDown("slow");
                } else {
                    parentDiv.html('<span onclick="changeStatus(this,\''+type+'\',1, '+id+',\''+db_table+'\' )" class="label label-important">In-Active</span>');
                    $('#access-success').slideUp("slow");
                    $('#access-success').slideDown("slow");
                }
            }
            else
            {
                $('#access-error').slideUp("slow");
                $('#access-error').slideDown("slow");
            }
            
        }
    },"json");
}

function updateAccessLevel(user_id, obj)
{     
    var role_id=obj.value;
    $.ajax({
        type: "POST",
		url : "update-access-level",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  },
        data : {
            role_id : role_id, 
            user_id : user_id
        },
        success : function(data){
            if(data > 0)
            {
                $(obj).val(data);
                $('#user-role-edit-error').slideUp("slow");
                $('#user-role-edit-success').slideUp("slow");
                $('#user-role-edit-success').slideDown("slow");
            }
            else
            {
                $('#user-role-edit-success').slideUp("slow");
                $('#user-role-edit-error').slideUp("slow");
                $('#user-role-edit-error').slideDown("slow");
            }
        }
    },"json");
}

function modalButtonOnClick(id, db_table, type)
{
    $('#dataConfirmOK').attr('onclick','deleteRecord('+id+',\''+db_table+'\' ,\''+type+'\')');
}

function deleteRecord(id,db_table,type)
{     
    $.ajax({
        type: "POST",
		url : "delete-record",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  },
        data : {
            db_table : db_table, 
            id : id, 
            type:type
        },
        success : function(data){
            if(data == 1)
            {
                $('#tr-'+id).slideUp("slow");
                $('#delete-success').slideUp("slow");
                $('#delete-success').slideDown("slow");
            }
            else
            {
                $('delete-error').slideUp("slow");
                $('#delete-error').slideDown("slow");
            }
        }
    },"json");
}
