 $(function() {
        $('#myButton').floatingWhatsApp({
            phone: '+971507807163',
            popupMessage: "Chat with us to reach out to the experts to handle all your queries and provide you with the best solution.",
            popupMessage1: 'send an instant Whatsapp message now or call to +971 50 780 7163',
            popupMessagex: 'test',
            message: "Hello, ",
            showPopup: true,
            showOnIE: false,
            headerTitle: 'Alpha Virtual Assistant!',
            headerColor: '#009688',
            backgroundColor: '#009688',
             buttonImage: `<img src="{{ asset('public/front/assets/img/whatsapp.svg') }}" />`
        });
    });