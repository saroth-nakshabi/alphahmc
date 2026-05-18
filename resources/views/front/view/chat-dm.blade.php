<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header chatyheadx">

                <h5 style="color:#fff;font-family: Roboto;margin-bottom: 0px;">Talk to a Digital Specialist</h5><a
                    data-dismiss="modal"><i class="icofont-close alphaico"></i></a>
            </div>
            <div class="modal-body">

                <div class="chatInner">
                    <h5>Whatsapp Alpha Consultants for:</h5>
                    <ul>
                        <li><i class="icofont-bubble-right"></i><a
                                href="https://api.whatsapp.com/send?phone=+971507802259?&text=Hello, Looking for digital marketing services">
                                Digital Marketing Services</a></li>
                        <li><i class="icofont-bubble-right"></i><a
                                href="https://api.whatsapp.com/send?phone=+971507802259?&text=Hello, Looking for Branding & Web Development">
                                Branding & Web Development</a></li>
                        <li><i class="icofont-bubble-right"></i> <a
                                href="https://api.whatsapp.com/send?phone=+971507802259?&text=Hello, Looking for Social Media Marketing services">
                                Social Media Marketing</a></li>
                        <li><i class="icofont-bubble-right"></i> <a
                                href="https://api.whatsapp.com/send?phone=+971507802259?&text=Hello, Looking for CRM Software Solutions">
                                CRM Software Solutions</a></li>

                    </ul>

                    <div class="chatWa">
                        <a class="btn btn-primary btnx" href="https://wa.me/971507802259">WHATSAPP NOW</a> <a
                            class="" href="https://wa.me/971507802259"><img
                                src="{{ asset('public/front/assets/img/whatsapp.png') }}" alt="" /></a>
                    </div>
                    <div class="chatText">
                        <p>*Sunday - Thursday, 9am to 9pm</p>
                        <p>click on the options to send an instant Whatsapp message at <b><a
                                    href="tel:+971507802259">+971 50 780 2259</a></b></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="chatBtn" id="chatBtn" style="padding-right: 10px;">
    <h3>Chat with a consultant now&nbsp;&nbsp;&nbsp;&nbsp;<i class="icofont-rounded-up  alphaico"></i></h3>
</div>
<script>
    $(window).load(function() {
        setTimeout(function() {
            $('#myModal').modal('show');
            $('#chatBtn').modal('show');
        }, 5000);
    });
</script>
<script>
    $("#chatBtn").click(function() {
        $('#myModal').modal('show');
    });

    $(function() {
        $('.fa-angle-down').click(function() {
            $(this).closest('.chatbox').toggleClass('chatbox-min');
        });
    });
</script>
