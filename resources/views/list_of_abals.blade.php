@auth
    
@extends('layouts.app')

@section('content')
<style>
    @media screen and (max-width: 600px){
    td:first-child{
        background-color: #333;
        color: #fff;

    }
    #no-more-tables tbody,
    #no-more-tables tr,
    #no-more-tables td {
        display: block;
    }
    #no-more-tables thead tr {
        position: absolute;
        top: 9999px;
        left: 9999px;
    }
    #no-more-tables td {
        position: relative;
        padding-left: 50%;
    }
    #no-more-tables td:before {
        content: attr(data-title);
        position: absolute;
        left: 6px;
        font-weight: bold;
    }
}

</style>
<!-- Delete Modal Start -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">User Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="delete_id">
            <h4>Are you sure ? delete this User ?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary delete_btn">Delete User</button>
        </div>
      </div>
    </div>
  </div>
  <!--Delete Modal End-->
  
<div class="container" id="click_me">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header">List Of All User</div>

                <div class="card-body">
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" class="text-center">

                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Add New User</div>

                <div class="card-body">
                   <div id="success_message"></div>
                   
                   <div id="add_abals">
                        <div class="form-group" >
                            <label for="" class="form-label">User Name</label>
                            <input type="text" class="form-control abal_name" id="name" name="abal_name">
                            <small id="nameError" class="form-text text-danger"></small>
                            @if(session('nameError'))
                            <strong class="text-danger mt-2">{{session('nameError')}}</strong>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">User E-mail</label>
                            <input type="email" class="form-control abal_email" id="email" name="abal_email">
                            <small id="emailError" class="form-text text-danger"></small>
                            @if(session('errors'))
                            <strong class="text-danger mt-2">{{session('errors')}}</strong>
                            @endif
                        </div>
                    </div>
                        <br>
                        <button type="submit" class="btn btn-dark add_abal">Add User</button>
                   
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer_script')
<script>

    $(document).ready(function(){
        show_abal();
        //Data show function start here
        function show_abal(){
                $.ajax({
                  type: 'GET',
                  url : '/show-abals',
                  dataType : 'json',
                  success : function(response){
                    $('tbody').html('');
                    $.each(response.abals, function(key, item){
                        $('tbody').append('<tr>\
                                            <td>'+key+'</td>\
                                            <td>'+item.abal_name+'</td>\
                                            <td>'+item.abal_email+'</td>\
                                            <td><button value="'+item.id+'" class="delete_abal btn btn-danger btn-sm" onclick="deleteData('+item.id+')">Delete</button></td>\
                                        </tr>');
                    });
    
                    }
                 });
            }
            //  Data show end function end here

            // data delete start here
            $(document).on('click', '.delete_abal', function(e){
            e.preventDefault();
            var abal_delete = $(this).val();
            // alert(abal_delete);
                $('#delete_id').val(abal_delete);
                $('#DeleteModal').modal('show');

                $(document).on('click', '.delete_btn', function(e){
                    e.preventDefault();
                    var abal_id = $('#delete_id').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                    });

                    $.ajax({
                    url   : '/delete/'+abal_id,
                    type  : 'Delete',
                    success   : function(response){
                        
                        Swal.fire({
                                toast : true,
                                width : '20em',
                                timerProgressBar  : true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'This User Deleted Successfully',
                                showConfirmButton: false,
                                timer: 1500,
                                });
                                $('#DeleteModal').modal('hide');
                                show_abal();
                    }                   
                });
            });
        });
            

        $(document).on('click', '.add_abal', function(e){
            e.preventDefault();

            var data = {
                'abal_name': $('.abal_name').val(),
                'abal_email': $('.abal_email').val(),
            }
            function errorClear(){
                    $("#nameError").text('');
                    $("#emailError").text('');
            };
            function success_message(){
                    $('#success_message').removeClass('alert alert-sucess');
                    $('#success_message').html('');
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

           var name = $('#name').val();
           var email = $('#email').val();
            $.ajax({
                type    :'POST',
                url     :'/add/abals',
                data    : {
                            abal_name  : name,
                            abal_email : email,
                        },
                dataType: "json",
                
                success:function(response){

                      $('#add_abals').find('input').val("");
                      errorClear();
                      $('#success_message').addClass('alert alert-success');
                      $('#success_message').text(response.message);
                        $('#click_me').bind('click', function(){
                            success_message();
                        });
                        show_abal();
            },
            error : function(error){
                  $("#nameError").text(error.responseJSON.errors.abal_name);
                  $("#emailError").text(error.responseJSON.errors.abal_email);
                    $('#click_me').bind('click', function(){
                        errorClear();
                    });
                    $('#click_me').bind('click', function(){
                        errorClear();
                    });
                  success_message();
              },
                
            });
        });

    });

// //data delete start here
//         function deleteData(id){
//                     $.ajaxSetup({
//                         headers: {
//                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                             }
//                     });

//                     $.ajax({
//                     url   : '/delete/'+id,
//                     type  : 'Delete',
//                     dataType  : 'json',
//                     success   : function(response){
                        
//                                 Swal.fire({
//                                 toast : true,
//                                 width : '20em',
//                                 timerProgressBar  : true,
//                                 position: 'top-end',
//                                 icon: 'warning',
//                                 title: 'This Abal Deleted Successfully',
//                                 showConfirmButton: false,
//                                 timer: 3000,
//                                 });
//                                show_abal();
//                     },
//                     error   : function(error){
//                         deleteData();
//                     }
//                     });
//                 }
        //data delete Ends here

// function deleteData(id){
//     if(confirm("Do you really want to delete this Abal")){

//                 $.ajax({
//                      url   : '/delete/'+id,
//                      type  : 'Delete',
//                      data  : {
//                          _token : $("input[name=_token]").val()
//                      },
//                      success   : function(response){
//                         $(id).remove();
//                        show_abal()
//                      }
//                 });

//     }
// }
    
</script>

@endsection

@else
<script>
    window.location.href = "{{ route('login') }}"
</script>
@endauth
