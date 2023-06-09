@extends('layouts.app')

@section('script')
<style>
.content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 500px;
    height: 200px;
    text-align: center;
    background-color: white;
    box-sizing: border-box;
    padding: 10px;
    z-index: 100;
    display: none;
    /*to hide popup initially*/
}
.close-btn {
    position: absolute;
    right: 45%;
    top: 20%;
}
</style>

<script>
$(document).ready(function(){
    $(document).ajaxStart(function(){
    $("#wait").css("display", "block");
  });
  $(document).ajaxComplete(function(){
    $("#wait").css("display", "none");
  });
  $(document).on('click','.delete_btn',(function(){
    var id = $(this).data('id');

    $(".card").load();
    Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "info",
            showCancelButton: !0,
            confirmButtonText: "Delete",
            customClass: {confirmButton: "btn btn-primary", cancelButton: "btn btn-outline-danger ms-1"},
            buttonsStyling: !1
        }).then((result) => {
            if (result.dismiss === 'cancel') {
                swal.fire("Your Delectation is cancel!");
            } 
            else {
                $.ajaxSetup({headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")}}),
                $.ajax({
                    url:"delete_product/"+id,
                    type:"delete",
                    dataType:"json",
                    data:{product_id:id},
                    success:function(){
                        $(".content").toggle();
                        $(".close-btn").on('click',function(){
                            $(".content").toggle('close');
                            location.reload();
                        });
                    },
                    error:function(){
                        swal.fire('Not delete');
                        window.location.reload();
                    }
                });
                
            }
        });
    }));

});
    
</script>
@endsection
@section('content')
   
        <!-- Main Container-->
        <div class="container-fluid mt-5">
        @if ($message = Session::get('status'))
            <div class="alert alert-success">
                <p class="text-success fw-bold">{{ $message }}</p>
            </div>
        @endif
            <div class="card m-3">
                <div class="card-header d-flex bg-white">
                    <h3 class="fw-bold">Products</h3>
                </div>
                
                <div class="card-body overflow-auto">
                    <table class="table">
                        <tr style="background-color:#f2f2f2;">
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category Name</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                        @foreach($product as $key=>$value)
                        <tr>
                            <td>{{$value->id}}</td>
                            <td><img src="{{ asset('storage/'.$value->image) }}" width="50" alt="img"> </td>
                            <td>{{$value->pro_name}}</td>
                            <td>{{$value->category->cat_name}}</td>
                            <td>{{$value->detail}}</td>
                            <td><span class="bg-success fw-bold text-white rounded p-1 
                                @if($value->status == 'deactive')
                                bg-danger
                                @endif
                                ">{{$value->status}}</span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{route('product.edit',$value->id)}}" class="btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </a>
                                    <!-- <form method="post" action="{{route('product.destroy',$value->id)}}">
                                        @method('delete')
                                        @csrf -->
                                        <button type="submit" class="delete_btn btn" data-id="{{$value->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                            </svg>
                                        </button>
                                    <!-- </form> -->
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    <div class="content shadow-lg border border-3 rounded border-danger">
                        <h3 class="pt-5">Click To Delete Product </h3>
                        <button onclick="togglePopup()" class="close-btn btn btn-danger fw-bold mt-5">
                            Delete
                        </button>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{url('product/create')}}" class="text-decoration-none ">
                        <button class="btn btn-outline-primary fs-6 fw-bold">Add New</button>
                    </a>
                </div>
            </div>
            <div id="wait" style="display:none;position:absolute;top:50%;left:50%;">
                <img src='image/demo_wait.gif' width="64" height="64" /><br>
            </div>

        </div>

@endsection