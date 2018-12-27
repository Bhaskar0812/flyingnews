var base_url = $('#tl_admin_main_body').attr('data-base-url');

$(".compress").hover(function(){
  $(".showImage").show();

});

function show_loader(){
    $('#tl_admin_loader').show();
}

function hide_loader(){
    $('#tl_admin_loader').hide();
}

$( document ).ready(function() {

   $('#tl_admin_loader').fadeOut();
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#upload-photo").change(function(){
    readURL(this);
});

$("#editProfile").submit(function(e){
  e.preventDefault();
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"editSubmit",
    cache:false,
    contentType: false,
    processData: false,
    data: new FormData(this), 
    beforeSend: function () { 
      show_loader(); 
    },
    success:function(res){
    hide_loader();
        var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
           });
          }
          if(obj.messages.success){
            toastr.remove()
            toastr.clear()
           toastr.success(obj.messages.success);
            var surl = 'profileView'; 
            window.setTimeout(function() { window.location = surl; }, 2000);
          } 
          if(obj.messages.unsuccess){
            toastr.remove()
            toastr.clear()
             toastr.error(obj.messages.unsuccess);
          }
          if(obj.messages.imageerror){
            toastr.remove()
            toastr.clear()
           toastr.error(obj.messages.imageerror);
          }
    }
  });
});

$("#forgetPass").submit(function(e){
  $("#sub_btn").html('wait..'); 
  $("#sub_btn").prop('disabled', true); 
  e.preventDefault();
  $(".error").html(''); 
// do something in the background
  $.ajax({
    type:"POST",
    url:"admin/forgetPassword",
    data: $(this).serialize(), 
    datatype:"JSON",
    success:function(res){
        var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
           $("#sub_btn").html('Success');
           });
             $("#sub_btn").html('Reset'); 
            $("#sub_btn").prop('disabled', false);
          }
          if(obj.messages.success){
             $.toaster({ priority : 'success', message : obj.messages.success});
              var surl = 'admin'; 
             window.setTimeout(function() { window.location = surl; }, 2000);
          } 
          if(obj.messages.unsuccess){
            $("#sub_btn").html('Reset'); 
            $("#sub_btn").prop('disabled', false);
           $('#unsuccess').html(obj.messages.unsuccess);
          }
    }
  });

});

$("#setPass").submit(function(e){
  $("#sub_btn").html('wait..'); 
  e.preventDefault();
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"../../setPassReset",
    data: $(this).serialize(), 
    datatype:"JSON",
    success:function(res){
        var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
            $("#sub_btn").html('Success');
            window.setTimeout(function() {  $("#sub_btn").html('Submit'); }, 1000);
           });
          }
          if(obj.messages.success){
             toastr.success(obj.messages.success);
              var surl = '../../'; 
             window.setTimeout(function() { window.location = surl; }, 2000);
          } 
          if(obj.messages.unsuccess){
           $('#unsuccess').html(obj.messages.unsuccess);
          }
    }
  });
});

$("#setPassUser").submit(function(e){
  $("#sub_btn").html('wait..'); 
  e.preventDefault();
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"../../setPassReset",
    data: $(this).serialize(), 
    datatype:"JSON",
    success:function(res){
        var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
            $("#sub_btn").html('Success');
            window.setTimeout(function() {  $("#sub_btn").html('Submit'); }, 1000);
           });
          }
          if(obj.messages.success){
             toastr.success(obj.messages.success);
              var surl = '../../passwordSet'; 
             window.setTimeout(function() { window.location = surl; }, 2000);
          } 
          if(obj.messages.unsuccess){
           $('#unsuccess').html(obj.messages.unsuccess);
          }
    }
  });
});

function logout(){
  swal({
  title: "Logout ! Are you sure?",
  icon: base_url+"/backend_assets/logo/150.png",
  showCancelButton: true,
  buttons: true,
  dangerMode: false,
  allowOutsideClick: true,
})
.then((willDelete) => {
  if (willDelete) {
    $.toaster({ priority : 'success', message : 'Successfully Logged Out..'});
    var surl = base_url+'admin/logout'; 
    window.setTimeout(function() { window.location = surl,500;});
  }
});
}

