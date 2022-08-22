@extends('layouts.app')
  
@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div> 
    @endif
</div>

<div class="container">
  <table id="cart" class="table table-hover table-condensed">
    <thead>
      <tr>
        <th style="width:50%">Product</th>
        <th style="width:10%">Price</th>
        <th style="width:8%">Quantity</th>
        <th style="width:22%" class="text-center">Subtotal</th>
        <th style="width:10%"></th>
      </tr>
    </thead>

    <tbody>
      @php $total = 0 @endphp
      @foreach($getproduct as $details)
        @php $total += $details['price'] * $details['quantity'] @endphp
        <tr data-id="{{ $details['id']}}">
          <td data-th="Product">
            <div class="row">
              <div class="col-sm-3 hidden-xs">
                <img src="{{ $details['image'] }}" width="100" height="100" class="img-responsive"/>
              </div>
              <div class="col-sm-9">
                <h4 class="nomargin">{{ $details['name'] }}</h4>
              </div>
            </div>
          </td>
          <td data-th="Price">${{ $details['price'] }}</td>
          <td data-th="Quantity">
            <input type="number" id="qty<?php echo $details['id'] ?>" value="{{ $details['quantity'] }}" class="form-control quantity cart_update" min="1" onchange="updatecart({{$details['id']}})"/>
          </td>
          <td data-th="Subtotal" class="text-center">${{ $details['price'] * $details['quantity'] }}</td>
          <td class="actions" data-th="">
            <button class="btn btn-danger btn-sm cart_remove" onclick="deletecart({{$details['id']}})"><i class="fa fa-trash-o"></i> Delete</button>
          </td>
        </tr>
      @endforeach
    </tbody>
    
    <tfoot>
      <tr>
        <td colspan="5" class="text-right"><h3><strong>Total ${{ $total }}</strong></h3></td>
      </tr>

      <tr>
        <td colspan="5" class="text-right">
          <a href="{{ url('/home') }}" class="btn btn-danger"> <i class="fa fa-arrow-left"></i> Continue Shopping</a>
          <a href="{{ url('/paymentDetails') }}" class="btn btn-success"><i class="fa fa-money"></i> Checkout</a>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
@endsection
  
@section('scripts')
<script type="text/javascript">
  /*$(".cart_update").change(function (e) {
      e.preventDefault();

      var ele = $(this);

      $.ajax({
          url: '{{ route('update_cart') }}',
          method: "patch",
          data: {
              _token: '{{ csrf_token() }}', 
              id: ele.parents("tr").attr("data-id"), 
              quantity: ele.parents("tr").find(".quantity").val()
          },
          success: function (response) {
             window.location.reload();
          }
      });
  });*/

  function updatecart($id)
  {
    $qty = $("#qty"+$id).val();

    $.ajax(
    {
      url: '{{ route('update_cart') }}',
      method: "patch",
      data: 
      {
        _token: '{{ csrf_token() }}', 
        id: $id, 
        quantity: $qty
      },
      success: function (response) 
      {
        window.location.reload();
      }
    });
  }

  function deletecart($id)
  {
    if(confirm("Do you really want to remove?")) 
    {
      $.ajax(
      {
        url: '{{ route('remove_from_cart') }}',
        method: "DELETE",
        data: 
        {
          _token: '{{ csrf_token() }}', 
          id: $id
        },
        success: function (response) 
        {
          window.location.reload();
        }
      });
    }
  }

  
  /*$(".cart_remove").click(function (e) 
  {
    e.preventDefault();

    var ele = $(this);

    if(confirm("Do you really want to remove?")) 
    {
      $.ajax(
      {
        url: '{{ route('remove_from_cart') }}',
        method: "DELETE",
        data: 
        {
          _token: '{{ csrf_token() }}', 
          id: ele.parents("tr").attr("data-id")
        },
        success: function (response) 
        {
          window.location.reload();
        }
      });
    }
  });*/
</script>
@endsection