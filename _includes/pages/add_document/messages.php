
<?php
  $is_messages = false;

  if( isset($_SESSION['success']) )
  {
      $is_messages = true;
      $message_type = "Success";
      $messages = $_SESSION['success'];
      unset($_SESSION['success']);
  }
  if( isset($_SESSION['error']) )
  {
    $is_messages = true;
    $message_type = "Error";
    $messages = $_SESSION['error'];
    unset($_SESSION['error']);
  }

  /*
    Make sure they're logged in
  */
  if( !(isset($_SESSION['user'])) )
  {
    header('Location: login.php?redirectPage=add-document&query=');
  }

  if($is_messages)
  {
?>

  <div class="message-wrap <?php echo $message_type; ?>">
    <h3><?php echo $message_type; ?></h3>
    <?php foreach ($messages as $item): ?>
      <div class="item">
        <?php echo $item; ?>
      </div>
    <?php endforeach; ?>
  </div>

<?php
  }
?>
