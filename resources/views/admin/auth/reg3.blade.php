<style>
    /* Font Family Link */
@import url('https://fonts.googleapis.com/css?family=Roboto:300i,400,400i,500,700,900');

.multi_step_form {
    background: #f6f9fb;
    display: block;
    overflow: hidden;
}

#msform {
    text-align: center;
    position: relative;
    padding-top: 50px;
    min-height: 820px;
    max-width: 810px;
    margin: 0 auto;
    background: #ffffff;
    z-index: 1;
}

#msform .tittle {
    text-align: center;
    padding-bottom: 55px;
}

#msform .tittle h2 {
    font: 500 24px/35px 'Roboto', sans-serif;
    color: #3f4553;
    padding-bottom: 5px;
}

#msform .tittle p {
    font: 400 16px/28px 'Roboto', sans-serif;
    color: #5f6771;
}

#msform fieldset {
    border: 0;
    padding: 20px 105px 0;
    position: relative;
    width: 100%;
    left: 0;
    right: 0;
}

#msform fieldset:not(:first-of-type) {
    display: none;
}

#msform fieldset h3 {
    font: 500 18px/35px 'Roboto', sans-serif;
    color: #3f4553;
}

#msform fieldset h6 {
    font: 400 15px/28px 'Roboto', sans-serif;
    color: #5f6771;
    padding-bottom: 30px;
}

#msform .intl-tel-input {
    display: block;
    background: transparent;
    border: 0;
    box-shadow: none;
    outline: none;
}

#msform .intl-tel-input .flag-container .selected-flag {
    padding: 0 20px;
    background: transparent;
    border: 0;
    box-shadow: none;
    outline: none;
    width: 65px;
}

#msform .intl-tel-input .flag-container .selected-flag .iti-arrow {
    border: 0;
}

#msform .intl-tel-input .flag-container .selected-flag .iti-arrow:after {
    content: "\f35f";
    position: absolute;
    top: 0;
    right: 0;
    font: normal normal normal 24px/7px Ionicons;
    color: #5f6771;
}

#msform #phone {
    padding-left: 80px;
}

#msform .form-group {
    padding: 0 10px;
}

#msform .fg_2,
#msform .fg_3 {
    padding-top: 10px;
    display: block;
    overflow: hidden;
}

#msform .fg_3 {
    padding-bottom: 70px;
}

#msform .form-control,
#msform .product_select {
    border-radius: 3px;
    border: 1px solid #d8e1e7;
    padding: 0 20px;
    height: auto;
    font: 400 15px/48px 'Roboto', sans-serif;
    color: #5f6771;
    box-shadow: none;
    outline: none;
    width: 100%;
}

#msform .form-control:hover,
#msform .form-control:focus {
    border-color: #5cb85c;
}

#msform .form-control:focus::-webkit-input-placeholder {
    color: transparent;
}

#msform .product_select:after {
    display: none;
}

#msform .product_select:before {
    content: "\f35f";
    position: absolute;
    top: 0;
    right: 20px;
    font: normal normal normal 24px/48px Ionicons;
    color: #5f6771;
}

#msform .done_text {
    padding-top: 40px;
}

#msform .done_text .don_icon {
    height: 36px;
    width: 36px;
    line-height: 36px;
    font-size: 22px;
    margin-bottom: 10px;
    background: #5cb85c;
    display: inline-block;
    border-radius: 50%;
    color: #ffffff;
    text-align: center;
}

#msform .done_text h6 {
    line-height: 23px;
}

#msform .code_group {
    margin-bottom: 60px;
}

#msform .code_group .form-control {
    border: 0;
    border-bottom: 1px solid #a1a7ac;
    border-radius: 0;
    display: inline-block;
    width: 30px;
    font-size: 30px;
    color: #5f6771;
    padding: 0;
    margin-right: 7px;
    text-align: center;
    line-height: 1;
}

#msform .passport {
    margin-top: -10px;
    padding-bottom: 30px;
    position: relative;
}

#msform .passport .don_icon {
    height: 36px;
    width: 36px;
    line-height: 36px;
    font-size: 22px;
    position: absolute;
    top: 4px;
    right: 0;
    background: #5cb85c;
    display: inline-block;
    border-radius: 50%;
    color: #ffffff;
    text-align: center;
}