var deleteImg = function(id) {
  var image = $("#image").val();
  if(image){
  bootbox.confirm({
  message: "Are you sure, you want to delete your image.?",
  buttons: {
    confirm: {
      label: 'Ok',
      className: 'btn-primary'
    },
    cancel: {
      label: 'Cancel',
      className: 'btn-danger'
    }
  },
  callback: function (result) {
    if (result) {
      show_loader();
   $.ajax({
      type:"POST",
      url:'deleteImg',
      data:{'id':id,'image':image},
      datatype:"json",
      success: function(res){
        var obj = JSON.parse(res);
        if(obj.success){
          var surl ='profileView'; 
          window.setTimeout(function() { window.location = surl; }, 500);
        }
      }
    });
  } 
}
});
}else{
  toastr.error("Image not found.");
}
}

$("#changePass").submit(function(e){ //change admin password function...
      e.preventDefault();
       $(".error").html('');
        $.ajax({
          type:"POST",
          url:"changePassword",
          data:$(this).serialize(),
          datatype:"json",
          beforeSend: function () { 
            show_loader(); 
          },
          success:function(res){
            hide_loader();
            var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
           });
          }
          if(obj.messages.success){
           $("#success").html(obj.messages.success); 
            toastr.success(obj.messages.success);
            var surl = 'admin/logout'; 
            window.setTimeout(function() { window.location = surl; }, 2000);
          }
          if(obj.messages.oldPaas){
            toastr.error(obj.messages.oldPaas);
          }
        }
    });
});

$(function () {
    var user_list = $('#userList').DataTable({ 
    "processing": true, //Feature control the processing indicator. 
    "serverSide": true, //Feature control DataTables' servermside processing mode. 
    "order": [], //Initial no order. 
    "paging": true, "lengthChange": false, 
    "searching": true, 
    "ordering": true, 
    "info": true, 
    "autoWidth": false, 
    "blengthChange": false, 
    "iDisplayLength" :10, 
    "bPaginate": true, 
    "bInfo": true, 
    "bFilter": false, 
  
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+"admin/users/getUsersList",
        "type": "POST",
        "dataType": "json",
        "dataSrc": function (jsonData) { 
          return jsonData.data;
        }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { orderable: false, targets: -1 }, 
    ]
});
});

$(function () {
    var user_list = $('#myPost').DataTable({ 
    "processing": true, //Feature control the processing indicator. 
    "serverSide": true, //Feature control DataTables' servermside processing mode. 
    "order": [], //Initial no order. 
    "paging": true, "lengthChange": false, 
    "searching": true, 
    "ordering": true, 
    "info": true, 
    "autoWidth": false, 
    "blengthChange": false, 
    "iDisplayLength" :10, 
    "bPaginate": true, 
    "bInfo": true, 
    "bFilter": false, 
  
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+"admin/users/myPosts/"+$('#users_id').val(),
        "type": "POST",
        "dataType": "json",
        "dataSrc": function (jsonData) { 
          return jsonData.data;
        }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { orderable: false, targets: -1 }, 
    ]
});
});

$(function () {
  //alert('hello');
    var user_list = $('#groupList').DataTable({ 
    "processing": true, //Feature control the processing indicator. 
    "serverSide": true, //Feature control DataTables' servermside processing mode. 
    "order": [], //Initial no order. 
    "paging": true, "lengthChange": false, 
    "searching": true, 
    "ordering": true, 
    "info": true, 
    "autoWidth": false, 
    "blengthChange": false, 
    "iDisplayLength" :10, 
    "bPaginate": true, 
    "bInfo": true, 
    "bFilter": false, 
  
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+"admin/groups/getGroupList",
        "type": "POST",
        "dataType": "json",
        "dataSrc": function (jsonData) { 
          return jsonData.data;
        }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { orderable: false, targets: -1 }, 
    ]
});
});

