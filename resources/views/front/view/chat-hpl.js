 $(function() {
        $('#myButton').floatingWhatsApp({
            phone: '+971564200934',
            popupMessage: "You can chat with our licensing consultant to discuss New license Application, Dataflow Processing, Exam Booking, Renew your license, Transfer your license,  Activate your license",
            popupMessage1: 'send an instant Whatsapp message now or call to +971 56 420 0934',
            popupMessagex: 'test',
            message: "Hello, ",
            showPopup: true,
            showOnIE: false,
            headerTitle: 'Welcome to Alpha Chat Assistant!',
            headerColor: '#009688',
            backgroundColor: '#009688',
             buttonImage: `<img src="{{ asset('public/front/assets/img/whatsapp.svg') }}" />`
        });
    });