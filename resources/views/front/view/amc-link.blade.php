  <!-- Annual contact -->
  <section class="repair-services-area ">
      <div class="container" style=" max-width: 100%; ">
          <div class="row">
              <div class="col-lg-8 ptb-100 pf-5">
                  <div class="section-title text-center">
                      <span>Are you are representing healthcare facility?</span>
                      <h3>Check Our Healthcare Management Service Plans</h3>
                      <p>We customized the essential services for all healthcare center and brought as an annual
                          healthcare management service plan that is efficient and cost effective. Our annual service
                          contact will benefit to reduce your internal operational cost, increase the efficient of the
                          process, achieves the requirements of government regulatory authorities and maximize your
                          profit.</p>
                      <br>

                      <br>
                      <div><a href="{{ route('services.healthcare-management-outsourcing') }}"
                              class="btn btn-primary">Know More</a></div>

                  </div>
              </div>


              <div class="col-lg-4 text-center section-title " style="background-color: #4e4e4e;">
                  <h2>Why to Join Annual Contract</h2>
                  <div class="pf-5">
                      <div class="col-lg-12">
                          <div class="single-inner-services">
                              <h3>Reliable Partnership</h3>
                              <p>Exclusive & highly trusted annual healthcare management services for healthcare
                                  establishments .</p>

                          </div>

                      </div>

                      <div class="col-lg-12">
                          <div class="single-inner-services">
                              <h3>Cost Effective</h3>
                              <p>Save money with long term contract with alpha with customized service bundle from
                                  ALPHA.</p>
                          </div>
                      </div>

                      <div class="col-lg-12">
                          <div class="single-inner-services">
                              <h3>Hassle free</h3>
                              <p>when ever an inquiry arises or change is needed Alpha team takes it and it is sorted .
                              </p>
                          </div>
                      </div>
                  </div>
              </div>

          </div>
      </div>

  </section>
  <section class="ptb-100">
      <div class="col-md-10 offset-md-1 section-title text-center">
          <div class="  repair-services-inner">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="">

                          <h3>Send us an Email!</h3>
                          <form id="contactForm" name="contactForm" class="">
                              <div class="row">
                                  <div class="col-lg-3  form-group">
                                      <input id="fullName" name="fullName" type="text" placeholder="Your Name*"
                                          class="form-control">
                                  </div>

                                  <div class="col-lg-3 form-group">
                                      <input id="Email" name="Email" type="email" placeholder="Email*"
                                          class="form-control">
                                  </div>

                                  <div class="col-lg-3 form-group">
                                      <input id="Phone" name="Phone" type="text" placeholder="Phone Number*"
                                          class="form-control">
                                  </div>
                                  <div class="col-lg-3">
                                      <select class="browser-default custom-select form-group form-control"
                                          name="service">
                                          <option selected>Select Consigned Department</option>
                                          <option value="Digital Marketing">Healthcare Management</option>
                                          <option value="Healthcare Professional Licensing">Healthcare Professional
                                              Licensing</option>
                                          <option value="Healthcare Project Management">Healthcare Project Management
                                          </option>
                                          <option value="Healthcare Digital Marketing">Healthcare Digital Marketing
                                          </option>
                                          <option value="Education & Courses">Education & Courses</option>
                                          <option value="General Sales & Marketing">General Sales & Marketing</option>
                                          <option value="Customer Service Care">Customer Service Care</option>
                                          <option value="Technical Support">Technical Support</option>
                                          <option value="Customer Feedback & Suggestions">Customer Feedback &
                                              Suggestions</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="form-group">

                                  <textarea id="Message" name="Message" class="form-control required" rows="3" placeholder="Enter Message"></textarea>
                              </div>
                              <p class="success"
                                  style="display:none;color:#066d77;text-align:center;width:100%;position:absolute;">We
                                  have received your inquiry, one of your representatives will contact you shorly</p>
                              <br>
                              <button type="submit" class="btn btn-primary">Send Message Now</button>


                          </form>

                      </div>

                  </div>

              </div>
          </div>
          <script>
              $("form#contactForm").submit(function(event) {
                  event.preventDefault();
                  var formData = new FormData($(this)[0]);
                  $("#AjaxLoader").show();
                  $.ajax({
                      url: 'mail.php',
                      type: 'POST',
                      data: formData,
                      async: false,
                      cache: false,
                      contentType: false,
                      processData: false,
                      success: function(data) {
                          $("#AjaxLoader").hide();
                          $('.success').slideDown('slow', function() {
                              $('.success').delay(7000).slideUp();
                          });
                      }
                  });

                  return false;
              });
          </script>
      </div>
  </section>
  <!-- End Annual contact -->