$(function () {
  //alert('hello');
    var user_list = $('#commentList').DataTable({ 
    "processing": true, //Feature control the processing indicator. 
    "serverSide": true, //Feature control DataTables' servermside processing mode. 
    "order": [], //Initial no order. 
    "paging": true, "lengthChange": false, 
    "searching": true, 
    "ordering": true, 
    "info": true, 
    "autoWidth": false, 
    "blengthChange": false, 
    "iDisplayLength" :10, 
    "bPaginate": true, 
    "bInfo": true, 
    "bFilter": false, 
  
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+"admin/comments/getCommentList",
        "type": "POST",
        "dataType": "json",
        "dataSrc": function (jsonData) { 
          return jsonData.data;
        }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { orderable: false, targets: -1 }, 
    ]
});
});

$(function () {
  //alert('hello');
    var user_list = $('#categoryList').DataTable({ 
    "processing": true, //Feature control the processing indicator. 
    "serverSide": true, //Feature control DataTables' servermside processing mode. 
    "order": [], //Initial no order. 
    "paging": true, "lengthChange": false, 
    "searching": true, 
    "ordering": true, 
    "info": true, 
    "autoWidth": false, 
    "blengthChange": false, 
    "iDisplayLength" :10, 
    "bPaginate": true, 
    "bInfo": true, 
    "bFilter": false, 
  
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+"admin/Categories/getCategoryList",
        "type": "POST",
        "dataType": "json",
        "dataSrc": function (jsonData) { 
          return jsonData.data;
        }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { orderable: false, targets: -1 }, 
    ]
});
});

$(function () {
    var user_list = $('#postList').DataTable({ 
    "processing": true, //Feature control the processing indicator. 
    "serverSide": true, //Feature control DataTables' servermside processing mode. 
    "order": [], //Initial no order. 
    "paging": true, "lengthChange": false, 
    "searching": true, 
    "ordering": true, 
    "info": true, 
    "autoWidth": false, 
    "blengthChange": false, 
    "iDisplayLength" :10, 
    "bPaginate": true, 
    "bInfo": true, 
    "bFilter": false, 
  
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url+"admin/users/postList",
        "type": "POST",
        "dataType": "json",
        "dataSrc": function (jsonData) { 
          return jsonData.data;
        }
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { orderable: false, targets: -1 }, 
    ]
});
});

function model(){
  $('#model').modal("show");
}

var statusFn = function (table, field, id, status,dataitem,showmsg) {
var message = "";
if (status == '1') {
    message = "inactive";
    tosMsg = 'Successfully inactivated';
} else if (status == '0') {
    message = "active";
    tosMsg = 'Successfully activated';
}

bootbox.confirm({
  message: "Are you sure, you want to " + message + " this "+dataitem+" ?",
  buttons: {
    confirm: {
      label: 'Ok',
      className: 'btn-primary'
    },
    cancel: {
      label: 'Cancel',
      className: 'btn-danger'
    }
  },
  callback: function (result) {
    if (result) {
      show_loader();
      var url = base_url+"admin/activeInactive";
      $.ajax({
        method: "POST",
        url: url,
        data: {id: id, id_name: field, table: table, status: status},
        datatype:"JSON",
        success: function (response) {
          hide_loader();
          var obj = JSON.parse(response);
          if (response) {
            if(obj.messages.activated){
              toastr.success(showmsg +' '+ obj.messages.activated);
              window.setTimeout(function () {
              window.location.reload();
             }, 2000);}else{
             toastr.error(showmsg +' '+ obj.messages.inactivated);
              window.setTimeout(function () {
              window.location.reload();
             }, 2000);

              }  
          }
        },
          error: function (error, ror, r) {
              bootbox.alert(error);
          },
      });
    }
}
});
}

