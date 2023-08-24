<?php

  function getUserRole() 
  {
    
    return 'admin';
  }

  $Email = $FileError = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = test_input($_POST["Email"]);
    $File = $_FILES["File"];

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
      $EmailError = "Error. Enter a valid email address.";
    }

    $AllowedExtensions = array("png", "jpeg", "jpg");
    $FileExtension = strtolower(pathinfo($File["name"], PATHINFO_EXTENSION));

    if (!in_array($FileExtension, $AllowedExtensions)) {
      $FileError = "Please select a PNG or JPG file.";
    }

    
    $userRole = getUserRole();
    if ($userRole !== 'admin')
    {
      echo "You do not have permission to upload files.";
    } else 
    {
    
      if (empty($EmailError) && empty($FileError)) {
        $TargetDirectory = "uploads/";
        $TargetFile = $TargetDirectory . basename($File["name"]);

        if (move_uploaded_file($File["tmp_name"], $TargetFile)) 
        {
         
          $stmt = $conn->prepare("INSERT INTO Files (Email, filename) VALUES (?, ?)");
          $stmt->bind_param("ss", $Email, $TargetFile);

         
          if ($stmt->execute()) 
          {
            echo "File uploaded successfully.";
          } else 
          {
            echo "Sorry, there was an error uploading your file.";
          }

        
          $stmt->close();
        } else 
        {
          echo "Error uploading your file.";
        }
      }
    }
  }


  function test_input($Data) 
  {
    $Data = trim($Data);
    $Data = stripslashes($Data);
    $Data = htmlspecialchars($Data);
    return $Data;
  }
?>
