 $(function() {
        $('#myButton').floatingWhatsApp({
            phone: '+971507802259',
            popupMessage: "Welcome to Alpha, A Health Authority approved healthcare management consultancy, you can chat with us about our services, general inquiries, customer feedback & complains",
            popupMessage1: 'send an instant Whatsapp message now or call to +971 50 780 7163',
            popupMessagex: 'test',
            message: "Hello, ",
            showPopup: true,
            showOnIE: false,
            headerTitle: 'Welcome to Alpha Virtual Assistant!',
            headerColor: '#009688',
            backgroundColor: '#009688',
             buttonImage: `<img src="{{ asset('public/front/assets/img/whatsapp.svg') }}" />`
        });
    });