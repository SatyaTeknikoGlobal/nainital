  <!-- footer section start -->
        <footer class="footer" id="contact">
            <div class="container">
                
                <div class="row">
                    <div class="col-lg-12">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-area">
                            <ul>
                                <li><a href="#"><i class="icofont icofont-social-facebook" style="    font-size: 20px"></i></a></li>
                                <li><a href="#"><i class="icofont icofont-social-twitter" style="    font-size: 20px"></i></a></li>
                                <li><a href="#"><i class="icofont icofont-brand-linkedin" style="    font-size: 20px"></i></a></li>
                                <li><a href="#"><i class="icofont icofont-social-pinterest" style="    font-size: 20px"></i></a></li>
                                <li><a href="#"><i class="icofont icofont-social-google-plus" style="    font-size: 20px"></i></a></li>
                            </ul>
                            <p>Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved | <strong>Nainital ERP</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer><!-- footer section end -->
        <a href="#" class="scrollToTop">
            <i class="icofont icofont-arrow-up" style="color: #fff;"></i>
        </a>
        <div class="switcher-area" id="switch-style">
            <div class="display-table">
                <div class="display-tablecell">
                    <a class="switch-button" id="toggle-switcher"><i>Become a Client</i></a>
                    
                    <!-- <a class="switch-button" id="toggle-switcher"><i class="icofont icofont-wheel"></i></a> -->
                    <div class="switched-options">
                        <!-- <div class="config-title"><a href="#"><center>Quick Contact</center></a></div> -->
                        <div class="container"> 
                          <form method="POST" id="contact"  action="<?=base_url('enquiry')?>">
                           <!--  <form id="contact" action="./enquiry.php" method="POST"> -->
                                <h3 class="text-center">Quick Contact</h3>
                                <h4 class="text-center">Contact us today, and get reply within 24 hours!</h4>
                                <fieldset>
                                    <input placeholder="Your name" type="text" tabindex="1" id="name" name="name" required autofocus>
                                </fieldset>
                                <fieldset>
                                    <input placeholder="Your Email Address" type="email" tabindex="2"  id="email" name="email" required>
                                </fieldset>
                                <fieldset>
                                    <input placeholder="Your Phone Number" type="text" tabindex="3"  id="phone" name="phone"  required>
                                </fieldset>
    <!-- <fieldset>
      <input placeholder="Your Web Site starts with http://" type="url" tabindex="4" required>
  </fieldset> -->
  <fieldset>
    <textarea placeholder="Type your Message Here...." tabindex="5" id="message" name="message" required></textarea>
  </fieldset>
  <fieldset>

    <button name="submit" type="submit"  data-submit="...Sending" >Submit</button>
  </fieldset>
</form>


</div>
</div>
                        <!-- <ul>
                            <li><a href="index.html">Home - Fixed Text</a></li>
                            <li class="active"><a href="index-slider.html">Home - Slider Effect</a></li>
                            <li><a href="index-video.html">Home - Video Background</a></li>
                        </ul> -->
                        <!-- <div class="config-title">Other Pages</div> -->
                        <!-- <ul>
                            <li><a href="#">Blog</a></li>
                            <li><a href="blog-detail.html">Blog Details</a></li>
                        </ul> -->
                        <!-- </div> -->
                    </div>
                </div>
            </div>


           <!--  <script type="text/javascript">
                $(document).ready(function(){
                    $("#submit_btn").click(function(){
                        var name = $("#name").val();

                        var email = $("#email").val();
                        var phone = $("#phone").val();
                        var message = $("#message").val();
                        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        var data = "name="+name+"&email="+email+"&phone="+phone+"&message="+message;
                        if(name == '' || email == '' ||phone == '' || message == '')
                        {
                            alert("Please Enter All Required Fields");
                        }else {
                            if (regex.test(email)) {
                                $.ajax({
                                    type:"POST",
                                    url:$('#form').attr('action'),
                                    data:data,
                                    success: function(resp)
                                    {
                                        if (resp == "Your Enquiry has been successfully submitted") {
                                            alert(resp);
                                            window.location.replace("https://www.teknikoglobal.com/tekniko_sub_domains/nainital/");
                                        }else{
                                            alert(resp);
                                        }

                                    }
                                });
                            }else{
                                alert("Please Input a valid email");
                            }

                        }

                    });
                });
            </script> -->
            <!-- jquery main JS -->
            <script src="<?=base_url('')?>assets/js/jquery.min.js"></script>
            <!-- Bootstrap JS -->
            <script src="<?=base_url('')?>assets/js/bootstrap.min.js"></script>
            <!-- Slick nav JS -->
            <script src="<?=base_url('')?>assets/js/jquery.slicknav.min.js"></script>
            <!-- Slick JS -->
            <script src="<?=base_url('')?>assets/js/slick.min.js"></script>
            <!-- owl carousel JS -->
            <script src="<?=base_url('')?>assets/js/owl.carousel.min.js"></script>
            <!-- Popup JS -->
            <script src="<?=base_url('')?>assets/js/jquery.magnific-popup.min.js"></script>
            <!-- Counter JS -->
            <script src="<?=base_url('')?>assets/js/jquery.counterup.min.js"></script>
            <!-- Counterup waypoints JS -->
            <script src="<?=base_url('')?>assets/js/waypoints.min.js"></script>
            <!-- YTPlayer JS -->
            <script src="<?=base_url('')?>assets/js/jquery.mb.YTPlayer.min.js"></script>
            <!-- jQuery Easing JS -->
            <script src="<?=base_url('')?>assets/js/jquery.easing.1.3.js"></script>
            <!-- Gmap JS -->
            <script src="<?=base_url('')?>assets/js/gmap3.min.js"></script>
            <!-- Google map api -->
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnKyOpsNq-vWYtrwayN3BkF3b4k3O9A_A"></script>
            <!-- Custom map JS -->
            <script src="<?=base_url('')?>assets/js/custom-map.js"></script>
            <!-- WOW JS -->
            <script src="<?=base_url('')?>assets/js/wow-1.3.0.min.js"></script>
            <!-- Switcher JS -->
            <script src="<?=base_url('')?>assets/js/switcher.js"></script>
            <!-- main JS -->
            <script src="<?=base_url('')?>assets/js/main.js"></script>
        </body>
        </html>