var deleteFn = function (table, field, id, status,dataitem,error) {

var errorMessage = status;
var message = "";
bootbox.confirm({
  message: "Are you sure, you want to delete this"+' '+status+"?",
  buttons: {
    confirm: {
      label: 'Ok',
      className: 'btn-primary'
    },
    cancel: {
      label: 'Cancel',
      className: 'btn-danger'
    }
  },
  callback: function (result) {
    if (result) {
      show_loader();
      var url = base_url+"admin/deleteBusinessData";
      $.ajax({
        method: "POST",
        url: url,
        data: {id: id, id_name: field, table: table, status: status},
        datatype:"JSON",
        success: function (response) {
          hide_loader();
          var obj = JSON.parse(response);
          if (response) {
            if(obj.messages.delete){
             $.toaster({ priority : 'warning', title : 'Notice', message : errorMessage+' '+ "deleted successfully"});
              window.setTimeout(function () {
              window.location.reload();
             }, 2000);}else{
              $.toaster({ priority : 'danger', title : 'Notice', message : obj.messages.notDelete});
              window.setTimeout(function () {
              window.location.reload();
             }, 2000);

              }  
          }
        },
          error: function (error, ror, r) {
              bootbox.alert(error);
          },
      });
    }
}
});
}

var openModal = function (controller) { 
  alert(controller);
  $.ajax({
   url: base_url + controller + "/openModal",
   type: 'POST',
   success: function (data, textStatus, jqXHR) {
    $('#form-modal-box').html(data);
    $("#commonModal").modal('show');
    } 
  }); 
}

var editFn = function (fol, ctrl, method, id) {
 //alert(method);
 $.ajax({
 url: base_url + fol + "/" + ctrl + "/" + method,
//alert(url);
 type: 'POST',
 data: {'id': id},
 success: function (data, textStatus, jqXHR) {
 $('#form-modal-box').html(data);
 $("#commonModal").modal('show');
 //addFormBoot();
 //hide_loader()
 }
 });
 }

$(function () {
    "use strict";
    
    $(".popup img").click(function () {
        var $src = $(this).attr("src");
        $(".show").fadeIn();
        $(".img-show img").attr("src", $src);
    });
    
    $("span, .overlay").click(function () {
        $(".show").fadeOut();
    });
    
});

$(".compress").hover(function(){
  $(".image").show();

});







/* code for post detail load more list*/
function get_comment_on_group(is_load_more){
 
   var scroll_loader = $('.scroll_loader'),
   offset = scroll_loader.attr('data-offset'),
   list_cont = $('.customerData'),
   //alert(offset);
   load_more_btn = $('.load_more_customer').parent();
  /* gender = $('#search_gender').val();
   fullNm = $('#search_name').val();
   data: {'offset': offset,'gender':gender,'fullName':fullNm},
   fullNm = fullNm.trim();*/
   if(is_load_more ==0){

   offset = 0;
   
   
   }
   
   //alert($('#groupcomnt').val());
   $.ajax({
   type: "POST",
   url: base_url+"admin/groups/get_comment_on_group/"+$('#groupcomnt').val(),
   dataType: "json",
   data: {'offset': offset},
   beforeSend: function () {
   scroll_loader.show();
   },
   complete:function(){
   scroll_loader.hide();
   },
   success: function (data, textStatus, jqXHR) {
   if(is_load_more ==0){
   list_cont.html(''); 
   } 
   if (data.status == 1){
   
   list_cont.append(data.html);
   
   scroll_loader.attr('data-is-next',data.is_next);
   scroll_loader.attr('data-offset',data.new_offset); //set new offset
   
   //remove load more button. if is_next is 0
   
   if(data.is_next===0){
   //console.log('hide');
   load_more_btn.hide();
   }else{
   //console.log('welcome');
   load_more_btn.show();
   }
   } else if(data.status == -1) {
   //session exipred
   toastr.error(data.msg);
   if(data.url){
   window.setTimeout(function () {
   window.location = data.url;
   }, 2000);
   }
   }else{
   toastr.error(data.msg);
   }
   },
   
   });
}

//load more_comment click handler
// $('body').on('click','.load_more_customer', function(){
//  var _that = $(this);
//  _that.parent().hide();
//  get_comment_on_group(1); //get customer list 
// });

//load list only if container element exist
if($('.customerData').length>0){
 get_comment_on_group(); //get initial customer list on page 
}



