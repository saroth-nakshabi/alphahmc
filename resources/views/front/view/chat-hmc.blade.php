<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header chatyheadx">

                <h5 style="color:#fff;font-family: Roboto;margin-bottom: 0px;">Talk to a licensing specialist</h5><a
                    data-dismiss="modal"><i class="icofont-close alphaico"></i></a>
            </div>
            <div class="modal-body">

                <div class="chatInner">
                    <h5>Whatsapp Alpha Consultants for:</h5>
                    <ul>
                        <li><i class="icofont-bubble-right"></i> <a
                                href="https://api.whatsapp.com/send?phone=+971507807163?&text=Apply for fecility license">Apply
                                Healthcare fecility licensing</a></li>
                        <li><i class="icofont-bubble-right"></i> <a
                                href="https://api.whatsapp.com/send?phone=+971507807163?&text=Need more information on profosional licensing">Get
                                Profosional licensing information</a></li>
                        <li><i class="icofont-bubble-right"></i> <a
                                href="https://api.whatsapp.com/send?phone=+971507807163?&text=Need to know more information about healthcare management services">Healthcare
                                Management Services</a></li>
                        <li><i class="icofont-bubble-right"></i> <a
                                href="https://api.whatsapp.com/send?phone=+971507807163?&text=Need more information about Healthcare Quality Assurance services">Healthcare
                                Quality Assurance</a></li>
                    </ul>

                    {{-- <div class="chatWa">
                        <a class="btn btn-primary btnx" href="https://wa.me/971507807163">WHATSAPP NOW</a> <a
                            class="" href="https://wa.me/971507807163"><img
                                src="{{ asset('public/front/assets/img/whatsapp.pngAlpha"><img
                                        src="{{ asset('public/front/assets/img/whatsapp.png') }}" alt="" /></a>
                    </div> --}}
                    <div class="chatWa">
                        <a class="btn btn-primary btnx" href="https://wa.me/971507807163">WHATSAPP NOW</a> <a
                            class=""
                            href="https://wa.me/971507807163?&text=Hello,%20I%20wanted%20your%20assistance%20in%20my%20UAE%20healthcare%20professional%20license"><img
                                src="{{ asset('public/front/assets/img/whatsapp.pngAlpha') }}"><img
                                src="{{ asset('public/front/assets/img/whatsapp.png') }}" alt="+971507807163" /></a>
                    </div>

                    <div class="chatText">
                        <p>*Sunday - Thursday, 9am to 7pm</p>
                        <p>click on the options to send an instant Whatsapp message or call at <b><a
                                    href="tel:+971507807163">+971 50 780 7163</a></b></p>
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
