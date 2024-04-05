<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal</title>

    <!-- PAYPAL FUNCTION -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php CLIENT_ID ?>&currency=<?php echo CURRENCY; ?>"></script>
    <!-- En la funcion de paypal, debes agregar tu, clave despues de id=AQUI VA TU CLIENTE ID" -->
    <!-- En mi caso al ser de mexico, para agregar la moneda, despues de cambiar el CLIENTE ID, Agrege un andperson currency=&MXN -->

</head>
<body>
    
    <div id="paypal-button-container"></div>

        <script>
            paypal.Buttons({
                style:{
                    color: 'blue',
                    shape: 'pill',
                    laber: 'pay'
                },
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: 20000
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    actions.order.capture().then(function (detalles) {
                        console.log(detalles);
                    });
                },
                onCancel: function(data){
                    alert('El pago ha sido cancelado');
                    console.log(data);
                }
            }).render('#paypal-button-container');
        </script>

</body>
</html>