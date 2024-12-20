<div class="modal email-results-modal" style="display: none;">
	<div class="overlay"></div>
	<div class="modal_content">
		<button name="close" title="Close" class="close_modal" aria-label="Close">
			&times;
		</button>
		<div class="modal_content_wrapper">
            <div class="email-wrapper">
                <h2 class="title">Email Search Results</h2>

                <div class="form-wrapper">
                    <div class="form-group">
                        <label for="user_login">Email</label>
                        <input type="email" class="form-control" name="email-address" placeholder="Enter your email address" autocomplete="username email" required="required" />
                    </div>
                    <button name="email-submit" class="signin btn" type="submit">SUBMIT <span class="spinner"></span></button>
                </div>
            </div>
            <div class="success-wrapper">
                <h2 class="title">Search Results Sent</h2>
                <div class="form-wrapper">
                    <p>Your search was sent to your email successfully!</p>
                    <button name="success-close" type="submit" class="btn btn-primary">Back to Apartments</button>
                </div>
            </div>
		</div>
	</div>
</div>
