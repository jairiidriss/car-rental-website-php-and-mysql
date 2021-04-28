<?php
	session_start();
    include "connect.php";
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
	include "Includes/functions/functions.php";

	if (isset($_POST['reserve_car']) && $_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$_SESSION['pickup_location'] = test_input($_POST['pickup_location']);
		$_SESSION['return_location'] = test_input($_POST['return_location']);
		$_SESSION['pickup_date'] = test_input($_POST['pickup_date']);
		$_SESSION['return_date'] = test_input($_POST['return_date']);
	}
?>

<!-- BANNER SECTION -->
<div class = "reserve-banner-section">
	<h2>
		Reserve your car
	</h2>
</div>

<!-- CAR RESERVATION SECTION -->
<section class="car_reservation_section">
	<div class="container">
		<?php
			if(isset($_POST['submit_reservation']) && $_SERVER['REQUEST_METHOD'] === 'POST')
			{
				$selected_car = $_POST['selected_car'];
				$full_name = test_input($_POST['full_name']);
				$client_email = test_input($_POST['client_email']);
				$client_phonenumber = test_input($_POST['client_phonenumber']);
				$pickup_location = $_SESSION['pickup_location'];
				$return_location = $_SESSION['return_location'];
				$pickup_date = $_SESSION['pickup_date'];
				$return_date = $_SESSION['return_date'];
				
				$con->beginTransaction();

                try
                {
					//Getting Client Table Current ID
					$stmtgetCurrentClientID = $con->prepare("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'car_rental' AND TABLE_NAME = 'clients'");
            
					$stmtgetCurrentClientID->execute();
					$client_id = $stmtgetCurrentClientID->fetch();
					
					//Inserting Client Details
					$stmtClient = $con->prepare("insert into clients(full_name,client_email,client_phone) 
									values(?,?,?)");
					$stmtClient->execute(array($full_name,$client_email,$client_phonenumber));

					//Inserting Reservation Details
					$stmt_appointment = $con->prepare("insert into reservations(client_id, car_id, pickup_date, return_date, pickup_location, return_location ) values(?, ?, ?, ?, ?, ?)");
                    $stmt_appointment->execute(array($client_id[0],$selected_car,$pickup_date,$return_date,$pickup_location,$return_location));
					
					echo "<div class = 'alert alert-success'>";
                        echo "Great! Your reservation has been created successfully.";
                    echo "</div>";

					$con->commit();
                }
                catch(Exception $e)
                {
                    $con->rollBack();
                    echo "<div class = 'alert alert-danger'>"; 
                        echo $e->getMessage();
                    echo "</div>";
                }


			}
			elseif (isset($_SESSION['pickup_date']) && isset($_SESSION['return_date']))
			{
				$pickup_location = $_SESSION['pickup_location'];
				$return_location = $_SESSION['return_location'];
				$pickup_date = $_SESSION['pickup_date'];
				$return_date = $_SESSION['return_date'];

				$stmt = $con->prepare("SELECT *
				from cars, car_brands, car_types
				where cars.brand_id = car_brands.brand_id and cars.type_id = car_types.type_id and 
						cars.id not in (select car_id 
									 from reservations
									 where (? between pickup_date and return_date 
										 or ? BETWEEN pickup_date and return_date )
										 and canceled = 0
									 )");
                $stmt->execute(array($pickup_date, $return_date));
                $available_cars = $stmt->fetchAll();
				?>
					<form action = "reserve.php" method = "POST" id="reservation_second_form" v-on:submit = "checkForm">
						<div class = "row" style = "margin-bottom: 20px;">
							<div class = "col-md-3 reservation_cards">
								<p>
									<i class="fas fa-calendar-alt"></i>
									<span>Pickup Date : </span><?php echo $_SESSION['pickup_date']; ?>
								</p>
							</div>
							<div class = "col-md-3 reservation_cards">
								<p>
									<i class="fas fa-calendar-alt"></i>
									<span>Return Date : </span><?php echo $_SESSION['return_date']; ?>
								</p>
							</div>
							<div class = "col-md-3 reservation_cards">
								<p>
									<i class="fas fa-map-marked-alt"></i>
									<span>Pickup Location : </span><?php echo $_SESSION['pickup_location']; ?>
								</p>
							</div>
							<div class = "col-md-3 reservation_cards">
								<p>
									<i class="fas fa-map-marked-alt"></i>
									<span>Return Location : </span><?php echo $_SESSION['return_location']; ?>
								</p>
							</div>
						</div>
						<div class = "row">
							<div class = "col-md-7">
								<div class="btn-group-toggle" data-toggle="buttons">
									<div class="invalid-feedback" style = "display:block;margin: 10px 0px;font-size: 15px;" v-if="selected_car === null">
										Select your car
									</div>
									<div class="items_tab">
										<?php

											foreach($available_cars as $car)
											{
												echo "<div class='itemListElement'>";
													echo "<div class = 'item_details'>";
														echo "<div>";
															echo $car['car_name'];
														echo "</div>";
														echo "<div class = 'item_select_part'>";
													?>
															<div class="select_item_bttn">
																<label class="item_label btn btn-secondary active">
																	<input type="radio" class="radio_car_select" name="selected_car" v-model = 'selected_car' value="<?php echo $car['id'] ?>">Select
																</label>	
															</div>
													<?php
														echo "</div>";
													echo "</div>";
												echo "</div>";
											}
										?>
									</div>
								</div>
							</div>
							<div class = "col-md-5">
								<div class = "client_details">
									<div class = "form-group">
										<label for="full_name">Full Name</label>
										<input type = "text" class = "form-control" placeholder = "John Doe" name = "full_name" v-model = 'full_name'>
										<div class="invalid-feedback" style = "display:block" v-if="full_name === null">
											Full name is required
										</div>
									</div>
									<div class = "form-group">
										<label for="client_email">E-mail</label>
										<input type = "email" class = "form-control" name = "client_email" placeholder = "abc@mail.xyz" v-model = 'client_email'>
										<div class="invalid-feedback" style = "display:block" v-if="client_email === null">
											E-mail is required
										</div>
									</div>
									<div class = "form-group">
										<label for="client_phonenumber">Phone numbder</label>
										<input type = "text"  name = "client_phonenumber" placeholder = "0123456789" class = "form-control" v-model = 'client_phonenumber'>
										<div class="invalid-feedback" style = "display:block" v-if="client_phonenumber === null">
											Phone number is required
										</div>
									</div>
									<button type="submit" class="btn sbmt-bttn" name = "submit_reservation">Book Instantly</button>
								</div>
							</div>
						</div>
					</form>
				<?php
			}
			else
			{
				?>
					<div style = "max-width:500px; margin:auto;">
						<div class = "alert alert-warning">
							Please, select first pickup and return date.
						</div>
						<button class = "btn btn-info" style = "display:block;margin:auto">
							<a href="./#reserve" style = "color:white">Homepage</a>
						</button>
					</div>
				<?php
			}
		?>
	</div>
</section>



<!-- FOOTER BOTTOM -->

<?php include "Includes/templates/footer.php"; ?>


<script>

new Vue({
    el: "#reservation_second_form",
    data: {
		selected_car : '',
        full_name: '',
        client_email: '',
        client_phonenumber: '',
    },
    methods:{
        checkForm: function(event){
            if( this.full_name && this.client_email && this.client_phonenumber)
            {
                return true;
            }
            
            if (!this.full_name)
            {
                this.full_name = null;
            }

            if (!this.client_email)
            {
                this.client_email = null;
            }

            if (!this.client_phonenumber)
            {
                this.client_phonenumber = null;
            }

			if (!this.selected_car)
            {
                this.selected_car = null;
            }
            
            event.preventDefault();
        },
    }
})


</script>