/* code for post detail load more list*/
function get_comment_on_post(is_load_more){
 
 var scroll_loader = $('.scroll_loader'),
 offset = scroll_loader.attr('data-offset'),
 list_cont = $('.postComentData'),
 //alert(offset);
 load_more_btn = $('.load_more_customer').parent();
/* gender = $('#search_gender').val();
 fullNm = $('#search_name').val();
 data: {'offset': offset,'gender':gender,'fullName':fullNm},
 fullNm = fullNm.trim();*/
 if(is_load_more ==0){

 offset = 0;
 
 
 }
 
 //alert($('#qwerty').val());
 $.ajax({
 type: "POST",
 url: base_url+"admin/users/get_comment_on_post/"+$('#qwerty').val(),
 dataType: "json",
 data: {'offset': offset},
 beforeSend: function () {
 scroll_loader.show();
 },
 complete:function(){
 scroll_loader.hide();
 },
 success: function (data, textStatus, jqXHR) {
 if(is_load_more ==0){
 list_cont.html(''); 
 } 
 if (data.status == 1){
 
 list_cont.append(data.html);
 
 scroll_loader.attr('data-is-next',data.is_next);
 scroll_loader.attr('data-offset',data.new_offset); //set new offset
 
 //remove load more button. if is_next is 0
 
 if(data.is_next===0){
 //console.log('hide');
 load_more_btn.hide();
 }else{
 //console.log('welcome');
 load_more_btn.show();
 }
 } else if(data.status == -1) {
 //session exipred
 toastr.error(data.msg);
 if(data.url){
 window.setTimeout(function () {
 window.location = data.url;
 }, 2000);
 }
 }else{
 toastr.error(data.msg);
 }
 },
 
 });
}
//load more_comment click handler
$('body').on('click','.load_more_customer', function(){
 var _that = $(this);
 var check = _that.data('check');
 if(check=='post'){
  get_comment_on_post(1); //get customer list 
 }else if(check=='group'){
  get_comment_on_group(1); //get customer list 
 }
 _that.parent().hide();
});

//load list only if container element exist
if($('.postComentData').length>0){
 get_comment_on_post(); //get initial customer list on page 
}


/* code for group members load more list*/
function get_group_likes(is_load_more){
 
 var scroll_loader_likes = $('.scroll_loader_likes'),
 offset = scroll_loader_likes.attr('data-offset'),
 list_cont = $('.groupLikes'),
 //alert(offset);
 load_more_btn = $('.load_more_group_likes').parent();
/* gender = $('#search_gender').val();
 fullNm = $('#search_name').val();
 data: {'offset': offset,'gender':gender,'fullName':fullNm},
 fullNm = fullNm.trim();*/
 if(is_load_more ==0){

 offset = 0;
 
 
 }
 
 //alert($('#groupcomnt').val());
 $.ajax({
 type: "POST",
 url: base_url+"admin/groups/get_group_likes/"+$('#groupcomnt').val(),
 dataType: "json",
 data: {'offset': offset},
 beforeSend: function () {
 scroll_loader_likes.show();
 },
 complete:function(){
 scroll_loader_likes.hide();
 },
 success: function (data, textStatus, jqXHR) {
 if(is_load_more ==0){
 list_cont.html(''); 
 } 
 if (data.status == 1){
 
 list_cont.append(data.html);
 
 scroll_loader_likes.attr('data-is-next',data.is_next);
 scroll_loader_likes.attr('data-offset',data.new_offset); //set new offset
 
 //remove load more button. if is_next is 0
 
 if(data.is_next===0){
 //console.log('hide');
 load_more_btn.hide();
 }else{
 //console.log('welcome');
 load_more_btn.show();
 }
 } else if(data.status == -1) {
 //session exipred
 toastr.error(data.msg);
 if(data.url){
 window.setTimeout(function () {
 window.location = data.url;
 }, 2000);
 }
 }else{
 toastr.error(data.msg);
 }
 },
 
 });
}

//load more_comment click handler
$('body').on('click','.load_more_group_likes', function(){
 var _that = $(this);
 _that.parent().hide();
 get_group_likes(1); //get customer list 
});

//load list only if container element exist
if($('.groupLikes').length>0){
 get_group_likes(); //get initial customer list on page 
}


