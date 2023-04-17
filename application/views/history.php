<h2>History</h2>
<?php foreach ($transactions as $transaction) { ?>
  <p><?php echo 'On : '.$transaction->created_at; ?>  Receiver ID: <?php echo $transaction->receiver_id; ?>  Amount: <?php echo $transaction->amount; ?></p>
<?php } ?>
