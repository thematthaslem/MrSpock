<!--
  The Make a new claim form
-->
<div class="new-claim-wrapper">
  <h1>New Claim:</h1>
<?php
  /*
    Check if user is logged in
  */
  $loggedin = false;
  if( isset($_SESSION['user']) )
  {
    $loggedin = true;
  }


  /*
    If the user is not logged in, give them a link to log in.
  */
  if( !($loggedin) )
  {
    // Need to get the query from url to pass to login.php so we can return after logging in.
    $redirect_query = $_SERVER['QUERY_STRING'];

    echo "
      <a class=\"link\" href=\"login.php?redirectPage=page&query=$redirect_query\">Log in</a> to submit a claim.
    ";
  }
  else
  {
?>

<form id="new-claim" method="post" action="_php/send_claim_action.php">
  <h2>Claim by <?php echo $_SESSION['user']; ?></h2>
  <label for="name">Claim:</label>
  <input type="text" name="name" />
    <label for="reproduce">Can you reproduce this claim?</label>
  <div class="row">
    <a class="button reproduce-button">Yes</a>
    <a class="button reproduce-button">No</a>
    <a class="button reproduce-button">Partially</a>
  </div>
  <input name="reproduce" type="hidden" value="" />
  <h3>Proof of experiments:</h3>
  <label for="source-code">Source Code:</label>
  <input name="source-code" type="text" />
  <label for="datasets">Datasets:</label>
  <input name="datasets" type="text" />
  <label for="results">Experiments and Results</label>
  <textarea name="results">

  </textarea>

  <a id="submit-claim" class="button">Submit</a>

</form>

<?php
  }
?>

</div>
<!--
  Show claims
-->
