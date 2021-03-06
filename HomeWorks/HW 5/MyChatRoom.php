<?php
  require 'php/GetContacts.php';
  require 'php/GetMessages.php';
  require 'php/Online.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>Chat Room</title>
    <style>
      .myBg {
        background: #24292e !important;
        color: #ffffff !important;
      }
    </style>
  </head>
  <body class="myBg" style="height: 100vh;">
    <div class="container-fluid">
      <div class="row">
        <div class="d-flex justify-content-center">
          <div class="w-100">
            <div>
              <div class="row">
                <div class="col-4">
                  <div class="border-end px-3 border-2" style="height: 100vh;">
                    <div class="d-flex flex-wrap align-items-center mb-4 p-2 pt-4" style="color:#ffffff; font-size:20px;"> 
                      <span class="d-inline-block bg-light rounded-circle" style="width:70px; height:70px; margin-right: 5px;">
                        <?php if (file_exists('uploads/' . $_GET['user'])) { ?>
                          <img src="uploads/<?php echo $_GET['user'] ?>" class="rounded-circle" style="width:70px; height:70px;" alt="user image" />
                        <?php } ?>
                      </span>
                      <span>
                        <?php echo "Welcome " . $_GET['user']; ?>
                      </span>
                      <a href="php/Logout.php?username=<?php echo $_GET['user']; ?>" class="d-inline-block btn btn-danger mt-md-2 mt-sm-2" style="margin-left:10px;">Logout</a>
                      <a href="Edit.php?username=<?php echo $_GET['user']; ?>" class="d-inline-block btn btn-light mt-md-2 mt-sm-2" style="margin-left:10px;">Edit</a>
                    </div>
                    <div class="my-4 p-2">
                      <!-- A form for adding a new contact -->
                      <form class="d-flex flex-wrap justify-content-start" action="php/AddContact.php" method="post">
                        <input type="submit" class="btn btn-outline-light me-2" name="" value="Add" />
                        <input type="reset" class="btn btn-outline-light me-2" name="" value="Clear" />
                        <input type="hidden" name="sender" value="<?php echo $_GET['user']; ?>" />
                        <input type="text" class="form-control mt-2" name="username" placeholder="username" required />
                      </form>
                      <?php if (isset($_GET['error'])) { ?>
                        <?php if ($_GET['error'] == 'fialedtofinduser') { ?>
                          <div class="alert alert-danger p-2 mt-2">
                            Username not found
                          </div>
                        <?php exit(); } ?>
                      <?php } ?>
                    </div>
                    <div class="overflow-auto pe-1">
                      <h3 class="text-light">Contacts</h3>
                      <?php foreach ($contacts as $contact) { ?>
                        <a href="<?php echo "MyChatRoom.php?user={$_GET['user']}&chater=$contact"; ?>" class="btn btn-outline-light text-start w-100 my-2 <?php if (array_key_exists('chater', $_GET)) if ($_GET['chater'] == $contact) echo 'bg-light text-dark'; ?>">
                          <div class="fs-5">
                            <?php echo $contact; ?>
                            <?php if(isOnline($contact, 'data/online.txt')) { ?>
                              <span class="d-inline-block badge bg-success light-text" style="float: right;">Is online</span>
                            <?php } ?>
                          </div>
                        </a>
                      <?php } ?>
                    </div>

                  </div>
                </div>
                <div class="col-8">
                  <div class="h-100">
                    <?php if (array_key_exists('chater', $_GET)) { ?>
                      <div id="chatScreen" class="align-bottom overflow-auto" style="height: calc(100vh - 5rem); overflow:auto;">
                        <?php foreach ($messages as $message) { ?>
                          <div class="row m-0">
                            <div class="d-flex justify-content-<?php echo $message['sender'] ? "end" : "start"; ?>">
                              <div class="w-50">
                                <div class="<?php echo $message['sender'] ? "bg-light text-dark" : "bg-dark"; ?> p-2 my-2 rounded">
                                  <?php if ($message['isFile']) { ?>
                                    <img src="<?php echo $message['message']; ?>" width="300" />
                                  <?php } else { 
                                    echo $message['message'];
                                  } ?>
                                  <div class="text-<?php echo $message['sender'] ? "info" : "secondary"; ?>">
                                    <?php echo date('l jS F Y h:i:s A', $message['time'])?>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                      <div class="py-2" style="height: 5rem;">
                        <div class="pt-3 border-top border-2">
                          <form class="h-100" action="php/SendMessage.php" method="post">
                            <div class="h-100 d-flex justify-content-start align-items-center">
                                <input type="text" class="form-control mx-2" name="message" placeholder="Message..." required maxlength="100">
                                <input type="hidden" name="sender" value="<?php echo $_GET['user'] ?>">
                                <input type="hidden" name="to" value="<?php echo $_GET['chater'] ?>">
                                <input type="submit" class="btn mx-2 btn-primary" value="Send">
                                <a class="btn mx-2 btn-primary" href="SendFile.php/?sender=<?php echo $_GET['user'] ?>&to=<?php echo $_GET['chater'] ?>">File</a>
                            </div>
                          </form>
                        </div>
                      </div>
                    <?php } else { ?>
                      <div class="d-flex justify-content-center align-items-center h-100 fs-3 text-light">
                        Please select a friend to chat
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
      var objDiv = document.getElementById("chatScreen");
      objDiv.scrollTop = objDiv.scrollHeight;
    </script>
  </body>
</html>
