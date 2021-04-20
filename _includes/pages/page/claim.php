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

<div class="message success">
  Claim successfully submitted!
</div>
<div class="message fail">
  Sorry! Something went wrong with submitting your claim.
</div>

<form id="new-claim" method="post" action="_php/send_claim_action.php">
  <input type="hidden" name="art_id" value="<?php echo $_GET['id'];?>" />
  <h2>Claim by <?php echo $_SESSION['user']; ?></h2>
  <input type="hidden" name="author" value="<?php echo $_SESSION['user']; ?>" />
  <label for="title">Claim:</label>
  <input type="text" name="title" required />
    <label for="reproduce">Can you reproduce this claim?</label>
  <div class="row">
    <a class="button reproduce-button active">Yes</a>
    <a class="button reproduce-button">No</a>
    <a class="button reproduce-button">Partially</a>
  </div>
  <input name="reproduce" type="hidden" value="Yes" />
  <h3>Proof of experiments:</h3>
  <label for="source-code">Source Code:</label>
  <input name="source-code" type="text" required />
  <label for="datasets">Datasets:</label>
  <input name="datasets" type="text" required />
  <label for="results">Experiments and Results</label>
  <textarea name="results" required>

  </textarea>

  <button type="submit" id="submit-claim" class="button">Submit</button>




</form>

<?php
  }
?>

</div>
<!--
  Show claims
-->
<div class="claims-wrapper">
  <h1>Claims:</h1>

  <input id="art_id" type="hidden" value="<?php echo $_GET['id'];?>" />

  <div class="claim-items-wrapper">
    <div class="item-wrapper">
      <div class="title">#1 - This is a Claim</div>
      <div class="info">By: <span class="value">Matthew Haslem</span></div>
      <div class="info">Can reproduce?: <span class="value">Partially</span></div>
      <div class="info">Source Code: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">Datasets: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">
        Results:
        <p>
          "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..
        </p>
      </div>
    </div>
    <div class="item-wrapper">
      <div class="row">
        <div class="title">#1 - This is a Claim</div>

        <!--
          VOTE BUTTONS
          - only allow voting if user is logged in
          - disable voting by adding class 'not-logged-in'
        -->
        <?php
          $notLoggedIn = "";
          if( !(isset($_SESSION['user'])) )
          {
            $notLoggedIn = "not-logged-in";
          }
          ?>
          <div class="vote-buttons-wrap">
            <div class="votes">130</div>
            <div class="vote-button upvote <?php echo $notLoggedIn; ?>"><div class="count">65</div></div>
            <div class="vote-button downvote <?php echo $notLoggedIn; ?>"><div class="count">65</div></div>
          </div>
          
      </div>

      <div class="info">By: <span class="value">Matthew Haslem</span></div>
      <div class="info">Can reproduce?: <span class="value">Partially</span></div>
      <div class="info">Source Code: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">Datasets: <span class="value">http://localhost:5601/app/dev_tools#/console</span></div>
      <div class="info">
        Results:
        <p>
          "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..
        </p>
      </div>
    </div>
  </div>

</div>
