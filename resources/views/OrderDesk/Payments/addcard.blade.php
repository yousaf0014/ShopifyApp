@extends('layouts.orderdesk.app')
@section('content')
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Add card</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="setup-form" data-secret="{{$intentData['client_secret']}}">
                          <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Card Holder Name</label>
                                <input type="text" name="name" id="cardholder-name" value="" required="" class="form-control input-sm" placeholder='Name on card' />
                            </div>
                          <div id="card-element"></div>
                          <div class="mt-4">
                                <button type="button" id="card-button" class="btn btn-primary btn-sm">Save</button>
                                <a href="{{ url('payment') }}" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                    </span>
                                    <span class="text">Go Back</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
    .StripeElement {
      box-sizing: border-box;

      height: 40px;

      padding: 10px 12px;

      border: 1px solid transparent;
      border-radius: 4px;
      background-color: white;

      box-shadow: 0 1px 3px 0 #e6ebf1;
      -webkit-transition: box-shadow 150ms ease;
      transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
      box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
      border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
      background-color: #fefde5 !important;
    }
</style>
@endsection
@section('scripts')
    <script type="text/javascript">
        var stripe = Stripe('{{env('STRIPE_PUBLIC')}}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');
        var cardholderName = document.getElementById('cardholder-name');
        var cardButton = document.getElementById('card-button');
        var clientSecret = '{{$intentData['client_secret']}}';

        cardButton.addEventListener('click', function(ev) {

          stripe.confirmCardSetup(
            clientSecret,
            {
              payment_method: {
                card: cardElement,
                billing_details: {
                  name: cardholderName.value,
                },
              },
            }
          ).then(function(result) {
            if (result.error) {
              alert(result.error.message);
            } else {
              //console.log(result);
              // Simulate a mouse click:
              window.location.href = '{{url('savePaymentMethodCustomer')}}/'+result.setupIntent.payment_method;
            }
          });
        });

    </script>
@endsection