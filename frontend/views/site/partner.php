<?php
$selected = "contact";
$time = time();
setcookie("checktime", $time, 0);
session_start();
include('header.php');
//include('partner-email.php');
?>
<style>
    .fleft { float:left;margin-left:12px;}
</style>
<!-- Contact Start -->
<div  id="box-after">
    <img src="img/osmos_animated_small.gif" style="width:100%;" />
    <p style="font-size:16px;text-align:center;">Thank you for contacting us, we will get back to you as soon as we can.</p> 
    <p style="font-size:16px;font-weight:bold;text-align:center;">Have a nice day</p>
</div>
<div class="contact padd">
    <div class="container">
        <div class="heading">
            <!-- Heading -->
            <h2>Apply for Partner</h2>
        </div>

        <!-- Contact Item -->
        <div class="contact-item"> 
            <!-- Paragraph -->
            <div class="item-contact"></div>
            <div class="item-contact"></div>
            <div class="item-contact"></div>
        </div> 
        <!-- Contact Container -->
        <div class="contact-container">
            <!-- Heading -->
            <h3>Get In Touch</h3>
            <!-- Border -->
            <div class="contact-border"></div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    ///--------------

                    if (isset($_REQUEST['email'])) {
                        
                        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : "-";

                        $business_name = isset($_REQUEST['business_name']) ? $_REQUEST['business_name'] : "-";

                        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : "-";

                        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : "-";
                        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : "-";
                        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : "-";
                        $website = isset($_REQUEST['website']) ? $_REQUEST['website'] : "-";
                        $referral = isset($_REQUEST['referral']) ? $_REQUEST['referral'] : "-";
                        $certified = isset($_REQUEST['certified']) ? $_REQUEST['certified'] : "-";
                        $activity = isset($_REQUEST['activity']) ? $_REQUEST['activity'] : "-";
                        $goodfit = isset($_REQUEST['goodfit']) ? $_REQUEST['goodfit'] : "-";
                        // EDIT THE 2 LINES BELOW AS REQUIRED

                        $message = "Please follow the deatils for Apply for Partner.\n \n";
                        $message = $message . "Full Name : " . $name . " \n Business Name : " . $business_name . " \n City : " . $city . " \n Country : " . $country . " \n Phone : " . $phone . " \n Email : " . $email;
                        $message = $message . " \n Website : " . $website . " \n Program Apply : " . $referral;
                        $message = $message ." \n Tell us what is your main business activity? : " . $activity . " \n Why is your business a good fit? : " . $goodfit;

                        $email_to = "vimalpatel30989@gmail.com";

                        //$email_to="temani.afif@gmail.com";

                        $email_subject = "New Apply for Partner";

                        // validation expected data exists



                        if (!isset($_REQUEST['name']) || !isset($_REQUEST['email'])) {



                            echo 'We are sorry, but there appears to be a problem with the form you submitted.';

                            exit;
                        }



                        $name = $_REQUEST['name']; // required



                        $email_from = $_REQUEST['email']; // required



                        $email_message = $message; // required
                        // create email headers



                        $headers = "MIME-Version: 1.0\r\n";

                        $headers .= "From: " . $email_from . "\r\n";

                        $headers .= 'Reply-To: ' . $email_from . "\r\n";

                        $headers .= 'Content-Type: text/plain; charset="iso-8859-1"';

                        $headers .= "\r\nContent-Transfer-Encoding: 8bit\r\n";

                        $headers .= 'X-Mailer:PHP/' . phpversion() . "\r\n";





                        if (mail($email_to, $email_subject, $email_message, $headers)) {

                            echo "Thank you for contacting us, we will get back to you as soon as we can. Have a nice day";

                            exit;
                        } else {

                            echo "Sorry email could not be sent";

                            exit;
                        }
                    }
                    ?>
                    <!-- Form -->
                    <form class="form-horizontal" id="partner-form" role="form" method="post">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="col-lg-4 control-label">Full Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">Business Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business Name" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">City</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">Country</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="country" name="country" placeholder="Country" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">Phone</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone (Include country area code)" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">Email</label>
                                <div class="col-lg-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-4" for="message">I agree to the Terms & Conditions</label>
                                <div class="col-lg-8">
                                    <div class="fleft">
                                        <input type="radio" class="form-control"  id="tearm_condition" name="tearm_condition" value="I agree to the Terms & Conditions">

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">Website</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="website" name="website" placeholder="Website" required >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-4 control-label">Program you wish to apply</label>
                                <div class="col-lg-8">
                                    <div class="fleft">
                                        <input type="checkbox" class="form-control" id="referral" name="referral" value="Referral Partner">
                                        <label>Referral Partner</label>
                                    </div>
                                    <div class="fleft">
                                        <input type="checkbox" class="form-control"  id="certified" name="certified" value="Certified Partner">
                                        <label>Certified Partner</label>
                                    </div>
                                    <div class="fleft">
                                        <input type="checkbox" class="form-control" id="strategic" name="strategic" value="Strategic Partner">
                                        <label>Strategic Partner</label>
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-4" for="message">Tell us what is your main business activity?</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" id="activity" name="activity" rows="5" placeholder="Tell us what is your main business activity?" required ></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-4" for="message">Why is your business a good fit?</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" id="goodfit" name="goodfit" rows="5" placeholder="Why is your business a good fit?" required ></textarea>
                                </div>
                            </div>

                        </div>



                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-info btn-red">Submit</button> &nbsp;
                                <button type="reset" class="btn btn-default">Cancel</button>
                                <p class="form-group text-success">
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<script id="setmore_script" type="text/javascript" src="https://my.setmore.com/js/iframe/setmore_iframe.js"></script><a id="Setmore_button_iframe" style="float:none; position: fixed; right: -2px; top: 25%; display: block; z-index: 20000" href="https://my.setmore.com/shortBookingPage/c33575e7-503e-4f9d-bfc5-63c89f1fc60b"><img border="none" src="http://osmoscloud.com/img/book-demo.png" alt="Book Demo with Osmos Cloud" /></a>
<!-- Contact End -->
<?php include('footer.php'); ?>