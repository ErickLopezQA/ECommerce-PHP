//Funcion para añadir productos al carrito...
function addProducto(id, token) {
    let url = 'clases/carrito.php'
    let formData = new FormData()
    formData.append('id', id)
    formData.append('token', token)

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                let elemento = document.getElementById("num_cart")
                elemento.innerHTML = data.numero
            }
        })
}
//Funcion de el boton de PayPal...
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