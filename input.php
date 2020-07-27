<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Peter Chen">

        <title>Twin Oaks Labor tracker</title>
        <link rel="icon" href="images/logo.png"/>

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="styles/reset.css"/>
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="styles/style.css"/>
        <link rel="stylesheet" type="text/css" href="styles/inputStyle.css"/>
        
        <!-- Font awesome is used for the icons (<i> elements) and requires this line-->
        <script src="https://kit.fontawesome.com/245f30a0ca.js" crossorigin="anonymous"></script>
    </head>

	<!--Save last input as cookie -->
	<!--checks to see if the user is logged in-->
	<?php
	session_start();
	if (isset($_SESSION['user']))
	{
	?>

    <body>
        <?php include('header.html'); ?>

        <div class="buffer"><?php include('inputFormHandler.php'); ?></div>

        <div class="container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="row">
                    <div class="col-md-3">
                        <label for="hours">Hours worked:</label>
                        <input id="hours" type="number" name="hours"
                            value="<?php if (isset($_POST['hours']) && !$confirm) echo $_POST['hours']; ?>"
                            <?php if (empty($_POST['hours'])) { ?> autofocus <?php }; ?> autofocus />
                        <span class="alert-danger"><?= $hours_msg; ?></span>
                    </div>

                    <div class="col-md-3">
                        <label for="work-area">Search for work area:</label>
                        <input type="text" list="work-areas" name="work-area" id="work-area"
                            value="<?php if(isset($_POST['work-area']) && !$confirm) echo $_POST['work-area'] ?>"
                            <?php if(empty($_POST['work-area'])) { ?> autofocus <?php }; ?> />
                        <datalist id="work-areas">
                            <option value="Curds"></option>
                            <option value="Garden"></option>
                            <option value="Hammocks"></option>
                            <option value="Kettle"></option>
                            <option value="Pack Help"></option>
                            <option value="Pack Honcho"></option>
                            <option value="Seed Racks"></option>
                            <option value="Seeds"></option>
                            <option value="Tofu Hut"></option>
                            <option value="Trays"></option>
                        </datalist>
                        <span class="alert-danger"><?= $workArea_msg; ?></span>
                    </div>

                    <div class="col-md-3">
                        <label for="date">Enter date worked:</label>
                        <input type="date" name="date" id="date" />
                        <span class="alert-danger"><?= $date_msg; ?></span>
                    </div>

                    <div class="col-md-1">
                        <input type="submit" class="btn btn-primary btn-lg" id="submit" value="Submit"/>
                    </div>
                </div>
            </form>

        </div>
        <script src="js/inputScript.js"></script>
		
		<?php
		//Make a table of all the work areas and populate it (if they don't exist already)
		$work_areas = array(["Curds", 1000],
						["curds", 1000],
						["Garden", 1000],
						["Hammocks", 1000],
						["Kettle", 1000],
						["Pack Help", 1000],
						["Pack Honcho", 1000],
						["Seed Racks", 1000],
						["Seeds", 1000],
						["Tofu Hut", 1000],
						["Trays", 1000]
					);

		require('model/connect-db.php');
		$query = "SELECT * FROM `work_areas`";
		$statement = $db->prepare($query);
		$statement->execute();	
		$result = $statement->fetchAll();
		//The table doesn't exit
		if (!$result){
				echo "It doesn't exist!";
				
				$query = "CREATE TABLE work_areas(
				area_name VARCHAR(255) PRIMARY KEY,
				labor_balance INT NOT NULL )";
				$statement = $db->prepare($query);
				$statement->execute();
				$statement->closeCursor();
			}
		foreach ($work_areas as $work_area){
			$query = "SELECT * FROM `work_areas` WHERE area_name=:area";
			$statement = $db->prepare($query);
			$statement->bindValue(':area', $work_area[0]);
			$statement->execute();
			$result = $statement->fetch();
			//The work area doesn't exist
			if (!$result){
				$que = "INSERT INTO `work_areas` (area_name, labor_balance) VALUES(:area, :labor)";
				$state = $db->prepare($que);
				$state->bindValue(':area', (String)$work_area[0]);
				$state->bindValue(':labor', (int)$work_area[1]);
	
				$r = $state->execute();
			}
			else{
				echo "Yay! The work area exists! </br>";
			}
		}
	}
?>
		
    </body>
</html>