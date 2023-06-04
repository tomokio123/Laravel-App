<p>決済ページへリダイレクトします</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
  const publicKey = "{{ $publicKey }}" 
  const stripe = Stripe(publicKey)

  window.onload = function() {
    stripe.redirectToCheckout({//checkoutへとばす
      sessionId: "{{ $session->id }}"//cartControllerでcreateしていたsession情報を渡す
    }).then(function (result) {//もしエラーが出たらcart.cancelに渡す
      window.location.href = "{{ route('user.cart.cancel') }}"
    });
  }
</script>