@extends('layouts.app')
  
@section('content')  
	<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default credit-card-box">
          <div class="panel-heading display-table" >
            <div class="row display-tr" >
            	<div class="row">
            		<div class="col-md-11">
            			<h3 class="panel-title display-td" ><b>Payment Details</b></h3>
            		</div>

            		<div class="col-md-1">
            			<a href="{{ url('/cart') }}" class="btn btn-success"> <i class="fa fa-arrow-left"></i> Back</a>
            		</div>
            	</div>
            </div>                    
          </div><br>

          <div class="panel-body">
            @if (Session::has('success'))
              <div class="alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p>{{ Session::get('success') }}</p>
              </div>
            @endif

            <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
              @csrf
            	<div class="row">
            		<div class="col-md-6">
            			<div class='form-row row'>
		                <div class='col-xs-12 form-group required'>
		                  <label class='control-label'>Customer Name</label> 
		                  <input class='form-control' type='text' name="customer_name" placeholder="Enter Customer Name">
		                  <input class='form-control' type='hidden' name="amount" placeholder="Enter Customer Name" value="{{$price}}">
		                </div>
		              </div>

		              <div class='form-row row'>
		                <div class='col-xs-12 form-group required'>
		                  <label class='control-label'>Customer Mobile No. </label> 
		                  <input class='form-control' type='text' name="customer_mobile" placeholder="Enter Customer Mobile No.">
		                </div>
		              </div>

		              <div class='form-row row'>
		                <div class='col-xs-12 form-group required'>
		                  <label class='control-label'>Customer Address</label> 
		                  <input class='form-control' type='text' name="customer_address" placeholder="Enter Customer Address">
		                </div>

		                <div class='col-xs-12 col-md-4 form-group required'>
		                  <label class='control-label'>City</label> 
		                  <input class='form-control' placeholder='Enter City' name="city" type='text'>
		                </div>
		                
		                <div class='col-xs-12 col-md-4 form-group required'>
		                  <label class='control-label'>State</label> 
		                  <input class='form-control' placeholder='Enter State' name="state" type='text'>
		                </div>

		                <div class='col-xs-12 col-md-4 form-group required'>
		                  <label class='control-label'>ZipCode</label> 
		                  <input class='form-control' placeholder='Enter ZipCode' name="zipcode" type='text'>
		                </div>
		              </div>
            		</div>

            		<div class="col-md-6">
            			<div class='form-row row'>
		                <div class='col-xs-12 form-group required'>
		                  <label class='control-label'>Name on Card</label> 
		                  <input class='form-control' size='4' type='text'>
		                </div>
		              </div>

		              <div class='form-row row'>
		                <div class='col-xs-12 form-group required'>
		                  <label class='control-label'>Card Number</label> 
		                  <input autocomplete='off' class='form-control card-number' size='20' type='text'>
		                </div>
		              </div>

		              <div class='form-row row'>
		                <div class='col-xs-12 col-md-4 form-group cvc required'>
		                  <label class='control-label'>CVC</label> 
		                  <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
		                </div>

		                <div class='col-xs-12 col-md-4 form-group expiration required'>
		                  <label class='control-label'>Expiration Month</label> 
		                  <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
		                </div>
		                
		                <div class='col-xs-12 col-md-4 form-group expiration required'>
		                  <label class='control-label'>Expiration Year</label> 
		                  <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
		                </div>
		              </div>

		              <div class='form-row row'>
		                <div class='col-xs-12'>
		                  <center>
		                  	<button class="btn btn-primary btn-lg" type="submit">Payment</button>
		                  </center>
		                </div>
		              </div>
            		</div>
            	</div> 
            </form>
          </div>
        </div>        
      </div>
    </div>
	</div>
@endsection
  
@section('scripts')
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	  
	<script type="text/javascript">
		$(function() {
		   
		    var $form         = $(".require-validation");
		   
		    $('form.require-validation').bind('submit', function(e) {
		        var $form         = $(".require-validation"),
		        inputSelector = ['input[type=email]', 'input[type=password]',
		                         'input[type=text]', 'input[type=file]',
		                         'textarea'].join(', '),
		        $inputs       = $form.find('.required').find(inputSelector),
		        $errorMessage = $form.find('div.error'),
		        valid         = true;
		        $errorMessage.addClass('hide');
		  
		        $('.has-error').removeClass('has-error');
		        $inputs.each(function(i, el) {
		          var $input = $(el);
		          if ($input.val() === '') {
		            $input.parent().addClass('has-error');
		            $errorMessage.removeClass('hide');
		            e.preventDefault();
		          }
		        });
		   
		        if (!$form.data('cc-on-file')) {
		          e.preventDefault();
		          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
		          Stripe.createToken({
		            number: $('.card-number').val(),
		            cvc: $('.card-cvc').val(),
		            exp_month: $('.card-expiry-month').val(),
		            exp_year: $('.card-expiry-year').val()
		          }, stripeResponseHandler);
		        }
		  
		  });
		  
		  function stripeResponseHandler(status, response) {
		        if (response.error) {
		            $('.error')
		                .removeClass('hide')
		                .find('.alert')
		                .text(response.error.message);
		        } else {
		            /* token contains id, last4, and card type */
		            var token = response['id'];
		               
		            $form.find('input[type=text]').empty();
		            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
		            $form.get(0).submit();
		        }
		    }
		   
		});
	</script>
@endsection