/* code for post likes load more list*/
function get_post_likes(is_load_more){
 
 var scroll_loader_likes_post = $('.scroll_loader_likes_post'),
 offset = scroll_loader_likes_post.attr('data-offset'),
 list_cont = $('.postLikes'),
 //alert(offset);
 load_more_btn = $('.load_more_post_likes').parent();
/* gender = $('#search_gender').val();
 fullNm = $('#search_name').val();
 data: {'offset': offset,'gender':gender,'fullName':fullNm},
 fullNm = fullNm.trim();*/
 if(is_load_more ==0){

 offset = 0;
 
 
 }
 
 //alert($('#groupcomnt').val());
 $.ajax({
 type: "POST",
 url: base_url+"admin/users/get_post_likes/"+$('#qwerty').val(),
 dataType: "json",
 data: {'offset': offset},
 beforeSend: function () {
 scroll_loader_likes_post.show();
 },
 complete:function(){
 scroll_loader_likes_post.hide();
 },
 success: function (data, textStatus, jqXHR) {
 if(is_load_more ==0){
 list_cont.html(''); 
 } 
 if (data.status == 1){
 
 list_cont.append(data.html);
 
 scroll_loader_likes_post.attr('data-is-next',data.is_next);
 scroll_loader_likes_post.attr('data-offset',data.new_offset); //set new offset
 
 //remove load more button. if is_next is 0
 
 if(data.is_next===0){
 //console.log('hide');
 load_more_btn.hide();
 }else{
 //console.log('welcome');
 load_more_btn.show();
 }
 } else if(data.status == -1) {
 //session exipred
 toastr.error(data.msg);
 if(data.url){
 window.setTimeout(function () {
 window.location = data.url;
 }, 2000);
 }
 }else{
 toastr.error(data.msg);
 }
 },
 
 });
}

//load more_comment click handler
$('body').on('click','.load_more_post_likes', function(){
 var _that = $(this);
 _that.parent().hide();
 get_post_likes(1); //get customer list 
});

//load list only if container element exist
if($('.postLikes').length>0){
 get_post_likes(); //get initial customer list on page 
}




/* code for group members load more list*/
function get_group_members(is_load_more){
 
     var scroll_loader_members = $('.scroll_loader_members'),
     offset = scroll_loader_members.attr('data-offset'),
     list_cont = $('.groupMemders'),
     //alert(offset);
     load_more_btn = $('.load_more_group_members').parent();
    /* gender = $('#search_gender').val();
     fullNm = $('#search_name').val();
     data: {'offset': offset,'gender':gender,'fullName':fullNm},
     fullNm = fullNm.trim();*/
     if(is_load_more ==0){

     offset = 0;
     
     
     }
     
     //alert($('#groupcomnt').val());
     $.ajax({
       type: "POST",
       url: base_url+"admin/groups/get_group_members/"+$('#groupcomnt').val(),
       dataType: "json",
       data: {'offset': offset},
       beforeSend: function () {
       scroll_loader_members.show();
       },
       complete:function(){
       scroll_loader_members.hide();
       },
       success: function (data, textStatus, jqXHR) {
       if(is_load_more ==0){
       list_cont.html(''); 
       } 
       if (data.status == 1){
       
       list_cont.append(data.html);
       
       scroll_loader_members.attr('data-is-next',data.is_next);
       scroll_loader_members.attr('data-offset',data.new_offset); //set new offset
       
       //remove load more button. if is_next is 0
       
       if(data.is_next===0){
       //console.log('hide');
       load_more_btn.hide();
       }else{
       //console.log('welcome');
       load_more_btn.show();
       }
       } else if(data.status == -1) {
       //session exipred
       toastr.error(data.msg);
       if(data.url){
       window.setTimeout(function () {
       window.location = data.url;
       }, 2000);
       }
       }else{
       toastr.error(data.msg);
       }
       },
    
     });
}

//load more_comment click handler
$('body').on('click','.load_more_group_members', function(){
 var _that = $(this);
 _that.parent().hide();
 get_group_members(1); //get customer list 
});

