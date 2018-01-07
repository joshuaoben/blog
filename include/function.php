<?php 
		
		function upload($imgname, $imgsize, $imgtempname){
		define('MAX_FILE_SIZE', '2097152');
		$ext = ['image/jpg', 'image/jpeg', 'image/png'];

		
			$errors =[];

			if (empty($imgname)) {
				$errors[] = "Please select a file, biko";
			}
			if ($imgsize > MAX_FILE_SIZE) {
				$errors[] = "File too large o, max wey we fit accept na " .MAX_FILE_SIZE;
				$imgtempname = null;
			}
			if (!in_array($_FILES['pics']['type'], $ext)) {
				$errors[] = "File format not supported";
			}


			$rnd = rand(0000000000,9999999999);
			$strip_name = str_replace(' ', '_',$imgname);
			$filename = $rnd.$strip_name;
			$destination = './uploads/'.$filename;

			/* if (!move_uploaded_file($imgtempname, $destination)) {
				$errors[] = "File not uploaded"; 
			}*/

			if (empty($errors)) {
				move_uploaded_file($imgtempname, $destination);
				echo "File Upload Successful";
			}
			else {
				foreach ($errors as $err) {
					echo $err."<br>";
				}
			}
		
		}

		function doAdminRegister($dbconn, $input){

			$hash = password_hash($input['password'], PASSWORD_BCRYPT);
			$stmt = $dbconn->prepare("INSERT INTO admin(firstName, lastName, email, hash) VALUES(:f, :l, :e, :h)");
			$data = [
				":f" => $input['fname'],
				":l" => $input['lname'],
				":e" => $input['email'],
				":h" => $hash
			];

			$stmt ->execute($data);

		}

		function doesEmailExist($dbconn, $email){

			$result = false;
			$stmt = $dbconn->prepare("SELECT email FROM admin WHERE :e=email");

			$stmt->bindParam(":e" , $email);
			$stmt->execute();

			$count = $stmt->rowCount();
			if ($count > 0) {
				$result = true;
			}
			return $result;
		}

		function displayErrors($errors, $name){
			$result ="";
			if (isset($errors[$name])) {
				$result = '<span class=err>'.$errors[$name] . '</span>';
			}
			return $result;
		}

		function validateLogin($dbconn, $email, $password) {
        $result = "";

        $stmt = $dbconn->prepare("SELECT * FROM admin WHERE :e=email");

        $stmt->bindParam(":e", $email);
        $stmt->execute();

        while($result=$stmt->fetch(PDO::FETCH_ASSOC)) {
            //print_r($result);
            $hash = $result['hash'];
            if(password_verify($password, $hash)) {
                $result=true;
            } else {
                $result=false;
            }
            return $result;

            
            /* $hash = $result['hash'];
            
            $stmt2 = $dbconn->prepare("SELECT * FROM admin WHERE :p=$hash");
            $hashed = password_verify($password, $hash);
            $stmt2->bindParam(":p", $hashed);
            $stmt2->execute();
 */
        }
    }
		


?>

	



<?php 





