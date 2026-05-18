  <!-- Annual contact -->
	<section class="digital-services-area">
            <div class="container" style=" max-width: 100%; ">
			<div class="row">
			<div class="col-lg-8 ptb-100 pf-5">
               <div class="section-title text-center">
                    <span>Your brand's digital journey starts here!</span>
                    <h3>We're Alpha Digital, your digital marketing guide. We use our digital expertise to drive your business growth.</h3>
                    <p>We’re a specialized digital marketing agency on a mission to move healthcare digital forward in the region specially in United Arab Emirates- and that means doing things a certain way. We measure our success not just by how quickly we reach our destination, but how we get there and what happens along the way. We take time to explore, consult, recalibrate: only then do we move forward. Here’s what else we’re about..</p>
					
					<h5>Get in touch to set up a free consultation.</h5>
					<p>Talk to our Healthcare digital marketing experts to discuss about your company’s digital strategy and <br>get a consultation to start your digital journey with Alpha.
					</p>
					<a>call to: +971 50 780 2259</a><br>
					<a>email at: dm@alphatsm.com</a>
					<br>
					
					<br>
					<div><a href="#dmcon" class="btn btn-primary">Contact Now</a></div>
				
                </div>
                </div>

              
                    <div class="col-lg-4 text-center section-title" style="background-color: #4e4e4e;">
					<h2>Why Alpha for<br>Digital marketing </h2>
                        <div class="pf-5">
						
                        <div class="col-lg-12">
                            <div class="single-inner-services">
                                <h3>Reliable Partnership</h3>
                                <p>Alpha has a large digital marketing team of experts in managing & planning the services for your business development.</p>
								
                            </div>
							
                        </div>

                        <div class="col-lg-12">
                            <div class="single-inner-services">
                                <h3>Cost Effective</h3>
                                <p>With the strength of remote based team internationally alpha able to provide the best service with the most affordable cost with higher standard in service delivery.</p>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="single-inner-services">
                                <h3>Hustle free</h3>
                                <p>The key team managers & client service department are located closer to the client’s physical location that gives you a hustle free experience, when ever you need we will be with you in matter of hours.</p>
                            </div>
                        </div>
                    </div>
                    </div>
					
            </div>
            </div>
			
        </section>
		<section id="dmcon" class="ptb-100"><div class="col-md-10 offset-md-1 section-title text-center">
		 <div class="  repair-services-inner">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="">
							
                                <h3>Send us an Email!</h3>
								<p class="mt-20 mb-20">Get in touch with our team by sending a message, one of our coordinators will get back to you as soon as possiboe, if you have any instant quarry please call to <a href="tel:+971507802259">+971 50 780 2259</a>  </p>
								 <form id="contactForm" name="contactForm" class="">
								 <div class="row">
                                <div class="col-lg-3  form-group">
                                    <input id="fullName" name="fullName" type="text" placeholder="Your Name*" required class="form-control">
                                </div>
                                
                                <div class="col-lg-3 form-group">
                                    <input id="Email" name="Email" type="email" placeholder="Email*" required  class="form-control">
                                </div>
                                
                                <div class="col-lg-3 form-group">
                                    <input id="Phone" name="Phone" type="text" placeholder="Phone Number*" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                <select class="browser-default custom-select form-group form-control" name="service">
  <option selected>Select required service type</option>
  <option value="Web Development">Web Development</option>
  <option value="Branding & Designing">Branding & Designing</option>
  <option value="Social Media Management">Social Media Management</option>
  <option value="Managed Web Services">Managed Web Services</option>
  <option value="Digital Advertisement Solution">Digital Advertisement Solution</option>
  <option value="General Sales & Marketing">General Sales & Marketing</option>
  <option value="Customer Service Care">Customer Service Care</option>
  <option value="Technical Support">Technical Support</option>
  <option value="Customer Feedback & Suggestions">Customer Feedback & Suggestions</option>
</select> </div>
</div>
<div class="form-group">
              
                <textarea id="Message" name="Message" class="form-control required" rows="3" placeholder="Enter Message"></textarea>
              </div>
			  <p class="success" style="display:none;color:#066d77;text-align:center;width:100%;position:absolute;">We have received your inquiry, one of your representatives will contact you shorly</p><br>
                                <button type="submit" class="btn btn-primary">Send Message Now</button>
								
								
                            </form>
								
                            </div>
							
                        </div>

                    </div>
                </div>
							<script> 

    $("form#contactForm").submit(function(event){
    event.preventDefault();
    var formData = new FormData($(this)[0]);
    $("#AjaxLoader").show();
      $.ajax({
        url: 'maildm.php',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $("#AjaxLoader").hide();
    		$('.success').slideDown('slow', function(){
			$('.success').delay(7000).slideUp(); 
		});
    	}
      });
    
      return false;
    });   
    
</script> 
        </div></section>
        <!-- End Annual contact -->