//load list only if container element exist
if($('.groupMemders').length>0){
 get_group_members(); //get initial customer list on page 
}



//CODE FOR UPDATE ABOUT US
  $(document).ready(function() {
    $("#editAboutUs").validate({
        rules: {
          pdf: {
                required: true,
            },
        } 
    });

  });

   
  $('#editAboutUs').submit(function(e){
    e.preventDefault();
    if ($('#editAboutUs').valid()==false) {
      toastr.error('Fillup required condtion');
      return false;
    }

    for (instance in CKEDITOR.instances) {
    CKEDITOR.instances[instance].updateElement();



    var formData = new FormData(this);
    $.ajax({
      type:"post",
      url:base_url+"/admin/aboutUsSub",
      data:formData,
      dataType:'json',
      cache:false,
      processData:false,
      contentType:false,
      beforeSend: function () { 
       show_loader(); 
       },
      success:function(response){
        hide_loader();
        if (response.status==1) {
          var delay = 2000;
          toastr.success(response.message);
          setTimeout(function(){
            window.location = 'aboutUs'
          },delay);

        }
         if (response.status==0) {
          toastr.error(response.message);
        }
      }
    });
  }
  });


  
  $(document).ready(function() {
    $("#editForm").validate({
        rules: {
          pdf: {
                required: true,
            },
        } 
    });

  });

   
  $('#editForm').submit(function(e){
    e.preventDefault();
    if ($('#editForm').valid()==false) {
      toastr.error('Fillup required condtion');
      return false;
    }
    var formData = new FormData(this);
    $.ajax({
      type:"post",
      url:base_url+"/admin/policySub",
      data:formData,
      dataType:'json',
      cache:false,
      processData:false,
      contentType:false,
      beforeSend: function () { 
       show_loader(); 
       },
      success:function(response){
        hide_loader();
        if (response.status==1) {
          var delay = 2000;
          toastr.success(response.message);
          setTimeout(function(){
            window.location = 'policy'
          },delay);

        }
         if (response.status==0) {
          toastr.error(response.message);
        }
      }
    });
  });



  $(document).ready(function() {
    $("#editFormAjax").validate({
        rules: {
          pdf: {
                required: true,
            },
        } 
    });

  });

   
  $('#editFormAjax').submit(function(e){
    e.preventDefault();
    if ($('#editFormAjax').valid()==false) {
      toastr.error('Fillup required condtion');
      return false;
    }
    var formData = new FormData(this);
    $.ajax({
      type:"post",
      url:base_url+"/admin/updateTermAndCondition",
      data:formData,
      dataType:'json',
      cache:false,
      processData:false,
      contentType:false,
      beforeSend: function () { 
       show_loader(); 
       },
      success:function(response){
        hide_loader(); 
        if (response.status==1) {
          var delay = 2000;
          toastr.success(response.message);
          setTimeout(function(){
            window.location = 'termAndCondition'
          },delay);

        }
         if (response.status==0) {
          toastr.error(response.message);
        }
      }
    });
  });




jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
            console.log(split_extension+'---'+file_name.split("."));
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg, jpeg, png');
            return false;
        }
        
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }

        $('.edit_img').addClass("imUpload");
});

jQuery('body').on('change', '.input_img3', function () {

    var file_name = jQuery(this).val(),
        fileObj = this.files[0],
        calculatedSize = fileObj.size / (1024 * 1024),
        split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
        ext = ["jpg", "png", "jpeg"];
    if (jQuery.inArray(split_extension, ext) == -1){
        $(this).val(fileObj.value = null);
        $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg,jpeg,png');
        return false;
    }
    if (calculatedSize > 5){
        $(this).val(fileObj.value = null);
        $('.ceo_file_error').html('File size should not be greater than 5MB');
        return false;
    }
    if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
        $('.ceo_file_error').html('');
        readURL(this);
    }
});

jQuery('body').on('click', '.remove_img1', function () {
     var img = jQuery(this).data('avtar');
     jQuery('.ceo_logo img').attr('src', img);
     //jQuery(this).css("display", "none");
     jQuery('#check_delete').val('1');
});







