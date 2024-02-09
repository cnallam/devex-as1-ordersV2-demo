paypal.Buttons({
    createOrder: function(data, actions) {
        // Call your server to set up the transaction
        return fetch('/api/orders', {
            method: 'post',
            headers: {
                'content-type': 'application/json'
            }
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            return orderData.id;
        });
    },
    onApprove: function(data, actions) {
        // Call your server to finalize the transaction
        return fetch('/api/orders/' + data.orderID + '/capture', {
            method: 'post',
            headers: {
                'content-type': 'application/json'
            }
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            // Successful capture! You can show a success message to the buyer.
            if (orderData.status === 'COMPLETED') {
                console.log('Transaction completed by ' + orderData.payer.name.given_name + '!');
            }
        });
    }
}).render('#paypal-button-container');
