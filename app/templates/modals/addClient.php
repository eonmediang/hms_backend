<form class="clearfix new-client" method="post" action="/ajax/clients/add" data-modal-name="addClient">
	<h3 class="headline b-border bdblue" style="margin-bottom: 20px;">Add new client</h3>
	<div class="row">
		<div class="col-sm-6">
			<div class="row">
				<div class="form-group col-sm-12">
					<label for="client_name">
						Client name
					</label>
					<input type="text" name="client_name" class="client_name form-control" placeholder="Enter client name" required max-length="100">
				</div>
				<div class="form-group col-sm-12">
					<label for="client_short_name">
						Short name
					</label>
					<input type="text" name="client_short_name" class="client_short_name form-control" placeholder="Enter short name" max-length="50">
				</div>
				<div class="form-group col-sm-12">
					<label for="address">
						Address
					</label>
					<textarea name="address" class="address form-control" placeholder="Enter address" required max-length="500" rows="4"></textarea>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<label for="phone_nos">
				Phone numbers
			</label>
			<div class="row">
				<div class="col-sm-6 form-group">
					<input type="text" name="phone_nos" class="phone_nos form-control" placeholder="Number 1">
				</div>
				<div class="col-sm-6 form-group">
					<input type="text" name="phone_nos" class="phone_nos form-control" placeholder="Number 2">
				</div>
				<div class="col-sm-6 form-group">
					<input type="text" name="phone_nos" class="phone_nos form-control" placeholder="Number 3">
				</div>
				<div class="col-sm-6 form-group">
					<input type="text" name="phone_nos" class="phone_nos form-control" placeholder="Number 4">
				</div>
				<div class="col-sm-12 form-group">
					<label for="client_email">
						Email address
					</label>
					<input type="email" name="client_email" class="client_email form-control" placeholder="Email address" max-length="50">
				</div>
				<div class="col-sm-12 form-group">
					<label for="client_website">
						Website
					</label>
					<input type="url" name="client_website" class="client_website form-control" placeholder="Website" max-length="80">
				</div>
			</div>
		</div>
	</div><!-- row -->
	<footer class="border-top fw">
		<div class="actions">
			<input type="submit" name="submit-client" value="Add client" data-action="ok" class="btn bblue">
			<input type="button" class="btn btn-default cancel" data-cancel data-action="cancel" value="Cancel">
		</div>
	</footer>
</form>