#msform .passport h4 {
    font: 500 15px/23px 'Roboto', sans-serif;
    color: #5f6771;
    padding: 0;
}

#msform .input-group {
    padding-bottom: 40px;
}

#msform .input-group .custom-file {
    width: 100%;
    height: auto;
}

#msform .input-group .custom-file .custom-file-label {
    width: 168px;
    border-radius: 5px;
    cursor: pointer;
    font: 700 14px/40px 'Roboto', sans-serif;
    border: 1px solid #99a2a8;
    text-align: center;
    color: #5f6771;
}

#msform .input-group .custom-file .custom-file-label:hover,
#msform .input-group .custom-file .custom-file-label:focus {
    background: #5cb85c;
    border-color: #5cb85c;
    color: #fff;
}

#msform .input-group .custom-file input {
    display: none;
}

#msform .file_added {
    text-align: left;
    padding-left: 190px;
    padding-bottom: 60px;
}

#msform .file_added li {
    font: 400 15px/28px 'Roboto', sans-serif;
    color: #5f6771;
}

#msform .file_added li a {
    color: #5cb85c;
    font-weight: 500;
    display: inline-block;
    position: relative;
    padding-left: 15px;
}

#msform .file_added li a i {
    font-size: 22px;
    padding-right: 8px;
    position: absolute;
    left: 0;
    transform: rotate(20deg);
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
}

#progressbar li {
    list-style-type: none;
    color: #99a2a8;
    font-size: 9px;
    width: calc(100%/3);
    float: left;
    position: relative;
    font: 500 13px/1 'Roboto', sans-serif;
}

#progressbar li:nth-child(2):before {
    content: "\f12f";
}

#progressbar li:nth-child(3):before {
    content: "\f457";
}

#progressbar li:before {
    content: "\f1fa";
    font: normal normal normal 30px/50px Ionicons;
    width: 50px;
    height: 50px;
    line-height: 50px;
    display: block;
    background: #eaf0f4;
    border-radius: 50%;
    margin: 0 auto 10px auto;
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 10px;
    background: #eaf0f4;
    position: absolute;
    left: -50%;
    top: 21px;
    z-index: -1;
}

#progressbar li:last-child:after {
    width: 150%;
}

#progressbar li.active {
    color: #5cb85c;
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: #5cb85c;
    color: white;
}

.action-button {
    background: #5cb85c;
    color: white;
    border: 0 none;
    border-radius: 5px;
    cursor: pointer;
    min-width: 130px;
    font: 700 14px/40px 'Roboto', sans-serif;
    border: 1px solid #5cb85c;
    margin: 0 5px;
    text-transform: uppercase;
    display: inline-block;
}

.action-button:hover,
.action-button:focus {
    background-color: #449d44;
    border-color: #419641;
}

#msform .fs-title {
    font-size: 22px;
    text-transform: uppercase;
    color: #2c3343;
    margin-bottom: 10px;
}

.error_field {
    border-color: #fd1717;
}

.error_field:focus {
    border-color: #fd1717 !important;
}

.custom_file_error .custom-file-label {
    border-color: #fd1717 !important;
    color: #fd1717 !important;
}

.custom_file_error .custom-file-label:hover,
.custom_file_error .custom-file-label:focus {
    background: #fd1717;
    border-color: #fd1717;
    color: #fff;
}

