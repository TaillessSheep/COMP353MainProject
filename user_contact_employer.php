<div class="container">
    <div class="text-center">
        <h2 class="section-heading text">Contact Us</h2>
    </div>
    <form id="contactForm" name="sentMessage" method="post">
        <div class="row align-items-stretch mb-5">
            <div class="col-md-6">
                <div class="form-group">
                    <input class="form-control" name="name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name." required/>
                </div>
                <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="Your Email *" required="required" data-validation-required-message="Please enter your email address." />
                </div>
                <div class="form-group mb-md-0">
                    <input class="form-control" name="phone" type="tel" placeholder="Your Phone *" required="required" data-validation-required-message="Please enter your phone number." />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-group-textarea mb-md-0">
                    <textarea class="form-control" name="message" placeholder="Your Message *" required="required" data-validation-required-message="Please enter a message."></textarea>
                </div>
            </div>
        </div>
        <div class="text-center">
            <div id="success"></div>
            <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">Send Message</button>
        </div>
    </form>
</div>