</style>
<!-- Multi step form -->
<section class="multi_step_form">
    <form id="msform">
        <!-- Tittle -->
        <div class="tittle">
            <h2>Verification Process</h2>
            <p>In order to use this service, you have to complete this verification process</p>
        </div>
        <!-- progressbar -->
        <ul id="progressbar">
            <li class="active">Verify Phone</li>
            <li>Upload Documents</li>
            <li>Security Questions</li>
        </ul>
        <!-- fieldsets -->
        <fieldset>
            <h3>Setup your phone</h3>
            <h6>We will send you a SMS. Input the code to verify.</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="tel" id="phone" class="form-control" placeholder="+880">
                </div>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" placeholder="1123456789">
                </div>
            </div>
            <div class="done_text">
                <a href="#" class="don_icon"><i class="ion-android-done"></i></a>
                <h6>A secret code is sent to your phone. <br>Please enter it here.</h6>
            </div>
            <div class="code_group">
                <input type="text" class="form-control" placeholder="0">
                <input type="text" class="form-control" placeholder="0">
                <input type="text" class="form-control" placeholder="0">
                <input type="text" class="form-control" placeholder="0">
            </div>
            <button type="button" class="action-button previous_button">Back</button>
            <button type="button" class="next action-button">Continue</button>
        </fieldset>
        <fieldset>
            <h3>Verify Your Identity</h3>
            <h6>Please upload any of these documents to verify your Identity.</h6>
            <div class="passport">
                <h4>Govt. ID card <br>PassPort <br>Driving License.</h4>
                <a href="#" class="don_icon"><i class="ion-android-done"></i></a>
            </div>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="upload">
                    <label class="custom-file-label" for="upload"><i class="ion-android-cloud-outline"></i>Choose
                        file</label>
                </div>
            </div>
            <ul class="file_added">
                <li>File Added:</li>
                <li><a href="#"><i class="ion-paperclip"></i>national_id_card.png</a></li>
                <li><a href="#"><i class="ion-paperclip"></i>national_id_card_back.png</a></li>
            </ul>
            <button type="button" class="action-button previous previous_button">Back</button>
            <button type="button" class="next action-button">Continue</button>
        </fieldset>
        <fieldset>
            <h3>Create Security Questions</h3>
            <h6>Please update your account with security questions</h6>
            <div class="form-group">
                <select class="product_select">
                    <option data-display="1. Choose A Question">1. Choose A Question</option>
                    <option>2. Choose A Question</option>
                    <option>3. Choose A Question</option>
                </select>
            </div>
            <div class="form-group fg_2">
                <input type="text" class="form-control" placeholder="Anwser here:">
            </div>
            <div class="form-group">
                <select class="product_select">
                    <option data-display="1. Choose A Question">1. Choose A Question</option>
                    <option>2. Choose A Question</option>
                    <option>3. Choose A Question</option>
                </select>
            </div>
            <div class="form-group fg_3">
                <input type="text" class="form-control" placeholder="Anwser here:">
            </div>
            <button type="button" class="action-button previous previous_button">Back</button>
            <a href="#" class="action-button">Finish</a>
        </fieldset>
    </form>
</section>
<!-- End Multi step form -->
<script>
    ;
    (function($) {
        "use strict";

        //* Form js
        function verificationForm() {
            //jQuery time
            var current_fs, next_fs, previous_fs; //fieldsets
            var left, opacity, scale; //fieldset properties which we will animate
            var animating; //flag to prevent quick multi-click glitches

            $(".next").click(function() {
                if (animating) return false;
                animating = true;

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale current_fs down to 80%
                        scale = 1 - (1 - now) * 0.2;
                        //2. bring next_fs from the right(50%)
                        left = (now * 50) + "%";
                        //3. increase opacity of next_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'position': 'absolute'
                        });
                        next_fs.css({
                            'left': left,
                            'opacity': opacity
                        });
                    },
                    duration: 800,
                    complete: function() {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            });

            $(".previous").click(function() {
                if (animating) return false;
                animating = true;

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //de-activate current step on progressbar
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function(now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale previous_fs from 80% to 100%
                        scale = 0.8 + (1 - now) * 0.2;
                        //2. take current_fs to the right(50%) - from 0%
                        left = ((1 - now) * 50) + "%";
                        //3. increase opacity of previous_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'left': left
                        });
                        previous_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'opacity': opacity
                        });
                    },
                    duration: 800,
                    complete: function() {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            });

            $(".submit").click(function() {
                return false;
            })
        };

        //* Add Phone no select
        function phoneNoselect() {
            if ($('#msform').length) {
                $("#phone").intlTelInput();
                $("#phone").intlTelInput("setNumber", "+880");
            };
        };
        //* Select js
        function nice_Select() {
            if ($('.product_select').length) {
                $('select').niceSelect();
            };
        };
        /*Function Calls*/
        verificationForm();
        phoneNoselect();
        nice_Select();
    })(jQuery);
</script>
